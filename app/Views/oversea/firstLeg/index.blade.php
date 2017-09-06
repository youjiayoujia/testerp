@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>物流名</th>
    <th>仓库</th>
    <th>运输方式</th>
    <th>时效(天)</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $firstLeg)
        <tr>
            <td>{{ $firstLeg->id }}</td>
            <td>{{ $firstLeg->name }}</td>
            <td>{{ $firstLeg->warehouse ? $firstLeg->warehouse->name : ''}}</td>
            <td>{{ $firstLeg->transport == '0' ? '海运' : '空运' }}</td>
            <td>{{ $firstLeg->days }}</td>
            <td>{{ $firstLeg->created_at }}</td>
            <td>
                <a href="{{ route('firstLeg.show', ['id'=>$firstLeg->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                <a href="{{ route('firstLeg.edit', ['id'=>$firstLeg->id]) }}" class="btn btn-warning btn-xs" title='编辑'>
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $firstLeg->id }}"
                   data-url="{{ route('firstLeg.destroy', ['id' => $firstLeg->id]) }}" title='删除'>
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </td>
        </tr>
    @endforeach
@stop
