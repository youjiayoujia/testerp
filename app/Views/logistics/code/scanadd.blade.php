<style type="text/css">
    .delete_div{
        height:22px;
        width:150px;
        line-height:21px;
        margin:4px 0 4px 10px;
        padding-right:9px;
        border-radius:2px;
        border:1px dotted #c40000;
        color:#c40000;
        display: inline-block;
    }
    .delete_div:hover{
        border:1px solid #c40000;
    }
</style>
@extends('common.form')
<script type='text/javascript' src="{{ asset('js/jquery.min.js') }}"></script>
@section('formAction') {{ route('scanAddTrCodeFn') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="row">
        <div class="col-lg-4">
            <strong>当前物流方式</strong>: {{ $logistics->name }}
        </div>
        <div class="col-lg-4">
            <strong>当前物流方式简码</strong>: {{ $logistics->code }}
        </div>
        <br/>
        <br/>
        <div class="form-group col-lg-12" style="width:1200px;">
            <label for="url" class="control-label">扫码输入(后回车)：</label>
            <input type="hidden" name="logistics_id" value="{{ $logistics->id }}">
            <input type="text" id="scan_input" class="form-control" name="scan_input" value="" style="width:300px;">
        </div>
    </div>
    <div class="form-group col-lg-12" style="width:1200px;height:250px;float:left;clear:left;border:1px solid gray;" id="add_delete_div">
    </div>
@stop
<script type='text/javascript'>
    function delete_div(that){
        $(that).remove();
    }

    $(function(){
        $('#scan_input').bind('keypress',function(event){
            if(event.keyCode == "13")
            {
                var input_str = "";
                input_str += '<div class="delete_div" onclick="delete_div(this)">';
                input_str += '<input type="text" name="codes[]" value="'+ $(this).val() +'" style="width:120px;height:20px;border:0px;" readOnly="true">';
                input_str += '<img src="{{ asset('image/delete.png') }}" alt="" style="float:right;">';
                input_str += '</div>';
                $("#add_delete_div").append(input_str);
                $("#scan_input").focus().select();
                $(this).val("");
                $(this).focus();
            }
        });
        $("#scan_input").focus().select();
    });

    //阻止表单通过回车键提交
    $(function() {
        $("input").keypress(function (e) {
            var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
            if (keyCode == 13) {
                for (var i = 0; i < this.form.elements.length; i++) {
                    if (this == this.form.elements[i]) break;
                }
                i = (i + 1) % this.form.elements.length;
                this.form.elements[i].focus();
                return false;
            } else {
                return true;
            }
        });
    });

</script>