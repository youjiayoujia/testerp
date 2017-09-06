@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">汇总</div>
        <div class="panel-body">
            <p>全部数据共{{ count($errors[0])}}条，出错{{ count($errors)-1 }}条</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">失败记录</div>
        <div class="panel-body">
            <div class='row'>
                <div class='col-lg-2'><label>package_id</label></div>
                <div class='col-lg-2'><label>is_over</label></div>
            </div>
            @foreach($errors as $key => $value)
            @if($key != 0)
            <div class='row'>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['package_id']) }}"></div>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['is_over']) }}"></div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
@stop