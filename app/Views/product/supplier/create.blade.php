@extends('common.form')
@section('formAction') {{ route('productSupplier.store') }} @stop
@section('formAttributes') name='creator' @stop
@section('formBody')
    <div class="row">
{{--        <div class="form-group col-lg-2">
            <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="供货商名字" name='name' value="{{ old('name') }}">
        </div>--}}
{{--        <div class="form-group col-lg-1">
            <label for='province'>省份</label> 
            <select name="province" onChange = "select()" class='form-control'></select>
        </div>　
        <div class="form-group col-lg-1">
            <label for='city'>城市</label> 
            <select name="city" onChange = "select()" class='form-control'></select>
        </div>--}}
        <div class="form-group col-lg-2">
            <label for="company">公司名称</label>
            <input type='text' class="form-control" id="company" placeholder="公司名称" name='company' value="{{ old('company') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="official_url">供货商官网</label>
            <input type='text' class="form-control" id="official_url" placeholder="供货商官网" name='official_url' value="{{ old('official_url') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="address">详细地址</label>
            <input type='text' class="form-control" id="address" placeholder="详细地址" name='address' value="{{ old('address') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="type">供货商类型</label>
            <div class='radio'>
                <label>
                    <input type='radio' name='type' value='1' {{ old('type') ? (old('type') == '1' ? 'checked' : '') : '' }}>线上
                </label>
                <label>
                    <input type='radio' name='type' value='0' {{ old('type') ? (old('type') == '0' ? 'checked' : '') : 'checked' }}>线下
                </label>
                <label>
                    <input type='radio' name='type' value='2' {{ old('type') ? (old('type') == '2' ? 'checked' : '') : '' }}>做货
                </label>
            </div>
        </div>

        <div class="form-group col-lg-3">
            <label for="name" class='control-label'>采购周期</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="purchase_time" placeholder="采购周期" name='purchase_time' value="{{ old('purchase_time') }}">
        </div>

    </div>
    <div class="row">

       <div class="form-group col-lg-3">
            <label for="name" class='control-label'>开户行</label>
            <input type='text' class="form-control" id="bank_account" placeholder="开户行" name='bank_account' value="{{ old('bank_account') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="name" class='control-label'>银行卡号</label> 
            <input type='text' class="form-control" id="bank_code" placeholder="银行卡号" name='bank_code' value="{{ old('bank_code') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="name" class='control-label'>支付方式</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
           <select name='pay_type' class='form-control'>
            @foreach(config('product.product_supplier.pay_type') as $key=>$pay_type)
                <option value="{{$key}}" > {{$pay_type}} </option>
            @endforeach
            </select>
        </div>
    </div>　
    <div class="row">

{{--        <div class="form-group col-lg-3">
            <label for="url">供货商网址</label>
            <input type='text' class="form-control url" id="url" placeholder="供货商url" name='url' value="{{ old('url') }}" {{ old('type') ? old('type') != '1' ? 'readonly' : '' : 'readonly' }}>
        </div>--}}
        <div class="form-group col-lg-3">
            <label for="contact_name">联系人</label>
            <input class="form-control" id="contact_name" placeholder="联系人" name='contact_name' value="{{ old('contact_name') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="telephone">电话</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="telephone" placeholder="电话" name='telephone' value="{{ old('telephone') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="wangwang">旺旺</label>
            <input class="form-control" id="email" placeholder="旺旺" name='wangwang' value="{{ old('email') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="qq">QQ</label>
            <input class="form-control"  placeholder="QQ" name='qq' value="{{ old('qq') }}">
        </div>
    </div>
    <div class="row">
{{--        <div class="form-group col-lg-4">
            <label for="purchase_id">采购员</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='purchase_id' class='form-control'>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
            </select>
        </div>--}}
        <div class="form-group col-lg-4">
            <label for="level">供货商等级</label>
            <select name='level_id' class='form-control'>
            @foreach(config('product.supplier.level') as $key => $level)
                <option value="{{$key}}" {{ old('level_id') ? (old('level_id') == $key ? 'selected' : '') : '' }}> {{$level}} </option>
            @endforeach
            </select>
       </div>
       <div class='form-group col-lg-4'>
            <label name='created_by' class='control-group'>
                创建人
            </label>
            <input class='form-control' type='text' value="{{request()->user()->name}}" readonly />
            <input class='form-control' type='text' value='{{request()->user()->id}}' name='created_by' style="display: none;"/>
       </div>
   </div>
    <div class="row">
        <div class="form-group col-lg-3 file-group" id="update_examine" >
            <label for="url">上传审核资料</label>
            <button type="button"  class="btn btn-warning add-input-file">增加一个文件</button>
            <input type='file' class=" file" id="qualifications" placeholder="上传审核资料" name='qualifications[]' >
        </div>
    </div>
@stop
@section('pageJs')
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
<script type='text/javascript'>
    $(document).ready(function(){
        $('.add-input-file').click(function(){
            var input =  '<input type="file" class=" file" id="qualifications" placeholder="上传审核资料" name="qualifications[]" >';
            $('.file-group').append(input);
        });


        var buf = new Array();
        buf[0] = "{{ old('province') }}" ;
        buf[1] = "{{ old('city') }}" ;
        init(buf[0],buf[1]);

        $('.radio').click(function(){
            if($(this).find(':radio:checked').val() != '1') {
                $(this).parent().parent().next().find('.url').val('');
                $(this).parent().parent().next().find('.url').attr('readonly', true);
				if($(this).find(':radio:checked').val() == 0){
					$('#update_examine').show();
				}else{
					$('#update_examine').hide();
					}
            }
            else {
                $(this).parent().parent().next().find('.url').attr('readonly', false);
				$('#update_examine').hide();
            }
        });
    });
</script>
@stop