@extends('common.form')
@section('formAction') {{ route('logistics.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="code" class="control-label">物流方式简码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="code" placeholder="物流方式简码" name='code' value="{{ old('code') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="name" class="control-label">物流方式名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="物流方式名称" name='name' value="{{ old('name') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="warehouse_id">仓库</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="warehouse_id" class="form-control" id="warehouse_id">
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->id}}" {{ Tool::isSelected('warehouse_id', $warehouse->id) }}>
                        {{$warehouse->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_supplier_id" class='control-label'>物流商</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control logistics_supplier_id" id="logistics_supplier_id" name="logistics_supplier_id"></select>
        </div>
        <div class="form-group col-lg-2">
            <label for="docking" class="control-label">对接方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="docking" id="docking">
                @foreach(config('logistics.docking') as $docking_key => $docking)
                    <option value="{{ $docking_key }}" {{ old('docking') == $docking_key ? 'selected' : '' }}>
                        {{ $docking }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="type" class="control-label">物流商物流方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="type" placeholder="物流商物流方式" name='type' value="{{ old('type') }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-2">
            <label for="logistics_catalog_id" class="control-label">物流分类</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="logistics_catalog_id" id="logistics_catalog_id">
                <option value="0">==选择物流分类==</option>
                @foreach($catalogs as $catalog)
                    <option value="{{$catalog->id}}" {{ Tool::isSelected('logistics_catalog_id', $catalog->id) }}>
                        {{$catalog->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_email_template_id" class="control-label">回邮模版</label>
            <select class="form-control" name="logistics_email_template_id" id="logistics_email_template_id">
                <option value="0">==选择回邮模版==</option>
                @foreach($emailTemplates as $emailTemplate)
                    <option value="{{$emailTemplate->id}}" {{ Tool::isSelected('logistics_email_template_id', $emailTemplate->id) }}>
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
                    <option value="{{$template->id}}" {{ Tool::isSelected('logistics_template_id', $template->id) }}>
                        {{$template->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="driver" class="control-label">驱动名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="driver" placeholder="驱动名" name='driver' value="{{ old('driver') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="priority" class="control-label">优先级</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="priority" placeholder="优先级" name='priority' value="{{ old('priority') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_code" class="control-label">物流编码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="logistics_code" placeholder="物流编码" name='logistics_code' value="{{ old('logistics_code') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="is_express" class="control-label">平邮or快递</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_express" value="1">快递
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_express" value="0" checked>平邮
                </label>
            </div>
        </div>
        <div class="form-group col-lg-2">
            <label for="is_enable" class="control-label">是否启用</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="1">是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_enable" value="0" checked>否
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
                        <option value="{{ $single->channel_id.','.$single->name }}">{{$single->name}}</option>
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
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">缺货是否标记发货</div>
        <div class="panel-body">
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
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流追踪网址</div>
        <div class="panel-body">
            @foreach($channels as $channel)
                <div class="form-group col-lg-2">
                    <label for="url[{{ $channel->id }}]" class="control-label">{{ $channel->name }}追踪网址</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" id="url[{{ $channel->id }}]" placeholder="追踪网址" name='url[{{ $channel->id }}]' value="{{ old('url[' . $channel->id . ']') }}">
                </div>
            @endforeach
        </div>
    </div>
@stop
@section('pageJs')
<script type='text/javascript'>
    $(document).ready(function () {
        $('.merchant').select2();
        $('.logistics_limits').select2();

        $('.logistics_supplier_id').select2({
            ajax: {
                url: "{{ route('logistics.ajaxSupplier') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        logistics_supplier_id: params.term,
                        page: params.page
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
            }
        });

    });
</script>
@stop