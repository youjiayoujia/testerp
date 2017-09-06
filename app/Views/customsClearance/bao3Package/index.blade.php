@extends('common.table')
@section('tableHeader')
    <th>package ID</th>
    <th>orderID</th>
    <th>shipping</th>
    <th>tracking_no</th>
    <th>print_time</th>
    <th>发往南京</th>
    <th>海关审结</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $bao3package)
        <tr>
            <td>{{ $bao3package->id }}</td>
            <td>{{ $bao3package->order_id }}</td>
            <td>{{ $bao3package->logistics ? $bao3package->logistics->name : '' }}</td>
            <td>{{ $bao3package->tracking_no }}</td>
            <td>{{ $bao3package->printed_at }}</td>
            <td>{{ $bao3package->is_tonanjing ? '是' : '否' }}</td>
            <td>{{ $bao3package->is_over ? '是' : '否' }}</td>
            <td>{{ $bao3package->created_at }}</td>
            <td>
                <a href="{{ route('bao3Package.show', ['id'=>$bao3package->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $bao3package->id }}"
                   data-url="{{ route('bao3Package.destroy', ['id' => $bao3package->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')@stop