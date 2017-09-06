@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading"><a href="{{route('purchaseStockIn.create')}}" class="btn btn-info btn-xs"> 单件入库
                </a>/<a href="/manyStockIn" class="btn btn-info btn-xs"> 多件入库
                </a>/<a href="{{route('purchaseStockIn.index')}}" class="btn btn-info btn-xs"> 已入库列表
                </a></div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
            <th>采购条目ID</th> 
            <th>sku</th>
            <th>需求数量</th> 
            <th>入库状态</th>
            <th>实际已入库数量</th>
            <th>重量</th>
            <th>到货时间</th>
            <th>库位</th>  
        </tr>
    </thead>
    <tbody>
        @foreach($data as $k=>$purchaseItem)
        <tr> 
            <td>{{$purchaseItem->id}}</td>
            <td>{{$purchaseItem->sku}}</td>
            <td>{{$purchaseItem->purchase_num}}</td>
            <td>@if($purchaseItem->storageStatus ==1)
            部分入库
            @elseif($purchaseItem->storageStatus ==2)
            全部入库
            @endif
            </td>
            <td>
            {{$purchaseItem->storage_qty}}
            </td>
            <td>{{$purchaseItem->item->weight}}</td>
            <td>{{$purchaseItem->arrival_time}}</td>
            <td>{{$purchaseItem->stock->warehouse->name}}-{{$purchaseItem->stock->position->name}}</td>
            </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
  
@stop
