@extends('common.form')
@section('formAction') {{ route('pickList.statisticsProcess') }} @stop
@section('formBody')
    <div class="panel panel-default">
        <div class="panel-heading">拣货排行榜</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-2'>时间</td>
                    <td class='col-lg-2'>姓名</td>
                    <td class='col-lg-2'>所属仓库</td>
                    <td class='col-lg-2'>拣货单数</td>
                    <td class='col-lg-2'>sku品种数</td>
                    <td class='col-lg-2'>货品总数</td>
                </thead>
                <tbody>
                @foreach($pick as $userId => $single)
                <tr>
                    <td class='col-lg-2'>{{ $start_time.'---'.$end_time }}</td>
                    <td class='col-lg-2'>{{ $single->first()->pickByName ? $single->first()->pickByName->name : '' }}</td>
                    <td class='col-lg-2'></td>
                    <td class='col-lg-2'>{{ $single->count() }}</td>
                    <td class='col-lg-2'>{{ $single->sum('sku_num') }}</td>
                    <td class='col-lg-2'>{{ $single->sum('goods_quantity') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class='col-lg-2'>{{ $start_time.'---'.$end_time }}</td>
                    <td class='col-lg-2'></td>
                    <td class='col-lg-2'><font>合计</font></td>
                    <td class='col-lg-2'>{{ $pickNum }}</td>
                    <td class='col-lg-2'>{{ $skuNum }}</td>
                    <td class='col-lg-2'>{{ $goodsNum }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">多多分拣排行榜</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-2'>时间</td>
                    <td class='col-lg-2'>姓名</td>
                    <td class='col-lg-2'>所属仓库</td>
                    <td class='col-lg-2'>分拣单数</td>
                    <td class='col-lg-2'>sku品种数</td>
                    <td class='col-lg-2'>货品总数</td>
                </thead>
                <tbody>
                @foreach($inbox as $userId => $single)
                <tr>
                    <td class='col-lg-2'>{{ $start_time.'---'.$end_time }}</td>
                    <td class='col-lg-2'>{{ $single->first()->inboxByName ? $single->first()->inboxByName->name : '' }}</td>
                    <td class='col-lg-2'></td>
                    <td class='col-lg-2'>{{ $single->count() }}</td>
                    <td class='col-lg-2'>{{ $single->sum('sku_num') }}</td>
                    <td class='col-lg-2'>{{ $single->sum('goods_quantity') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class='col-lg-2'>{{ $start_time.'---'.$end_time }}</td>
                    <td class='col-lg-2'></td>
                    <td class='col-lg-2'><font>合计</font></td>
                    <td class='col-lg-2'>{{ $inboxPickNum }}</td>
                    <td class='col-lg-2'>{{ $inboxSkuNum }}</td>
                    <td class='col-lg-2'>{{ $inboxGoodsNum }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">包装排行榜</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-2'>时间</td>
                    <td class='col-lg-2'>姓名</td>
                    <td class='col-lg-2'>所属仓库</td>
                    <td class='col-lg-2'>包裹数</td>
                    <td class='col-lg-2'>sku品种数</td>
                    <td class='col-lg-2'>货品总数</td>
                </thead>
                <tbody>
                @foreach($pack as $userId => $single)
                <tr>
                    <td class='col-lg-2'>{{ $start_time.'---'.$end_time }}</td>
                    <td class='col-lg-2'>{{ $single->first()->packByName ? $single->first()->packByName->name : '' }}</td>
                    <td class='col-lg-2'></td>
                    <td class='col-lg-2'>{{ $single->sum('package_num') }}</td>
                    <td class='col-lg-2'>{{ $single->sum('sku_num') }}</td>
                    <td class='col-lg-2'>{{ $single->sum('goods_quantity') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class='col-lg-2'>{{ $start_time.'---'.$end_time }}</td>
                    <td class='col-lg-2'></td>
                    <td class='col-lg-2'><font>合计</font></td>
                    <td class='col-lg-2'>{{ $packPickNum }}</td>
                    <td class='col-lg-2'>{{ $packSkuNum }}</td>
                    <td class='col-lg-2'>{{ $packGoodsNum }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop