<div class='row block_item'>
    <div class="form-group col-sm-2">
        <select name='arr[item_id][{{$current}}]' class='form-control sku sku1'></select>
    </div>
    <div class="form-group col-sm-2 position_html">
        <input type='text' class="form-control warehouse_position_id" placeholder="库位" name='arr[warehouse_position_id][{{$current}}]' value="{{ old('arr[warehouse_position_id][$current]') }}">
    </div>
    <div class='form-group col-sm-2'>
        <input type='text' class='form-control access_quantity' placeholder='可用数量' name='arr[access_quantity][{{$current}}]' value="{{ old('arr[available_quantity][$current]') }}" readonly>
    </div>
    <div class='form-group col-sm-2'>
        <input type='text' class='form-control quantity' id='arr[quantity][{{$current}}]' placeholder='数量' name='arr[quantity][{{$current}}]' value={{ old('arr[quantity][$current]') }}>
    </div>
    <button type='button' class='btn btn-danger btn-outline bt_right'><i class='glyphicon glyphicon-trash'></i></button>
</div>