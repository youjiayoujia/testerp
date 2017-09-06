@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">ID</th>
    <th>批次号</th>
    <th>计费重量(kg)</th>
    <th>理论重量(kg)</th>
    <th>计费总运费(元)</th>
    <th>理论总运费(元)</th>
    <th>总条数</th>
    <th>各渠道均价(元)</th>
    <th>导入人</th>
    <th class="sort" data-field="created_at">导入时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $shipmentCost)
        <tr>
            <td><input type='checkbox' name='single[]' class='single'></td>
            <td>{{ $shipmentCost->id }}</td>
            <td>{{ $shipmentCost->shipmentCostNum }}</td>
            <td>{{ $shipmentCost->all_weight }}</td>
            <td>{{ $shipmentCost->theory_weight }}</td>
            <td>{{ $shipmentCost->all_shipment_cost }}</td>
            <td>{{ $shipmentCost->theory_shipment_cost }}</td>
            <td>{{ $shipmentCost->items->count() }}</td>
            <td>{{ $shipmentCost->average_price }}</td>
            <td>{{ $shipmentCost->importBy ? $shipmentCost->importBy->name : '' }}</td>
            <td>{{ $shipmentCost->created_at }}</td>
            <td>
                <a href="{{ route('shipmentCostItem.showInfo', ['id'=>$shipmentCost->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span> 
                </a>
                <a href="{{ route('shipmentCostError.showError', ['id'=>$shipmentCost->id]) }}" class="btn btn-danger btn-xs" title='错误信息'>
                    <span class="glyphicon glyphicon-eye-open"></span> 
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success import" href="javascript:">
        导入数据
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-success export" href="javascript:">
        导出模板
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-primary destroyRows" href="javascript:">
        批量删除
    </a>
</div>
@stop
@section('childJs')
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.export', function(){
        location.href = "{{ route('shipmentCost.export') }}";
    });

    $(document).on('click', '.import', function(){
        location.href = "{{ route('shipmentCost.import') }}";
    });

    $(document).on('click', '.destroyRows', function(){
        arr = new Array();
        i = 0;
        $.each($('.single:checked'), function () {
            tmp = $(this).parent().next().text();
            arr[i] = tmp;
            i++;
        })
        if (arr.length) {
            if(confirm('确认删除?')) {
                location.href = "{{ route('shipmentCost.destroyRows', ['arr' => '']) }}/" + arr;
            }
        } else {
            alert('未选择信息');
        }
    });

    $('.select_all').click(function () {
        if ($(this).prop('checked') == true) {
            $('.single').prop('checked', true);
        } else {
            $('.single').prop('checked', false);
        }
    });
})
</script>
@stop