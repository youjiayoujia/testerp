<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Channel\AccountModel;
use App\Models\Message\MessageModel;
use App\Models\ChannelModel;
use Channel;


class SendMessages extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $reply;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reply)
    {
        //
        $this->reply = $reply;
        $this->description = 'Send message to' . $this->reply['to_email'] . '(message_id:' . $this->reply['message_id'] . ') in SYS.';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        if ($this->reply) {
            $account = $this->reply->message->account;

            // if($account->channel->driver == 'aliexpress') //亚马逊渠道邮件

            $channel = Channel::driver($account->channel->driver, $account->api_config);
            if ($channel->sendMessages($this->reply)) {//发送渠道message
                $this->result['status'] = 'success';
                $this->result['remark'] = 'the message send to [' . $this->reply['to_email'] . '] message successful!';
            } else {
                $this->result['status'] = 'fail';
                $this->result['remark'] = 'the message send to [' . $this->reply['to_email'] . '] message failed!';
            }


        } else {
            $this->result['status'] = 'fail';
            $this->result['remark'] = 'not find message';
        }

        $this->lasting = round(microtime(true) - $start, 3);
        $this->log('SendMessages', json_encode($this->reply));
    }
}
