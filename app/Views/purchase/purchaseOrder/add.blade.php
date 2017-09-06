<div class='row'>
    <div class="form-group col-sm-2">
                    <input type='text' class="form-control sku" id="post[{{$current}}][post_coding]" placeholder="物流单号" name='post[{{$current}}][post_coding]' value="{{ old('post[$current][post_coding]') }}">
                </div>
               
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control quantity" id="post[{{$current}}][postage]" placeholder="物流费" name='post[{{$current}}][postage]' value="{{ old('post[$current][postage]') }}">
                </div>
        
                <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
 </div>