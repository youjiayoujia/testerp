@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>时间</strong>: {{ $model->date }}
            </div>
            <div class="col-lg-4">
                <strong>调整人</strong>: {{ $model->adjustBy ? $model->adjustBy->name : '' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">调整内容</div>
        <div class="panel-body">
            <div class='row'>
                <div class="form-group col-lg-2">
                    <label>sku</label>
                </div>
                <div class="form-group col-sm-2">
                    <label>海外仓sku</label>
                </div>
                <div class="form-group col-sm-2">
                    <label>海外仓sku单价</label>
                </div>
                <div class="form-group col-sm-2">
                    <label>库位</label>
                </div>
                <div class="form-group col-sm-1">
                    <label>调整 (正(入库)/负(出库))</label>
                </div>
                <div class="form-group col-sm-1">
                    <label>数量</label>
                </div>
                <div class="form-group col-sm-2">
                    <label>备注</label>
                </div>
            </div>
            @foreach($model->forms as $key => $single)
            <div class='row'>
                <div class="form-group col-lg-2">
                    <input type='text' class='form-control' value="{{ $single->sku }}">
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class='form-control' value="{{ $single->oversea_sku }}">
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class='form-control' value="{{ $single->oversea_cost }}">
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class='form-control' value="{{ $single->warehouse_position }}">
                </div>
                <div class="form-group col-sm-1">
                    <input type='text' class='form-control' value="{{ $single->type == 'in' ? '入库' : '出库'}}">
                </div>
                <div class="form-group col-sm-1">
                    <input type='text' class='form-control' value="{{ $single->quantity }}">
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class='form-control' value="{{ $single->remark }}">
                </div>
            </div>
            @endforeach
            </div>
        </div>
@stop