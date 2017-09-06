@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th class='sort' data-field='adjust_form_id'>调整单号</th>
    <th>仓库</th>
    <th>备注</th>
    <th>调整人</th>
    <th>状态</th>
    <th>审核人</th>
    <th class='sort' data-field='check_time'>审核时间</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $adjust)
        <tr>
            <td>{{ $adjust->id }}</td>
            <td>{{ $adjust->adjust_form_id }}</td>
            <td>{{ $adjust->warehouse ? $adjust->warehouse->name : '' }}</td>
            <td>{{ $adjust->remark }}</td>
            <td>{{ $adjust->adjustByName ? $adjust->adjustByName->name : '' }} </td>
            <td>{{ $adjust->status ? ($adjust->status == '1' ? '未通过' : '已通过') : '未审核' }}</td>
            <td>{{ $adjust->checkByName ? $adjust->checkByName->name : '' }}</td>
            <td>{{ $adjust->check_time }}</td>
            <td>{{ $adjust->created_at }}</td>
            <td>
                <a href="{{ route('stockAdjustment.show', ['id'=>$adjust->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span> 
                </a>
                @if(!$adjust->status)
                <a href="{{ route('stockAdjustment.edit', ['id'=>$adjust->id]) }}" class="btn btn-warning btn-xs" title='编辑'>
                    <span class="glyphicon glyphicon-pencil"></span> 
                </a>
                <a href="{{ route('stockAdjustment.check', ['id'=>$adjust->id]) }}"  class="btn btn-info btn-xs" title='审核'>
                    <span class="glyphicon glyphicon-comment"></span>
                    
                </a>
                @endif
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $adjust->table }}" data-id="{{$adjust->id}}" title='日志'>
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop