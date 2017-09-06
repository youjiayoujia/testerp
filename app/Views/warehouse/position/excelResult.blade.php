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
                <div class='col-lg-1'><label>库位</label></div>
                <div class='col-lg-1'><label>仓库</label></div>
                <div class='col-lg-2'><label>备注</label></div>
                <div class='col-lg-2'><label>类型</label></div>
                <div class='col-lg-1'><label>长(cm)</label></div>
                <div class='col-lg-1'><label>宽(cm)</label></div>
                <div class='col-lg-1'><label>高(cm)</label></div>
                <div class='col-lg-1'><label>是否启用</label></div>
            </div>
            @foreach($errors as $key => $value)
            @if($key != 0)
            <div class='row'>
                <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['name']) }}"></div>
                <div class='col-lg-1'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['warehouse']) }}"></div>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['remark']) }}"></div>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ $errors[0][$value]['size'] == 'small' ? '小' : ($errors[0][$value]['size'] == 'middle' ? '中' : '大')}}"></div>
                <div class='col-lg-1'><input type='text' class='form-control' value="{{ $errors[0][$value]['length']}}"></div>
                <div class='col-lg-1'><input type='text' class='form-control' value="{{ $errors[0][$value]['width']}}"></div>
                <div class='col-lg-1'><input type='text' class='form-control' value="{{ $errors[0][$value]['height']}}"></div>
                <div class='col-lg-1'><input type='text' class='form-control' value="{{ $errors[0][$value]['is_available'] ? '启用' : '不启用'}}"></div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
@stop