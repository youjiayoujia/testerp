<style>
.form-group{
    margin-top:10px;
}

.red{
    color: #dd5a43 !important;
}
</style>
@extends('common.form')
@section('formAction') {{ route('smtAfterSale.store') }} @stop
@section('formBody')
<div class="panel panel-default">
    <div class="panel-body"> 
        <div class="row">
            <div class="form-group">
              <div class="col-sm-2">
                 <label class="control-label">平台类型</label>
              </div>
                 <div class="col-sm-5">
                       <select name="plat" id="plat" class="form-control">
                            <option value="0">--请选择--</option>
                            <option value="6">速卖通</option>
                       </select>
                 </div>
            </div>
        </div>
        <div class="row">    
            <div class="form-group col-sm-2">
                <label class="control-label"><span class="red">*</span>模版名称</label>
            </div>        
            <div class="form-group col-sm-10">
                <input type="text" name="name" value="" id="name" datatype="*" nullmsg="请输入 模板名称" errormsg="请输入 模板名称"  class="form-control">
            </div>
        
        </div>
        <div class="row">
            <div class="form-group col-sm-2">
                <label class="control-label">模板详情</label>
            </div>
            <div class="col-sm-10">
                <textarea name="content" id="content" class="form-control" rows="5">
                  
                </textarea>
            </div>
         </div>
    </div>
</div>
@stop
@section('formButton')
     <div class="text-center">  
        <button type="submit" class="btn btn-success submit_btn ">确定保存</button>
        <button type="button" class="btn btn-success submit_btn " onclick="window.location.href  = '{{route('smtAfterSale.index')}}'">返回列表</button>
     </div>
@stop
@section('pageJs')
<script src="{{ asset('plugins/kindeditor/kindeditor.js') }}"></script>
<script src="{{ asset('plugins/layer/layer.js') }}"></script>
<script type="text/javascript">
    KindEditor.ready(function (K) {
        var editor = K.create("#content", {
            "width": "100%",
            "height": "400px",
            "filterModel": false,//是否过滤html代码,true过滤
            "resizeType": "2",//是否可以改变editor大小，0：不可以   1：可改高   2：无限
            "items": ['source', '|', 'fullscreen', 'undo', 'redo',
                'cut', 'copy', 'paste', 'plainpaste',
                'wordpaste', '|', 'justifyleft', 'justifycenter',
                'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist',
                'indent', 'outdent', 'subscript', 'superscript', '|', 'selectall', '-', 'title',
                'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'strikethrough', 'removeformat', '|', 'image', 'multiimage', 'advtable', 'hr',
                'emoticons', 'link', 'unlink', 'table'],
            "htmlTags": false, //要过滤style中的样式的话，直接不用写这句
            "afterBlur": function(){this.sync();} ,//必须，不然提交不到
            'extraFileUploadParams' : {_token : '{{csrf_token()}}'}
        });
    });

    $(function () {
        //联动获取账号
        $(document).on('change', '#plat', function () {
            var plat = $(this).val();

            if (!plat) {
                $('#token_id').closest('.form-group').remove();
                return false;
            } else {
                $.ajax({
                    url: '{{route('smtAfterSale.ajaxGetTokenList')}}',
                    data: 'plat=' + plat,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status) {
                            var input = '<div class="row"><div class="form-group">' +
                                '<label for="token_id" class="col-xs-12 col-sm-2 control-label no-padding-right">账号</label></div>' +
                                '<div class="col-xs-12 col-sm-5">' +
                                '<select name="token_id" id="token_id" class="form-control">';
                            var options = '<option>--请选择--</option>'; //选项可以公用
                            $.each(data.data, function (index, el) {                              
                                options += '<option value="' + el.id + '">' + el.id + '-' + el.alias + '</option>';
                                
                            });

                            if ($('#token_id').length > 0) { //说明输入框已经存在了
                                $('#token_id').empty().append(options);
                            } else {
                                input += options;
                                input += '</select>' +
                                '</div>' +
                                '<div>';
                                $('#plat').closest('.row').after(input);
                            }
                        } else {
                            $('#token_id').closest('.row').remove();
                            showxbtips(data.info, 'alert-warning');
                        }
                    }
                });
            }
        });
    })
</script>
@stop