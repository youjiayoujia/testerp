@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 渠道
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($channels as $channel)
                <li>
                    <a href="{{ DataList::filtersEncode(['channel_id','=',$channel->id]) }}">{{ $channel->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 启用
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ DataList::filtersEncode(['is_available','=',1]) }}">启用</a>
                <a href="{{ DataList::filtersEncode(['is_available','=',0]) }}">停用</a>
            </li>
        </ul>
    </div>
    @parent
@stop
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="channel_id">渠道</th>
    <th class="sort" data-field="name">账号</th>
    <th class="sort" data-field="alias">账号别名</th>
    <th>国家</th>
    <th>订单前缀</th>
    <th>订单同步周期</th>
    <th>订单抓取天数</th>
    <th>订单每页抓取数</th>
    <th>运营人员</th>
    <th>客服人员</th>
    <th>客服邮箱地址</th>
    <th>状态</th>
    <th>地域渠道名</th>
    <th>创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $account)
        <tr>
            <td>{{ $account->id }}</td>
            <td>{{ $account->channel ? $account->channel->name : '' }}</td>
            <td>{{ $account->account }}</td>
            <td>{{ $account->alias }}</td>
            <td>{{ $account->country ? $account->country->cn_name:'全球' }}</td>
            <td>{{ $account->order_prefix }}</td>
            <td>{{ $account->sync_cycle }}</td>
            <td>{{ $account->sync_days }}天</td>
            <td>{{ $account->sync_pages }}</td>
            <td>{{ $account->operator ? $account->operator->name : '无' }}</td>
            <td>{{ $account->customer_service ? $account->customer_service->name : '无' }}</td>
            <td>{{ $account->service_email }}</td>
            <td>{{ $account->is_available?'启用':'停用' }}</td>
            <td>{{ $account->catalogChannelName }}</td>
            <td>{{ $account->created_at }}</td>
            <td>{{ $account->updated_at }}</td>
            <td>
                <a href="{{ route('channelAccount.show', ['id'=>$account->id]) }}" class="btn btn-info btn-xs" title="查看">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                <a href="{{ route('channelAccount.edit', ['id'=>$account->id]) }}" class="btn btn-warning btn-xs" title="编辑">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <button class="btn btn-primary btn-xs"
                        data-toggle="modal"
                        data-target="#myModal{{ $account->id }}"
                        title="API设置">
                    <span class="glyphicon glyphicon-link"></span>
                </button>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $account->id }}"
                   data-url="{{ route('channelAccount.destroy', ['id' => $account->id]) }}" title="删除">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </td>
        </tr>
        @include('channel.account.api.'.$account->channel->driver)
    @endforeach
@stop
