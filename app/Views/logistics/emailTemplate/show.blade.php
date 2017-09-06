@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            @if($model->type == 'default')
                <div class="col-lg-2">
                    <strong>编号</strong>: {{ $model->id }}
                </div>
                <div class="col-lg-2">
                    <strong>名称</strong>: {{ $model->name }}
                </div>
                <div class="col-lg-2">
                    <strong>协议客户</strong>: {{ $model->customer }}
                </div>
                <div class="col-lg-2">
                    <strong>邮编</strong>: {{ $model->zipcode }}
                </div>
                <div class="col-lg-2">
                    <strong>电话</strong>: {{ $model->phone }}
                </div>
                <div class="col-lg-2">
                    <strong>寄件人</strong>: {{ $model->sender }}
                </div>
                <div class="col-lg-2">
                    <strong>城市</strong>: {{ $model->city }}
                </div>
                <div class="col-lg-2">
                    <strong>省份</strong>: {{ $model->province }}
                </div>
                <div class="col-lg-2">
                    <strong>国家代码</strong>: {{ $model->country_code }}
                </div>
                <div class="col-lg-6">
                    <strong>发件地址</strong>: {{ $model->address }}
                </div>
                <div class="col-lg-6">
                    <strong>退件单位</strong>: {{ $model->unit }}
                </div>
                <div class="col-lg-6">
                    <strong>备注</strong>: {{ $model->remark }}
                </div>
            @else
                <div class="col-lg-6">
                    <strong>API信息</strong>: {{ $model->eub_api }}
                </div>                
                <div class="col-lg-6">
                    <strong>回邮地址标题</strong>: {{ $model->eub_head }}
                </div>
                <div class="col-lg-6">
                    <strong>是否使用E邮宝 API</strong>: {{ $model->eub_weather }}
                </div>
                <div class="col-lg-6">
                    <strong>EUB打印方式</strong>: {{ $model->eub_print_type }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝交运方式</strong>: {{ $model->eub_transport_type }}
                </div>
                <div class="col-lg-6">
                    <strong>联系人</strong>: {{ $model->eub_contact_name }}
                </div>
                <div class="col-lg-6">
                    <strong>公司名称</strong>: {{ $model->eub_contact_company_name }}
                </div>
                <div class="col-lg-6">
                    <strong>街道</strong>: {{ $model->eub_street }}
                </div>
                <div class="col-lg-6">
                    <strong>地区代码</strong>: {{ $model->eub_zone_code }}
                </div>
                <div class="col-lg-6">
                    <strong>城市代码</strong>: {{ $model->eub_city_code }}
                </div>

                <div class="col-lg-6">
                    <strong>省份代码</strong>: {{ $model->eub_province_code }}
                </div>
                <div class="col-lg-6">
                    <strong>国家</strong>: {{ $model->eub_country }}
                </div>
                <div class="col-lg-6">
                    <strong>Email</strong>: {{ $model->eub_email }}
                </div>
                <div class="col-lg-6">
                    <strong>移动电话</strong>: {{ $model->eub_mobile_phone }}
                </div>
                <div class="col-lg-6">
                    <strong>固定电话</strong>: {{ $model->eub_phone }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件人</strong>: {{ $model->eub_sender }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件公司名称</strong>: {{ $model->eub_sender_company }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件街道地址</strong>: {{ $model->eub_sender_street }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件地区</strong>: {{ $model->eub_sender_zone }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件城市</strong>: {{ $model->eub_sender_city }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件 州/省</strong>: {{ $model->eub_sender_province }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件 州/省编码</strong>: {{ $model->eub_sender_province_code }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件城市编码</strong>: {{ $model->eub_sender_city_code }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件地区编码</strong>: {{ $model->eub_sender_zone_code }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件国家</strong>: {{ $model->eub_sender_country }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件地邮编</strong>: {{ $model->eub_sender_zipcode }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件人Email</strong>: {{ $model->eub_sender_email }}
                </div>
                <div class="col-lg-6">
                    <strong>E邮宝寄件人移动电话</strong>: {{ $model->eub_sender_mobile_phone }}
                </div>
                <div class="col-lg-6">
                    <strong>系统默认报关申报价值</strong>: {{ $model->eub_default_value }}
                </div>
                <div class="col-lg-6">
                    <strong>系统默认报关申报重量</strong>: {{ $model->eub_default_weight }}
                </div>
                <div class="col-lg-6">
                    <strong>系统默认中文报关物品名称</strong>: {{ $model->eub_default_cn_name }}
                </div>
                <div class="col-lg-6">
                    <strong>系统默认英文报关物品名称</strong>: {{ $model->eub_default_name }}
                </div>
                <div class="col-lg-6">
                    <strong>系统默认物品原产地国家或地区代码</strong>: {{ $model->eub_default_code }}
                </div>
                <div class="col-lg-6">
                    <strong>退回包裹--联系人</strong>: {{ $model->eub_return_contact }}
                </div>
                <div class="col-lg-6">
                    <strong>退回包裹--公司名</strong>: {{ $model->eub_return_company }}
                </div>
                <div class="col-lg-6">
                    <strong>退回包裹--地址</strong>: {{ $model->eub_return_address }}
                </div>
                <div class="col-lg-6">
                    <strong>退回包裹--区</strong>: {{ $model->eub_return_zone }}
                </div>
                <div class="col-lg-6">
                    <strong>退回包裹--市</strong>: {{ $model->eub_return_city }}
                </div>
                <div class="col-lg-6">
                    <strong>退回包裹--省</strong>: {{ $model->eub_return_province }}
                </div>
                <div class="col-lg-6">
                    <strong>退回包裹--国家</strong>: {{ $model->eub_return_country }}
                </div>
                <div class="col-lg-6">
                    <strong>退回包裹--邮编</strong>: {{ $model->eub_return_zipcode }}
                </div>
            @endif
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop