<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChannelModel;
use App\Models\Message\MessageModel;
use Channel;
class AutoGetMessageAliexpress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoMessageAliexpress:get';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = '速卖通消息自动导入';


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
        /**
         * 获取所有账号的速卖通消息
         * 执行时间： 每天  8:00 ，15:40 执行
         */
        $channel = ChannelModel::where('driver','=','aliexpress')->first();
        $accounts =  $channel->accounts()->where('is_available','=','1')->get();

        if(!$accounts->isEmpty()){
            foreach ($accounts as $account){

                //实例化渠道驱动
                $this->info( $account->account . '  start get messages.');

                $channel = Channel::driver($account->channel->driver, $account->api_config);
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

                            $messageNew->list_id = !empty($message['list_id']) ? $message['list_id'] : '';

                            !empty($message['channel_order_number']) ? $messageNew->channel_order_number=$message['channel_order_number'] : '';

                            $messageNew->save();
                            $this->info('Message #' . $messageNew->message_id . ' Received.');

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

        $this->info('finish.');
    }
}
