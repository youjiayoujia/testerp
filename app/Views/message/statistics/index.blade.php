@extends('common.table')
@section('tableToolButtons')

@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>客服</th>
    <th class="sort" data-field="should_reply">分配数量</th>
    <th class="sort" data-field="reply">已回复</th>
    <th class="sort" data-field="not_reply">未完成</th>
    <th class="sort" data-field="compute_time">统计时间</th>

@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->user_name}}</td>
            <td>{{$item->should_reply}}</td>
            <td>{{$item->reply}}</td>
            <td>{{$item->not_reply}}</td>
            <td>{{$item->compute_time}}</td>
        </tr>
    @endforeach

@stop
@section('childJs')

@stop