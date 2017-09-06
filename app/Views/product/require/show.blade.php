@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">基础信息</div>
    <div class="panel-body">
        @if($model->img1)
            <div class="col-lg-2">
                <strong>图片1</strong>: <img src="{{ $model->img1 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($model->img2)
            <div class="col-lg-2">
                <strong>图片2</strong>: <img src="{{ $model->img2 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($model->img3)
            <div class="col-lg-2">
                <strong>图片3</strong>: <img src="{{ $model->img3 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($model->img4)
            <div class="col-lg-2">
                <strong>图片4</strong>: <img src="{{ $model->img4 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($model->img5)
            <div class="col-lg-2">
                <strong>图片5</strong>: <img src="{{ $model->img5 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($model->img6)
            <div class="col-lg-2">
                <strong>图片6</strong>: <img src="{{ $model->img6 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        <div class="col-lg-2">
            <strong>URL1</strong>: {{ $model->url1 }}
        </div>
        <div class="col-lg-2">
            <strong>URL2</strong>: {{ $model->url2 }}
        </div>
        <div class="col-lg-2">
            <strong>URL3</strong>: {{ $model->url3 }}
        </div>
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>产品名</strong>: {{ $model->name }}
        </div>
        <div class="col-lg-2">
            <strong>品类</strong>: {{ $model->catalogByName ? $model->catalogByName->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>货源地(省)</strong>: {{ $model->province }}
        </div>
        <div class="col-lg-2">
            <strong>货源地(市)</strong>: {{ $model->city }}
        </div>
        <div class="col-lg-2">
            <strong>颜色</strong>: {{ $model->color }}
        </div>
        <div class="col-lg-2">
            <strong>材料</strong>: {{ $model->material }}
        </div>
        <div class="col-lg-2">
            <strong>工艺</strong>: {{ $model->technique }}
        </div>
        <div class="col-lg-2">
            <strong>配件</strong>: {{ $model->parts }}
        </div>
        <div class="col-lg-2">
            <strong>类似款sku</strong>: {{ $model->similar_sku }}
        </div>
        <div class="col-lg-2">
            <strong>竞争产品url</strong>: {{ $model->competition_url }}
        </div>
        <div class="col-lg-2">
            <strong>需求描述</strong>: {{ $model->remark }}
        </div>
        <div class="col-lg-2">
            <strong>期待上传日期</strong>: {{ $model->expected_date }}
        </div>
        <div class="col-lg-2">
            <strong>需求渠道</strong>: {{ $model->neederName ? $model->channel->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>需求帐号</strong>: {{ $model->channelAccount?$model->channelAccount->account : '' }}
        </div>
        <div class="col-lg-2">
            <strong>采购人</strong>: {{ $model->purchase?$model->purchase->name : '' }}
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">日志信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>创建人</strong>: {{ $model->createdByName ? $model->createdByName->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>创建时间</strong>: {{ $model->created_at }}
        </div>
        <div class="col-lg-2">
            <strong>状态</strong>: {{ $model->status ? ($model->status == '1' ? '未找到' : ($model->status == '2' ? '已找到' : ('已创建'))) : '新需求'}}
        </div>
        <div class="col-lg-2">
            <strong>处理者</strong>: {{ $model->userName ? $model->userName->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>处理时间</strong>: {{ $model->handle_time }}
        </div>
    </div>
</div>
@stop