@extends('common.form')
@section('formAction') {{ route('logisticsSupplier.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="name" class="control-label">物流商名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type="text" class="form-control" id="name" placeholder="物流商名称" name='name' value="{{ old('name') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="customer_id" class="control-label">客户ID</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="customer_id" placeholder="客户ID" name='customer_id' value="{{ old('customer_id') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="password" class="control-label">密码</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="password" placeholder="密码" name='password' value="{{ old('password') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="url" class="control-label">URL</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="url" placeholder="URL" name='url' value="{{ old('url') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="secret_key" class="control-label">密钥</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="secret_key" placeholder="密钥" name='secret_key' value="{{ old('secret_key') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="client_manager" class="control-label">客户经理</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="client_manager" placeholder="客户经理" name='client_manager' value="{{ old('client_manager') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="manager_tel" class="control-label">客户经理联系方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="manager_tel" placeholder="客户经理联系方式" name='manager_tel' value="{{ old('manager_tel') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="technician" class="control-label">技术人员</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="technician" placeholder="技术人员" name='technician' value="{{ old('technician') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="technician_tel" class="control-label">技术联系方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="technician_tel" placeholder="技术联系方式" name='technician_tel' value="{{ old('technician_tel') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="customer_service_name" class="control-label">客服名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="customer_service_name" placeholder="客服名称" name='customer_service_name' value="{{ old('customer_service_name') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="customer_service_qq" class="control-label">客服QQ</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="customer_service_qq" placeholder="客服QQ" name='customer_service_qq' value="{{ old('customer_service_qq') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="customer_service_tel" class="control-label">客服电话</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="customer_service_tel" placeholder="客服电话" name='customer_service_tel' value="{{ old('customer_service_tel') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="finance_name" class="control-label">财务名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="finance_name" placeholder="财务名称" name='finance_name' value="{{ old('finance_name') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="finance_qq" class="control-label">财务QQ</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="finance_qq" placeholder="财务QQ" name='finance_qq' value="{{ old('finance_qq') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="finance_tel" class="control-label">财务电话</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="finance_tel" placeholder="财务电话" name='finance_tel' value="{{ old('finance_tel') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="driver" class="control-label">取件司机</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="driver" placeholder="取件司机" name='driver' value="{{ old('driver') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="driver_tel" class="control-label">司机电话</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="driver_tel" placeholder="司机电话" name='driver_tel' value="{{ old('driver_tel') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_collection_info_id" class="control-label">收款信息</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="logistics_collection_info_id" id="logistics_collection_info_id">
                <option value="0">==选择收款信息==</option>
                @foreach($collectionInfos as $collectionInfo)
                    <option value="{{$collectionInfo->id}}" {{ Tool::isSelected('logistics_collection_info_id', $collectionInfo->id) }}>
                        {{$collectionInfo->bank}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="credentials">企业证件：</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input name='credentials' type='file'/>
        </div>
        <div class="form-group col-lg-4">
            <label for="remark" class="control-label">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="is_api">是否有API</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_api" value="1" {{old('is_api') ? (old('is_api') == '1' ? 'checked' : '') : 'checked'}}>有
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_api" value="0" checked {{old('is_api') ? (old('is_api') == '0' ? 'checked' : '') : 'checked'}}>没有
                </label>
            </div>
        </div>
    </div>
@stop