<div class='row'>
    <div class='form-group col-sm-3'>
        <input class="form-control" placeholder="开始重量" name="arr[weight_from][{{$current}}]" value="{{ old('arr[weight_from][$current]') }}">
    </div>
    <div class="form-group col-sm-3">
        <input class="form-control" placeholder="结束重量" name="arr[weight_to][{{$current}}]" value="{{ old('arr[weight_to][$current]') }}">
    </div>
    <div class="form-group col-sm-3 position_html">
        <input class="form-control" placeholder="价格" name="arr[price][{{$current}}]" value="{{ old('arr[price][$current]') }}">
    </div>
    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
</div>

