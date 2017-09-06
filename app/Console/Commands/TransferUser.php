<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserModel;
use App\Models\Sellmore\UserModel as smUser;

class TransferUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer User';

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
        $len = 100;
        $start = 0;
        $originNum = 0;
        $createdNum = 0;
        $updatedNum = 0;
        $smCds = smUser::where(['status' => '1'])->skip($start)->take($len)->get();
        while ($smCds->count()) {
            $start += $len;
            foreach ($smCds as $smCd) {
                $originNum++;
                $cd = [
                    'name' => $smCd->user_name,
                    'email' => $smCd->user_name.'@moonarstore.com',
                    'password' => bcrypt($smCd->user_name.'@moonarstore.com'),
                    'is_available' => '1'
                ];
                $exist = UserModel::where(['email' => $smCd->user_name.'@moonarstore.com'])->first();
                if($exist) {
                    $exist->update($cd);
                    $updatedNum++;
                } else {
                    UserModel::create($cd);
                    $createdNum++;
                }
            }
            $smCds = smUser::where(['status' => '1'])->skip($start)->take($len)->get();
        }
        $this->info('Transfer [User]: Origin:'.$originNum.' => Created:'.$createdNum.' Updated:'.$updatedNum);
    }
}
