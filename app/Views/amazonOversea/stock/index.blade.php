@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>sku</th>
    <th>卖家sku</th>
    <th>fnsku</th>
    <th>title</th>
    <th>fba总数量</th>
    <th>fba可用数量</th>
    <th>fba不可卖数量</th>
    <th>单位体积</th>
    <th>渠道帐号</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $fbaStock)
        <tr>
            <td>{{ $fbaStock->id }}</td>
            <td>{{ $fbaStock->item  ? $fbaStock->item->sku : '' }}</td>
            <td>{{ $fbaStock->channel_sku }}</td>
            <td>{{ $fbaStock->fnsku }}</td>
            <td>{{ $fbaStock->title }}</td>
            <td>{{ $fbaStock->afn_warehouse_quantity }}</td>
            <td>{{ $fbaStock->afn_fulfillable_quantity }}</td>
            <td>{{ $fbaStock->afn_unsellable_quantity }}</td>
            <td>{{ $fbaStock->per_unit_volume }}</td>
            <td>{{ $fbaStock->account ? $fbaStock->account->account : '' }}</td>
            <td>{{ $fbaStock->created_at }}</td>
            <td>
                <a href="{{ route('fbaStock.show', ['id'=>$fbaStock->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $fbaStock->id }}"
                   data-url="{{ route('fbaStock.destroy', ['id' => $fbaStock->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success" href="{{ route('fbaStock.updateStock') }}">
        <i class="glyphicon glyphicon-plus"></i> 更新库存信息
    </a>
</div>
@stop
