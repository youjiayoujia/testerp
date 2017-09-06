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
                <label for="price" class="control-label">价格(/kg)</label>
                <input class="form-control" id="price" placeholder="价格" name='price' value="{{ old('price') ? old('price') : $zone->price }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <label for="other_price" class="control-label">其他费用</label>
                <input class="form-control" id="other_price" placeholder="其他费用" name='other_price' value="{{ old('other_price') ? old('other_price') : $zone->other_price }}" readonly>
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
        var price = parseFloat($("#price").val());
        var other_price = parseFloat($("#other_price").val());
        var discount = parseFloat($("#discount").val());
        if (weight != null && price != null && other_price != null && discount != null) {
            var result = weight * (price + other_price) * discount;
            $("#count").val(result.toFixed(2));
        }
    });
</script>