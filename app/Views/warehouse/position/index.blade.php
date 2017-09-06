@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>库位名</th>
    <th>所属仓库名</th>
    <th>备注信息</th>
    <th class="sort" data-field="volumn">库位容积</th>
    <th>长(cm)</th>
    <th>宽(cm)</th>
    <th>高(cm)</th>
    <th>是否启用</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $position)
        <tr>
            <td>{{ $position->id }}</td>
            <td>{{ $position->name }}</td>            
            <td>{{ $position->warehouse ? $position->warehouse->name : '' }}</td>
            <td>{{ $position->remark }} </td>            
            <td>{{ $position->size == 'small' ? '小' : ($position->size == 'middle' ? '中' : '大') }}</td>
            <td>{{ $position->length }}</td>
            <td>{{ $position->width }}</td>
            <td>{{ $position->height }}</td>
            <td>{{ $position->is_available == '1' ? '是' : '否'}}</td>
            <td>{{ $position->created_at }}</td>
            <td>
                <a href="{{ route('warehousePosition.show', ['id'=>$position->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span> 
                </a>
                <a href="{{ route('warehousePosition.edit', ['id'=>$position->id]) }}" class="btn btn-warning btn-xs" title='编辑'>
                    <span class="glyphicon glyphicon-pencil"></span> 
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $position->id }}"
                   data-url="{{ route('warehousePosition.destroy', ['id' => $position->id]) }}" title='删除'>
                    <span class="glyphicon glyphicon-trash"></span> 
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $position->table }}" data-id="{{$position->id}}" title='日志'>
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr> 
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
        <i class="glyphicon glyphicon-plus"></i> 新增
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-info" href="{{ route('position.getExcel') }}">
        获取excel
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-success" href="{{ route('position.importByExcel') }}">
        excel导入
    </a>
</div>
@stop
