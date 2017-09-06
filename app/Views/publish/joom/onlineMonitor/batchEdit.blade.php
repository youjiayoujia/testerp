<script type="text/javascript">
    function batchOperate(param){
        var str = prompt("输入数值(百分比请在数值后添加%)");
        var type = $("input[name='operateType']:checked").val();
        var string = '%';
        var num = '';
        if (str) {
            $("."+param).each(function(){
                var old_value = $(this).val();
                if(type=='add'){
                    if(str.indexOf(string)<0){    //exist %
                        old_value = Number(old_value)+Number(str);
                    }else{
                        num = str.replace('%', '');    //replace
                        old_value = Number(old_value) + Number(num)/100 * Number(old_value);
                  }
                }else if(type=='percentage'){
                    old_value = Number(old_value) + Number(str)/100*Number(old_value);
                }else if(type=='fixed'){
                    if(str.indexOf(string)<0){
                        old_value = Number(str);
                    }else{
                        num = str.replace('%', '');    //replace
                        old_value = Number(num)/100 * Number(old_value);
                    }
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
@section('formAction'){{ route('joomonline.batchUpdate')}} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$product_ids}}' name="product_ids">
    <div class="row">
        <?php
        switch ($param) {
        case 'changeQuantity':
        ?>
        <div class="form-group col-md-12">
            <label for="model">批量设置Joom-sku在线数量</label>
            <input type="text" class="hidden" name="operate" value="changeQuantity" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('quantity')"
                    >批量设置数量
            </a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)           
            <div class="form-group col-md-12">
                <label>{{ $product->sku}}</label>
                <br/>
                <input class="form-control quantity" placeholder="在线数量"
                       name='quantity[{{$product->id}}]' value="{{$product->inventory}}">
            </div>                 
        @endforeach
        <?php
        break;
        case 'changePrice':
        ?>
        <div class="form-group col-md-12">
            <label for="model">批量设置Joom-sku普通价格</label>
            <input type="text" class="hidden" name="operate" value="changePrice" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('price')"
                    >批量设置
            </a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)           
            <div class="form-group col-md-12">
                <label>{{ $product->sku}}</label>
                <br/>
                <input class="form-control price" placeholder="普通价格"
                       name='price[{{$product->id}}]' value="{{$product->price}}">
            </div>                 
        @endforeach
        <?php 
        break;
        case 'changeshipping':
        ?>
         <div class="form-group col-md-12">
            <label for="model">批量设置Joom-sku运费</label>
            <input type="text" class="hidden" name="operate" value="changeshipping" />
            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="batchOperate('price')"
                    >批量设置
            </a>
            <input type="radio"  name="operateType" checked value="add"> 增加
            <input type="radio"  name="operateType" value="fixed"> 固定值
        </div>
        @foreach($products as $key=> $product)           
            <div class="form-group col-md-12">
                <label>{{ $product->sku}}</label>
                <br/>
                <input class="form-control price" placeholder="普通价格"
                       name='saleshipping[{{$product->id}}]' value="{{$product->shipping}}">
            </div>                 
        @endforeach
        <?php 
        break;
        }
        ?>
    </div>
@stop