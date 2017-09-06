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
                <div class='col-lg-3'><label>package_id</label></div>
                @if($type == '3')
                    <div class='col-lg-3'><label>tracking_no</label></div>
                @elseif ($type == '4')
                    <div class='col-lg-3'><label>logistics_id</label></div>
                    <div class='col-lg-3'><label>tracking_no</label></div>
                    <div class='col-lg-3'><label>失败原因</label></div>
                @else
                    <div class='col-lg-3'><label>cost</label></div>
                @endif
            </div>
            @foreach($errors as $key => $value)
            @if($key != 0)
            <div class='row'>
                <div class='col-lg-3'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['package_id']) }}"></div>
                @if($type == '3')
                    <div class='col-lg-3'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['tracking_no']) }}"></div>
                @elseif($type == '4')
                    <div class='col-lg-3'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['logistics_id']) }}"></div>
                    <div class='col-lg-3'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['tracking_no']) }}"></div>
                    <div class='col-lg-3'><input type='text' class='form-control' value="包裹不存在，或包裹状态不为已包装或已发货"></div>
                @else
                    <div class='col-lg-3'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['cost']) }}"></div>
                @endif
            </div>
            @endif
            @endforeach
        </div>
    </div>
@stop