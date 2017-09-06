@extends('common.detail')

<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="zone" class="control-label">物流分区</label>
                <input class="form-control" id="zone" placeholder="物流分区" name="zone" value="{{ old('zone') ? old('zone') : $zone->zone }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="logistics_id">物流方式</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select name="logistics_id" class="form-control" id="logistics_id">
                    @foreach($logistics as $logisticses)
                        <option value="{{$logisticses->id}}" {{$logisticses->id == $zone->logistics_id ? 'selected' : ''}} disabled>
                            {{$logisticses->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_id" class="control-label">种类</label>
                <input class="form-control" id="shipping_id" placeholder="种类" name="shipping_id" value="{{ old('shipping_id') ? old('shipping_id') : $zone->shipping_id }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="fixed_weight" class="control-label">首重</label>
                <input class="form-control" id="fixed_weight" placeholder="首重" name='fixed_weight' value="{{ old('fixed_weight') ? old('fixed_weight') : $zone->fixed_weight }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="fixed_price" class="control-label">首重价格</label>
                <input class="form-control" id="fixed_price" placeholder="首重价格" name='fixed_price' value="{{ old('fixed_price') ? old('fixed_price') : $zone->fixed_price }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="continued_weight" class="control-label">续重</label>
                <input class="form-control" id="continued_weight" placeholder="续重" name='continued_weight' value="{{ old('continued_weight') ? old('continued_weight') : $zone->continued_weight }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="continued_price" class="control-label">续重价格</label>
                <input class="form-control" id="continued_price" placeholder="续重价格" name='continued_price' value="{{ old('continued_price') ? old('continued_price') : $zone->continued_price }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="other_fixed_price" class="control-label">其他固定费用</label>
                <input class="form-control" id="other_fixed_price" placeholder="其他固定费用" name='other_fixed_price' value="{{ old('other_fixed_price') ? old('other_fixed_price') : $zone->other_fixed_price }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="other_scale_price" class="control-label">其他比例费用</label>
                <input class="form-control" id="other_scale_price" placeholder="其他比例费用" name='other_scale_price' value="{{ old('other_scale_price') ? old('other_scale_price') : $zone->other_scale_price }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="discount" class="control-label">最后折扣</label>
                <input class="form-control" id="discount" placeholder="最后折扣" name='discount' value="{{ old('discount') ? old('discount') : $zone->discount }}" readonly>
            </div>
            <div class="form-group col-lg-12">
                <label for="country_id" class="control-label">国家</label>
                <textarea class="form-control" rows="3" id="country_id" placeholder="国家" name="country_id" readonly>{{ old('country_id') ? old('country_id') : $zone->country($zone->country_id) }}</textarea>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">运费</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="count" class="control-label">运费</label>
                <input class="form-control" id="count" placeholder="运费" name="count" readonly>
            </div>
        </div>
    </div>
@stop
<script type="text/javascript">
    $(document).ready(function() {
        var weight = 1.2;
        var length = 20;
        var width = 20;
        var height = 20;
        var fixed_weight = parseFloat($("#fixed_weight").val());
        var fixed_price = parseFloat($("#fixed_price").val());
        var continued_weight = parseFloat($("#continued_weight").val());
        var continued_price = parseFloat($("#continued_price").val());
        var other_fixed_price = parseFloat($("#other_fixed_price").val());
        var other_scale_price = parseFloat($("#other_scale_price").val());
        var discount = parseFloat($("#discount").val());
        var volume = (length * width * height) / 6000;
        var N;
        if (weight > volume) {
            N = Math.ceil((weight - fixed_weight) / continued_weight);
        }else {
            N = Math.ceil((volume - fixed_weight) / continued_weight);
        }
        var result = (fixed_price + continued_price * N + other_fixed_price) * other_scale_price * discount;
        var count = $("#count").val(result.toFixed(2));
    });
</script>