@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('logisticsRule.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="name" class="control-label">名称</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="名称" name='name' value="{{ old('name') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="type_id">物流方式</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="type_id" class="form-control" id="type_id">
                <option value="{{ $logistics_id }}">
                    {{ $logistics_name }}
                </option>
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="is_clearance">是否通关</label>
            <div class="radio">
                <label>
                    <input type="radio" name="is_clearance" value="1" {{old('is_clearance') ? (old('is_clearance') == '1' ? 'checked' : '') : 'checked'}}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_clearance" value="0" {{old('is_clearance') ? (old('is_clearance') == '0' ? 'checked' : '') : ''}}>否
                </label>
            </div>
        </div>
    </div>

    <div class="modal fade" id="catalogs" tabindex="-1" role="dialog" 
       aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                       品类选择
                       <input type='checkbox' class='catalog_all'>全选
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class='form-group row'>
                        @foreach($catalogs as $catalog)
                            <div class='col-lg-4'>
                                <input type='checkbox' class='catalog' name='catalogs[]' value="{{ $catalog->id }}"><font size='2px'>{{ $catalog->c_name }}</font>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="channels" tabindex="-1" role="dialog" 
       aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                       渠道选择
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class='form-group row'>
                        @foreach($channels as $channel)
                            <div class='col-lg-3'>
                                <input type='checkbox' name='channels[]' value="{{ $channel->id }}"><font size='3px'>{{ $channel->name }}</font>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="countrys" tabindex="-1" role="dialog" 
       aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                       发货国家
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        @foreach($countrySorts as $countrySort)
                        <div class='form-group'>
                            <div class='col-lg-12'>
                                <font size='3px' color='blue'>{{$countrySort->name}}</font>
                                <button type='button' class='btn btn-info all_select'>全选</button>
                                <button type='button' class='btn btn-info opposite_select'>反选</button>
                            </div>
                            @foreach($countrySort->countries as $country)
                            <div class='col-lg-4'>
                                <input type='checkbox' name='countrys[]' value="{{ $country->id }}"><font size='3px'>{{ $country->cn_name }}</font>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logistics_limit" tabindex="-1" role="dialog" 
       aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                       物流限制
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        @foreach($logisticsLimits as $key => $logisticsLimit)
                            <div class='form-group row'>
                                <label>{{ $logisticsLimit->name }}:</label>
                                <input type='radio' name="limits[{{$logisticsLimit->id}}]" value="0" checked>含
                                <input type='radio' name="limits[{{$logisticsLimit->id}}]" value="1">不含
                                <input type='radio' name="limits[{{$logisticsLimit->id}}]" value="2">可以含
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="accounts" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        销售账号
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class='form-group row'>
                            @foreach($accounts as $account)
                                <div class='col-lg-6'>
                                    <input type='checkbox' name='accounts[]' value="{{ $account->id }}"><font size='2px'>{{ $account->account }}</font>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transports" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        运输方式
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class='form-group row'>
                            @foreach($transports as $transport)
                                <div class='col-lg-6'>
                                    <input type='checkbox' name='transports[]' value="{{ $transport->id }}"><font size='2px'>{{ $transport->name }}</font>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class='form-group col-lg-1'>
            <h5>重量(kg)<input type='checkbox' class='weight_section' name='weight_section' value='1'></h5>
        </div>
        <div class='form-group col-lg-2'>
            <input class="form-control col-lg-3 weight" id="weight_from" placeholder="重量从" name='weight_from' value="{{ old('weight_from') }}" disabled>
        </div>
        <div class='col-lg-1'>
            <h5><=  重量区间  <=</h5>
        </div>
        <div class='form-group col-lg-2'>
            <input class="form-control col-lg-3 weight" id="weight_to" placeholder="重量至" name='weight_to' value="{{ old('weight_to') }}" disabled>
        </div>

        <div class='form-group col-lg-1'>
            <h5>金额($)<input type='checkbox' class='order_amount_section' name='order_amount_section' value='1'></h5>
        </div>
        <div class='form-group col-lg-2'>
            <input class="form-control col-lg-3 order_amount" id="order_amount_from" placeholder="金额从" name='order_amount_from' value="{{ old('order_amount_from') }}" disabled>
        </div>
        <div class='col-lg-1'>
            <h5><=  金额区间  <=</h5>
        </div>
        <div class='form-group col-lg-2'>
            <input class="form-control col-lg-3 order_amount" id="order_amount_to" placeholder="金额至" name='order_amount_to' value="{{ old('order_amount_to') }}" disabled>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="catalogs" class="control-label">产品分类:</label>
            <input type='checkbox' class='catalog_section' name='catalog_section' value='1'>
            <button type="button" class="btn btn-success catalog_button" data-toggle="modal" data-target="#catalogs" disabled>产品分类</button>
        </div>
        <div class="form-group col-lg-3">
            <label for="channels" class="control-label">订单来源渠道:</label>
            <input type='checkbox' class='channel_section' name='channel_section' value='1'>
            <button type="button" class="btn btn-success channel_button" data-toggle="modal" data-target="#channels" disabled>订单来源渠道</button>
        </div>
        <div class="form-group col-lg-3">
            <label for="countrys" class="control-label">发货国家:</label>
            <input type='checkbox' class='country_section' name='country_section' value='1'>
            <button type="button" class="btn btn-success country_button" data-toggle="modal" data-target="#countrys" disabled>发货国家</button>
        </div>
        <div class="form-group col-lg-3">
            <label for="countrys" class="control-label">物流限制:</label>
            <input type='checkbox' class='limit_section' name='limit_section' value='1'>
            <button type="button" class="btn btn-success limit_button" data-toggle="modal" data-target="#logistics_limit" disabled>物流限制</button>
        </div>
        <div class="form-group col-lg-3">
            <label for="accounts" class="control-label">销售账号:</label>
            <input type='checkbox' class='account_section' name='account_section' value='1'>
            <button type="button" class="btn btn-success account_button" data-toggle="modal" data-target="#accounts" disabled>销售账号</button>
        </div>
        <div class="form-group col-lg-3">
            <label for="transports" class="control-label">运输方式:</label>
            <input type='checkbox' class='transport_section' name='transport_section' value='1'>
            <button type="button" class="btn btn-success transport_button" data-toggle="modal" data-target="#transports" disabled>运输方式</button>
        </div>
    </div>
@stop
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '.all_select', function(){
            block = $(this).parent().parent();
            block.find("input[type='checkbox']").prop('checked', true);
        });

        $(document).on('click', '.opposite_select', function(){
            block = $(this).parent().parent();
            $.each(block.find("input[type='checkbox']"), function(){
                if($(this).prop('checked') == true) {
                    $(this).prop('checked', false);
                } else {
                    $(this).prop('checked', true);
                }
            });
        });

        $(document).on('click', '.weight_section', function(){
            if($(this).prop('checked') == true) {
                $('.weight').val('');
                $('.weight').prop('disabled', false);
            } else {
                $('.weight').prop('disabled', true);
            }
        });

        $(document).on('click', '.order_amount_section', function(){
            if($(this).prop('checked') == true) {
                $('.order_amount').val('');
                $('.order_amount').prop('disabled', false);
            } else {
                $('.order_amount').prop('disabled', true);
            }
        });

        $(document).on('click', '.catalog_section', function(){
            if($(this).prop('checked') == true) {
                $('.catalog_button').prop('disabled', false);
            } else {
                $('.catalog_button').prop('disabled', true);
            }
        });

        $(document).on('click', '.channel_section', function(){
            if($(this).prop('checked') == true) {
                $('.channel_button').prop('disabled', false);
            } else {
                $('.channel_button').prop('disabled', true);
            }
        });

        $(document).on('click', '.country_section', function(){
            if($(this).prop('checked') == true) {
                $('.country_button').prop('disabled', false);
            } else {
                $('.country_button').prop('disabled', true);
            }
        });

        $(document).on('click', '.limit_section', function(){
            if($(this).prop('checked') == true) {
                $('.limit_button').prop('disabled', false);
            } else {
                $('.limit_button').prop('disabled', true);
            }
        });

        $(document).on('click', '.account_section', function(){
            if($(this).prop('checked') == true) {
                $('.account_button').prop('disabled', false);
            } else {
                $('.account_button').prop('disabled', true);
            }
        });

        $(document).on('click', '.catalog_all', function(){
            if($(this).prop('checked') == true) {
                $('.catalog').prop('checked', true);
            } else {
                $('.catalog').prop('checked', false);
            }
        });

        $(document).on('click', '.transport_section', function(){
            if($(this).prop('checked') == true) {
                $('.transport_button').prop('disabled', false);
            } else {
                $('.transport_button').prop('disabled', true);
            }
        });
    });
</script>