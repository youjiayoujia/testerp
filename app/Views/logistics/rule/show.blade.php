@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>物流方式</strong>: {{ $model->logistics->name }}
            </div>
            <div class="col-lg-2">
                <strong>物流方式简码</strong>: {{ $model->logistics->code }}
            </div>
            @if($model->weight_section)
                <div class="col-lg-2">
                    <strong>重量从(kg)</strong>: {{ $model->weight_from }}
                </div>
                <div class="col-lg-2">
                    <strong>重量至(kg)</strong>: {{ $model->weight_to }}
                </div>
            @else
                <font color='red' size='3px'>重量不限定</font>
            @endif
            @if($model->order_amount_section)
                <div class="col-lg-2">
                    <strong>起始订单金额($)</strong>: {{ $model->order_amount_from }}
                </div>
                <div class="col-lg-2">
                    <strong>结束订单金额($)</strong>: {{ $model->order_amount_to }}
                </div>
            @else
                <font color='red' size='3px'>金额不限定</font>
            @endif
            <div class="col-lg-2">
                <strong>是否通关</strong>: {{ $model->is_clearance == '1' ? '是' : '否' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">国家</div>
        <div class="panel-body">
        <div class='form-group'>
        @if($model->country_section)
            @foreach($countries as $country)
            <div class='col-lg-2'>
                <input type='text' class='form-control' value="{{ $country->cn_name}}">
            </div>
            @endforeach
        @else
            <font size='3px' color='red'>国家没限制</font>
        @endif
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">渠道</div>
        <div class="panel-body">
        <div class='form-group'>
        @if($model->channel_section)
            @foreach($channels as $channel)
            <div class='col-lg-2'>
                <input type='text' class='form-control' value="{{ $channel->name}}">
            </div>
            @endforeach
        @else
            <font size='3px' color='red'>渠道没限制</font>
        @endif
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">品类</div>
        <div class="panel-body">
            <div class='form-group'>
            @if($model->catalog_section)
                @foreach($catalogs as $catalog)
                <div class='col-lg-2'>
                    <input type='text' class='form-control' value="{{ $catalog->name}}">
                </div>
                @endforeach
            @else
                <font size='3px' color='red'>品类没限制</font>
            @endif
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流限制</div>
        <div class="panel-body">
        <div class='form-group'>
        @if($model->limit_section)
            @foreach($limits as $limit)
            <div class='col-lg-2'>
                <label>{{ $limit->name }}</label>
                <input type='text' class='form-control' value="{{ $limit->pivot->type == '0' ? '含' : ($limit->pivot->type == '1' ? '不含' : '可以含')}}">
            </div>
            @endforeach
        @else
            <font size='3px' color='red'>物流没限制</font>
        @endif
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">销售账号</div>
        <div class="panel-body">
            <div class='form-group'>
                @if($model->account_section)
                    @foreach($accounts as $account)
                        <div class='col-lg-2'>
                            <input type='text' class='form-control' value="{{ $account->account}}">
                        </div>
                    @endforeach
                @else
                    <font size='3px' color='red'>账号没限制</font>
                @endif
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">运输方式</div>
        <div class="panel-body">
            <div class='form-group'>
                @if($model->transport_section)
                    @foreach($transports as $transport)
                        <div class='col-lg-2'>
                            <input type='text' class='form-control' value="{{ $transport->name}}">
                        </div>
                    @endforeach
                @else
                    <font size='3px' color='red'>运输没限制</font>
                @endif
            </div>
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