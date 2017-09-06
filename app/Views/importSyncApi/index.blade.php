@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>关联ID</th>
    <th>类型</th>
    <th>route</th>
    <th>执行结果</th>
    <th>执行次数</th>
    <th>执行信息</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $api)
        <tr>
            <td>{{ $api->id }}</td>
            <td>{{ $api->relations_id }}</td>
            <td>{{ $api->type }}</td>
            <td>{{ $api->route }}</td>
            <td class="bg-{{ $api->color }}">{{ $api->text }}</td>
            <td>{{ $api->times }}</td>
            <td>{{ $api->error_msg }}</td>
            <td>{{ $api->created_at }}</td>
            <td>
                <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample{{ $api->id }}" aria-expanded="false" aria-controls="collapseExample">数据详情</a>
            </td>
        <tr class="collapse" id="collapseExample{{ $api->id }}">
            <td colspan="10">
                @if($api->data)
                    @foreach(unserialize($api->data) as $key => $value)
                        @if(is_array($value))
                            <dl class="dl-horizontal">
                                <dt>{{ $key }}</dt>
                                <dd>
                                    <pre>{{ var_dump($value) }}</pre>
                                </dd>
                            </dl>
                        @else
                            <dl class="dl-horizontal">
                                <dt>{{ $key }}</dt>
                                <dd>{{ $value }}</dd>
                            </dl>
                        @endif
                    @endforeach
                @endif
            </td>
        </tr>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 接口
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ DataList::filtersEncode(['type','=','product']) }}">产品</a>
                <a href="{{ DataList::filtersEncode(['type','=','supplier']) }}">供应商</a>
            </li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 结果
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ DataList::filtersEncode(['status','=','0']) }}">未成功</a>
                <a href="{{ DataList::filtersEncode(['status','=','1']) }}">成功</a>
            </li>
        </ul>
    </div>
@stop