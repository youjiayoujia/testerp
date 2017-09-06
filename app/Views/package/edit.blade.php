@extends('common.form')
@section('formAction') {{ route('package.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <label>ID</label>
                    <input type='text' class='form-control' value="{{ $model->id }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>渠道</label>
                    <input type='text' class='form-control' value="{{ old('channelAccount') ? old('channelAccount') : $model->channelAccount ? $model->channelAccount->alias : '' }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>订单号</label>
                    <input type='text' class='form-control' value="{{ $model->order->id }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>状态</label>
                    <select name='status' class='form-control'>
                        @foreach($status as $key => $value)
                            <option value="{{ $key }}" {{ $model->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label>类型</label>
                    <input type='text' class='form-control' value="{{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>仓库</label>
                    <input type='text' class='form-control' value="{{ $model->warehouse->name }}" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">产品信息</div>
        <div class="panel-body">
            <table class="table">
                <thead>
                <tr>
                    <th>图片</th>
                    <th>SKU</th>
                    <th>库位</th>
                    <th>数量</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                @foreach($model->items as $item)
                    <tr>
                        <td><img src="{{ asset($item->item ? $item->item->image : '') }}" width="100" readonly></td>
                        <td>
                            <input type='text' class='form-control' value="{{ old('sku') ? old('sku') : $item->item ? $item->item->sku : '' }}" readonly>
                        </td>
                        <td>
                            <input type='text' class='form-control' value="{{ old('warehouse_position_id') ? old('warehouse_position_id') : $item->warehousePosition ? $item->warehousePosition->name : '' }}" readonly>
                        </td>
                        <td>
                            <input type='text' class='form-control' value="{{ old('quantity') ? old('quantity') : $item->quantity }}" readonly>
                        </td>
                        <td>
                            <input type='text' class='form-control' value="{{ old('remark') ? old('remark') : $item->remark }}" readonly>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">拣货信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <strong>拣货单</strong>: {!! $model->picklist ? $model->picklist->picknum : '<small>未拣货</small>' !!}
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">发货信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <label>物流方式</label>
                    <select name='logistics_id' class='form-control'>
                        <option value=""></option>
                        @foreach($logisticses as $logistics)
                            <option value="{{ $logistics->id }}" {{ $logistics->id == $model->logistics_id ? 'selected' : ''}}>{{ $logistics->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label>物流单号</label>
                    <input type='text' class='form-control' name='tracking_no' value="{{ old('tracking_no') ? old('tracking_no') : $model->tracking_no }}">
                </div>
                <div class="col-lg-2">
                    <label>查询地址</label>
                    <input type='text' class='form-control' name='tracking_link' value="{{ old('tracking_link') ? old('tracking_link') : $model->tracking_link }}">
                </div>
                <div class="col-lg-2">
                    <label>物流成本</label>
                    <input type='text' class='form-control' name='cost' value="{{ old('cost') ? old('cost') : $model->cost }}">
                </div>
                <div class="col-lg-2">
                    <label>重量
                        <small>Kg</small>
                    </label>
                    <input type='text' class='form-control' name='weight' value="{{ old('weight') ? old('weight') : $model->weight }}">
                </div>
                <div class="col-lg-2">
                    <label>体积</label>
                    <input type='text' class='form-control' value="{{ old('volumn') ? old('volumn') : $model->length.'cm*'.$model->width.'cm*'.$model->height.'cm' }}" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">地址信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-3">
                    <label>姓名</label>
                    <input type='text' class='form-control' name='name' value="{{ old('name') ? old('name') : $model->shipping_firstname.' '.$model->shipping_lastname }}">
                </div>
                <div class="col-lg-3">
                    <label>地址</label>
                    <input type='text' class='form-control' name='shipping_address' value="{{ old('shipping_address') ? old('shipping_address') : $model->shipping_address }}">
                </div>
                <div class="col-lg-3">
                    <label>地址1</label>
                    <input type='text' class='form-control' name='shipping_address1' value="{{ old('shipping_address1') ? old('shipping_address1') : $model->shipping_address1 }}">
                </div>
                <div class="col-lg-3">
                    <label>市</label>
                    <input type='text' class='form-control' name='shipping_city' value="{{ old('shipping_city') ? old('shipping_city') : $model->shipping_city }}">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <label>省/洲</label>
                    <input type='text' class='form-control' name='shipping_state' value="{{ old('shipping_state') ? old('shipping_state') : $model->shipping_state }}">
                </div>
                <div class="col-lg-3">
                    <label>国家</label>
                    <input type='text' class='form-control' name='shipping_country' value="{{ old('shipping_country') ? old('shipping_country') : $model->shipping_country }}">
                </div>
                <div class="col-lg-3">
                    <label>邮编</label>
                    <input type='text' class='form-control' name='shipping_zipcode' value="{{ old('shipping_zipcode') ? old('shipping_zipcode') : $model->shipping_zipcode }}">
                </div>
                <div class="col-lg-3">
                    <label>电话</label>
                    <input type='text' class='form-control' name='shipping_phone' value="{{ old('shipping_phone') ? old('shipping_phone') : $model->shipping_phone }}">
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-warning">
        <div class="panel-heading">备注</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    {{ $model->remark }}
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <strong>分配物流时间</strong>: {{ $model->logistic_assigned_at }}
                </div>
                <div class="col-lg-2">
                    <strong>打印时间</strong>: {{ $model->printed_at }}
                </div>
                <div class="col-lg-2">
                    <strong>发货时间</strong>: {{ $model->shipped_at }}
                </div>
                <div class="col-lg-2">
                    <strong>妥投时间</strong>: {{ $model->delivered_at }}
                </div>
                <div class="col-lg-2">
                    <strong>创建时间</strong>: {{ $model->created_at }}
                </div>
                <div class="col-lg-2">
                    <strong>更新时间</strong>: {{ $model->updated_at }}
                </div>
            </div>
        </div>
    </div>
@stop