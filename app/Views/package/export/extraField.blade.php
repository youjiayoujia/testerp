<div class='row'>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control" placeholder="字段名" name='arr[fieldName][{{$current}}]' value="{{ old('arr[fieldName][$current]') }}">
    </div>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control" placeholder="字段值" name='arr[fieldValue][{{$current}}]' value="{{ old('arr[fieldValue][$current]') }}">
    </div>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control" placeholder="字段排序值" name='arr[fieldLevel][{{$current}}]' value="{{ old('arr[fieldLevel][$current]') }}">
    </div>
    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
</div>