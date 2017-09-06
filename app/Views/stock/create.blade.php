@extends('common.form')
@section('formAction') {{ route('stock.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='item_id' class='form-control sku'></select>
        </div>
        <div class="form-group col-sm-4">
            <label for="oversea_sku">海外仓sku</label>
            <input type='text' class="form-control" placeholder="海外仓sku" name='oversea_sku' value="{{ old('oversea_sku') }}">
        </div>
        <div class="form-group col-sm-4">
            <label for="oversea_cost">海外仓sku单价</label>
            <input type='text' class="form-control" placeholder="海外仓sku单价" name='oversea_cost' value="{{ old('oversea_cost') }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-4">
            <label for="warehouse_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_id' id='warehouse_id' class='form-control'>
                <option>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouse_id') ? old('warehouse_id') == $warehouse->id ? 'selected' : '' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_position_id' class='form-control warehouse_position_id'></select>
        </div>
        <div class="form-group col-sm-4">
            <label for="all_quantity" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control all_quantity" id="all_quantity" placeholder="总数量" name='all_quantity' value="{{ old('all_quantity') }}">
        </div>
    </div>
@stop
@section('pageJs')
<script type='text/javascript'>
    $(document).ready(function(){
        $('.sku').select2({
            ajax: {
                url: "{{ route('stock.ajaxSku') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    sku: params.term, // search term
                    page: params.page,
                  };
                },
                results: function(data, page) {
                    if((data.results).length > 0) {
                        var more = (page * 20)<data.total;
                        return {results:data.results,more:more};
                    } else {
                        return {results:data.results};
                    }
                }
            },
        });

        $('.warehouse_position_id').select2({
            ajax: {
                url: "{{ route('position.ajaxCheckPosition') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    position: params.term, // search term
                    page: params.page,
                    warehouse_id: $('#warehouse_id').val(),
                  };
                },
                results: function(data, page) {
                    if((data.results).length > 0) {
                        var more = (page * 20)<data.total;
                        return {results:data.results,more:more};
                    } else {
                        return {results:data.results};
                    }
                }
            },
        });
    });
</script>
@stop