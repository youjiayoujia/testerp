<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\PaypalsModel;

class inputPaypalList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inputPaypalList:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $data = DB::select("SELECT * FROM erp_paypal_list WHERE paypal_password IS NOT NULL");
        foreach ($data as $row){
            $paypal = new PaypalsModel;
            $paypal->paypal_email_address = $row->paypal_email_address;
            $paypal->paypal_account       = $row->paypal_account;
            $paypal->paypal_password      = $row->paypal_password;
            $paypal->paypal_token         = $row->paypal_token;

            if($row->paypal_enable == 0){
                $row->paypal_enable == 2;
            }
            $paypal->is_enable            = $row->paypal_enable;

            $paypal->save();
            $this->info('insert one');
        }
    }
}
