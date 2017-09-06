@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>物流限制名称</th>
    <th>图标</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $logisticsLimits)
        <tr>
            <td>{{ $logisticsLimits->id }}</td>
            <td>{{ $logisticsLimits->name }}</td>

            <td>
                @if($logisticsLimits->ico)
                    <img width="40px" src="{{config('logistics.limit_ico_src').$logisticsLimits->ico}}" />
                @else
                    无
                @endif
            </td>
            <td>{{ $logisticsLimits->created_at }}</td>
            <td>{{ $logisticsLimits->updated_at }}</td>
            <td>
                <a href="{{ route('logisticsLimits.show', ['id'=>$logisticsLimits->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsLimits.edit', ['id'=>$logisticsLimits->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $logisticsLimits->id }}"
                   data-url="{{ route('logisticsLimits.destroy', ['id' => $logisticsLimits->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
