@extends('common.form')
@section('formAction') {{ route('channelAccount.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-lg-2">
                    <label for="channel_id" class='control-label'>渠道</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select class="form-control" name="channel_id">
                        @foreach($channels as $channel)
                            <option value="{{ $channel->id }}" {{ Tool::isSelected('channel_id', $channel->id, $model) }}>{{ $channel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-2">
                    <label for="account" class='control-label'>账号</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control" id="account" name='account' value="{{ old('account') ? old('account') : $model->account }}">
                </div>
                <div class="form-group col-lg-1">
                    <label for="alias" class='control-label'>账号别名</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control" id="alias" name='alias' value="{{ old('alias') ? old('alias') : $model->alias }}">
                </div>
                <div class="form-group col-lg-1">
                    <label for="country_id" class='control-label'>国家</label>
                    <select class="form-control iSelect" name="country_id">
                        <option value="0">不限</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ Tool::isSelected('country_id', $country->id, $model) }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-1">
                    <label for="sync_cycle" class='control-label'>订单同步周期(小时)</label>
                    <input type='text' class="form-control" id="sync_cycle" name='sync_cycle' value="{{ old('sync_cycle') ? old('sync_cycle') : $model->sync_cycle }}">
                </div>
                <div class="form-group col-lg-1">
                    <label for="sync_days" class='control-label'>订单抓取天数(天)</label>
                    <select class="form-control" name="sync_days">
                        @for($i=1;$i<31;$i++)
                            <option value="{{ $i }}" {{ Tool::isSelected('sync_days', $i, $model) }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group col-lg-1">
                    <label for="sync_pages" class='control-label'>订单每页抓取数</label>
                    <select class="form-control" name="sync_pages">
                        @for($i=100;$i>0;$i-=10)
                            <option value="{{ $i }}" {{ Tool::isSelected('sync_pages', $i, $model) }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group col-lg-1">
                    <label for="order_prefix" class="control-label">订单前缀</label>
                    <input type='text' class="form-control" id="order_prefix" name='order_prefix' value="{{ old('order_prefix') ? old('order_prefix') : $model->order_prefix }}">
                </div>
                <div class="form-group col-lg-1">
                    <label for="operator_id" class='control-label'>运营人员</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select class="form-control iSelect" name="operator_id">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ Tool::isSelected('operator_id', $user->id, $model) }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-1">
                    <label for="customer_service_id" class='control-label'>客服人员</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select class="form-control iSelect" name="customer_service_id">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ Tool::isSelected('customer_service_id', $user->id, $model) }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-2">
                    <label for="service_email" class='control-label'>客服邮箱地址</label>
                    <input type='text' class="form-control" id="service_email" name='service_email' value="{{ old('service_email') ? old('service_email') : $model->service_email }}">
                </div>
                <div class="form-group col-lg-2">
                    <label for="domain" class='control-label'>账号对应域名</label>
                    <input type='text' class="form-control" id="domain" name='domain' value="{{ old('domain') ? old('domain') : $model->domain }}">
                </div>
                <div class="form-group col-lg-2">
                    <label for="image_domain" class='control-label'>产品图片域名</label>
                    <input type='text' class="form-control" id="image_domain" name='image_domain' value="{{ old('image_domain') ? old('image_domain') : $model->image_domain }}">
                </div>
                <div class="form-group col-lg-1">
                    <label for="is_clearance" class="control-label">可否通关</label>

                    <div class="radio">
                        <label>
                            <input type="radio" name="is_clearance" value="1" {{ Tool::isChecked('is_clearance', '1', $model) }}>是
                        </label>
                        <label>
                            <input type="radio" name="is_clearance" value="0" {{ Tool::isChecked('is_clearance', '0', $model) }}>否
                        </label>
                    </div>
                </div>
                <div class="form-group col-lg-1">
                    <label for="is_available" class="control-label">是否激活</label>

                    <div class="radio">
                        <label>
                            <input type="radio" name="is_available" value="1" {{ Tool::isChecked('is_available', '1', $model) }}>是
                        </label>
                        <label>
                            <input type="radio" name="is_available" value="0" {{ Tool::isChecked('is_available', '0', $model) }}>否
                        </label>
                    </div>
                </div>

                <div class="form-group col-lg-1">
                    <label for="is_available" class="control-label">渠道地域</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>

                    <select class="form-control" name="catalog_rates_channel_id">
                        @foreach($catalog_rates_channels as $catalog_rates_channel)
                            <option value="{{ $catalog_rates_channel->id }}" @if($model->catalog_rates_channel_id == $catalog_rates_channel->id) selected @endif >{{ $catalog_rates_channel->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
@stop
@section('pageJs')
    <script type="text/javascript">
        $('.iSelect').select2();
    </script>
@stop