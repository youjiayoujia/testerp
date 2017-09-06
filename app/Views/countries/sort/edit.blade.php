@extends('common.form')
@section('formAction') {{ route('countriesSort.update', ['id' => $model->id]) }} @stop
@section('formBody')
   <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="name" class='control-label'>分类名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="name" placeholder="分类名称" name='name' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-6'>
            <label for="country_id">选择国家</label>
            <select class='form-control country_id' name='country_id[]' multiple>
                <option value=''></option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ $model->id ? $model->id == $country->parent_id ? 'selected' : '' : ''}}>{{$country->cn_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
@stop

@section('pageJs')
<script type="text/javascript">
    $(document).ready(function () {
        $('.country_id').select2();
    });
    function getPostCountry(){
        var selectCountry = "";
        $(".thecountry").each(function(){
            selectCountry += $.trim($(this).attr('value')) + ",";
        });
        selectCountry = selectCountry.substring(0,selectCountry.length - 1);
        $("#country_id").html(selectCountry);
    }

    // 检测是否被选
    function checkWhetherSelected(that) {
        var selectCountry = [];
        $(".thecountry").each(function () {
            selectCountry.push($(this).val());
        });

        var status = selectCountry.indexOf($(that).val());
        if (status >= 0) {
            return true;
        } else if (status < 0) {
            return false;
        }
    }

    function addCountry(that){
        if(!checkWhetherSelected(that)) {
            var countryHtml = '<option class="form-control thecountry" value="' + $(that).val() + '" onclick="deleteCountry( this )">' + $(that).html() + '</option>';
            $("#dselectCountry").append(countryHtml);
            getPostCountry();
        }
    }

    function deleteCountry(that){
        $(that).remove();
        getPostCountry();
    }

    //全选
    function quanxuan(that)
    {
        var checkCountries = '@foreach($countries as $country)' +
                '<option class="form-control thecountry" value="{{ $country->id }}" onclick="deleteCountry(this)">' +
                '{{ $country->name }}' + '</option>' + '@endforeach';
        $("#dselectCountry").append(checkCountries);
        getPostCountry();
    }

</script>

@stop