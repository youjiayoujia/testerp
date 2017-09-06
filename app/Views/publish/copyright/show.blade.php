@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">信息详情</div>
        <div class="panel-body">
            <div class="col-lg-12">
                <strong>账号:</strong>: {{ $model->account }}
            </div>
            <div class="col-lg-12">
                <strong>平台</strong>: 
                @if($model->plat == 1)
                ebay
                @elseif($model->plat == 6)
                smt
                @else
                wish
                @endif
            </div>
            <div class="col-lg-12">
                <strong>SKU:</strong> {{ $model->sku }}
            </div>
            <div class="col-lg-12">
                <strong>产品广告ID:</strong> {{ $model->pro_id }}
            </div>
            <div class="col-lg-12">
                <strong>投诉人:</strong> {{ $model->complainant }}
            </div>
            <div class="col-lg-12">
                <strong>侵权原因:</strong> {{ $model->reason }}
            </div>
            <div class="col-lg-12">
                <strong>商标名:</strong> {{ $model->trademark }}
            </div>
            <div class="col-lg-12">
                <strong>知识产权编号:</strong> {{ $model->ip_number }}
            </div>
            <div class="col-lg-12">
                <strong>严重程度:</strong> {{ $model->degree }}
            </div>
            <div class="col-lg-12">
                <strong>违规大类:</strong> {{ $model->violatos_big_type }}
            </div>
            <div class="col-lg-12">
                <strong>违规小类:</strong> {{ $model->violatos_small_type }}
            </div>
            <div class="col-lg-12">
                <strong>是否有效:</strong> {{ $model->status ? '有效' : '无效' }}
            </div>
            <div class="col-lg-12">
                <strong>分值:</strong> {{ $model->score }}
            </div>
            <div class="col-lg-12">
                <strong>违规生效时间:</strong> {{ $model->violatos_start_time }}
            </div>
            <div class="col-lg-12">
                <strong>违规失效时间:</strong> {{ $model->violatos_fail_time }}
            </div>
            <div class="col-lg-12">
                <strong>销售:</strong> {{ $model->seller }}
            </div>
            <div class="col-lg-12">
                <strong>备注信息:</strong> {{ $model->remark }}
            </div>
            <div class="col-lg-12">
                <strong>联系人:</strong> {{ $model->contact_name }}
            </div>
            <div class="col-lg-12">
                <strong>电话:</strong> {{ $model->phone }}
            </div>
             <div class="col-lg-12">
                <strong>邮箱:</strong> {{ $model->email }}
            </div>
            <div class="col-lg-12">
                <strong>导入时间:</strong> {{ $model->import_time }}
            </div>
            <div class="col-lg-12">
                <strong>导入用户:</strong> {{ $model->user ? $model->user->name : '' }}
            </div>            
        </div>
    </div>    
@stop