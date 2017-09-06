@extends('common.form')
@section('formAction') {{ route('CatalogCategory.update', ['id' => $model->id]) }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
   <input type='hidden' value='PUT' name="_method">
    <div class="row">    
        <div class="form-group col-md-3">
            <label for="color">中文名称</label>
            <input class="form-control"  placeholder="中文名称" name='cn_name' value="{{ old('cn_name') ?  old('cn_name') : $model->cn_name }}">
        </div>
        
        <div class="form-group col-md-3">
            <label for="color">英文名称</label>
            <input class="form-control"  placeholder="英文名称" name='en_name' value="{{ old('en_name') ?  old('en_name') : $model->en_name }}">
        </div>
    </div>
@stop

@section('pageJs')
    <script type="text/javascript">
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