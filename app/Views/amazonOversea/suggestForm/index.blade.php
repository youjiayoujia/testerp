@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>sku</th>
    <th>渠道sku</th>
    <th>fnsku</th>
    <th>fba库存总数量</th>
    <th>fba库存可用数量</th>
    <th>本地总数量</th>
    <th>7天销量</th>
    <th>14天销量</th>
    <th>建议采购数</th>
    <th>渠道帐号</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $suggestForm)
        <tr>
            <td>{{ $suggestForm->id }}</td>
            <td>{{ $suggestForm->item ? $suggestForm->item->sku : '' }}</td>
            <td>{{ $suggestForm->channel_sku }}</td>
            <td>{{ $suggestForm->fnsku }}</td>
            <td>{{ $suggestForm->fba_all_quantity }}</td>
            <td>{{ $suggestForm->fba_available_quantity }}</td>
            <td>{{ $suggestForm->all_quantity }}</td>
            <td>{{ $suggestForm->sales_in_seven }}</td>
            <td>{{ $suggestForm->sales_in_fourteen }}</td>
            <td>{{ $suggestForm->suggest_quantity }}</td>
            <td>{{ $suggestForm->account ? $suggestForm->account->account : '' }}</td>
            <td>{{ $suggestForm->created_at }}</td>
            <td>{{ $suggestForm->updated_at }}</td>
            <td>
                <a href="{{ route('suggestForm.show', ['id'=>$suggestForm->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $suggestForm->id }}"
                   data-url="{{ route('suggestForm.destroy', ['id' => $suggestForm->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success" href="{{ route('suggestForm.createForms') }}">
        一键生成
    </a>
</div>
@stop