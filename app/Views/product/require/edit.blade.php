@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
@section('formAction') {{ route('productRequire.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="img" class='control-label'>参考图片:</label>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-4'>
            @if($model->img1)
            <img src="{{ $model->img1 }}" width='170px' height='100px'/> 
            @endif
            <input id="img1" name='img1' class="file" type="file" multiple>
        </div>
        <div class='form-group col-lg-4'>
            @if($model->img2)
            <img src="{{ $model->img2 }}" width='170px' height='100px'/>
            @endif  
            <input id="img2" name='img2' class="file" type="file" multiple>
        </div>
        <div class='form-group col-lg-4'>
            @if($model->img3)
            <img src="{{ $model->img3 }}" width='170px' height='100px'/>
            @endif
            <input id="img3" name='img3' class="file" type="file" multiple>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-4'>
            @if($model->img4)
            <img src="{{ $model->img4 }}" width='170px' height='100px'/>
            @endif
            <input id="img4" name='img4' class="file" type="file" multiple>
        </div>
        <div class='form-group col-lg-4'>
            @if($model->img5)
            <img src="{{ $model->img5 }}" width='170px' height='100px'/>
            @endif
            <input id="img5" name='img5' class="file" type="file" multiple>
        </div>
        <div class='form-group col-lg-4'>
             @if($model->img6)
            <img src="{{ $model->img6 }}" width='170px' height='100px'/>
            @endif
            <input id="img6" name='img6' class="file" type="file" multiple>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="name" class='control-label'>产品名</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="产品名" name='name' value="{{ old('name') ? old('name') : $model->name}}">
        </div>
        <div class="form-group col-lg-3">
            <label for="name" class='control-label'>品类</label>
            <select name='catalog_id' class='form-control'>
                @foreach($catalogs as $catalog)
                    <option value="{{ $catalog->id }}" {{ old('catalog_id') ? (old('catalog_id') == $catalog->id ? 'selected' : '') : ($model->catalog_id == $catalog->id ? 'selected' : '') }}>{{ $catalog->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for='province'>货源地(省)</label> <select name="province" onChange = "select()" class='form-control'></select>　
        </div>
        <div class='form-group col-lg-3'> 
            <label for='city'>货源地(市)</label> <select name="city" onChange = "select()" class='form-control'></select>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="color" class='control-label'>颜色</label>
            <input type='text' class="form-control" placeholder="颜色" name='color' value="{{ old('color') ? old('color') : $model->color }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="material" class='control-label'>材料</label>
            <input type='text' class="form-control" placeholder="材料" name='material' value="{{ old('material') ? old('material') : $model->material }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="technique" class='control-label'>工艺</label>
            <input type='text' class="form-control" id="technique" placeholder="工艺" name='technique' value="{{ old('technique') ? old('technique') : $model->technique }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="parts" class='control-label'>配件</label>
            <input type='text' class="form-control" id="parts" placeholder="配件" name='parts' value="{{ old('parts') ? old('parts') : $model->parts }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="sku" class='control-label'>类似款sku</label>
            <input type='text' class="form-control" id="sku" placeholder="类似款sku" name='sku' value="{{ old('sku') ? old('sku') : $model->similar_sku}}">
        </div>
        <div class="form-group col-lg-3">
            <label for="competition_url" class='control-label'>竞争产品url</label>
            <input type='text' class="form-control" id="competition_url" placeholder="竞争产品url" name='competition_url' value="{{ old('competition_url') ? old('competition_url') : $model->competition_url }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="remark" class='control-label'>需求描述</label>
            <input type='text' class="form-control" id="remark" placeholder="需求描述" name='remark' value="{{ old('remark') ? old('remark') : $model->remark }}">
        </div>
        <div class='form-group col-lg-3'>
            <label for="expected_date">期待上传日期</label>
            <input id="expected_date" class='form-control' name='expected_date' type="text" value=" {{ old('expected_date') ? old('expected_date') : $model->expected_date }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="needer_id">需求渠道</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select  class="form-control" name="needer_id" id="needer_id">
                @foreach($channel as $_channel)
                    <option value="{{ $_channel->id}}" {{ $_channel->id == $model->needer_id ? 'selected' : '' }} >{{$_channel->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label for="needer_shop_id">需求帐号</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select  class="form-control" name="needer_shop_id" id="needer_shop_id">
                @foreach($channel_account as $_channelAccount)
                    <option value="{{ $_channelAccount->id}}" {{ $_channelAccount->id == $model->needer_shop_id ? 'selected' : '' }} >{{$_channelAccount->account}}</option>
                @endforeach
            </select>
        </div>
        <div class='form-group col-lg-4'>
            <label for='created_by'>创建人</label>
            <input type='text' class='form-control' id='created_by' placeholder='创建人' name='created_by' value="{{ old('created_by') ? old('craeted_by') : $model->created_by }}" readonly>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="url1" class='control-label'>URL1</label>
            <input type='text' class="form-control" id="url1" placeholder="URL" name='url1' value="{{ old('url1') ? old('url1') : $model->url1}}">
        </div>
        <div class="form-group col-lg-4">
            <label for="url2" class='control-label'>URL2</label>
            <input type='text' class="form-control" id="url2" placeholder="URL" name='url2' value="{{ old('url2') ? old('url2') : $model->url2 }}">
        </div>
        <div class="form-group col-lg-4">
            <label for="url3" class='control-label'>URL3</label>
            <input type='text' class="form-control" id="url3" placeholder="URL" name='url3' value="{{ old('url3') ? old('url3') : $model->url3 }}">
        </div>
    </div>
@stop
<script type='text/javascript'>
    window.onload = function(){
        var buf = new Array();
        buf[0] = "{{ old('province') ? old('province') : $model->province }}";
        buf[1] = "{{ old('city') ? old('city') : $model->city }}";
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
    };
</script>