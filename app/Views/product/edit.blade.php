@extends('common.form')
@section('formAction') {{ route('product.update', ['id' => $product->id]) }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <div class="form-group">
        <label for="catalog_id">分类</label>
        <select id="catalog_id" class="form-control" name="catalog_id" disabled="disabled">
            @foreach($catalogs as $_catalogs)
                <option value="{{ $_catalogs->id }}" {{ $_catalogs->id == $product->catalog_id ? 'selected' : '' }}>{{ $_catalogs->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="ajaxinsert">
        <div class="panel panel-info adjustmargin">
        <div class="panel-heading">选择variation属性:</div>            
                <div class="checkbox panel-body ">
                    <div class="checkbox col-md-2" style="width:auto">
                        <label style="padding-left:0px">
                            {{$product->model}}
                        </label>
                    </div>
                    @if($product->catalog)
                        @foreach($product->catalog->variations as $key=>$getattr)        
                            <div class="checkbox col-md-2 innercheckboxs">{{$getattr->name}}:
                                @foreach($getattr->values as $innervalue)
                                    <label>
                                        <input type='checkbox' class='{{$getattr->id}}-{{$innervalue->name}}' name='variations[{{$getattr->id}}][{{$innervalue->id}}]' value='{{$innervalue->name}}' {{ in_array($innervalue->id, $variation_value_id_arr)? 'checked' : '' }}>{{$innervalue->name}}
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
        </div>
        <div class="form-group third">
            <label for='set'>feature属性:</label>
            <div class="panel panel-info">
                <div class="checkbox panel-body "><?php $i=0; ?>
                    @if($product->catalog)
                        @foreach($product->catalog->features as $key=>$getfeature)
                            
                            @if($getfeature->type==1)
                                <div class="featurestyle" style="padding-bottom:10px">                           
                                        {{$getfeature->name}} : <input type="text" style="margin-left:15px" id="featuretext{{$getfeature->id}}" value="<?php if(count($features_input)>0)echo $features_input[$i]['feature_value'];$i++; ?>" name='featureinput[{{$getfeature->id}}]' />
                                </div>
                                
                            @elseif($getfeature->type==2)
                                <div class="radio">{{$getfeature->name}}
                                @foreach($getfeature->values as $value)
                                <label>
                                    <input class='{{$getfeature->id}}-{{$value->name}}' {{ in_array($value->id, $features_value_id_arr)? 'checked' : '' }} type='radio' name='features[{{$getfeature->id}}][]' value='{{$value->name}}'>{{$value->name}}
                                </label>
                                @endforeach
                                </div>
                            @else($getfeature->type==3)
                                <div class="checkbox">{{$getfeature->name}}
                                @foreach($getfeature->values as $value)
                                <label>
                                    <input class='{{$getfeature->id}}-{{$value->name}}' {{ in_array($value->id, $features_value_id_arr)? 'checked' : '' }} type='checkbox' name='features[{{$getfeature->id}}][]' value='{{$value->name}}'>{{$value->name}}
                                </label>
                                @endforeach
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div> 
    </div>
    <div class='row'>
        <div class="form-group col-md-3">
            <label for="color">产品中文名</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') ?  old('c_name') : $product->c_name }}">
        </div>
        <div class="form-group col-md-3">
            <label for="size">主供应商</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id="supplier_id" class="form-control supplier" name="supplier_id">
                @if($product->supplier)
                    <option value="{{$product->supplier->id}}">{{$product->supplier->name}}</option>
                @endif
            </select>
        </div> 
        <div class="form-group col-md-3">
            <label for="color">主供应商货号</label>
            <input class="form-control" id="supplier_sku" placeholder="主供应商货号" name='supplier_sku' value="{{ old('supplier_sku') ?  old('supplier_sku') : $product->supplier_sku }}">
        </div>
    </div>

    <div class='row'>  
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select id="second_supplier_id" class="form-control supplier" name="second_supplier_id">
               <option value="{{$product->second_supplier?$product->second_supplier->id:0}}">{{$product->second_supplier?$product->second_supplier->name:''}}</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">辅供应商货号</label>
            <input class="form-control" id="second_supplier_sku" placeholder="主供应商货号" name='second_supplier_sku' value="{{ old('second_supplier_sku') ?  old('second_supplier_sku') : $product->second_supplier_sku }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">采购链接</label>
            <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') ?  old('purchase_url') : $product->purchase_url }}">
        </div>
        <div class="form-group col-md-1">
            <label for="size">采购价(RMB)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_price" placeholder="采购价" name='purchase_price' value="{{ old('purchase_price') ?  old('purchase_price') : $product->purchase_price }}">
        </div>
        <div class="form-group col-md-1">
            <label for="color">采购物流费(RMB)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_carriage" placeholder="采购物流费" name='purchase_carriage' value="{{ old('purchase_carriage') ?  old('purchase_carriage') : $product->purchase_carriage }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">采购天数</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_day" placeholder="采购天数" name='purchase_day' value="{{ old('purchase_day') ?  old('purchase_day') : $product->purchase_day }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-md-3">
            <label for="size">仓库</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select  class="form-control" name="warehouse_id">
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ $product->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{$warehouse->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-1">
            <label for="size">尺寸类型</label><small class="text-danger glyphicon glyphicon-asterisk"></small>

            <select id="supplier_id" class="form-control" name="product_size">     
                <option value="大" {{ $product->product_size == '大' ? 'selected' : '' }}>大</option>
                <option value="中" {{ $product->product_size == '中' ? 'selected' : '' }}>中</option>
                <option value="小" {{ $product->product_size == '小' ? 'selected' : '' }}>小</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="color">产品包装尺寸（cm）(长*宽*高)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>(长*宽*高)
            <input class="form-control" id="package_size" placeholder="产品包装尺寸" name='package_size' value="{{ old('package_size') ?  old('package_size') : $product->package_size }}">
        </div>
        <div class="form-group col-md-1">
            <label for="size">产品重量(kg)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight" placeholder="产品重量" name='weight' value="{{ old('weight') ?  old('weight') : $product->weight }}">
        </div>
        <div class="form-group col-md-1">
            <label for="color">更新人</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="upload_user" placeholder="上传人" name='upload_user' value="{{ old('upload_user') ?  old('upload_user') : $product->upload_user }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="length">长</label>
            <input class="form-control" id="length" placeholder="length" name='length' value="{{ old('length') ?  old('length') : $product->length }}">
        </div>
            <div class="form-group col-md-3">
            <label for="width">宽</label>
            <input class="form-control" id="width" placeholder="width" name='width' value="{{old('width') ?  old('width') : $product->width }}">
        </div>
        <div class="form-group col-md-3">
            <label for="height">高</label>
            <input class="form-control" id="height" placeholder="height" name='height' value="{{ old('height') ?  old('height') : $product->height }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="package_length">包装后长</label>
            <input class="form-control" id="package_length" placeholder="package_length" name='package_length' value="{{ old('package_length') ?  old('package_length') : $product->package_length }}">
        </div>
            <div class="form-group col-md-3">
            <label for="package_width">包装后宽</label>
            <input class="form-control" id="package_width" placeholder="package_width" name='package_width' value="{{old('package_width') ?  old('package_width') : $product->package_width }}">
        </div>
        <div class="form-group col-md-3">
            <label for="package_height">包装后高</label>
            <input class="form-control" id="package_height" placeholder="package_height" name='package_height' value="{{ old('package_height') ?  old('package_height') : $product->package_height }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="size">采购负责人</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id="purchase_adminer" class="form-control purchase_adminer" name="purchase_adminer">
               <option value="{{$product->purchaseAdminer?$product->purchase_adminer:0}}">{{$product->purchaseAdminer?$product->purchaseAdminer->name:''}}</option>
            </select>
        </div> 
        <div class="form-group col-md-3">
            <label for="color">url1</label>
            <input class="form-control" id="url1" placeholder="url" name='url1' value="{{ old('url1') ?  old('url1') : $product->url1 }}">
        </div>
            <div class="form-group col-md-3">
            <label for="size">url2</label>
            <input class="form-control" id="url2" placeholder="url" name='url2' value="{{old('url2') ?  old('url2') : $product->url2 }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">url3</label>
            <input class="form-control" id="url3" placeholder="url" name='url3' value="{{ old('url3') ?  old('url3') : $product->url3 }}">
        </div>
    </div>

    <div class='row'>
        <div class="form-group col-md-12" style="">
            <label for="color">物流限制</label><br>
            @foreach($logisticsLimit as $carriage_limit)
                    <label>
                        <input type='checkbox' name='carriage_limit_arr[]' value='{{$carriage_limit->id}}' {{ in_array($carriage_limit->id, $logisticsLimit_arr)? 'checked' : '' }} >@if($carriage_limit->ico)<img width="30px" src="{{config('logistics.limit_ico_src').$carriage_limit->ico}}" />@else{{$carriage_limit->name}} @endif
                    </label>
                    <br>
            @endforeach
        </div>
        <div class="form-group col-md-12" style="">
            <label for="color">包装限制</label>
            @foreach($wrapLimit as $wrap_limit)
                    <label>
                        <input type='checkbox' name='package_limit_arr[]' value='{{$wrap_limit->id}}' {{ in_array($wrap_limit->id, $wrapLimit_arr)? 'checked' : '' }} >{{$wrap_limit->name}}
                    </label>
            @endforeach
        </div>
        
        <div class="form-group col-md-3">
            <label for="color">质检标准</label>
            <input class="form-control" id="quality_standard" placeholder="质检标准" name='quality_standard' value="{{ old('quality_standard') ?  old('quality_standard') : $product->quality_standard }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">尺寸描述</label>
            <input class="form-control" id="size_description" placeholder="尺寸描述" name='size_description' value="{{ old('size_description') ?  old('size_description') : $product->size_description }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">描述</label>
            <input class="form-control" id="description" placeholder="备注" name='description' value="{{ old('description') ?  old('description') : $product->description }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $product->remark }}">
        </div>
    </div>

    <div class="row">    
        <div class="form-group col-md-3">
            <label for="color">申报中文</label>
            <input class="form-control" id="declared_cn" placeholder="申报中文" name='declared_cn' value="{{ old('declared_cn') ?  old('declared_cn') : $product->declared_cn }}">
        </div>
        
        <div class="form-group col-md-3">
            <label for="color">申报英文</label>
            <input class="form-control" id="declared_en" placeholder="申报中文" name='declared_en' value="{{ old('declared_en') ?  old('declared_en') : $product->declared_en }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">注意事项</label>
            <input class="form-control" id="declared_value" placeholder="注意事项" name='notify' value="{{ old('notify') ?  old('notify') : $product->notify }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">配件</label>
            <input class="form-control" id="parts" placeholder="配件" name='parts' value="{{ old('parts') ?  old('parts') : $product->parts }}">
        </div>
    </div>
@stop

@section('pageJs')
    <script type="text/javascript">
        $('.supplier').select2({
                ajax: {
                    url: "{{ route('ajaxSupplier') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        supplier:params.term,
                      };
                    },
                    results: function(data, page) {
                        
                    }
                },
            });

            $('.purchase_adminer').select2({
                ajax: {
                    url: "{{ route('ajaxUser') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        user:params.term,
                      };
                    },
                    results: function(data, page) {
                        
                    }
                },
        });

    </script>
@stop