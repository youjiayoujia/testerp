@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">编号</th>
    <th>类型</th>
    <th>名称</th>
    <th>协议客户</th>
    <th>邮编</th>
    <th>电话</th>
    <th>寄件人</th>
    <th>城市</th>
    <th>省份</th>
    <th>国家代码</th>
    <th>发件地址</th>
    <th>退件单位</th>
    <th>备注</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $emailTemplate)
        <tr>
            <td>{{ $emailTemplate->id }}</td>
            <td>{{ $emailTemplate->type == 'default' ? '默认' : 'EUB'}}</td>
            @if($emailTemplate->type == 'default')
                <td>{{ $emailTemplate->name }}</td>
            @else
                <td>{{ $emailTemplate->eub_head }}</td>
            @endif
            <td>{{ $emailTemplate->customer }}</td>
            <td>{{ $emailTemplate->zipcode }}</td>
            <td>{{ $emailTemplate->phone }}</td>
            <td>{{ $emailTemplate->sender }}</td>
            <td>{{ $emailTemplate->city }}</td>
            <td>{{ $emailTemplate->province }}</td>
            <td>{{ $emailTemplate->country_code }}</td>
            <td>{{ $emailTemplate->address }}</td>
            <td>{{ $emailTemplate->unit }}</td>
            <td>{{ $emailTemplate->remark }}</td>
            <td>{{ $emailTemplate->updated_at }}</td>
            <td>{{ $emailTemplate->created_at }}</td>
            <td>
                <a href="{{ route('logisticsEmailTemplate.show', ['id'=>$emailTemplate->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsEmailTemplate.edit', ['id'=>$emailTemplate->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $emailTemplate->id }}"
                   data-url="{{ route('logisticsEmailTemplate.destroy', ['id' => $emailTemplate->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $emailTemplate->table }}" data-id="{{$emailTemplate->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
