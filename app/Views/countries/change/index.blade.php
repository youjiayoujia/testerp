@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="country_from">来源国家</th>
    <th class="sort" data-field="country_to">目标国家</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $countriesChange)
        <tr>
            <td>{{ $countriesChange->id }}</td>
            <td>{{ $countriesChange->country_from }}</td>
            <td>{{ $countriesChange->country_to }}</td>
            <td>{{ $countriesChange->created_at }}</td>
            <td>{{ $countriesChange->updated_at }}</td>
            <td>
                <a href="{{ route('countriesChange.show', ['id'=>$countriesChange->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('countriesChange.edit', ['id'=>$countriesChange->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $countriesChange->id }}"
                   data-url="{{ route('countriesChange.destroy', ['id' => $countriesChange->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
