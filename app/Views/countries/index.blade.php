@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>国家名</th>
    <th>中文名</th>
    <th>地区</th>   
    <th>简码</th>
    <th>number</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $country)
        <tr>
            <td>{{ $country->id }}</td>
            <td>{{ $country->name }}</td>
            <td>{{ $country->cn_name }}</td>
            <td>{{ $country->countriesSort ? $country->countriesSort->name : ''}}</td>
            <td>{{ $country->code }}</td>
            <td>{{ $country->number }}</td>
            <td>{{ $country->created_at }}</td>
            <td>
                <a href="{{ route('countries.show', ['id'=>$country->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('countries.edit', ['id'=>$country->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $country->id }}"
                   data-url="{{ route('countries.destroy', ['id' => $country->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')@stop