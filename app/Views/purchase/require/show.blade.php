@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">需求明细 :</div>
        
     <table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
            <td>ID</td>
            <td>SKU</td>
            <td>订单号</td>
            <td>订单itemID</td>
            <td>数量</td>
            <td>创建时间</td>           
        </tr>
    </thead>
    <tbody>
    @foreach($result as $key=>$require)
         <tr>
            <td>{{$require->id}}</td>
            <td>{{$require->sku}}</td>
            <td>{{$require->order_id}}</td>
            <td>{{$require->order_item_id}}</td>
            <td>{{$require->quantity}}</td>
            <td>{{$require->created_at}}</td>           
          </tr>
    @endforeach
    </tbody>
    </table>
        
</div>
@stop