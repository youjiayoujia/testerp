@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('logistics.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="code" class="control-label">物流方式简码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="code" placeholder="物流方式简码" name='code' value="{{ old('code') ? old('code') : $model->code }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="name" class="control-label">物流方式名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="物流方式名称" name='name' value="{{ old('name') ? old('name') : $model->name}}">
        </div>
        <div class="form-group col-lg-2">
            <label for="warehouse_id">仓库</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="warehouse_id" class="form-control" id="warehouse_id">
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->id}}" {{$warehouse->id == $model->warehouse_id ? 'selected' : ''}}>
                        {{$warehouse->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_supplier_id" class="control-label">物流商</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="logistics_supplier_id" class="form-control logistics_supplier_id" id="logistics_supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{$supplier->id}}" {{$supplier->id == $model->logistics_supplier_id ? 'selected' : ''}}>
                        {{$supplier->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="docking" class="control-label">对接方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="docking" id="docking">
                @foreach(config('logistics.docking') as $docking_key => $docking)
                    <option value="{{ $docking_key }}" {{ old('docking') ? (old('docking') == $docking_key ? 'selected' : '') : ($model->docking == $docking_key ? 'selected' : '') }}>
                        {{ $docking }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="type" class="control-label">物流商物流方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="type" placeholder="物流商物流方式" name='type' value="{{ old('type') ? old('type') : $model->type}}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-2">
            <label for="logistics_catalog_id">物流分类</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="logistics_catalog_id" id="logistics_catalog_id">
                <option value="0">==选择物流分类==</option>
                @foreach($catalogs as $catalog)
                    <option value="{{$catalog->id}}" {{ $catalog->id == $model->logistics_catalog_id ? 'selected' : '' }}>
                        {{$catalog->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_email_template_id">回邮模版</label>
            <select class="form-control" name="logistics_email_template_id" id="logistics_email_template_id">
                <option value="0">==选择回邮模版==</option>
                @foreach($emailTemplates as $emailTemplate)
                    <option value="{{$emailTemplate->id}}" {{ $emailTemplate->id == $model->logistics_email_template_id ? 'selected' : '' }}>
                        {{$emailTemplate->name}}{{$emailTemplate->eub_head}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_template_id" class="control-label">面单模版</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="logistics_template_id" id="logistics_template_id">
                <option value="0">==选择面单模版==</option>
                @foreach($templates as $template)
                    <option value="{{$template->id}}" {{ $template->id == $model->logistics_template_id ? 'selected' : ''}}>
                        {{$template->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="driver" class="control-label">驱动名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="driver" placeholder="驱动名" name='driver' value="{{ old('driver') ? old('driver') : $model->driver }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="priority" class="control-label">优先级</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="priority" placeholder="优先级" name='priority' value="{{ old('priority') ? old('priority') : $model->priority }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_code" class="control-label">物流编码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="logistics_code" placeholder="物流编码" name='logistics_code' value="{{ old('logistics_code') ? old('logistics_code') : $model->logistics_code }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="is_express" class="control-label">平邮or快递</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_express" value="1" {{ $model->is_express == '1' ? 'checked' : '' }}>快递
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_express" value="0" {{ $model->is_express == '0' ? 'checked' : '' }}>平邮
                </label>
            </div>
        </div>
        <div class="form-group col-lg-3">
            <label for="is_enable" class="control-label">是否启用</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="1" {{ $model->is_enable == '1' ? 'checked' : ''}}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="0" {{ $model->is_enable == '0' ? 'checked' : ''}}>否
                </label>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">渠道回传编码</div>
        <div class="panel-body">
            @foreach($arr as $key1 => $singles)
            <div class='form-group col-lg-3'>
                <label>{{$key1}}</label>
                <select name='merchant[{{$key1}}_merchant]' class='form-control merchant'>
                    <option value=''></option>
                    @foreach($singles as $key => $single)
                        <option value="{{ $single->channel_id.','.$single->name }}" {{ $model->inType($single->id) ? 'selected' : '' }}>{{$single->name}}</option>
                    @endforeach
                </select>
                <input type='text' class='form-control' name='{{$key1}}_merchant_name' placeholder='备选框'>
                <input type='hidden' name='{{$key1}}_merchant_channelId' value={{ $singles->first() ? $singles->first()->channel_id : '' }}>
            </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">渠道是否回传</div>
        <div class="panel-body">
            @if($channels->count() == $logisticsChannels->count())
                @foreach($channels as $channel)
                    @foreach($logisticsChannels as $logisticsChannel)
                        @if($channel->id == $logisticsChannel->channel_id)
                            <div class="form-group col-lg-2">
                                <label for="channel_id[{{ $channel->id }}]" class="control-label">{{ $channel->name }}平台</label>
                                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="channel_id[{{ $channel->id }}]" value="1" {{ $logisticsChannel->is_up == '1' ? 'checked' : '' }}>上传
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="channel_id[{{ $channel->id }}]" value="0" {{ $logisticsChannel->is_up == '0' ? 'checked' : '' }}>不上传
                                    </label>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            @endif
            @if($logisticsChannels->count() == 0)
                @foreach($channels as $channel)
                    <div class="form-group col-lg-2">
                        <label for="channel_id[{{ $channel->id }}]" class="control-label">{{ $channel->name }}平台</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <div class="radio">
                            <label>
                                <input type="radio" name="channel_id[{{ $channel->id }}]" value="1">上传
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="channel_id[{{ $channel->id }}]" value="0" checked>不上传
                            </label>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">缺货是否标记发货</div>
        <div class="panel-body">
            @if($channels->count() == $logisticsChannels->count())
                @foreach($channels as $channel)
                    @foreach($logisticsChannels as $logisticsChannel)
                        @if($channel->id == $logisticsChannel->channel_id)
                            <div class="form-group col-lg-2">
                                <label for="delivery[{{ $channel->id }}]" class="control-label">{{ $channel->name }}平台</label>
                                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="delivery[{{ $channel->id }}]" value="1" {{ $logisticsChannel->delivery == '1' ? 'checked' : '' }}>是
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="delivery[{{ $channel->id }}]" value="0" {{ $logisticsChannel->delivery == '0' ? 'checked' : '' }}>否
                                    </label>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            @endif
            @if($logisticsChannels->count() == 0)
                @foreach($channels as $channel)
                    <div class="form-group col-lg-2">
                        <label for="delivery[{{ $channel->id }}]" class="control-label">{{ $channel->name }}平台</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <div class="radio">
                            <label>
                                <input type="radio" name="delivery[{{ $channel->id }}]" value="1" checked>是
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="delivery[{{ $channel->id }}]" value="0">否
                            </label>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流追踪网址</div>
        <div class="panel-body">
            @if($channels->count() == $logisticsChannels->count())
                @foreach($channels as $channel)
                    @foreach($logisticsChannels as $logisticsChannel)
                        @if($channel->id == $logisticsChannel->channel_id)
                            <div class="form-group col-lg-2">
                                <label for="url[{{ $channel->id }}]" class="control-label">{{ $channel->name }}追踪网址</label>
                                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                <input class="form-control" id="url[{{ $channel->id }}]" placeholder="追踪网址" name='url[{{ $channel->id }}]' value="{{ old('url[' . $channel->id . ']') ? old('url[' . $channel->id . ']') : $logisticsChannel->url }}">
                            </div>
                        @endif
                    @endforeach
                @endforeach
            @endif
            @if($logisticsChannels->count() == 0)
                @foreach($channels as $channel)
                    <div class="form-group col-lg-2">
                        <label for="url[{{ $channel->id }}]" class="control-label">{{ $channel->name }}追踪网址</label>
                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <input class="form-control" id="url[{{ $channel->id }}]" placeholder="追踪网址" name='url[{{ $channel->id }}]' value="{{ old('url[' . $channel->id . ']') }}">
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@stop
<script type="text/javascript">
    $(document).ready(function() {
        $('.merchant').select2();
        $('.logistics_limits').select2();

        $('.logistics_supplier_id').select2();
    });
</script>