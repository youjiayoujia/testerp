@extends('common.table')
@section('tableToolButtons')

@stop

@section('tableHeader')

    <th class="sort" data-field="id">ID</th>
    <th>TransactionID</th>
    <th>ItemID</th>
    <th>Comment type</th>
    <th>Comment text</th>
    <th>Comment user</th>
    <th>Comment time</th>
    <th>Seller</th>
    <th>获取时间</th>
    <th>操作</th>

@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->transaction_id}}</td>
            <td>{{$item->ebay_item_id}}</td>
            <td>
                @if($item->comment_type == 'Positive')
                    <span class="label label-success">{{$item->comment_type}}</span>
                @elseif($item->comment_type == 'Negative')
                    <span class="label label-danger">{{$item->comment_type}}</span>
                @else
                    <span class="label label-warning">{{$item->comment_type}}</span>
                @endif
            </td>
            <td>{{$item->comment_text}}</td>
            <td>{{$item->commenting_user}}</td>
            <td>{{$item->comment_time}}</td>
            <td>{{$item->ChannelAccountAlias}}</td>
            <td>{{$item->created_at}}</td>
            <td></td>
        </tr>
    @endforeach

@stop
