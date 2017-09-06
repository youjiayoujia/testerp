<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\DoPackages;
use App\Models\UserModel;
use DB;

class UpdateUsers extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update users';

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
        $arr = array(
            array('prefix' => '001', 'userId' => 48),  
            array('prefix' => '002', 'userId' => 22),  
            array('prefix' => '003', 'userId' => 29),  
            array('prefix' => '004', 'userId' => 105), 
            array('prefix' => '006', 'userId' => 123), //wuqun
            array('prefix' => '007', 'userId' => 144), //yanghong
            array('prefix' => '008', 'userId' => 148),  //maonuosha
            array('prefix' => '009', 'userId' => 107),  //yangyang
            array('prefix' => '010', 'userId' => 150),  //taoling
            array('prefix' => '011', 'userId' => 132),  
            array('prefix' => '012', 'userId' => 128),  //sujing
            array('prefix' => '013', 'userId' => 161),  
            array('prefix' => '014', 'userId' => 160),  
            array('prefix' => '015', 'userId' => 163),  //jiangling
            array('prefix' => '016', 'userId' => 164),  //zhangsusu
            array('prefix' => '017', 'userId' => 162),  //zhaowenjin
            array('prefix' => '018', 'userId' => 165),  
            array('prefix' => '019', 'userId' => 151),  //daiqi
            array('prefix' => '020', 'userId' => 152),  
            array('prefix' => '021', 'userId' => 175),  
            array('prefix' => '022', 'userId' => 174),  
            array('prefix' => '023', 'userId' => 181),  
            array('prefix' => '024', 'userId' => 177),  
            array('prefix' => '025', 'userId' => 176),  //zhouzhixu
            array('prefix' => '027', 'userId' => 418),  //huchunyu
            array('prefix' => '028', 'userId' => 320),  //huangrong
            array('prefix' => '029', 'userId' => 470),  //yangleen
            array('prefix' => '030', 'userId' => 302),  //heyanhua
            array('prefix' => '031', 'userId' => 477),  
            array('prefix' => '032', 'userId' => 407), //malin
            array('prefix' => '033', 'userId' => 492), // chenmeilin
            array('prefix' => '034', 'userId' => 504), // guohui
            array('prefix' => '035', 'userId' => 509), // liangguixiang
            array('prefix' => '036', 'userId' => 414), // caili
            array('prefix' => '037', 'userId' => 516), //yufang
            array('prefix' => '040', 'userId' => 567),
            array('prefix' => '042', 'userId' => 570),//boziwei
            array('prefix' => '066', 'userId' => 696),//zengwenyan
            array('prefix' => '330', 'userId' => 48), //wangfei2
            array('prefix' => '331', 'userId' => 22),
            array('prefix' => '332', 'userId' => 107),//yangyang2
            array('prefix' => '333', 'userId' => 176),//zhouzhixu2
            array('prefix' => '334', 'userId' => 181),
            array('prefix' => '335', 'userId' => 320),//huangrong2
            array('prefix' => '336', 'userId' => 477),
            array('prefix' => '337', 'userId' => 302),//heyanhua2
            array('prefix' => '338', 'userId' => 492),
            array('prefix' => '339', 'userId' => 470),//yangleen2
            array('prefix' => '340', 'userId' => 516),//yufang2
            array('prefix' => '360', 'userId' => 567),
            array('prefix' => '366', 'userId' => 570),//boziwei2
            array('prefix' => '666', 'userId' => 696),//zengwenyan
            array('prefix' => '350', 'userId' => 572),
            array('prefix' => '351', 'userId' => 583 ),
            array('prefix' => '352', 'userId' => 585),
            array('prefix' => '353', 'userId' => 584),
            array('prefix' => '354', 'userId' => 609),
            array('prefix' => '355', 'userId' => 632),
            array('prefix' => '356', 'userId' => 647),
            array('prefix' => '357', 'userId' => 487),
            array('prefix' => '358', 'userId' => 660),
            array('prefix' => '359', 'userId' => 663),
            array('prefix' => '360', 'userId' => 675),
            array('prefix' => '361', 'userId' => 687),
            array('prefix' => '362', 'userId' => 691),
            array('prefix' => '363', 'userId' => 693),
            array('prefix' => '364', 'userId' => 695),
            array('prefix' => '365', 'userId' => 700),
            array('prefix' => '366', 'userId' => 706),
            array('prefix' => '367', 'userId' => 707),
            array('prefix' => '368', 'userId' => 708),
        );
        foreach($arr as $single) {
            var_dump($single['userId']);
            $user = DB::table('erp_manages')->find($single['userId']);
            var_dump($user);
            if(!$user) {
                continue;
            }
            $user_name = $user->username;
            $user = UserModel::where('name', $user_name)->first();
            var_dump($user->toarray());exit;
            if(!$user) {
                continue;
            }
            $user->update(['code' => $single['prefix']]);
        }
        $arr = DB::table('erp_smt_user_sale_code')->get();
        foreach($arr as $single) {
            $user = DB::table('erp_slme_user')->find($single->user_id);
            if(!$user) {
                continue;
            }
            $user_name = $user->user_name;
            $user = UserModel::where('name', $user_name)->first();
            if(!$user) {
                continue;
            }
            $user->update(['code' => $single->sale_code]);
        }
        var_dump('ok');
    }
}