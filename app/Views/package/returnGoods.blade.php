@extends('common.form')
@section('formAction') {{ route('package.processReturnGoods') }} @stop
@section('formBody')
<div class='row'>
    <div class='form-group col-lg-4'>
        <label for="ordernum" class='control-label'>上传文件</label>
        <input type='file' name='returnFile'>
        <a href="javascript:" class='btn btn-info tracking_no'>模板</a>
    </div>
    <div class='form-group col-lg-4'>
        <label for="ordernum" class='control-label'>状态变更</label>
        <div class='radio'>
            <label>
                <input type='radio' class='pass' name='type' value='pass' checked>加库存并变更成已通过
            </label>
            <label>
                <input type='radio' class='only' name='type' value='only'>仅加库存
            </label>
        </div>
    </div>
    <div class='form-group col-lg-4'>
        <label for="ordernum" class='control-label'>库存加到</label>
        <select name='stock_warehouse_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class='row'>
    <div class='form-group col-lg-4 tracking'>
        <label for="ordernum" class='control-label'>清空挂号码</label>
        <div class='radio'>
            <label>
                <input type='radio' name='trackingNo' value='on' checked>是
            </label>
            <label>
                <input type='radio' name='trackingNo' value='off'>否
            </label>
        </div>
    </div>
    <div class='form-group col-lg-4 logistics'>
        <label for="ordernum" class='control-label'>物流</label>
        <select name='logistics_id' class='form-control logistics_select'>
            <option value="auto">自动匹配</option>
            @foreach($logisticses as $logistics)
                <option value="{{ $logistics->id }}">{{ $logistics->code }}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group col-lg-4 warehouse'>
        <label for="ordernum" class='control-label'>匹配到仓库</label>
        <select name='from_warehouse_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
</div>
@stop
@section('pageJs')
    <script type="text/javascript">
        $('.pass').click(function(){
            $('.warehouse').show();
            $('.logistics').show();
            $('.tracking').show();
        })

        $('.only').click(function(){
            $('.warehouse').hide();
            $('.logistics').hide();
            $('.tracking').hide();
        })

        $('.logistics_select').select2();

        $('.tracking_no').click(function () {
            location.href = "{{ route('exportPackage.getTnoReturnExcel')}}";
        })
    </script>
@stop