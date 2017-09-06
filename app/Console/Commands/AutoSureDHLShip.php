<?php
/**DHL物流确认发货.
 * User: lidabiao
 * Date: 2016-12-15
 * Time: 17:35
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemModel;
use Logistics;
use App\Models\PackageModel;
class AutoSureDHLShip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dhl:sureShip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DHL确认追踪号发货';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        //DHL统一批量确认发货
        set_time_limit(0);
        //创建确认订单的发送数据,渠道，如果增加了DHL的物流方式需要把物流ID加入logistics_id这里
        $package = PackageModel::whereIn('logistics_id', [62, 66])->where('sure_tracking_no',0)->where('tracking_no','!=','')->get();
        if(count($package)<1){
            echo "没有需要确认发货的订单";exit;
        }
        $dhl = Logistics::driver('Dhl','');
        $res=$dhl->SendSureOrderShip($package);
        echo $res['info'];

    }
}