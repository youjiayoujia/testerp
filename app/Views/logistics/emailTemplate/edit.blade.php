@extends('common.form')
@section('formAction') {{ route('logisticsEmailTemplate.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class='row'>
        <div class="form-group col-lg-2">
            <label for="name" class="control-label">回邮类型</label>
            <select class='type form-control' name='type'>
                <option value="default" {{ $model->type == 'default' ? 'selected' : ''}}>默认</option>
                <option value="eub" {{ $model->type == 'eub' ? 'selected' : ''}}>EUB</option>
            </select>
        </div>
    </div>
    @if($model->type == 'default')
    <div class="row cont1">
    @else
    <div class='row cont1' style="display:none">
    @endif
        <div class="form-group col-lg-2">
            <label for="name" class="control-label">名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="名称" name='name' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="customer" class="control-label">协议客户</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="customer" placeholder="协议客户" name='customer' value="{{ old('customer') ? old('customer') : $model->customer }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="zipcode" class="control-label">邮编</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="zipcode" placeholder="邮编" name='zipcode' value="{{ old('zipcode') ? old('zipcode') : $model->zipcode }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="phone" class="control-label">电话</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="phone" placeholder="电话" name='phone' value="{{ old('phone') ? old('phone') : $model->phone }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="sender" class="control-label">寄件人</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="sender" placeholder="寄件人" name='sender' value="{{ old('sender') ? old('sender') : $model->sender }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="city" class="control-label">城市</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="city" placeholder="城市" name='city' value="{{ old('city') ? old('city') : $model->city }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="province" class="control-label">省份</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="province" placeholder="省份" name='province' value="{{ old('province') ? old('province') : $model->province }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="country_code" class="control-label">国家代码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="country_code" placeholder="国家代码" name='country_code' value="{{ old('country_code') ? old('country_code') : $model->country_code }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="address" class="control-label">发件地址</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="address" placeholder="发件地址" name='address' value="{{ old('address') ? old('address') : $model->address }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="unit" class="control-label">退件单位</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="unit" placeholder="退件单位" name='unit' value="{{ old('unit') ? old('unit') : $model->unit }}">
        </div>
        <div class="form-group col-lg-12">
            <label for="remark" class="control-label">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ? old('remark') : $model->remark }}">
        </div>
    </div>
    @if($model->type == 'eub')
    <div class="row cont2">
    @else
    <div class='row cont2' style="display:none">
    @endif
        <div class="form-group col-lg-6">
            <label class="control-label">api信息</label>
            <input class="form-control" placeholder="api信息" name='eub_api' value="{{ old('eub_api') ? old('eub_api') : $model->eub_api }}">
            <span class="help-block">   授权码,客户编码,大客户编码(大客户编码可为空，线下eub API 信息)</span>
        </div>
        <div class="form-group col-lg-6">
            <label class="control-label">回邮地址标题 </label>
            <input class="form-control" placeholder="回邮地址标题 " name='eub_head' value="{{ old('eub_head') ? old('eub_head') : $model->eub_head }}">
            <span class="help-block"> 请填写回邮地址标题</span>
        </div>
        <div class="form-group col-lg-12">
            <label class="control-label">是否使用E邮宝 API</label>
            <input class="form-control" placeholder="是否使用E邮宝 API" name='eub_wheather' value="{{ old('eub_wheather') ? old('eub_wheather') : $model->eub_weather }}">
            <span class="help-block">设置E邮宝 API功能: 1 - 使用E邮宝 API,打开该功能后，请确保销售帐号对应的E邮宝 API签名等内容已完善, 
0 - 关闭E邮宝 API,如果E邮宝 API的签名尚未获取，请设置为关闭。关闭后，系统将关闭E邮宝发货方式</span>
        </div>
        <div class="form-group col-lg-6">
            <label class="control-label">EUB打印方式</label>
            <input class="form-control" placeholder="EUB打印方式" name='eub_print_type' value="{{ old('eub_print_type') ? old('eub_print_type') : $model->eub_print_type }}">
            <span class="help-block">标签格式，可用值：0 - 适用于打印 A4 格式标签 1 – 适用于打印 4寸 的热敏标签纸格式标签</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">E邮宝交运方式</label>
            <input class="form-control" placeholder="E邮宝交运方式" name='eub_transport_type' value="{{ old('eub_transport_type') ? old('eub_transport_type') : $model->eub_transport_type }}">
            <span class="help-block">设置E邮宝交运方式，0 - 上门揽收 ， 1 - 卖家自送</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">联系人</label>
            <input class="form-control" placeholder="联系人" name='eub_contact_name' value="{{ old('eub_contact_name') ? old('eub_contact_name') : $model->eub_contact_name }}">
            <span class="help-block">设置E邮宝上门揽收包裹时的联系人</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">公司名称</label>
            <input class="form-control" placeholder="公司名称" name='eub_contact_company_name' value="{{ old('eub_contact_company_name') ? old('eub_contact_company_name') : $model->eub_contact_company_name }}">
            <span class="help-block">   设置E邮宝上门揽收时包裹所在公司</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">街道</label>
            <input class="form-control" placeholder="街道" name='eub_street' value="{{ old('eub_street') ? old('eub_street') : $model->eub_street }}">
            <span class="help-block">设置E邮宝上门包裹时的详细街道地址及门牌号</span>
        </div>
        <div class="form-group col-lg-6">
            <label class="control-label">地区代码</label>
            <input class="form-control" placeholder="地区代码" name='eub_zone_code' value="{{ old('eub_zone_code') ? old('eub_zone_code') : $model->eub_zone_code }}">
            <span class="help-block">设置E邮宝上门揽收包裹时所在地区的代码，地址代码请参考相关文档</span>
        </div>
        <div class="form-group col-lg-4">
            <label class="control-label">城市代码</label>
            <input class="form-control" placeholder="城市代码" name='eub_city_code' value="{{ old('eub_city_code') ? old('eub_city_code') : $model->eub_city_code }}">
            <span class="help-block">设置E邮宝上门揽收包裹时的所在城市的代码，代码请参考相关文档</span>
        </div>
        <div class="form-group col-lg-4">
            <label class="control-label">省份代码</label>
            <input class="form-control" placeholder="省份代码" name='eub_province_code' value="{{ old('eub_province_code') ? old('eub_province_code') : $model->eub_province_code }}">
            <span class="help-block">设置E邮宝上门揽收包裹时所在省份的代码，代码请参考相关文档</span>
        </div>
        <div class="form-group col-lg-4">
            <label class="control-label">邮编</label>
            <input class="form-control" placeholder="邮编" name='eub_zipcode' value="{{ old('eub_zipcode') ? old('eub_zipcode') : $model->eub_zipcode }}">
            <span class="help-block">设置E邮宝上门揽收包裹时的地址对应邮编</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">国家</label>
            <input class="form-control" placeholder="国家" name='eub_country' value="{{ old('eub_country') ? old('eub_country') : $model->eub_country }}">
            <span class="help-block">设置E邮宝上门揽收包裹的国家</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">Email</label>
            <input class="form-control" placeholder="Email" name='eub_email' value="{{ old('eub_email') ? old('eub_email') : $model->eub_email }}">
            <span class="help-block">设置E邮宝上门揽收包裹时的联系email</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">移动电话</label>
            <input class="form-control" placeholder="移动电话" name='eub_mobile_phone' value="{{ old('eub_mobile_phone') ? old('eub_mobile_phone') : $model->eub_mobile_phone }}">
            <span class="help-block">设置E邮宝上门揽收包裹时的联系移动电话</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">固定电话</label>
            <input class="form-control" placeholder="固定电话" name='eub_phone' value="{{ old('eub_phone') ? old('eub_phone') : $model->eub_phone }}">
            <span class="help-block">设置E邮宝上门揽收包裹时的联系电话</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">E邮宝寄件人</label>
            <input class="form-control" placeholder="E邮宝寄件人" name='eub_sender' value="{{ old('eub_sender') ? old('eub_sender') : $model->eub_sender }}">
            <span class="help-block">设置E邮宝寄件人(请用英文填写)</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">E邮宝寄件公司名称</label>
            <input class="form-control" placeholder="E邮宝寄件公司名称" name='eub_sender_company' value="{{ old('eub_sender_company') ? old('eub_sender_company') : $model->eub_sender_company }}">
            <span class="help-block">设置E邮宝寄件人公司名(请用英文填写)</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">E邮宝寄件街道地址</label>
            <input class="form-control" placeholder="E邮宝寄件街道地址" name='eub_sender_street' value="{{ old('eub_sender_street') ? old('eub_sender_street') : $model->eub_sender_street }}">
            <span class="help-block">设置E邮宝寄件人街道地址(请用英文填写)</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">E邮宝寄件地区</label>
            <input class="form-control" placeholder="E邮宝寄件地区" name='eub_sender_zone' value="{{ old('eub_sender_zone') ? old('eub_sender_zone') : $model->eub_sender_zone }}">
            <span class="help-block">设置E邮宝的寄件地区(请用英文填写)</span>
        </div>
        <div class="form-group col-lg-6">
            <label class="control-label">E邮宝寄件城市</label>
            <input class="form-control" placeholder="E邮宝寄件城市" name='eub_sender_city' value="{{ old('eub_sender_city') ? old('eub_sender_city') : $model->eub_sender_city }}">
            <span class="help-block">设置E邮宝寄件城市(请用英文填写)</span>
        </div>
        <div class="form-group col-lg-6">
            <label class="control-label">E邮宝寄件 州/省</label>
            <input class="form-control" placeholder="E邮宝寄件 州/省" name='eub_sender_province' value="{{ old('eub_sender_province') ? old('eub_sender_province') : $model->eub_sender_province }}">
            <span class="help-block">设置E邮宝寄件 州/省(请用英文填写)</span>
        </div>
        <div class="form-group col-lg-4">
            <label class="control-label">E邮宝寄件 州/省编码</label>
            <input class="form-control" placeholder="E邮宝寄件 州/省编码" name='eub_sender_province_code' value="{{ old('eub_sender_province_code') ? old('eub_sender_province_code') : $model->eub_sender_province_code }}">
            <span class="help-block"><font color='red'>设置E邮宝寄件 州/省编码(请用编码填写,仅限线下E邮宝使用)</font></span>
        </div>
        <div class="form-group col-lg-4">
            <label class="control-label">E邮宝寄件城市编码</label>
            <input class="form-control" placeholder="E邮宝寄件城市编码" name='eub_sender_city_code' value="{{ old('eub_sender_city_code') ? old('eub_sender_city_code') : $model->eub_sender_city_code }}">
            <span class="help-block"><font color='red'>设置E邮宝寄件 城市编码(请用编码填写,仅限线下E邮宝使用)</font></span>
        </div>
        <div class="form-group col-lg-4">
            <label class="control-label">E邮宝寄件地区编码</label>
            <input class="form-control" placeholder="E邮宝寄件地区编码" name='eub_sender_zone_code' value="{{ old('eub_sender_zone_code') ? old('eub_sender_zone_code') : $model->sender_zone_code }}">
            <span class="help-block"><font color='red'>设置E邮宝寄件地区编码(请用编码填写,仅限线下E邮宝使用)</font></span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">E邮宝寄件国家</label>
            <input class="form-control" placeholder="E邮宝寄件国家" name='eub_sender_country' value="{{ old('eub_sender_country') ? old('eub_sender_country') : $model->eub_sender_country }}">
            <span class="help-block">设置E邮宝寄件国家(请用英文填写)</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">E邮宝寄件地邮编</label>
            <input class="form-control" placeholder="E邮宝寄件地邮编" name='eub_sender_zipcode' value="{{ old('eub_sender_zipcode') ? old('eub_sender_zipcode') : $model->eub_sender_zipcode }}">
            <span class="help-block">设置E邮宝寄件地址对应邮编</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">E邮宝寄件人Email    </label>
            <input class="form-control" placeholder="E邮宝寄件人Email    " name='eub_sender_email' value="{{ old('eub_sender_email')  ? old('eub_sender_email') : $model->eub_sender_email }}">
            <span class="help-block">设置E邮宝寄件人电子邮件地址</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">E邮宝寄件人移动电话</label>
            <input class="form-control" placeholder="E邮宝寄件人移动电话" name='eub_sender_mobile_phone' value="{{ old('eub_sender_mobile_phone') ? old('eub_sender_mobile_phone') : $model->eub_sender_mobile_phone }}">
            <span class="help-block">设置E邮宝寄件人移动电话</span>
        </div>
        <div class="form-group col-lg-4">
            <label class="control-label">系统默认报关申报价值</label>
            <input class="form-control" placeholder="系统默认报关申报价值" name='eub_default_value' value="{{ old('eub_default_value') ? old('eub_default_value') : $model->eub_default_value }}">
            <span class="help-block">   订单物品未设置报关信息时，物品申报价值将以此值为准</span>
        </div>
        <div class="form-group col-lg-4">
            <label class="control-label">系统默认报关申报重量</label>
            <input class="form-control" placeholder="系统默认报关申报重量" name='eub_default_weight' value="{{ old('eub_default_weight') ? old('eub_default_weight') : $model->eub_default_weight }}">
            <span class="help-block">   订单物品未设置报关信息时，物品申报重量将以此值为准</span>
        </div>
        <div class="form-group col-lg-4">
            <label class="control-label">默认中文报关物品名称</label>
            <input class="form-control" placeholder="默认中文报关物品名称" name='eub_default_cn_name' value="{{ old('eub_default_cn_name') ? old('eub_default_cn_name') : $model->eub_default_cn_name }}">
            <span class="help-block">订单物品未设置报关信息时，物品中文报关名称将以此值为准</span>
        </div>
        <div class="form-group col-lg-6">
            <label class="control-label">默认英文报关物品名称</label>
            <input class="form-control" placeholder="默认英文报关物品名称" name='eub_default_name' value="{{ old('eub_default_name') ? old('eub_default_name') : $model->eub_default_name }}">
            <span class="help-block">订单物品未设置报关信息时，物品英文报关名称将以此值为准</span>
        </div>
        <div class="form-group col-lg-6">
            <label class="control-label">系统默认物品原产地国家或地区代码</label>
            <input class="form-control" placeholder="系统默认物品原产地国家或地区代码" name='eub_default_code' value="{{ old('eub_default_code') ? old('eub_default_code') : $model->eub_default_code }}">
            <span class="help-block">订单物品未设置报关信息时，物品原产地国家或地区代码将以此值为准。可用值请参考相关文件(根目录/Config/CountryCode.ini)</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">退回包裹--联系人</label>
            <input class="form-control" placeholder="退回包裹--联系人" name='eub_return_contact' value="{{ old('eub_return_contact') ? old('eub_return_contact') : $model->eub_return_contact }}">
            <span class="help-block">退回包裹--联系人（中文填写）</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">退回包裹--公司名</label>
            <input class="form-control" placeholder="退回包裹--公司名" name='eub_return_company' value="{{ old('eub_return_company') ? old('eub_return_company') : $model->eub_return_company }}">
            <span class="help-block">退回包裹--公司名（中文填写）</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">退回包裹--地址</label>
            <input class="form-control" placeholder="退回包裹--地址" name='eub_return_address' value="{{ old('eub_return_address') ? old('eub_return_address') : $model->eub_return_address }}">
            <span class="help-block">退回包裹--地址（中文填写）</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">退回包裹--区</label>
            <input class="form-control" placeholder="退回包裹--区" name='eub_return_zone' value="{{ old('eub_return_zone') ? old('eub_return_zone') : $model->eub_return_zone }}">
            <span class="help-block">退回包裹--区（中文填写）</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">退回包裹--市</label>
            <input class="form-control" placeholder="退回包裹--市" name='eub_retrun_city' value="{{ old('eub_retrun_city') ? old('eub_retrun_city') : $model->eub_return_city }}">
            <span class="help-block"> 退回包裹--市（中文填写）</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">退回包裹--省</label>
            <input class="form-control" placeholder="退回包裹--省" name='eub_return_province' value="{{ old('eub_return_province') ? old('eub_return_province') : $model->eub_return_province }}">
            <span class="help-block">退回包裹--省（中文填写）</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">退回包裹--国家</label>
            <input class="form-control" placeholder="退回包裹--国家" name='eub_return_country' value="{{ old('eub_return_country') ? old('eub_return_country') : $model->eub_return_country }}">
            <span class="help-block"> 退回包裹--国家（中文填写）</span>
        </div>
        <div class="form-group col-lg-3">
            <label class="control-label">退回包裹--邮编</label>
            <input class="form-control" placeholder="退回包裹--邮编" name='eub_return_zipcode' value="{{ old('eub_return_zipcode') ? old('eub_return_zipcode') : $model->eub_return_zipcode }}">
            <span class="help-block">退回包裹--邮编（中文填写）</span>
        </div>
    </div>
@stop
@section('pageJs')
<script type='text/javascript'>
    $(document).ready(function(){
        $(document).on('change', '.type', function(){
            if($(this).val() == 'default') {
                $('.cont1').show();
                $('.cont2').hide();
            } else {
                $('.cont1').hide();
                $('.cont2').show();
            }
        })
    });

    
</script>>
@stop