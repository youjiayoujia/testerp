<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use Cache;
use App\Models\StockModel;

class StockIOStatus
{
    protected $stock;

    public function __construct(StockModel $stock)
    {
        $this->stock = $stock;
    }

    public function handle($request, Closure $next)
    {
        $route = Request::path();
        $route = str_replace('/', '.', $route);
        Cache::store('file')->has('stockIOStatus') or Cache::store('file')->forever('stockIOStatus', 1);
        if (in_array($route, config('stockIOStatus')) && !Cache::store('file')->get('stockIOStatus')) {
            return redirect('/')->with('alert', view('common.alert', ['type'=>'danger','content'=>'库存盘点中，无法操作'])->render());
        }

        return $next($request);
    }
}
