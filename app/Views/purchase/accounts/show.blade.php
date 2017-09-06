@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-3">
                    <strong>ID</strong>: {{$model->id}}
                </div>
                <div class="col-lg-3">
                    <strong>账号名称</strong>:  {{$model->resource_owner}}
                </div>
                <div class="col-lg-3">
                    <strong>账号ID</strong>: {{$model->memberId}}
                </div>

            </div>
            <div class="row">
                <div class="col-lg-6">
                    <strong>access_token</strong>: {{$model->access_token}}
                </div>
                <div class="col-lg-3">
                    <strong>负责人</strong>:
                    @if($model->purchase_user_id)
                        {{ $model->PurchaseUserName}}
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop