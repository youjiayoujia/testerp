@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>spu</strong>: {{ $model->spu->spu }}
            </div>
            <div class="col-lg-3">
                <strong>model</strong>: {{ $model->model }}
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>分类</strong>: {{ $model->catalog?$model->catalog->name:'无分类' }}
            </div>
            <div class="col-lg-3">
                <strong>产品name</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-3">
                <strong>产品中文名</strong>: {{ $model->c_name }}
            </div>
        </div>

        <div class="panel-body">
            <div class="col-lg-3">
                <strong>尺寸类型</strong>: {{ $model->product_size }}
            </div>
            <div class="col-lg-3">
                <strong>产品包装尺寸（cm）(长*宽*高)</strong>: {{ $model->package_size }}
            </div>
            <div class="col-lg-3">
                <strong>产品重量（kg）</strong>: {{ $model->weight }}
            </div>
        </div> 

        <div class="panel-body">
            <div class="col-lg-3">
                <strong>长(单位：厘米)</strong>: {{ $model->length }}
            </div>
            <div class="col-lg-3">
                <strong>宽(单位：厘米)</strong>: {{ $model->width }}
            </div>
            <div class="col-lg-3">
                <strong>高(单位：厘米)</strong>: {{ $model->height }}
            </div>
        </div> 

        <div class="panel-body">
            <div class="col-lg-3">
                <strong>包装后长(单位：厘米)</strong>: {{ $model->package_length }}
            </div>
            <div class="col-lg-3">
                <strong>包装后宽(单位：厘米)</strong>: {{ $model->package_width }}
            </div>
            <div class="col-lg-3">
                <strong>包装后高(单位：厘米)</strong>: {{ $model->package_height }}
            </div>
        </div>       
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Feature属性:</div>
        <div class="panel-body">
            @foreach($model->featureTextValues as $featureModel)
            <div class="col-lg-3">
                <strong>{{$featureModel->featureName->name}}</strong>: {{$featureModel->feature_value}}
            </div>
            @endforeach
        </div> 
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">供应商信息:</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>主供应商</strong>: {{ $model->supplier?$model->supplier->name:'' }}
            </div>
            <div class="col-lg-3">
                <strong>销售链接</strong>: <a href="http://{{ $model->product_sale_url }}" target="_blank">{{ $model->product_sale_url }}</a>
            </div>
            <div class="col-lg-3">
                <strong>采购链接</strong>: <a href="http://{{ $model->purchase_url }}" target="_blank">{{ $model->purchase_url }}</a>
            </div>
            <div class="col-lg-3">
                <strong>采购价（RMB）</strong>: {{ $model->purchase_price }}
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>采购物流费（RMB）</strong>: {{ $model->purchase_carriage }}
            </div>
            <div class="col-lg-3">
                <strong>采购天数</strong>: {{ $model->purchase_day }} 天
            </div>
            <div class="col-lg-3">
                <strong>主供应商sku</strong>: {{ $model->supplier_sku }}
            </div>
            <div class="col-lg-3">
                <strong>辅供应商</strong>: <?php if($model->second_supplier_id==0){echo "无辅供应商";}else{echo $model->supplier->where('id',$model->second_supplier_id)->get()->first()->name;} ?>
            </div>
        </div>
        <div class="panel-body">
            
            <div class="col-lg-3">
                <strong>仓库</strong>: 
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">通关报关信息:</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>hscode</strong>: {{ $model->hs_code }}
            </div>
            <div class="col-lg-3">
                <strong>unit</strong>: <a href="http://{{ $model->product_sale_url }}" target="_blank">{{ $model->unit }}</a>
            </div>
            <div class="col-lg-3">
                <strong>规格型号</strong>: <a href="http://{{ $model->purchase_url }}" target="_blank">{{ $model->specification_model }}</a>
            </div>
            <div class="col-lg-3">
                <strong>通关状态</strong>: <?php if($model->specification_model==0){echo "已通关";}else{echo "未通关";} ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">其他信息:</div>
        <div class="panel-body">
            <div class="col-lg-12">
                <strong>物流限制</strong>：
            </div>
            @foreach($logisticsLimit_arr as $key=>$logistics_limit)
                <div class="col-lg-12" @if($key==0)style="margin-top:10px" @endif>
                    <img width="30px" src="{{config('logistics.limit_ico_src').$logistics_limit}}" />
                </div>
            @endforeach
        </div>
        <div class="panel-body">
            <div class="col-lg-12">
                <strong>包装限制</strong>：
            </div>
            @foreach($wrapLimit_arr as $key=>$wrap_limit)
                <div class="col-lg-12" @if($key==0)style="margin-top:10px" @endif>
                    {{$key+1}}. {{$wrap_limit}}
                </div>
            @endforeach
        </div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>采购负责人</strong>: {{ $model->purchase_adminer }}
            </div>
            <div class="col-lg-3">
                <strong>上传人</strong>: {{ $model->upload_user }}
            </div>
            <div class="col-lg-3">
                <strong>备注</strong>: {{ $model->remark }}
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>url1</strong>: {{ $model->url1 }}
            </div>
            <div class="col-lg-3">
                <strong>url2</strong>: {{ $model->url2 }}
            </div>
            <div class="col-lg-3">
                <strong>url3</strong>: {{ $model->url3 }}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">产品图片 :</div>
        <div class="panel-body">
            <?php if(isset($model->image->name)){ ?>
            <img src="{{ asset($model->image->path) }}/{{$model->image->name}}" width="600px" >
            <?php }else{ ?>
                无图片
            <?php } ?>
        </div>
    </div>
@stop