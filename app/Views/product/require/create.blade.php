@extends('common.form')
@section('formAction') {{ route('productRequire.store') }} @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="img" class='control-label'>参考图片:</label>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-2'>
            <input id="img1" name='img1' class="file" type="file">
        </div>
        <div class='form-group col-lg-2'>
            <input id="img2" name='img2' class="file" type="file">
        </div>
        <div class='form-group col-lg-2'>
            <input id="img3" name='img3' class="file" type="file">
        </div>
        <div class='form-group col-lg-2'>
            <input id="img4" name='img4' class="file" type="file">
        </div>
        <div class='form-group col-lg-2'>
            <input id="img5" name='img5' class="file" type="file">
        </div>
        <div class='form-group col-lg-2'>
            <input id="img6" name='img6' class="file" type="file">
        </div>    
    </div>
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="name" class='control-label'>产品名</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="产品名" name='name' value="{{ old('name') }}">
        </div>
       <div class="form-group col-lg-3">
            <label for="catalog_id">分类</label>
            <select class='form-control catalog_id' name="catalog_id"></select>
        </div>
        <div class="form-group col-lg-3">
            <label for='province'>货源地(省)</label> 
            <select name="province" onChange = "select()" class='form-control'></select>　
        </div>
        <div class=' form-group col-lg-3'> 
            <label for='city'>货源地(市)</label> 
            <select name="city" onChange = "select()" class='form-control'></select>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="color" class='control-label'>颜色</label>
            <input type='text' class="form-control" placeholder="颜色" name='color' value="{{ old('color') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="material" class='control-label'>材料</label>
            <input type='text' class="form-control" placeholder="材料" name='material' value="{{ old('material') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="technique" class='control-label'>工艺</label>
            <input type='text' class="form-control" id="technique" placeholder="工艺" name='technique' value="{{ old('technique') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="parts" class='control-label'>配件</label>
            <input type='text' class="form-control" id="parts" placeholder="配件" name='parts' value="{{ old('parts') }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-3"> 
            <label for="similar_sku" class='control-label'>类似款sku</label>
            <input type='text' class="form-control" id="similar_sku" placeholder="类似款sku" name='similar_sku' value="{{ old('similar_sku') }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="competition_url" class='control-label'>竞争产品url</label>
            <input type='text' class="form-control" id="competition_url" placeholder="竞争产品url" name='competition_url' value="{{ old('competition_url') }}">
        </div>
         <div class="form-group col-lg-3">
            <label for="remark" class='control-label'>需求描述</label>
            <input type='text' class="form-control" id="remark" placeholder="需求描述" name='remark' value="{{ old('remark') }}">
        </div>
        <div class='form-group col-lg-3'>
            <label for="expected_date">期望上传日期</label>
            <input id="expected_date" class='form-control' name='expected_date' type="text" placeholder='期望上传日期' value="{{ old('expected_date') }}">
        </div>
    </div>
    <div class='row'>
        <!-- <div class="form-group col-lg-4">
            <label for="needer_id">需求渠道</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select  class="form-control" name="needer_id" id="needer_id">
                    @foreach($channel as $_channel)
                        <option value="{{ $_channel->id}}">{{$_channel->name}}</option>
                    @endforeach
                </select>
           
        </div>
        <div class="form-group col-lg-4">
            <label for="needer_shop_id">需求帐号</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select  class="form-control" name="needer_shop_id" id="needer_shop_id">
                    @foreach($channel_account as $_channelAccount)
                        <option value="{{ $_channelAccount->id}}">{{$_channelAccount->account}}</option>
                    @endforeach
                </select>
            
        </div> -->
        <div class="form-group col-lg-4">
            <label for="purchase_id">采购人</label>
            <select class='form-control purchase_id' name="purchase_id"></select>
        </div>
    </div>

    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="url1">URL1</label>
            <input class="form-control" id="url1" placeholder="URL" name='url1' value="{{ old('url1') }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="url2">URL2</label>
            <input class="form-control" id="url2" placeholder="URL" name='url2' value="{{ old('url2') }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="url3">URL3</label>
            <input class="form-control" id="url3" placeholder="URL" name='url3' value="{{ old('url3') }}">
        </div>
    </div>
@stop
@section('pageJs')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/pro_city.js') }}"></script>
<script type='text/javascript'>
    $(document).ready(function(){
        var buf = new Array();
        buf[0] = "{{ old('province') }}";
        buf[1] = "{{ old('city') }}";
        init(buf[0],buf[1]);
        $('#expected_date').cxCalendar();

        $("#needer_id").change(function(){
            var url = "{{route('getAccountUser')}}";
            var channel_id = $(this).val();
            $.ajax({
                    url:url,
                    data:{channel_id:channel_id},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        html="";
                        for(i=0;i<result.length;i++){
                            html+= "<option value="+result[i].id+">"+result[i].account+"</option>"
                        }
                        $("#needer_shop_id").html(html);
                    }                  
            })
        })
    });

    $('.purchase_id').select2({
        ajax: {
            url: "{{ route('ajaxUser') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                user:params.term,
              };
            },
            results: function(data, page) {
                
            }
        },
    });

    $('.catalog_id').select2({
        ajax: {
            url: "{{ route('ajaxCatalog') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                catalog:params.term,
              };
            },
            results: function(data, page) {
                
            }
        },
    });
</script>
@stop