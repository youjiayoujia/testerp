@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>盘点表id</th>
    <th>盘点人</th>
    <th>盘点时间</th>
    <th>审核状态</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableTitle') @parent <font color='red'>(库存盘点时，出入库相关操作会冻结，完毕解冻)</font>@stop
@section('tableBody')
    @foreach($data as $taking)
        <tr>
            <td>{{ $taking->id }}</td>
            <td>{{ $taking->taking_id}}</td>
            <td>{{ $taking->stockTakingByName ? $taking->stockTakingByName->name : '' }}</td>
            <td>{{ $taking->stock_taking_time }}</td>
            <td>{{ $taking->check_status ? ($taking->check_status == '1' ? '审核未通过' : '审核通过') : '未审核'}}</td>
            <td>{{ $taking->created_at }}</td>
            <td>
                <a href="{{ route('stockTaking.show', ['id'=>$taking->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span> 
                </a>
                @if(!$taking->check_status && !$taking->create_status)
                    <a href="{{ route('stockTaking.edit', ['id'=>$taking->id]) }}" class="btn btn-warning btn-xs" title='录入实盘'>
                        <span class="glyphicon glyphicon-pencil"></span> 
                    </a>
                @endif
                @if(!$taking->check_status && !$taking->create_status && $taking->create_taking_adjustment == '1')
                    <a href="javascript:" class='btn btn-info btn-xs create_form' title='生成调整单'>
                        <span class="glyphicon glyphicon-eye-open"></span> 
                    </a>
                @endif
                @if(!$taking->check_status && $taking->create_status)
                    <a href="{{ route('StockTaking.takingAdjustmentShow', ['id'=>$taking->id])}}" class='btn btn-info btn-xs' title='调整单'>
                        <span class="glyphicon glyphicon-eye-open"></span> 
                    </a>
                @endif
                @if($taking->check_status == '0' && $taking->create_status == '1')
                    <a href="{{ route('stockTaking.takingCheck', ['id' => $taking->id])}}" class="btn btn-info btn-xs check" title='审核'>
                        <span class="glyphicon glyphicon-eye-open"></span> 
                    </a>
                @endif
                @if(!$taking->check_status)
                    <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                       data-id="{{ $taking->id }}"
                       data-url="{{ route('stockTaking.destroy', ['id' => $taking->id]) }}" title='删除'>
                        <span class="glyphicon glyphicon-trash"></span> 
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success" href="{{ route('stock.createTaking') }}">
        生成盘点表
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-success" href="{{ route('stock.getTakingExcel') }}">
        导出表格
    </a>
</div>
@stop
@section('childJs')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<script type='text/javascript'>
$(document).ready(function(){   
    $(document).on('click', '.create_form', function(){
        block = $(this).parent().parent();
        id = block.find('td:eq(0)').text();
        $.ajax({
            url:"{{route('stockTaking.takingCreate')}}",
            data:{id:id},
            dataType:'json',
            type:'get',
            success:function(result) {
                if(result != false) {
                    location.reload();
                }
            }
        })
    });
});
</script>
@stop