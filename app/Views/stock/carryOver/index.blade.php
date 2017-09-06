@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>月结时间</th>
    <th>仓库</th>
    <th>结转时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $model)
        <tr>
            <td>{{ $model->id }}</td>
            <td>{{ $model->date }}</td>
            <td>{{ $model->warehouse ? $model->warehouse->name : '' }}</td>
            <td>{{ $model->created_at }}</td>
            <td>
                <a href="{{ route('stockCarryOver.show', ['id'=>$model->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span> 
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success createCarryOver" href="{{ route('stockCarryOver.showStock') }}">
        查看库存
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-success" href="{{ route('stockCarryOver.createCarryOver') }}">
        月结
    </a>
</div>
@stop