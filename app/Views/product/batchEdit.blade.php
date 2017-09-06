@extends('common.form')
@section('formAction')  {{ route('productBatchUpdate') }} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$product_ids}}' name="product_ids">
    <div class="form-group">
        <label for="model">待编辑的model：</label>
    </div>
    <div class="row">
        
        @foreach($products as $product)
            <div class="form-group col-md-1">
                <label for="model">{{$product->model}}</label>
            </div>
        @endforeach
    </div>

    <div class="row">
        <?php 
            switch ($param) {
                case 'name':
                     ?>
                     
                        <div class="form-group col-md-3">
                            <label for="color">产品中文名</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="color">产品英文名</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input class="form-control" id="name" placeholder="产品英文名" name='name' value="{{ old('name') }}">
                        </div>
                    
                <?php 
                    break;
                
                case 'purchase_url':
                ?>
                <div class="form-group col-md-3">
                    <label for="color">参考链接</label>
                    <input class="form-control" id="purchase_url" placeholder="重量" name='purchase_url' value="{{old('purchase_url')}}">
                </div>
                <?php 
                    break;

                case 'package_limit':
                ?>
                
                    <div class="form-group col-md-12" style="padding-top:26px">
                        <label for="color">包装限制</label>
                        @foreach($wrapLimit as $wrap_limit)
                            <label>
                                <input type='checkbox' name='package_limit_arr[]' value='{{$wrap_limit->id}}'>{{$wrap_limit->name}}
                            </label>
                        @endforeach
                    </div>
                
                <?php
                    break;

                case 'quality_standard':
                ?>
                
                    <div class="form-group col-md-3">
                        <label for="color">质检标准</label>
                        <input class="form-control" id="quality_standard" placeholder="" name='quality_standard' value="{{old('quality_standard')}}">
                    </div>
                
                <?php
                    break;

                case 'declared':
                ?>
                
                    <div class="form-group col-md-3">
                        <label for="color">申报英文</label>
                        <input class="form-control" id="declared_en" placeholder="" name='declared_en' value="{{old('declared_en')}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="color">申报中文</label>
                        <input class="form-control" id="declared_cn" placeholder="" name='declared_cn' value="{{old('declared_cn')}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="color">申报价值</label>
                        <input class="form-control" id="declared_value" placeholder="" name='declared_value' value="{{old('declared_value')}}">
                    </div>
                
                <?php
                    break;
            } 
        ?>
    </div>

    
@stop