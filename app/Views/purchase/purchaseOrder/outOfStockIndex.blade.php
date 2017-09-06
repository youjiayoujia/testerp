@extends('common.table')
@section('tableToolbar')

    <form action="{{ route('purchase.outOfStock') }}" method="get">
        <div class="form-group col-lg-12">
            SKU：<input type="text" id="sku" name='sku' value='{{$sku}}'>
            采购人：
            <select id="user_id" name='user_id'>
                <option value="0">全部</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}" {{ $user_name==$user->name?'selected':'' }} >{{$user->name}}</option>
                @endforeach
            </select>
            产品状态：
            <select id="status" name='status'>
                <option value="0">全部</option>
                @foreach(config('item.status') as $key=>$value)
                    <option value='{{$key}}'  {{ $status==$key?'selected':'' }} >{{$value}}</option>
                @endforeach
            </select>
            时间<input type="text" name='date_from' id='date_from' class='datetimepicker_dark' value='{{$date_from}}'>--<input type="text" name='date_to' id='date_to' class='datetimepicker_dark' value='{{$date_to}}'>
            <button class="search">查询</button>
            <a href="javascript:" class="btn btn-warning download">CSV导出
                <i class="glyphicon glyphicon-arrow-down"></i>
            </a>
        </div>
    </form>
    

   
@stop
@section('tableHeader')
    <th>SKU号</th>
    <th>所属仓库</th>
    <th>物品名称</th>
    <th>在途</th>
    <th>特采在途</th>
    <th>欠货数量</th>
    <th>虚库存</th>
    <th>实库存</th>
    <th>最近采购</th>
    <th>缺货时间</th>
@stop

@section('tableBody')
    @foreach($data as $key=>$item)

    	@foreach($warehouses as $key=>$warehouse)
	        <tr>
                @if($key==0)
                <td rowspan="{{count($warehouses)}}">{{$item->sku}}</td> 
                @endif    
	            <td>{{$warehouse->name}}</td>
	            <td>{{$item->c_name}}</td>
	            <td>{{$item->transit_quantity[$warehouse->id]['normal']}}</td>
	            <td>{{$item->transit_quantity[$warehouse->id]['special']}}</td>
	            <td>{{$item->out_of_stock}}</td>
	            <td>{{$item->warehouse_quantity[$warehouse->id]['available_quantity']}}</td>
	            <td>{{$item->warehouse_quantity[$warehouse->id]['all_quantity']}}</td>
	            <td>{{$item->recently_purchase_time}}</td>
	            <td>{{$item->out_of_stock_time}}</td>
	        </tr>
        @endforeach
        
    @endforeach
@stop

@section("childJs")
<script src="{{ asset('js/jquery.datetimepicker.full.js') }}"></script>
<script type='text/javascript'>
$('.datetimepicker_dark').datetimepicker({theme:'dark'})
    $(document).ready(function(){
        $(document).on('click', '.download', function(){
            var user_id = $("#user_id").val();
            var status = $("#status").val();
            var sku = $("#sku").val();
            var date_from = $("#date_from").val();
            var date_to = $("#date_to").val();
            location.href="{{ route('purchase.exportOutOfStockCsv')}}?user_id="+user_id+"&status="+status+"&sku="+sku+"&date_from="+date_from+"&date_to="+date_to;
        })
    })
</script>
@stop