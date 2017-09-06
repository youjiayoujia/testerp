@extends('common.form')
@section('formAction') {{ route('beChosed') }} @stop
@section('formBody')
    <div class="form-group col-md-3"><label for="color">选择shop:</label>
        <select  class="form-control" name="channel_id">
            @foreach($channels as $channel)
                <option value="{{ $channel->id}}" >{{$channel->name}}</option>
            @endforeach
        </select>
    </div>

    <label for="product_model">product_model：</label>
    <div>
        <input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选
    </div>
    @foreach($data as $product)
     
    <div class="form-group">
        <input type="checkbox" name="product_ids[]"  value="{{$product['id']}}" >
        {{$product['model']}}
    </div>
    @endforeach
@stop

    <script type="text/javascript">
        function quanxuan()
        {
          var collid = document.getElementById("checkall");
          var coll = document.getElementsByName("product_ids[]");
          if (collid.checked){
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = true;
          }else{
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = false;
          }
        }
    </script>
