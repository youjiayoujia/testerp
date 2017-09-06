<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;
use App\Models\Message\MessageModel;
use App\Models\Message\AutoReplyRulesModel;
use App\Models\Message\MessageAttachment;
use Tool;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Jobs\SendMessages as queueSendMessage;
use App\Models\Message\ReplyModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\OrderModel;
use Channel;
use App\Models\Event\CategoryModel;

class getChannelAccountMessages extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:message {channel=all} {account=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取对应渠道的指定账号消息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $channel =  $this->argument('channel');
        $account =  $this->argument('account');

        if($account != 'all'){
            $accounts = AccountModel::where('alias', $account)->where('is_available', 1)->get();
        }else{
            $channel_ids =  ChannelModel::where(function ($query) use ($channel,$account){
                if($channel != 'all'){
                    $query->where('driver', $channel);
                }
            })->get()->pluck('id');
            $accounts = AccountModel::whereIn('channel_id', $channel_ids)->where('is_available', 1)->get();
        }

        foreach ($accounts as $account){
            $this->info( $account->account . '  start get messages.');

            $channel = Channel::driver($account->channel->driver, $account->api_config);

            //***获取此账号的自动规则****
            $rules = $account->AutoReplyRules()->where('status', 'ON')->get();
            //***获取此账号的自动规则****

            //获取Message列表
            $messageList = $channel->getMessages();
            if(is_array($messageList)){
                foreach ($messageList as $message) {
                    $messageNew = MessageModel::firstOrNew(['message_id' => $message['message_id']]);
                    if($messageNew->id == null){
                        $messageNew->account_id = $account->id;
                        $messageNew->channel_id = $account->channel_id;
                        $messageNew->message_id = $message['message_id'];
                        $messageNew->from_name = $message['from_name'];
                        $messageNew->labels = $message['labels'];
                        $messageNew->label = $message['label'];
                        $messageNew->from = $message['from'];
                        $messageNew->to = $message['to'];
                        $messageNew->date = $message['date'];
                        $messageNew->subject = $message['subject'];
                        $messageNew->content = $message['content'];
                        $messageNew->channel_message_fields = $message['channel_message_fields'];
                        $messageNew->status  = 'UNREAD';
                        $messageNew->related  = 0;
                        $messageNew->required  = 1;
                        $messageNew->read  = 0;
                        $messageNew->country  = ! empty($message['country']) ? $message['country'] : '';
                        $messageNew->channel_url  = ! empty($message['channel_url']) ? $message['channel_url'] : ''; //暂时存储 wish订单号

                        if(!empty($message['list_id'])){
                            $messageNew->list_id  = $message['list_id'];
                        }else{
                            $messageNew->list_id  = '';
                        }

                        !empty($message['channel_order_number']) ? $messageNew->channel_order_number=$message['channel_order_number'] : '';

                        $messageNew->is_auto_reply = 2; //未进行自动回复检查
                        $messageNew->save();


                        /**
                         * 程序根据消息条件自动回复信息
                         *
                         */

                        //step1: 关联消息订单
                        $messageNew->findOrderWithMessage();

                        if(! $rules->isEmpty()){ //存在规则

                            $this->comment('check this message is need auto reply.');
                            $messageNew->is_auto_reply = 0; //无需回复


                            $rule = $this->checkAutomaticReply($messageNew, $rules);
                            if(! empty($rule->template)){ //符合发送消息的条件

                                /**
                                 * 创建reply记录
                                 * 塞入发送队列
                                 *
                                 */
                                $new_reply = [
                                    'message_id' => $messageNew->id,
                                    'to' => $messageNew->from_name,
                                    'to_email' => $messageNew->from,
                                    'title' => $rule->name . '(自动回复)',
                                    'content' => $rule->template,
                                    'status' => 'NEW',
                                ];
                                $reply = ReplyModel::firstOrNew($new_reply);
                                $reply->save();

                                $job = new queueSendMessage($reply);
                                $job = $job->onQueue('SendMessages');
                                $this->dispatch($job);

                                $messageNew->status = 'COMPLETE';
                                $messageNew->type_id = 0;
                                $messageNew->end_at = date('Y-m-d H:i:s', time());
                                $messageNew->is_auto_reply = 1; //已经回复

                                $this->info('Auto replay #' . $messageNew->message_id . '.');
                            }
                        }
                        $messageNew->save();
                        $this->info('Message #' . $messageNew->message_id . ' Received.');
                        //log 写入
                        $this->eventLog('系统', '数据新增', json_encode($messageNew));

                        //附件写入
                        $messageInsert = MessageModel::firstOrNew(['message_id' => $message['message_id']]);
                        if($messageInsert){
                            if($message['attachment'] !=''){
                                foreach ($message['attachment'] as $value){
                                    if($value){
                                        $attachment = MessageAttachment::firstOrNew(['message_id' => $messageInsert->message_id]);
                                        $attachment->message_id =$messageInsert->id;
                                        $attachment->gmail_message_id =$messageInsert->message_id;
                                        $attachment->filename = $value['file_name'];
                                        $attachment->filepath = $value['file_path'];
                                        $attachment->save();
                                    }
                                }
                            }
                        }
                    }else{
                        $this->comment('Message #' . $messageNew->message_id . ' alerady exist.');
                    }


                }
            }

        }


    }

    /**
     * 基础验证消息关联订单 包裹 物流
     * @param $message
     * @return object | bool
     */
    public function basicVerification($message)
    {

        $order = $message->relatedOrders()->orderBy('id', 'DESC')->first();
        if(empty($order)){
            return -1;
        }
        $packages = OrderModel::find($order->order_id)->packages;
        if($packages->isEmpty()){ //存在包裹
            return -2;
        }
        if($packages->count() != 1){//只存在一个包裹
            return -3;
        }
        if($packages->first()->status != 'SHIPPED'){ //包裹状态为已发货
            return -4;
        }
        //检查 消息关联的订单物流方式必须是平邮
        if(! $message->MsgOrderIsExpress()){
            return -5;
        }
        //验证是否为平台第一条消息
        if(! $message->IsFristMsgForOrder()){
            return -6;
        }

        return $packages->first();
    }

    /**
     *
     * 检查消息是否需要自动回复
     * @param $message
     * @param $rules
     * @return bool
     */
    public function checkAutomaticReply($message, $rules)
    {
        $result = false;

        $package = $this->basicVerification($message);

        if(! is_object($package)){ //验证失败
            return $result;
        }
        $send_time = Carbon::parse($message->date);
        $shipped_at = Carbon::parse($package->shipped_at);
        $diff_day = $send_time->diffInDays($shipped_at);  // 相差天数

        foreach($rules as $rule){
            if($rule->status == 'ON'){
                switch ($rule->ChannelName){
                    case 'Wish':
                        $check_wish = true;
                        if( ! empty($rule->label_keywords)){//主题关键字
                            if(! strstr($message->labels, $rule->label_keywords)){
                                //主题匹配
                                $check_wish = -1;
                            }
                        }

                        if(! empty($rule->message_keywords)){ //用户消息中同时包含关键字
                            $check_wish = false;
                            foreach (explode(',', $rule->message_keywords) as $keyword){
                                if(! strstr($message->UserMsgInfo, trim($keyword))){
                                    $check_wish = true;
                                }
                            }
                        }

                        if($rule->type_shipping_fifty_day == 'ON'){ //50天按钮开
                            if($diff_day < 50){
                                $check_wish = -3;
                            }
                        }

                        if($rule->type_within_tuotou == 'ON'){  //在wish平台妥投时间之内
                            if($diff_day > 19){
                                $check_wish = -4;
                            }
                        }

                        if($check_wish == true)
                            $result = $rule;
                        break;
                    case 'Aliexpress':
                        //检查关键词
                        if(! empty($rule->message_keywords)){
                            $check_aliexpress = true;
                            foreach (explode(',', $rule->message_keywords) as $keyword){
                                $check_aliexpress = false;
                                if(! strstr($message->UserMsgInfo, $keyword)){
                                    $check_aliexpress = true;
                                }
                            }

                            if($rule->type_shipping_one_month == 'ON'){//SMT: 平邮已发货订单，据发货时间一个月之内
                                if($diff_day > 30){
                                    $check_aliexpress = false;
                                }
                            }

                            if($rule->type_shipping_one_two_month == 'ON'){//SMT 据发货时间  1～2个月没有
                                if(($diff_day < 30) || ($diff_day > 60 )){
                                    $check_aliexpress = false;
                                }
                            }

                            if($check_aliexpress)
                                $result = $rule;

                        }
                        break;

                    default:
                        break;
                }
            }
        }

        return $result;
    }

    public function eventLog($user, $content = '', $to = '', $from = '')
    {
        $modelName ='messages';
        if ($modelName) {
            $category = CategoryModel::where('model_name', $modelName)->first();
            if (!$category) {
                $category = CategoryModel::create(['model_name' => $modelName]);
            }
            $category->child()->create([
                'type_id' => ($to ? json_decode($to)->id : ''),
                'what' => $content,
                'when' => date('Y-m-d H:i:s', time()),
                'to_arr' => $to,
                'from_arr' => $from,
                'who' => $user
            ]);
        }
    }
}
