<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AllReportController;
use App\Models\Package\AllReportModel;

class AllReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'all:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All Report';

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
     * todo:订单优先级
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        $len = 1000;
        $start = 0;
        $begin = microtime(true);
        $allReport = new AllReportController(new AllReportModel());
        $allReport->createData();
        $end = microtime(true);
        echo '耗时' . round($end - $begin, 3) . '秒';
    }
}
