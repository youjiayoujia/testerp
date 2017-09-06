@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>存储方式</th>
    <th>队列</th>
    <th>内容</th>
    <th class="sort" data-field="created_at">失败时间</th>
@stop
@section('tableBody')
    @foreach($data as $log)
        <tr>
            <td>{{ $log->id }}</td>
            <td>{{ $log->connection }}</td>
            <td>{{ $log->queue }}</td>
            <td>{{ $log->payload }}</td>
            <td>{{ $log->failed_at }}</td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 队列
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach(config('queue.queues') as $queue => $text)
                <li>
                    <a href="{{ DataList::filtersEncode(['queue','=',$queue]) }}">{{ $text }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@stop