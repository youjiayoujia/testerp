@extends('common.form')
@section('formAction') {{ route('item.update', ['id' => $model->id]) }} @stop
@section('formBody')
<script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
<script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
<script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
<link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">
 <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <input type='hidden' value='PUT' name="_method">
    <div class="row">
        <div class="form-group col-md-3">
            <label for="size">sku</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" disabled="disabled" id="name" placeholder="sku" name='sku' value="{{ old('sku') ?  old('sku') : $model->sku }}">
        </div>

        <div class="form-group col-md-3">
            <label for="size">英文</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="英文"  name='name' value="{{ old('name') ?  old('name') : $model->name }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">中文</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="c_name" placeholder="中文"  name='c_name' value="{{ old('c_name') ?  old('c_name') : $model->c_name }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="size">主供应商</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id="supplier_id" class="form-control supplier" name="supplier_id">
               <option value="{{$model->supplier?$model->supplier->id:0}}">{{$model->supplier?$model->supplier->name:''}}</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">采购链接</label>
            <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') ?  old('purchase_url') : $model->purchase_url }}">
        </div>
        <div class="form-group col-md-3">
            <label for="size">采购价（RMB）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_price" placeholder="采购价" name='purchase_price' value="{{ old('purchase_price') ?  old('purchase_price') : $model->purchase_price }}">
        </div>
    </div>
    <div class="row">
        
        <div class="form-group col-md-2">
            <label for="color">采购物流费（RMB）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_carriage" placeholder="采购物流费" name='purchase_carriage' value="{{ old('purchase_carriage') ?  old('purchase_carriage') : $model->purchase_carriage }}">
        </div>
        
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="size">仓库</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select  class="form-control warehouse_id" name="warehouse_id" >
                <option value="0"></option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ $model->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{$warehouse->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">库位</label>
            <select id="warehouse_position" class="form-control" name="warehouse_position">
                <option value="{{$model->warehousePosition?$model->warehousePosition->id:0}}">{{$model->warehousePosition?$model->warehousePosition->name:''}}</option>
            </select>
        </div>
    </div>

    <div class="row">
        <!-- <div class="form-group col-md-3">
            <label for="size">尺寸类型</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id="product_size" class="form-control" name="product_size">     
                <option value="大" {{ $model->product_size == '大' ? 'selected' : '' }}>大</option>
                <option value="中" {{ $model->product_size == '中' ? 'selected' : '' }}>中</option>
                <option value="小" {{ $model->product_size == '小' ? 'selected' : '' }}>小</option>
            </select>
        </div> -->
        <!-- <div class="form-group col-md-3">
            <label for="color">item包装尺寸（cm）(长*宽*高)</label>
            <input class="form-control" id="package_size" placeholder="产品包装尺寸" name='package_size' value="{{ old('package_size') ?  old('package_size') : $model->package_size }}">
        </div> -->
        <div class="form-group col-md-3">
            <label for="size">包装前重量（kg）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight" placeholder="包装前重量" name='weight' value="{{ old('weight') ?  old('weight') : $model->weight }}">
        </div>   
        <div class="form-group col-md-3">
            <label for="size">包装后重量（kg）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="package_weight" placeholder="包装后重量" name='package_weight' value="{{ old('package_weight') ?  old('package_weight') : $model->package_weight }}">
        </div>      
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="length">长</label>
            <input class="form-control" id="length" placeholder="length" name='length' value="{{ old('length') ?  old('length') : $model->length }}">
        </div>
            <div class="form-group col-md-3">
            <label for="width">宽</label>
            <input class="form-control" id="width" placeholder="width" name='width' value="{{old('width') ?  old('width') : $model->width }}">
        </div>
        <div class="form-group col-md-3">
            <label for="height">高</label>
            <input class="form-control" id="height" placeholder="height" name='height' value="{{ old('height') ?  old('height') : $model->height }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="package_length">包装后长</label>
            <input class="form-control" id="package_length" placeholder="package_length" name='package_length' value="{{ old('package_length') ?  old('package_length') : $model->package_length }}">
        </div>
            <div class="form-group col-md-3">
            <label for="package_width">包装后宽</label>
            <input class="form-control" id="package_width" placeholder="package_width" name='package_width' value="{{old('package_width') ?  old('package_width') : $model->package_width }}">
        </div>
        <div class="form-group col-md-3">
            <label for="package_height">包装后高</label>
            <input class="form-control" id="package_height" placeholder="package_height" name='package_height' value="{{ old('package_height') ?  old('package_height') : $model->package_height }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="color">us税率</label>
            
            <input class="form-control" id="us_rate" placeholder="us税率" name='us_rate' value="{{ old('us_rate') ?  old('us_rate') :$model->us_rate }}">
        </div>

        <div class="form-group col-md-3">
            <label for="size">uk税率</label>
            <input class="form-control" id="uk_rate" placeholder="uk税率" name='uk_rate' value="{{ old('uk_rate') ?  old('uk_rate') :$model->uk_rate }}">
        </div>

        <div class="form-group col-md-3">
            <label for="size">eu税率</label>
            <input class="form-control" id="eu_rate" placeholder="eu税率" name='eu_rate' value="{{ old('eu_rate') ?  old('eu_rate') :$model->eu_rate }}">
        </div>

    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="color">申报价值</label>
            
            <input class="form-control" id="declared_value" placeholder="申报价值" name='declared_value' value="{{ old('declared_value') ?  old('declared_value') :$model->declared_value }}">
        </div>
    </div>


    <div class="row">
        <div class="form-group col-md-12" style="">
            <label for="color">物流限制：</label><br>
            @foreach($logisticsLimit as $carriage_limit)
                <label>
                    <input type='checkbox' name='carriage_limit_arr[]' value='{{$carriage_limit->id}}' {{ in_array($carriage_limit->id, $logisticsLimit_arr)? 'checked' : '' }} >&nbsp;&nbsp;@if($carriage_limit->ico)<img width="30px" src="{{config('logistics.limit_ico_src').$carriage_limit->ico}}" />@else{{$carriage_limit->name}} @endif
                </label>
                <br>
            @endforeach
        </div>
        <div class="form-group col-md-12" style="">
            <label for="color">包装限制：</label><br>
            @foreach($wrapLimit as $wrap_limit)
                <label>
                    <input type='checkbox' name='package_limit_arr[]' value='{{$wrap_limit->id}}' {{ in_array($wrap_limit->id, $wrapLimit_arr)? 'checked' : '' }} >{{$wrap_limit->name}}
                </label>
                <br>
            @endforeach
        </div>
        <div class="form-group col-md-12 recieveCheckbox" style="">
            <label for="color">收货包装：</label><br>
            @foreach($recieveWraps as $recieve_wrap)
                <label>
                    <input type='checkbox' name='recieve_wrap_id' value='{{$recieve_wrap->id}}' {{ $recieve_wrap->id==$model->recieve_wrap_id? 'checked' : '' }} >{{$recieve_wrap->name}}
                </label>
                <br>
            @endforeach
        </div>
        
        <div class="form-group col-md-3">
            <label for="color">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $model->remark }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">在售状态</label>
            <select  class="form-control" name="status">
                @foreach(config('item.status') as $key=>$value)
                    <option value="{{$key}}" {{ $model->status == $key ? 'selected' : '' }}>{{$value}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">是否激活</label>
            <select  class="form-control" name="is_available">
                <option value="1" {{ $model->is_available == 1 ? 'selected' : '' }}>激活</option>
                <option value="0" {{ $model->is_available == 0 ? 'selected' : '' }}>非激活</option>
            </select>
        </div>

        
        <div class="form-group col-lg-12">
            <input type='text' class="form-control " id="" placeholder="标题" name='default_name' value="{{$model->default_name}}">
        </div>
    
        <div class="form-group  col-lg-12">    
            <input type='text' class="form-control " id="" placeholder="关键词" name='default_keywords' value="{{$model->default_keywords}}">
        </div>
    
    
        <div class="col-lg-12" id="templateContent_{{$key}}">
            <label for="" >描述：</label>
            <div class="form-group">
                <textarea class="form-control privacy" id="editor" rows="16" placeholder="标题" name="html_mod" style="width:100%;height:400px;">{{$model->html_mod}}</textarea>
            </div>
        </div>
        
        <script type="text/javascript" charset="utf-8"> var um_{{$key}} = UM.getEditor('editor'); </script>
    </div>
@stop

@section('pageJs')
    <script type="text/javascript">

        $(function(){
            $(".recieveCheckbox input[type='checkbox']").each(function(){
                $(this).click(function(){
                        $(".recieveCheckbox input[type='checkbox']").attr("checked", false);
                        $(this).attr("checked", true);
                    
                });
            });
        });

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

        $('#warehouse_position').select2({
            ajax: {
                url: "{{ route('itemAjaxWarehousePosition') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    warehouse_position:params.term,
                    'item_id':"{{$model->id}}",
                    'warehouse_id':$('.warehouse_id').val(),
                  };
                },
                results: function(data, page) {
                    if((data.results).length > 0) {
                        var more = (page * 20)<data.total;
                        return {results:data.results,more:more};
                    } else {
                        return {results:data.results};
                    }
                }
            },
        });

        $(document).on('change', '.warehouse_id', function(){
            var warehouse_id = $('.warehouse_id').val();
            var item_id = "{{$model->id}}"; 
            html='<label for="color">库位</label><select class="form-control" id="warehouse_position" name="warehouse_position"></select>';
            $(".warehouse_position").html(html);
            $('#warehouse_position').select2({
                ajax: {
                    url: "{{ route('itemAjaxWarehousePosition') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        warehouse_position:params.term,
                        'item_id':item_id,
                        'warehouse_id':warehouse_id,
                      };
                    },
                    results: function(data, page) {
                        if((data.results).length > 0) {
                            var more = (page * 20)<data.total;
                            return {results:data.results,more:more};
                        } else {
                            return {results:data.results};
                        }
                    }
                },
            });

        });

    var pendingRequests = {};
        jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
        var key = options.url;
        //console.log(key);
        if (!pendingRequests[key]) {
        pendingRequests[key] = jqXHR;
        }else{
        //jqXHR.abort(); //放弃后触发的提交
        pendingRequests[key].abort(); // 放弃先触发的提交
        }

        var complete = options.complete;
        options.complete = function(jqXHR, textStatus) {
        pendingRequests[key] = null;
        if (jQuery.isFunction(complete)) {
        complete.apply(this, arguments);
        }
        };
    });

    $(".privacy").blur(function(){
        var text = $(this).children().text();
        $.ajax({
            url: "{{ route('spu.checkPrivacy') }}",
            data: {text:text},
            dataType: 'json',
            type: 'get',
            success: function (result) {
                if(result){
                    alert('字符'+result+'侵权');
                }else{
                    $(".btn-success").removeAttr('disabled');
                }
            }
        });
    })

    $(".privacy").focus(function(){
        $(".btn-success").attr('disabled','disabled');
    })

    </script>
@stop