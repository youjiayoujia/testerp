@extends('common.form')
@section('formAction') {{ route('firstLeg.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
        <div class="form-group col-lg-2">
            <label class='control-label'>仓库</label>
            <select name='warehouse_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ $warehouse->id == $model->warehouse_id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label class='control-label'>物流方式</label>
            <input type='text' name='name' class='form-control' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-2">
            <label class='control-label'>时效</label>
            <input type='text' name='days' class='form-control' value="{{ old('days') ? old('days') : $model->days }}">
        </div>
        <div class="form-group col-lg-2">
            <label class='control-label'>运输方式</label>
            <select name='transport' class='form-control'>
            <option value='0' {{ $model->transport ? ($model->transport == '0' ? 'selected' : '') : ''}}>海运</option>
            <option value='1' {{ $model->transport ? ($model->transport == '1' ? 'selected' : '') : ''}}>空运</option>
        </select>
        </div>
    </div>
    <div class="panel panel-default">
    <div class="panel-heading">区间收费</div>
        <div class="panel-body add_row">
            <div class='row'>
                <div class='form-group col-sm-3'>
                    <label for="weight_from">开始重量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-3">
                    <label for="weight_to">结束重量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-3">
                    <label for="price">价格</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            @foreach($forms as $key => $single)
                <div class='row'>
                    <div class='form-group col-sm-3'>
                        <input class="form-control" placeholder="开始重量" name="arr[weight_from][{{$key}}]" value="{{ old('arr[weight_from][$key]') ? old('arr[weight_from][$key]') : $single->weight_from }}">
                    </div>
                    <div class="form-group col-sm-3">
                        <input class="form-control" placeholder="结束重量" name="arr[weight_to][{{$key}}]" value="{{ old('arr[weight_to][$key]') ? old('arr[weight_to][$key]') : $single->weight_to }}">
                    </div>
                    <div class="form-group col-sm-3 position_html">
                        <input class="form-control" placeholder="价格" name="arr[price][{{$key}}]" value="{{ old('arr[price][$key]') ? old('arr[price][$key]') : $single->cost }}">
                    </div>
                    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
                </div>
            @endforeach
        </div>
        <div class="panel-footer create_form">
            <div class="create"><i class="glyphicon glyphicon-plus"></i></div>
        </div>
    </div>
@stop
@section('pageJs')
<script type="text/javascript">
    $(document).ready(function () {
        var current = 1;
        $(document).on('click', '.create_form', function () {
            $.ajax({
                url: "{{ route('firstLeg.sectionAdd') }}",
                data: {current: current},
                dataType: 'html',
                type: 'get',
                success: function (result) {
                    $('.add_row').children('div:last').after(result);
                }
            });
            current++;
        });

        $(document).on('click', '.bt_right', function () {
            $(this).parent().remove();
        });
    });
</script>
@stop