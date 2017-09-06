<?php
/**
 * User: Norton
 * Date: 2016/6/20
 * Time: 19:04
 */
namespace App\Models\Message;
use App\Base\BaseModel;
use App\Models\PackageModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use Tool;
use Translation;
use App\Models\Channel\AccountModel as Channel_Accounts;
use App\Models\ChannelModel;
use App\Models\Logistics\ChannelModel as LogisticChannel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
//use App\Models\Order\PackageModel;
class MessageModel extends BaseModel{
    public $table = 'messages';

    public $fillable = [
        'account_id',
        'message_id',
        'mime_type',
        'from',
        'from_name',
        'to',
        'date',
        'subject',
        'start_at',
        'content',
        'title_email',
        'label',
    ];

    public $searchFields = [
        'id'=>'ID',
/*        'from'=>'发件邮箱' ,
        'label' => '消息类型',*/
    ];

    public $rules = [];

    public $appends = [
        'channel_diver_name',
        'msg_time'
    ];

    public function account()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel');
    }


    public function assigner()
    {
        return $this->belongsTo('App\Models\UserModel', 'assign_id' , 'id');
    }

    public function getLabelTextAttribute()
    {
        $result = "<span class='label label-success'>$this->label</span>";
        return $result;
    }

    public function getStatusTextAttribute()
    {
        return config('message.statusText.' . $this->status);
    }

    public function relatedOrders()
    {
        return $this->hasMany('App\Models\Message\OrderModel', 'message_id');
    }

    public function channel(){
        return $this->hasOne('App\Models\ChannelModel','id','channel_id');
    }

    public function Order(){
        return $this->belongsTo('App\Models\OrderModel', 'channel_order_number','channel_ordernum');

    }
    public function replies()
    {
        return $this->hasMany('App\Models\Message\ReplyModel', 'message_id');
    }

    public function parts()
    {
        return $this->hasMany('App\Models\Message\PartModel', 'message_id');
    }
    public function getAttachment()
    {
        return $this->hasMany('App\Models\Message\MessageAttachment', 'message_id');
    }

    public function getChannelNameAttribute(){
        if(!empty($this->channel_id)){
            return $this->channel->name;
        }else{
            return '无';
        }
    }

    public function getAutoReplyStatusAttribute()
    {
        if($this->is_auto_reply == 2){
            $status = '未检查';
        }else if($this->is_auto_reply == 0){
            $status = '已检查';
        } else if($this->is_auto_reply == 1){
            $status = '已回复';
        } else {
            $status = '未知';
        }
        return $status;
    }

    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [
                'from_name',
                'from',
                'labels',
                'message_id',
                'channel_order_number',
            ],
            'filterSelects' => [
                'status' => config('message.statusText'),
                'assign_id' => UserModel::all()->pluck('name','id'),
            ],
            'selectRelatedSearchs' => [
                'channel' => ['name' => ChannelModel::all()->pluck('name', 'name')],
                'account' => ['account' => Channel_Accounts::all()->pluck('alias', 'account')],
                //'assigner' => ['name' => UserModel::all()->pluck('name','name')],
            ],
            'sectionSelect' => ['time'=>['created_at']],
        ];
    }

    /**
     * 分配
     * @param $userId
     * @return bool
     */
    public function assign($userId)
    {
        switch ($this->status) {
            case 'UNREAD':
                $this->assign_id = $userId;
                $this->status = 'PROCESS';
                return $this->save();
                break;
            default:
                return $this->assign_id == $userId;
                break;
        }
    }

    public function getUserAccountIDs($userId){
        if($userId) {
            $accounts = AccountModel::where('customer_service_id', '=', $userId)->get();
            if (count($accounts) <> 0) {
                foreach ($accounts as $key => $account) {
                    $ids_ary[] = $account->id;
                }
                return $ids_ary;
            }
        }
        return false;
    }

    public function assignToOther($fromId, $assignId)
    {

        $assignUser = UserModel::find($assignId);
        if ($assignUser) {
            $this->assign_id = $assignId;
            return $this->save();
        }

        return false;
    }

    public function getHistoriesAttribute()
    {
        return MessageModel::where('from','=', $this->from)
            ->where('id', '<>', $this->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function getMsgTimeAttribute(){
        if(! empty($this->date)){
            return Carbon::parse($this->date)->diffForHumans();
        }else{
            return '';
        }
    }

    public function getMessageContentAttribute()
    {
        $plainBody = '';
        foreach ($this->parts as $part) {
            if ($part->mime_type == 'text/html') {
                $htmlBody = Tool::base64Decode($part->body);
                $htmlBody=preg_replace("/<(\/?body.*?)>/si","",$htmlBody);
            }
            if ($part->mime_type == 'text/plain') {
                $plainBody .= nl2br(Tool::base64Decode($part->body));
            }
        }
        $body = isset($htmlBody) && $htmlBody != '' ? $htmlBody : $plainBody;
        
        return $body;
    }

    public function cancelRelatedOrder($relatedOrderId)
    {
        $relatedOrder = $this->relatedOrders()->find($relatedOrderId);
        if ($relatedOrder) {
            $relatedOrder->delete();
            if ($this->relatedOrders()->count() < 1) {
                $this->related = 0;
                $this->save();
            }
            return true;
        }
        return false;
    }

    public function notRelatedOrder()
    {
        $this->related = 1;
        return $this->save();
    }

    public function notRequireReply($userId)
    {
            $this->required = 0;
            $this->status = 'COMPLETE';
            if($this->save()){
                return true;
            }else{
                return false;

            }
    }

    public function dontRequireReply($userId)
    {
        if ($this->assign_id == $userId) {
            $this->required = 0;
            $this->status = 'PROCESS';
            $this->dont_reply = 1;
            return $this->save();
        }
        return false;
    }
    
    public function setRelatedOrders($numbers)
    {
        if ($numbers) {
            foreach ($numbers as $number) {
                
                $order = OrderModel::ofOrdernum($number)->first();
                if ($order) {
                    $this->relatedOrders()->create(['order_id' => $order->id]);
                } else {
                    $package = PackageModel::ofTrackingNo($number)->first();
                    if ($package) {
                        $this->relatedOrders()->create(['order_id' => $package->order_id]);
                    }
                }
            }
            if ($this->relatedOrders()->count() > 0) {
                $this->related = 1;
                $this->start_at = date('Y-m-d H:i:s', time());
                return $this->save();
            }
        }
        return false;
    }

    /**
     * 回复
     * @param $data
     * @return bool
     */
    public function reply($data)
    {
        $data['to_email'] = trim($data['to_email']);
        $data['status'] = 'NEW';
        if ($this->replies()->create($data)) {
            //记录回复邮件类型
            $this->type_id = 0;
            $this->status = 'COMPLETE';
            $this->end_at = date('Y-m-d H:i:s', time());
            return $this->save();
        }
        return false;
    }

    public function getMessageAttanchmentsAttribute()
    {
        $attanchments = [];
        foreach ($this->getAttachment as $key => $part) {
            if ($part->filename) {
                $attanchments[$key]['filename'] = $part->filename;
                $attanchments[$key]['filepath'] = '/' . config('message.attachmentSrcPath') .$part->filepath;
            }
        }
        return $attanchments;
    }


    //获取用户所有提问内容
    public function getUserMsgInfoAttribute(){
        $content_string = false;
        $message_info = $this->ContentDecodeBase64;
        if(! empty($message_info)){
            foreach ($message_info as $key => $content){
                switch ($key) {
                    case 'aliexpress':
                        foreach($content->result as $item){
                            if($this->from_name == $item->senderName){
                                $content_string .= $item->content;
                            }
                        }

                        break;
                    case 'wish':
                       foreach ($content as $k => $item){
                            if($item['Reply']['sender'] == 'user') {
                                $content_string .= $item['Reply']['message'];
                            }
                        }
                        break;
          /*          case 'ebay':
                        print_r($message_info);*/
                    default:
                        $content_string = false;
                }
            }
        }
        return $content_string;
    }

    public function IsFristMsgForOrder(){
        $message_info = $this->ContentDecodeBase64;
        $result = false;
        foreach ($message_info as $channel_name => $content){
            switch ($channel_name){
                case 'aliexpress':
                    $content_group = Collection::make($content->result)->groupBy('senderName');
                    if($content_group->count() == 1){ //只存在用户信息
                        $result = true;
                    } else {
                        foreach ($content_group as $key => $item){
                            if($key != $this->from_name){ //包含自动去信的第一个消息
                                if($item->count() == 1){
                                    $result = true;
                                }
                            }
                        }

                    }
                    break;
                case 'wish':
                    $hasMerchant =  Collection::make($content)->flatten()->search('merchant');
                    if(! $hasMerchant){
                        $result = true;
                    }
                    break;

                case 'ebay':

                    break;
                default:




            }
        }
        return $result;
    }

    public function MsgOrderIsExpress(){
        if($order = $this->relatedOrders()->first()){
            $package = OrderModel::find($order->order_id)->packages()->first();
            if(! empty($package)){
                if($package->logistics->is_express == '0'){
                    return true;
                }
            }
        }
        return false;
    }

    public function getMessageInfoAttribute(){
        if($this->ContentDecodeBase64){
            $html = '';
            foreach($this->ContentDecodeBase64 as $key => $content){
                switch ($key){
                    case 'wish':
                        foreach ($content as $k => $item){
                           if(!empty($item['Reply']['message'])){
                               if($item['Reply']['sender'] != 'merchant'){
                                   if($item['Reply']['sender'] == 'wish support'){
                                       $this->from_name = $item['Reply']['sender'];
                                   }
                                   $html .= '<div class="alert alert-warning col-md-10" role="alert"><p><strong>发件人：</strong>'.$this->from_name.':</p><strong>内容: </strong>'.$item['Reply']['message'];
                                   $html .= '<p class="time"><strong>时间：</strong>'.$item['Reply']['date'].'</p>';

                                   if(isset($item['Reply']['translated_message']) && isset($item['Reply']['translated_message_zh'])){
                                       $html .= '<div class="alert-danger"><strong>Wish翻译: </strong><p>'.$item['Reply']['translated_message'].'</p><p>'. $item['Reply']['translated_message_zh'].'</p></div>';
                                   }else{

                                   }
                                   if(! empty($item['Reply']['image_urls'])){
                                       $img_urls = $item['Reply']['image_urls'];
                                       $img_urls = str_replace('[', '', $img_urls);
                                       $img_urls = str_replace(']', '', $img_urls);
                                       $img_urls = explode(',', $img_urls);
                                       foreach($img_urls as $url){
                                           $tmp_url = explode('\'', $url);
                                           if(! empty($tmp_url[1])){
                                               $html .= '附图：<img width="500px" src="'.$tmp_url[1].'" /> <br/>';
                                           }
                                       }
                                   }
                                   $html .= '</div>';
                               }else{
                                   $html .= '<div class="alert alert-success col-md-10" role="alert" style="float: right"><p><strong>发件人：</strong>'.$item['Reply']['sender'].':</p><strong>内容: </strong>'.$item['Reply']['message'];
                                   $html .= '<p class="time"><strong>时间：</strong>'.$item['Reply']['date'].'</p>';
                                   $html .= '</div>';
                               }


                           }
                        }
                        break;
                    case 'aliexpress':
                        $message_content = array_reverse($content->result); //逆序
                        $product_html = '';
                        $message_fields_ary = false;
                        foreach ($message_content as $k => $item){

                            if($message_fields_ary == false && $item->messageType == 'product'){
                                $message_fields_ary['product_img_url']      = isset($item->summary->productImageUrl) ? $item->summary->productImageUrl : '';
                                $message_fields_ary['product_product_url']  = isset($item->summary->productDetailUrl) ? $item->summary->productDetailUrl : '';
                                $message_fields_ary['product_product_name'] = isset($item->summary->productName) ? $item->summary->productName : '';

                                $product_html .= '<div class="col-lg-12 alert-default">';
                                $product_html .= '<table class="table table-bordered table-striped table-hover sortable">';
                                $product_html .= '<tr>';
                                $product_html .= '<th>产品图片</th>';
                                $product_html .= '<th>产品名称</th>';
                                $product_html .= '<th>产品连接</th>';
                                $product_html .= '</tr>';
                                $product_html .= '<tr>';
                                $product_html .= '<td><img src ="'.$message_fields_ary['product_img_url'] .'"/></td>';
                                $product_html .= '<td>'.$message_fields_ary['product_product_name'] .'</td>';
                                $product_html .= '<td><a target="_blank" href="'.$message_fields_ary['product_product_url'].'">链接</a></td>';
                                $product_html .= '</tr>';
                                $product_html .= '</table>';
                                $product_html .= '</div>';

                            } else{
                                if($k==0 && ! empty($item->summary->orderUrl)){
                                    $product_html .= '<div class="col-lg-12" >渠道订单链接:<a target="_blank" href="' . $item->summary->orderUrl . '">'.$item->summary->orderUrl.'</a></div>';
                                }

                            }

                            //dd($message_fields_ary);
                            $row_html = '';
                            if($item->content == '< img >'){
                                foreach ($item->filePath as $item_path){
                                    if($item_path->mPath){
                                        $row_html .='<img src="'.$item_path->mPath.'" /><a href="'.$item_path->lPath.'" target="_blank">查看大图</a>';
                                    }
                                }
                            }
                            $content = $item->content;
                            $content = str_replace("&nbsp;", ' ', $content);
                            $content = str_replace("&amp;nbsp;", ' ', $content);
                            $content = str_replace("&amp;iquest;", ' ', $content);
                            $content = str_replace("\n", "<br />", $content);
                            $content = preg_replace("'<br \/>[\t]*?<br \/>'", '', $content);
                            $content = preg_replace("'\/\:0+([0-9]+0*)'", "<img style='width:25px' src='http://i02.i.aliimg.com/wimg/feedback/emotions/\\1.gif' />", $content);
                            $content = (stripslashes(stripslashes($content)));

                            $datetime = date('Y-m-d H:i:s',$item->gmtCreate/1000);
                            if($this->from_name != $item->summary->receiverName){
                                if($row_html != ''){
                                    $html .= '<div class="alert alert-warning col-md-10" role="alert"><p><strong>Sender: </strong>'.$item->senderName.':</p><strong>Content: </strong>'.$row_html;
                                    $html .= '<p class="time"><strong>Time: </strong>'.$datetime.'</p>';
                                    $html .= '</div>';
                                }else{
                                    $html .= '<div class="alert alert-warning col-md-10" role="alert"><p><strong>Sender: </strong>'.$item->senderName.':</p><strong>Content: </strong>'.$content;
                                    $html .= '<p class="time"><strong>Time: </strong>'.$datetime.'</p>';
                                    $html .= '<button style="float: right;" type="button" class="btn btn-success btn-translation" need-translation-content="'. preg_replace("'\/\:0+([0-9]+0*)'", '',$content) .'" content-key="'.$k.'">
                                    翻译
                                </button>
                                <p id="content-'.$k.'" style="color:green"></p>';
                                    $html .= '</div>';
                                }

                            }else{
                                $html .= '<div class="alert alert-success col-md-10" role="alert" style="float: right"><p><strong>Sender: </strong>'.$item->senderName.':</p><strong>Content: </strong>'.$content;
                                $html .= '<p class="time"><strong>Time: </strong> '.$datetime.'</p>';
                                $html .= '</div>';
                            }
                        }
                        break;

                    case 'ebay':
                        $html = $content;
                        break;
                    case 'amazon':
                        $html = $content;
                        break;
                    default :
                        $html = 'invaild channel message';
                }
            }

            return empty($product_html) ? $html : $product_html.$html;
        }else{
            return '';
        }
    }

    //渠道信息特殊属性
    public function getMessageFieldsDecodeBase64Attribute(){
        if($this->channel_message_fields){
            return unserialize(base64_decode($this->channel_message_fields));
        }else{
            return '';
        }
    }
    public function getContentDecodeBase64Attribute(){
        if($this->content){
            return unserialize(base64_decode($this->content));
        }else{
            return '';
        }
    }
    /**
     * 获取消息对应的渠道
     * @return mixed
     */
    public function getChannelDiver(){
        return $this->account->channel->driver;
    }

    public function getChannelDiverNameAttribute ()
    {
        return !empty($this->account->channel->driver) ? $this->account->channel->driver : false;
    }

    public function findOrderWithMessage(){
        $order_id = false;
        switch ($this->getChannelDiver()){
            case 'ebay':
                /**
                 * 由于ebay 的api 没有返回渠道订单号
                 * 关联规则：渠道用户ID 和 渠道订单的ItemID 进行关联
                 * 如果由多个情况 同时关联
                 *
                 */
                if(! empty($this->channel_order_number) && ! empty($this->from_name)){
                    $orders = OrderModel::where('by_id','=', $this->from_name)->get();
                    if(! $orders->isEmpty()){
                        $orderByUser = false;
                        foreach ($orders as $order){
                            $item = $order->items()->where('orders_item_number', '=', $this->channel_order_number)->first();
                            if($item){
                                $orderByUser[$item->order_id] = $item->order_id;
                            }
                        }
                        $order_id = $orderByUser;
                    }
                }
                break;
            case 'wish':
                //wish交易号
                $order_obj = OrderModel::where('transaction_number','=',$this->channel_order_number)->first();
                $order_id = empty($order_obj) ? false : $order_obj->id;   //根据 orders 表 交易号
                break;
            case 'aliexpress':
                $order_id = !empty($this->Order->id) ? $this->Order->id : false;
                break;
            default:
                $order_id = false;

        }
        if($order_id){
            if($this->getChannelDiver() != 'ebay'){
                if($this->relatedOrders()->create(['order_id' => $order_id])){
                    $this->related = 1;
                    $this->save();
                }
            } else {
                foreach($order_id as $item){
                    $this->relatedOrders()->create(['order_id' => $item]);
                }
                $this->related = 1;
                $this->save();
            }

        }

    }

    /**
     * 渠道参数信息
     */
    public function getChannelParamsAttribute(){
        $html = '<ul class="list-group">';
        $channel = $this->getChannelDiver();
        switch ($channel){
            case 'aliexpress':
                $html .= '<li class="list-group-item"><span class="label label-warning">'.$this->label.'</span></li>';
                $html .= '<li class="list-group-item"><code>渠道单号：'.$this->channel_order_number.'</code></li>';
                break;
            case 'wish':
                $files = $this->MessageFieldsDecodeBase64;
                if($files){
                    $html .= '<li class="list-group-item"><p><strong>标签</strong>:'.$this->labels.'</p></li>';
                    $html .= '<li class="list-group-item"><p><strong>Transaction id</strong>:'.$files['order_items'][0]['Order']['transaction_id'].'</p></li>';
                    $html .= '<li class="list-group-item"><p><strong>语言</strong>:'.$this->country.'</p></li>';
                }else{
                    $html .= '<li class="list-group-item"><p>暂无</p></li>';
                }
                break;
            case 'ebay':
                $files = $this->MessageFieldsDecodeBase64;
                if(!empty($files)){
                    $html .= '<li class="list-group-item"><p><strong>ItemID</strong><a href="http://www.ebay.com/itm/'.$files['ItemID'].'" target="_blank">:'.$files['ItemID'].'</a></p></li>';
                    $html .= '<li class="list-group-item"><p><strong>Ebay平台链接</strong>:<a target="_blank" href="'.$files['ResponseDetails'].'"><span class="glyphicon glyphicon-link"></span></a></p></li>';

                }

                break;

            default:
                $html = '';
        }
        $html .= '</ul>';

        return $html;
    }

    public function getMessageAccountNameAttribute()
    {
       $obj = $this->account;
        if(!empty($obj)){
            return  $obj->account;
        }else{
            return '平台账号';
        }
    }

    public function getMyWorkFlowMsg ($entry = 5)
    {
        return $this->workFlowMsg($entry)->get();
    }

    /**
     * 工作流消息
     * 说明：客服负责的账号，并且没有被别人操作的 未读或者处理中
     * @param $query
     * @param $entry
     * @return mixed
     */
    public function scopeWorkFlowMsg ($query,$entry)
    {
        $user_id = request()->user()->id;
        $account_ids = Channel_Accounts::where('customer_service_id',$user_id)->get()->pluck('id')->toArray(); //客服所属的账号

        return $query->where(function ($query) use ($account_ids){
            $query->where(['status'=> 'UNREAD', 'required'=> 1, 'dont_reply' => 0 ,'read' => 0])
                ->whereIn('account_id',$account_ids);
            })
            ->orWhere(function($query) use ($user_id, $account_ids){
                $query->where('status','=','PROCESS')
                    ->where('assign_id','=',$user_id)
                    ->where('required','=',1)
                    ->where('dont_reply','=',0)
                    ->where('read','=',0)
                ->whereIn('account_id',$account_ids);
            })
            ->take($entry)
            ->orderBy('id', 'ACS');
    }

    public function scopeSent($query)
    {
        return $query->where('status', '=', 'COMPLETE');
    }
    public function scopeNotRequiredSent($query)
    {
        return $query->where('status', '=', 'COMPLETE')->where('required', '=', '0');

    }

    public function contentTemplate ()
    {
        if($this->channel_diver_name){
            switch ($this->channel_diver_name){
                case 'aliexpress':
                    break;

            }

        }
    }

    public function completeMsg(){
        $this->status = 'COMPLETE';
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }


}