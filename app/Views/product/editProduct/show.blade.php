@extends('common.form')
@section('formAction') {{ route('EditProduct.update', ['id' => $model->id]) }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
<?php 
    $check = $model->productEnglishValue;
    $unedit_reason = '';
    $market_usd_price = '';
    $cost_usd_price = '';
    $sale_usd_price = '';
    $name = '';
    $c_name = '';
    $store = '';
    $filter_attributes = '';
    $brief = '';
    $description = '';
    if(!empty($check)){
        $unedit_reason = $check->unedit_reason;
        $market_usd_price = $check->market_usd_price;
        $cost_usd_price = $check->cost_usd_price;
        $sale_usd_price = $check->sale_usd_price; 
        $name = $check->name;
        $c_name = $check->c_name;
        $store = $check->store;
        $filter_attributes = $check->filter_attributes;
        $brief = $check->brief;
        $description = $check->description;
    } 
?>
<input type='hidden' value='PUT' name="_method">
<table class="table table-bordered">
    <tbody>
        <tr>
            <td>SKU</td>
            <td></td>
        </tr>
        <tr>
         <td>status:picked</td>
         <td></td>
        </tr>
        <tr>
        <td>备注:{{$model->remark}}</td>
         <td>
            <label style="width:80px">产品英文名: </label>
            <textarea class="form-control form55" style="width:300px;" disabled="disabled" id="name"  name="name">{{ old('name') ?  old('name') : $name }}</textarea>
            <br><label style="width:80px"></label>
            <span class="msg">0 characters</span>
        </td>
        </tr>
        <tr>
            <td><label>产品中文名: </label>{{$model->c_name}}</td>
            <td><label>主表:中文名: </label><input type="text" disabled="disabled" class="form-control form55" style="width:300px;" id="c_name" value="{{ old('c_name') ?  old('c_name') : $c_name }}" name="c_name"></td>
        </tr>
        <tr>
            <td><label>图片备注: </label></td>
            <td><lable>store:</lable>
                <input type="text" class="form-control form55" disabled="disabled" style="width:300px;" id="store" value="{{ old('store') ?  old('store') : $store }}" name="store">
            </td>
        </tr>
        <tr>
            <td>
                <label>图片URL: </label>
                <?php if(isset($model->image->name)){ ?>
                    <a target='_blank' href='{{ asset($model->image->path) }}/{{$model->image->name}}'>{{ asset($model->image->path) }}/{{$model->image->name}}</a>
                <?php }
                else{ ?>
                无图片
                <?php } ?>
            </td>
            <td>
                <?php if(isset($model->image->name)){ ?>
                <img src="{{ asset($model->image->path) }}/{{$model->image->name}}" width="150px" >
                <?php }else{ ?>
                    无图片
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td><label>颜色: </label>
                <?php echo substr($model->model, strpos($model->model, '-')+1,strlen($model->model)-1) ?>
            </td>
            <td>
                <label>Filter_attributes: </label>
                <br>
                <textarea class="vLargeTextField" cols="50" id="filter_attributes" disabled="disabled" name="filter_attributes" rows="3" >{{ old('filter_attributes') ?  old('filter_attributes') : $filter_attributes }}</textarea>
            </td>
        </tr>
        
        <tr>
            <td><label>尺码描述: </label>
                <?php 
                    $size = "";
                    foreach($model->variationValues->toArray() as $key=>$arr){
                        $size .= $arr['name'].",";
                        
                    }
                    echo substr($size,0,strlen($size)-1);
                 ?>
            </td>
            <td>
                <label>主表:简短描述(brief): </label>
                <br>
                <textarea class="vLargeTextField" cols="50" disabled="disabled" id="brief" name="brief" rows="3">{{ old('brief') ?  old('brief') : $brief }}</textarea>
            </td>
        </tr>
        
        <tr>
            <td>
                @foreach($model->featureTextValues as $featureModel)
                    <div class="col-lg-3" colspan="2">
                        <strong>{{$featureModel->featureName->name}}</strong>: {{$featureModel->feature_value}}
                    </div>
                    <br>
                @endforeach
            </td>

            <td></td>
        </tr>
        <tr>
            <td><label>材质: </label>{{$model->description}}</td>
            <td>
                <label>主表:描述(description): </label>
                <br>
                <textarea class="vLargeTextField" cols="50" disabled="disabled" id="description" name="description" rows="3" >{{ old('description') ?  old('description') : $description }}</textarea>
            </td>
        </tr>
        <tr>
            <td><label>净重: </label>{{$model->weight}} kg</td>
            <td>
                <label>主表:重量（kg）: </label>
                <input type="text" class="form-control form55" disabled="disabled" id="weight" value="{{ old('cost_usd_price') ?  old('cost_usd_price') : $cost_usd_price }}" name="weight">
            </td>
        </tr>
        <tr>
            <td><label>主供货商: </label>{{$model->supplier->name}}</td>
            <td><label>factory:</label>{{$model->supplier->name}}</td>
        </tr>
        <tr>
            <td><label>供货商地址: </label><a target='_blank' href='{{$model->purchase_url}}'>{{$model->purchase_url}}</a></td>
            <td><label>taobao_url: </label><a target='_blank' href='{{$model->purchase_url}}'>{{$model->purchase_url}}</a></td>
        </tr>
        <tr>
            <td><label>供应商货号: </label>{{$model->supplier_sku}}</td>
            <td>
                <label>supplier_sku: </label>{{$model->supplier_sku}}
            </td>
        </tr>
        <tr>
            <td><label>拿货价(RMB): </label><span id="we_cost">{{$model->purchase_price}}</span></td>
            <td style="width:927px">
                <label>主表:销售价美元: </label><input type="text" class="form-control form55" disabled="disabled" name="sale_usd_price" id="sale_usd_price" value="{{ old('sale_usd_price') ?  old('sale_usd_price') : $sale_usd_price }}"><a href="#" id="price_calculate">价格试算</a>
                <div id="price_calculate_div" style="display:none;">
                    <table cellspacing="1" cellpadding="1" border="1">
                        <tr><td>采购成本</td><td>价格系数</td><td>重量</td><td>重量系数</td><td>快递费用</td><td>销售价美元</td><td>成本价美元</td><td>利润率</td><td>实际价格</td><td>实际利润率</td></tr>
                        <tr>
                            <td id="c_cost">1</td>
                            <td id="c_price_coe">1</td>
                            <td id="c_weight">1</td>
                            <td id="c_weight_coe">1</td>
                            <td id="c_ship_price">2</td>
                            <td id="c_pprice">3</td>
                            <td id="c_pcost">4</td>
                            <td id="c_profit">
                                <td id="r_price">5</td>
                                <td id="r_profit">5</td>
                            </tr>
                    </table>
                </div
            </td>
        </tr>
        <tr>
            <td><label>参考现货数量: </label></td>
            <td>
                <label>主表:市场价美元: </label>
                <input type="text" class="form-control form55" id="market_usd_price" disabled="disabled" value="{{ old('market_usd_price') ?  old('market_usd_price') : $market_usd_price }}" name="market_usd_price">
            </td>
        </tr>
        <tr>
            <td><label>快递费用(RMB): </label><span id="ship_price">{{$model->purchase_carriage}}</span></td>
            <td>
                <label>主表:成本价美元: </label><span id="p_cost" style="color:red;"></span>
                <input type="text" disabled="disabled" class="form-control form55" id="cost_usd_price" value="{{ old('cost_usd_price') ?  old('cost_usd_price') : $cost_usd_price }}" name="cost_usd_price">
            </td>
        </tr>
        <tr>
            <td><label>是否透明: </label></td>
            <td></td>
        </tr>
        <tr>
            <td><label>信息录入员: </label></td>
            <td>
            </td>
        </tr>
        <tr>
            <td><label>选款人ID: </label>{{$model->upload_user}}</td>
            <td></td>
        </tr>
        <?php if($model->data_edit_not_pass_remark!=''){ ?>
            <tr>
                <td><label>资料审核不通过原因: </label></td>
                <td>
                    <label>{{$model->data_edit_not_pass_remark}}</label>
                </td>
            </tr>
        <?php } ?>

        <?php if($model->image_edit_not_pass_remark!=''){ ?>
            <tr>
                <td><label>图片不编辑原因: </label></td>
                <td>
                    <label>{{$model->image_edit_not_pass_remark}} </label>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="2"><label>上传时间: </label>{{$model->created_at}}</td>
            
        </tr>

    </tbody>
</table>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               请填写资料审核不通过原因
            </h4>
         </div>
         <input type="text" class="modal-body" name="data_edit_not_pass_remark" style="margin:10px 0px 10px 50px;width:500px;" value="{{ old('data_edit_not_pass_remark') ?  old('data_edit_not_pass_remark') : $model->data_edit_not_pass_remark }}"/>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">关闭
            </button>
            <button type="submit" class="btn btn-primary" name='edit' value='4'>
               提交
            </button>
         </div>
      </div>
</div>
</div>
@stop
@section('formButton')
    
@show{{-- 表单按钮 --}}

@section('pageJs')
    <script type="text/javascript">
        $(document).ready(function(){
            ajax_price();
            ajax_cost();
        })

        $('#name').keyup( function() {
            $('.msg').html($(this).val().length + ' characters');
        });

        $("#price_calculate").click(function(){
            $("#price_calculate_div").toggle();
            return false;
        })

    function ajax_price()
    {
        var price = document.getElementById('we_cost').innerHTML;
        var weight = document.getElementById('weight').value;
        var ship_price = document.getElementById('ship_price').innerHTML;
        var real_price = document.getElementById('sale_usd_price').value;
        if(real_price.length == 0)
            real_price = 0;
        var type = 'price';
        $.ajax({
            type:"POST",
            url :"{{route('productPrice')}}",
            data:"type=" + type + "&price=" + price + "&weight=" + weight + "&ship_price=" + ship_price + "&real_price=" + real_price,
            dataType:"json",
            success:function(res){
                if(real_price <= 0)
                document.getElementById('sale_usd_price').value = res.sale_price
                document.getElementById('c_cost').innerHTML = res.price
                document.getElementById('c_price_coe').innerHTML = res.price_coe
                document.getElementById('c_weight').innerHTML = res.weight
                document.getElementById('c_weight_coe').innerHTML = res.weight_coe
                document.getElementById('c_pprice').innerHTML = res.sale_price
                document.getElementById('c_profit').innerHTML = res.profit
                document.getElementById('r_price').innerHTML = res.r_price
                document.getElementById('r_profit').innerHTML = res.r_profit
            }
        });
        return false;
    }

    function ajax_cost()
    {
        var cost = document.getElementById('we_cost').innerHTML;
        var ship_price = document.getElementById('ship_price').innerHTML;
        var weight = document.getElementById('weight').value;
        var type = 'cost';
        $.ajax({
            type:"POST",
            url :"{{route('productPrice')}}",
            data:"type=" + type + "&cost=" + cost + "&ship_price=" + ship_price + "&weight=" + weight,
            dataType:"json",
            success:function(res){
                document.getElementById('p_cost').innerHTML = res.p_cost
                document.getElementById('cost_usd_price').value = res.p_cost
                document.getElementById('c_pcost').innerHTML = res.p_cost
                document.getElementById('c_ship_price').innerHTML = res.ship_price
            }
        });
        return false;
    }
    </script>
@stop