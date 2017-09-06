@extends('common.detail')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>物流分区</strong>: {{ $model->zone }}
            </div>
            <div class="col-lg-2">
                <strong>物流方式</strong>: {{ $model->logistics ? $model->logistics->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>物流方式简码</strong>: {{ $model->logistics ? $model->logistics->code : '' }}
            </div>
            <div class="col-lg-2">
                <strong>首重(kg)</strong>: {{ $model->fixed_weight }}
            </div>
            <div class="col-lg-2">
                <strong>首重价格</strong>: {{ $model->fixed_price }}
            </div>
            <div class="col-lg-2">
                <strong>续重(kg)</strong>: {{ $model->continued_weight }}
            </div>
            <div class="col-lg-2">
                <strong>续重价格</strong>: {{ $model->continued_price }}
            </div>
            <div class="col-lg-2">
                <strong>其他固定费用</strong>: {{ $model->other_fixed_price }}
            </div>
            <div class="col-lg-2">
                <strong>最后折扣</strong>: {{ $model->discount }}
            </div>
            <div class="col-lg-2">
                <strong>是否通折</strong>: {{ $model->discount_weather_all ? '是' : '否' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">区间价格</div>
        <div class="panel-body">
            @foreach($countries as $country)
            <div class='col-lg-2'>
                <input type='text' class='form-control' value="{{ $country->cn_name }}">
            </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">国家</div>
        <div class="panel-body">
            <div class='row'>
                <div class='form-group col-sm-3'>
                    <label for="weight_from">开始重量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-3">
                    <label for="weight_to">结束重量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-3">
                    <label for="price">价格</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            @foreach($sectionPrices as $key => $sectionPrice)
                <div class='row'>
                    <div class='form-group col-sm-3'>
                        <input class="form-control" placeholder="开始重量" name="arr[weight_from][{{$key}}]" value="{{ old('arr[weight_from][$key]') ? old('arr[weight_from][$key]') : $sectionPrice->weight_from }}">
                    </div>
                    <div class="form-group col-sm-3">
                        <input class="form-control" placeholder="结束重量" name="arr[weight_to][{{$key}}]" value="{{ old('arr[weight_to][$key]') ? old('arr[weight_to][$key]') : $sectionPrice->weight_to }}">
                    </div>
                    <div class="form-group col-sm-3 position_html">
                        <input class="form-control" placeholder="价格" name="arr[price][{{$key}}]" value="{{ old('arr[price][$key]') ? old('arr[price][$key]') : $sectionPrice->price }}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url : "{{ route('zoneShipping') }}",
            data : { id : '{{ $model->id }}' },
            dataType : 'json',
            type : 'get',
            success : function(result) {
                if (result == 'express') {
                    $("div#express").show();
                    $("div#packet").hide();
                }else {
                    $("div#packet").show();
                    $("div#express").hide();
                }
            }
        });
    });
</script>