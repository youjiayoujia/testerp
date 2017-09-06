@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>品类名称</strong>: {{ $model->all_name }}
            </div>
            <div class="col-lg-2">
                <strong>品类前缀</strong>: {{ $model->code }}
            </div>
            <div class="col-lg-2">
                <strong>分类</strong>: {{ $model->CatalogCategoryName }}
            </div>
            <div class="col-lg-3">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">set属性:</div>
        <div class="panel-body">
            @foreach($model->sets as $set)
                <div class="col-lg-12">
                    <div class="col-lg-1"><strong>{{ $set->name }}</strong>:</div>
                    @foreach($set->values as $setvalue)
                        <div class="col-lg-1">{{ $setvalue->name }}</div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">variation属性 :</div>
        <div class="panel-body">
            @foreach($model->variations as $attr)
                <div class="col-lg-12">
                    <div class="col-lg-1"><strong>{{ $attr->name }}</strong>:</div>
                    @foreach($attr->values as $attrvalue)
                        <div class="col-lg-1">{{ $attrvalue->name }}</div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">feature属性 :</div>
        <div class="panel-body">
            @foreach($model->features as $feature)
                <div class="col-lg-12">
                    <div class="col-lg-1"><strong>{{ $feature->name }}</strong>:</div>
                    @foreach($feature->values as $featurevalue)
                        <div class="col-lg-1">{{ $featurevalue->name }}</div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">平台费率 :</div>
        <div class="panel-body">
            @foreach($model->channels as $channel)
                <div class="col-lg-12">
                    <div class="col-lg-1"><strong>{{ $channel->name }}</strong>：</div>
                    <div class="col-lg-1">固定费：<strong>{{ $channel->pivot->flat_rate }}</strong></div>
                    <div class="col-lg-1">费率：<strong>{{ $channel->pivot->rate }}</strong></div>
                </div>
            @endforeach
        </div>
    </div>
@stop