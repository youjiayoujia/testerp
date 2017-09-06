@extends('common.form')
@section('formAction') {{ route('purchaseAccount.update', ['id' => $model->id]) }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
   <input type='hidden' value='PUT' name="_method">
    <div class="row">    
        <div class="form-group col-md-3">
            <label for="color">	账户名</label>
            <input class="form-control"  placeholder="	账户名" name='resource_owner' value="{{ old('resource_owner') ?  old('resource_owner') : $model->resource_owner }}">
        </div>
        
        <div class="form-group col-md-3">
            <label for="color">	账户ID</label>
            <input class="form-control"  placeholder="	账户ID" name='memberId' value="{{ old('memberId') ?  old('memberId') : $model->memberId }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">access_token</label>
            <input class="form-control"  placeholder="access_token" name='access_token' value="{{ old('access_token') ?  old('access_token') : $model->access_token }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">采购负责人</label>
            <select name = "purchase_user_id" class="form-control purchase_user_group" >
                @foreach($users as $user)
                    <option value="{{$user->id}}" @if($user->id == $model->purchase_user_id) selected @endif>{{$user->name}}</option>
                @endforeach

            </select>
        </div>
    </div>
@stop

@section('pageJs')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.purchase_user_group').select2();
        });
        $('.supplier').select2({
                ajax: {
                    url: "{{ route('ajaxSupplier') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        supplier:params.term,
                      };
                    },
                    results: function(data, page) {
                        
                    }
                },
            });

            $('.purchase_adminer').select2({
                ajax: {
                    url: "{{ route('ajaxUser') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        user:params.term,
                      };
                    },
                    results: function(data, page) {
                        
                    }
                },
        });

    </script>
@stop