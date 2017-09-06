@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">ID</th>
    <th>sku</th>
    <th>packageNum</th>
    <th>库位</th>
    <th>仓库</th>
    <th>数量</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $model)
        <tr>
            <td><input type='checkbox' name='single[]' class='single'></td>
            <td>{{ $model->id }}</td>
            <td>{{ $model->item ? $model->item->sku : ''}}</td>
            <td>{{ $model->packageNum }} </td>
            <td>{{ $model->warehousePosition ? $model->warehousePosition->name : '' }}</td>
            <td>{{ $model->warehouse ? $model->warehouse->name : '' }}</td>
            <td>{{ $model->quantity }}</td>
            <td>{{ $model->created_at }}</td>
            <td>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $model->id }}"
                   data-url="{{ route('errorList.destroy', ['id' => $model->id]) }}" title='删除'>
                    <span class="glyphicon glyphicon-trash"></span> 
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success exportException" href="javascript:">
        导出数据
    </a>
</div>
@stop
@section("childJs")
<script type='text/javascript'>
    $(document).ready(function(){
        $('.select_all').click(function () {
            if ($(this).prop('checked') == true) {
                $('.single').prop('checked', true);
            } else {
                $('.single').prop('checked', false);
            }
        });

        $(document).on('click', '.exportException', function(){
            arr = new Array()
            i = 0;
            $.each($('.single:checked'), function(){
                arr[i] = $(this).parent().next().text();
                i++;
            })
            location.href="{{ route('errorList.exportException', ['arr' => ''])}}/" + arr;
        })
    })
</script>
@stop