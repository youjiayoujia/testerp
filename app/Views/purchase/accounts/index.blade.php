@extends('common.table')
@section('tableHeader')
    <th>ID</th>
    <th>账户名</th>
    <th>账户ID</th>
    <th>access_token</th>
    <th>账号负责人</th>
    <th class="sort">创建时间</th>
    <th class="sort" >更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $account)
        <tr>
            <td>{{ $account->id }}</td>
            <td>{{ $account->resource_owner}}</td>
            <td>{{ $account->memberId}}</td>
            <td>{{ $account->access_token}}</td>
            <td>
                @if($account->purchase_user_id)
                    {{ $account->PurchaseUserName}}
                @endif
            </td>
            <td>{{ $account->updated_at}}</td>
            <td>{{ $account->created_at}}</td>

            <td>
                <a href="{{ route('purchaseAccount.show', ['id'=>$account->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('purchaseAccount.edit', ['id'=>$account->id]) }}" class="btn btn-warning btn-xs index-a-edit">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
{{--                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $account->id }}"
                   data-url="{{ route('purchaseAccount.destroy', ['id' => $account->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>--}}

                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $account->table }}" data-id="{{$account->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@stop
