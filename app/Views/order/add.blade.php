<div class='row'>
    <div class="form-group col-sm-2">
        <select class="form-control sku" id="arr[sku][{{$current}}]" name='arr[sku][{{$current}}]'></select>
    </div>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control channel_sku" id="arr[channel_sku][{{$current}}]" placeholder="渠道sku" name='arr[channel_sku][{{$current}}]' value="{{ old('arr[channel_sku][$current]') }}">
    </div>
    <div class="form-group col-sm-1">
        <input type='text' class="form-control quantity" id="arr[quantity][{{$current}}]" placeholder="数量" name='arr[quantity][{{$current}}]' value="{{ old('arr[quantity][$current]') }}">
    </div>
    <div class="form-group col-sm-1">
        <input type='text' class="form-control price" id="arr[price][{{$current}}]" placeholder="单价" name='arr[price][{{$current}}]' value="{{ old('arr[price][$current]') }}">
    </div>
    <div class="form-group col-sm-1">
        <select class="form-control is_active" name="arr[is_active][{{$current}}]" id="arr[is_active][{{$current}}]">
            @foreach(config('order.is_active') as $is_active_key => $is_active)
                <option value="{{ $is_active_key }}" {{ $is_active_key == '1' ? 'selected' : '' }}>
                    {{ $is_active }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-1">
        <select class="form-control is_gift" name="arr[is_gift][{{$current}}]" id="arr[is_gift][{{$current}}]">
            @foreach(config('order.whether') as $is_gift_key => $is_gift)
                <option value="{{ $is_gift_key }}" {{ old('arr[is_gift][$current]') == $is_gift_key ? 'selected' : '' }}>
                    {{ $is_gift }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control remark" id="arr[remark][{{$current}}]" placeholder="备注" name='arr[remark][{{$current}}]' value="{{ old('arr[remark][$current]') }}">
    </div>
    <div class="form-group col-sm-1">
        <select class="form-control status" name="arr[status][{{$current}}]" id="arr[status][{{$current}}]">
            @foreach(config('order.item_status') as $ship_status_key => $status)
                <option value="{{ $ship_status_key }}" {{ old('arr[status][$current]') == $ship_status_key ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>
    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
</div>