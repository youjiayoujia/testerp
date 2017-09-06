@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
        <div class="row">
            <div class="form-group col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            
            <div class="form-group col-lg-4">
                <strong>仓库</strong>:
              {{$model->warehouse->name}}
            </div>
            </div>
            <div class="row">
            <div class="form-group col-lg-4">
                <strong>sku</strong>:
              {{$model->sku}}
            </div>
            <div class="form-group col-lg-4">
           		<strong>产品样图</strong>:
                <img src="{{ asset($model->item->product->image->src) }}" width="100px">
            </div>
            <div class="form-group col-lg-4">
                <strong>采购状态</strong>:
               @foreach(config('purchase.purchaseItem.status') as $k=>$val)
            	@if($model->status == $k)
            		{{$val}}
                @endif
            	@endforeach
            </div> 
            </div>
            <div class="row">
            <div class="form-group col-lg-4">
                    <label >成本价格:</label>
                    {{$model->purchase_cost}}
            </div>
            
            <div class="form-group col-lg-4">
                <strong>采购数量/已到数量/仍需采购数量</strong>:
              	{{$model->purchase_num}}/{{$model->arrival_num}}/{{$model->lack_num}}
            </div>
           <div class="form-group col-lg-4">
            <strong>入库数量</strong>
            {{$model->storage_qty}}
            </div>
            </div>
            <div class="row">
            <div class="form-group col-lg-4">
            	<strong>供应商信息</strong>:
                名：{{$model->supplier->name}}&nbsp;电话：{{$model->supplier->telephone}} &nbsp;地址：{{$model->supplier->province}}{{$model->supplier->city}}{{$model->supplier->address}}
            </div>
            </div>
        </div>
    </div>
    
    <div class="panel panel-default">
    <div class="panel-heading">入库日志</div>
    <div class="panel-body">
    <table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
            <td>ID</td>
            <td>入库数量</td> 
            <td>入库备注</td> 
            <td>入库时间</td>          
        </tr>
    </thead>
    <tbody>
    @foreach($storageLogs as $k=>$storageLog)
     <tr>
        <td>{{$storageLog->id}}</td> 
        <td>{{$storageLog->quantity}}</td> 
        <td>{{$storageLog->remark}}</td> 
        <td>{{$storageLog->created_at}}</td>          
     </tr>
     @endforeach
    </tbody>
    </table>
    </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop
