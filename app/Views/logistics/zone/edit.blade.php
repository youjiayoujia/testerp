@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('logisticsZone.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="row">
        <div class="changediv form-group col-lg-3">
            <label for="zone" class="control-label">物流分区</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="命名建议:shipping+数字" name="zone" value="{{ old('zone') ? old('zone') : $model->zone }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="logistics_id">物流方式</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control logistics" id="logistics_id">
                <option value=""}}>
                    {{ $logistics_name }}
                </option>
            </select>
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
                           对应国家
                        </h4>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class='form-group'>
                                <div class='col-lg-12 row'>
                                    <font size='3px' color='blue'>{{$model->zone}}</font>
                                    <button type='button' class='btn btn-info all_select'>全选</button>
                                    <button type='button' class='btn btn-info opposite_select'>反选</button>
                                </div>
                                @foreach($model->logistics_zone_countries as $country)
                                    <div class='col-lg-4'>
                                        <input type='checkbox' class='country' name='countrys[]' value="{{ $country->id }}" checked><font size='3px'>{{ $country->cn_name }}</font>
                                    </div>
                                @endforeach
                            </div>
                            @foreach($partitions as $partition)
                            <div class='form-group'>
                                <div class='col-lg-12 row'>
                                    <font size='3px' color='blue'>{{$partition->name}}</font>
                                    <button type='button' class='btn btn-info all_select'>全选</button>
                                    <button type='button' class='btn btn-info opposite_select'>反选</button>
                                </div>
                                @foreach($partition->partitionSorts as $partitionSort)
                                    <div class='col-lg-4'>
                                        <input type='checkbox' class='country' name='countrys[]' value="{{ $partitionSort->country_id }}"}}><font size='3px'>{{ $partitionSort->country->cn_name }}</font>
                                    </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-3">
            <label for="countrys" class="control-label">对应国家:</label>
            <button type="button" class="btn btn-success country_button" data-toggle="modal" data-target="#countrys">对应国家</button>
        </div>
        <div class="form-group col-lg-2">
            <label for="type" class="control-label">种类</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='type' class='form-control type'>
                <option value='first' {{ $model->type == 'first' ? 'selected' : '' }}>方式一</option>
                <option value='second' {{ $model->type == 'second' ? 'selected' : '' }}>方式二</option>
            </select>
        </div>
    </div>
    @if($model->type == 'first')
        <div class="panel panel-default first" >
    @else
        <div class="panel panel-default first" style='display:none'>
    @endif
        <div class="panel-heading">方式一:首重续重</div>
        <div class="panel-body">
            <div class='row'>
                <div class="form-group col-lg-3">
                    <label for="fixed_weight" class="control-label">首重</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" placeholder="首重" name="fixed_weight" value="{{ old('fixed_weight') ? old('fixed_weight') : $model->fixed_weight }}">
                </div>
                <div class="form-group col-lg-3">
                    <label for="fixed_price" class="control-label">首重价格</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" placeholder="首重价格" name="fixed_price" value="{{ old('fixed_price') ? old('fixed_price') : $model->fixed_price }}">
                </div>
                <div class="form-group col-lg-3">
                    <label for="continued_weight" class="control-label">续重</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" placeholder="续重" name="continued_weight" value="{{ old('continued_weight') ? old('continued_weight') : $model->continued_weight }}">
                </div>
                <div class="form-group col-lg-3">
                    <label for="continued_price" class="control-label">续重价格</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" placeholder="续重价格" name="continued_price" value="{{ old('continued_price') ? old('continued_price') : $model->continued_price }}">
                </div>
            </div>
            <div class='row'>
                <div class="form-group col-lg-3">
                    <label for="other_fixed_price" class="control-label">其它固定费用</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" placeholder="其它固定费用" name="other_fixed_price" value="{{ old('other_fixed_price') ? old('other_fixed_price') : $model->other_fixed_price }}">
                </div> 

                <div class="form-group col-lg-3">
                    <label for="discount" class="control-label">折扣</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input class="form-control" placeholder="折扣 0.xx" name="discount" value="{{ old('discount') ? old('discount') : $model->discount }}">
                </div>
                <div class="form-group col-lg-3">
                    <label for="discount_weather_all" class="control-label">是否通折</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select name='discount_weather_all' class='form-control'>
                        <option value='0' {{ $model->discount_weather_all == '0' ? 'selected' : ''}}>否</option>
                        <option value='1' {{ $model->discount_weather_all == '1' ? 'selected' : ''}}>是</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <input type='hidden' name='len' class='len' value="{{$len}}">
    @if($model->type == 'second')
        <div class="panel panel-default second">
    @else
        <div class="panel panel-default second" style='display:none'>
    @endif
        <div class="panel-heading">方式二:区间收费</div>
        <div class="panel-body add_row">
            <div class='row'>
                <div class='form-group col-sm-3'>
                    <label for="weight_from">开始重量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-3">
                    <label for="weight_to">结束重量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-3">
                    <label for="price">价格</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            @foreach($sectionPrices as $key => $sectionPrice)
                <div class='row'>
                    <div class='form-group col-sm-3'>
                        <input class="form-control" placeholder="开始重量" name="arr[weight_from][{{$key}}]" value="{{ old('arr[weight_from][$key]') ? old('arr[weight_from][$key]') : $sectionPrice->weight_from }}">
                    </div>
                    <div class="form-group col-sm-3">
                        <input class="form-control" placeholder="结束重量" name="arr[weight_to][{{$key}}]" value="{{ old('arr[weight_to][$key]') ? old('arr[weight_to][$key]') : $sectionPrice->weight_to }}">
                    </div>
                    <div class="form-group col-sm-3 position_html">
                        <input class="form-control" placeholder="价格" name="arr[price][{{$key}}]" value="{{ old('arr[price][$key]') ? old('arr[price][$key]') : $sectionPrice->price }}">
                    </div>
                    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
                </div>
            @endforeach
        </div>
        <div class="panel-footer create_form">
            <div class="create"><i class="glyphicon glyphicon-plus"></i></div>
        </div>
    </div>
    
@stop
<script type="text/javascript">
    $(document).ready(function() {
        var current = $('.len').val();
        $(document).on('click', '.create_form', function(){
            warehouse = $('#warehouse_id').val();
            $.ajax({
                url:"{{ route('logisticsZone.sectionAdd') }}",
                data:{current:current},
                dataType:'html',
                type:'get',
                success:function(result) {
                    $('.add_row').children('div:last').after(result);
                }
            });
            current++;
        });

        $(document).on('change', '.logistics', function(){
            logistics_id = $(this).val();
            $.get(
                "{{ route('logisticsZone.getCountries')}}",
                {logistics_id:logistics_id},
                function(result) {
                    $.each($('.country'), function(){
                        id = $(this).val();
                        $(this).prop('disabled', false);
                        for(i=0;i<result.length;i++) {
                            if(result[i] == id) {
                                $(this).prop('disabled', true);
                                return;
                            }
                        }
                    });
                }        
            )
        });

        $(document).on('change', '.type', function(){
            if($(this).val() == 'first') {
                $('.first').show();
                $('.second').hide();
            } else {
                $('.first').hide();
                $('.second').show();
            }
        });

        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
        });

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


    });
</script>
