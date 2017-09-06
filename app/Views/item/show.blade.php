@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>sku</strong>: {{ $model->sku }}
            </div>
            <div class="col-lg-3">
                <strong>产品中文名</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-3">
                <strong>产品英文名</strong>: {{ $model->c_name }}
            </div>
        </div>

        <div class="panel-body">
            <div class="col-lg-3">
                <strong>us税率</strong>: {{ $model->us_rate }}
            </div>
            <div class="col-lg-3">
                <strong>uk税率</strong>: {{ $model->uk_rate }}
            </div>
            <div class="col-lg-3">
                <strong>eu税率</strong>: {{ $model->eu_rate }}
            </div>
            <div class="col-lg-3">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Feature属性:</div>
        <div class="panel-body">
            @foreach($model->product->featureTextValues as $featureModel)
            <div class="col-lg-3">
                <strong>{{$featureModel->featureName->name}}</strong>: {{$featureModel->feature_value}}
            </div>
            @endforeach
        </div> 
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">供应商信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>主供应商名</strong>: {{ $model->supplier?$model->supplier->name:'' }}
            </div>
            <div class="col-lg-3">
                <strong>主供应商sku</strong>: {{ $model->supplier_sku }}
            </div>
            <div class="col-lg-3">
                <strong>辅助供应商</strong>: <?php if($model->second_supplier_id==0){echo "无辅供应商";}else{echo $model->product->supplier->where('id',$model->second_supplier_id)->get()->first()->name;} ?>
            </div>
            <div class="col-lg-3">
                <strong>辅供应商sku</strong>: {{ $model->second_supplier_sku }}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">采购信息 :</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>销售链接</strong>: {{ $model->product->product_sale_url }}
            </div>
            <div class="col-lg-6">
                <strong>采购链接</strong>: {{ $model->purchase_url }}
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>采购价（RMB）</strong>: {{ $model->purchase_price }}
            </div>
            <div class="col-lg-3">
                <strong>采购物流费（RMB）</strong>: {{ $model->purchase_carriage }}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"> 库存信息:</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>仓库</strong>: {{ count($warehouse)?$warehouse->name:'' }}
            </div>
            <div class="col-lg-1">
                <strong>库位</strong>: {{ $model->warehouse_position }}
            </div>
            <div class="col-lg-1">
                <strong>库存</strong>: {{ $model->all_quantity }}
            </div>
            <div class="col-lg-1">
                <strong>库存金额（RMB）</strong>: {{ $model->all_quantity*$model->cost }}
            </div>
            <div class="col-lg-1">
                <strong>采购天数</strong>: {{ $model->product->purchase_day}} 天
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">物流信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>尺寸类型</strong>: {{ $model->product_size }}
            </div>
            <div class="col-lg-3">
                <strong>item包装尺寸（cm）(长*宽*高)</strong>: {{ $model->package_size }}
            </div>
            <div class="col-lg-3">
                <strong>item重量（kg）</strong>: {{ $model->weight }}
            </div>
            <div class="col-lg-3">
                <strong>物流限制</strong>: 
                
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
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>状态</strong>: 
                {{config('item.status')[$model->status]}}
            </div>
            <div class="col-lg-3">
                <strong>是否激活</strong>: 
                {{config('item.is_available')[$model->is_available]}}
            </div>
            <div class="col-lg-3">
                <strong>投诉比例</strong>: 
            </div>
            <div class="col-lg-3">
                <strong>退款率</strong>: 
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>备注</strong>: {{ $model->remark }}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">其他信息 :</div>
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
    </div>
@stop
