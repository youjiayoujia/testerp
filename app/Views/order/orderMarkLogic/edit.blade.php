@extends('common.form')
@section('formAction'){{ route('orderMarkLogic.update', ['id' => $model->id]) }}@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>规则名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name"  name='name' value="{{ $model->name }}">

        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>渠道</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>

            <select class="form-control" name="channel_id" id="channel_id">
                @foreach($channels as $channel)
                    <option value="{{ $channel->id }}" {{ Tool::isSelected( 'channel_id',$channel->id,$model) }}>{{ $channel->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>订单状态</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>

            @foreach($order_status as $key=> $status)
                <div> {{ $status }}: <input type="checkbox" value="{{ $key }}"  name="order_status[]"  {{ Tool::isCheckedByJson('order_status', $key,$model) }} ></div>
            @endforeach

            {{--<select class="form-control" name="order_status">
                @foreach($order_status as $key=> $status)
                    <option value="{{ $key }}" {{ Tool::isSelected('order_status',$key ,$model) }}>{{ $status }}</option>
                @endforeach
            </select>--}}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>订单创建后N小时</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="order_create">
                <option value="" >==请选择==</option>
                @for($i=1;$i<480;$i++)
                    <option value="{{ $i }}" {{ Tool::isSelected('order_create', $i,$model) }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
    </div>


    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>订单支付N小时</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="order_pay">
                <option value="" >==请选择==</option>
                @for($i=1;$i<480;$i++)
                    <option value="{{ $i }}" {{ Tool::isSelected('order_pay', $i,$model) }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
    </div>

    <hr/>


    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>承运商选择</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" onclick="useLogisticsName(1)" name="assign_shipping_logistics" value="1" {{ Tool::isChecked('assign_shipping_logistics', '1', $model) }}>根据平台承运商标记发货
                </label>
                <label>
                    <input type="radio" onclick="useLogisticsName(2)" name="assign_shipping_logistics" value="2" {{ Tool::isChecked('assign_shipping_logistics', '2',$model) }}>手动指定承运商标记发货
                </label>
            </div>
        </div>

        <div class="form-group col-lg-4 @if($model->assign_shipping_logistics==1) hidden @endif"


             id="logistics_name">
            <label for="name" class='control-label'>指定承运商名称</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="shipping_logistics_name"  name='shipping_logistics_name' value=" @if(isset($model->shipping_logistics_name)){{ $model->shipping_logistics_name }}@endif">
        </div>
    </div>




    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>是否上传追踪号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" id="is_upload" onclick="isupload(1)" name="is_upload" value="1" {{ Tool::isChecked('is_upload', '1', $model) }}>按物流渠道设置
                </label>
                <label>
                    <input type="radio" id="is_upload" onclick="isupload(2)"name="is_upload" value="2" {{ Tool::isChecked('is_upload', '2',$model) }}>标记发货但不上传跟踪号
                </label>
            </div>
        </div>
    </div>

    <div class="row" id="wish_is_upload">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>wish 上传追踪号（针对已经标记发货,但未上传追踪号）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div> 上传追踪号: <input type="checkbox" value="1 "  id="wish_upload_tracking_num" name="wish_upload_tracking_num"  {{ Tool::isChecked('wish_upload_tracking_num', '1', $model) }} ></div>

        </div>
    </div>
    <div class="row ">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>速卖通最后标记发货天数（针对未发货订单）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="expired_time">
                <option value="" >==请选择==</option>
                @for($i=1;$i<20;$i++)
                    <option value="{{ $i }}" {{ Tool::isSelected('expired_time', $i,$model) }} >{{ $i }}</option>
                @endfor
            </select>

        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>规则优先度(数字越大越先执行)</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control" name="priority">
                @for($i=1;$i<20;$i++)
                    <option value="{{ $i }}" {{ Tool::isSelected('priority', $i,$model) }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
    </div>


    <div class="row">
        <div class="form-group col-lg-4">
            <label for="name" class='control-label'>标记规则是否启用</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="is_use" value="1" {{ Tool::isChecked('is_use', '1', $model) }}>是
                </label>
                <label>
                    <input type="radio" name="is_use" value="0" {{ Tool::isChecked('is_use', '0',$model) }}>否
                </label>
            </div>
        </div>
    </div>

@stop

@section('pageJs')

    <script type="text/javascript">

     /*   $("#channel_id").change(function(){
            var channel_text = $("#channel_id").find("option:selected").text();
            if(channel_text=='Wish'){
                if($("#wish_is_upload").hasClass('hidden')){
                    $("#wish_is_upload").removeClass('hidden');
                }
            }else{
                if(!$("#wish_is_upload").hasClass('hidden')){
                    $("#wish_is_upload").addClass('hidden');
                }
            }
        });

        $("#wish_is_upload_num").click(function(){
            var is_upload= $('input[name="is_upload"]:checked').val();
            if(is_upload==2){
                alert("无法选择");
                return false;
            }

        });*/

        function useLogisticsName(value){
            if(value==1){
                if(!($("#logistics_name").hasClass('hidden'))){
                    $("#logistics_name").addClass('hidden');
                }
            }else{
                if(($("#logistics_name").hasClass('hidden'))){
                    $("#logistics_name").removeClass('hidden');
                }
            }
        }

    </script>
@stop