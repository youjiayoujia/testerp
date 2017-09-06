<script type="text/javascript">
    function batchOperate(param){
        var str = prompt("输入数值(百分比不需要%)");
        var type = $("input[name='operateType']:checked").val();
        if (str) {
            $("."+param).each(function(){
                var old_value = $(this).val();
                if(type=='add'){
                     old_value = Number(old_value)+Number(str);
                }else if(type=='percentage'){
                    old_value = Number(old_value) + Number(str)/100*Number(old_value);
                }else if(type=='fixed'){
                     old_value = Number(str);
                }
                if(param=='price'||param=='shipping'||param=='international'){
                    $(this).val(old_value.toFixed(2));
                }else{
                    $(this).val(old_value);
                }
            })
        }
    }
</script>
@extends('common.form')
@section('formAction'){{ route('lazada.batchUpdate')}} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$product_ids}}' name="product_ids">
    <div class="row">
        <?php
        switch ($param) {
        case 'changeQuantity':
        ?>
        <div class="form-group col-md-12">
            <label for="model">设置sellerSku在线数量</label>
            <input type="text" class="hidden" name="operate" value="changeQuantity" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('quantity')"
                    >批量设置数量
            </a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)           
            <div class="form-group col-md-12">
                <label>{{ $product->sellerSku}}</label>
                <br/>
                <input class="form-control quantity" placeholder="在线数量"
                       name='quantity[{{$product->id}}]' value="{{$product->quantity}}">
            </div>                 
        @endforeach
        <?php
        break; 
        case 'changeStatus':
        ?>
        <input type="text" class="hidden" name="operate" value="changeStatus" />
        @foreach($products as $product)
        <div class="form-group col-md-12">
             <label>{{ $product->sellerSku}}的在线状态</label>
             <select name="status[{{$product->id}}]" class="form-control">
                <option value="active" <?php if($product->status == 'active') echo "selected = 'selected'";?>>active</option>
                <option value="inactive" <?php if($product->status == 'inactive') echo "selected = 'selected'";?>>inactive</option>
                <option value="deleted" <?php if($product->status == 'deleted') echo "selected = 'selected'";?>>deleted</option>                         
             </select>
        </div>
        @endforeach
        <?php 
        break;
        case 'changePrice':
        ?>
        <div class="form-group col-md-12">
            <label for="model">设置sellerSku普通价格</label>
            <input type="text" class="hidden" name="operate" value="changePrice" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('price')"
                    >批量设置
            </a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)           
            <div class="form-group col-md-12">
                <label>{{ $product->sellerSku}}</label>
                <br/>
                <input class="form-control price" placeholder="普通价格"
                       name='price[{{$product->id}}]' value="{{$product->price}}">
            </div>                 
        @endforeach
        <?php 
        break;
        case 'changeSalePrice':        
        ?>
         <div class="form-group col-md-12">
            <label for="model">设置sellerSku销售价格</label>
            <input type="text" class="hidden" name="operate" value="changeSalePrice" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('price')"
                    >批量设置
            </a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)           
            <div class="form-group col-md-12">
                <label>{{ $product->sellerSku}}</label>
                <br/>
                <input class="form-control price" placeholder="普通价格"
                       name='salePrice[{{$product->id}}]' value="{{$product->salePrice}}">
            </div>                 
        @endforeach
        <?php 
        break;
        }
        ?>
    </div>
@stop