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
                            <option value="6" <?php if($data->plat == 6) echo "selected = 'selected'";?>>速卖通</option>
                       </select>
                 </div>
            </div>
        </div>
         <div class="row">    
            <div class="form-group col-sm-2">
                <label class="control-label"></span>帐号</label>
            </div>        
            <div class="form-group col-sm-10">
                <select name="token_id" id="token_id" class="form-control">
                     <option>--请选择--</option>
                     @foreach($accoutList as $account)
                        <option value="{{$account['id']}}" <?php if($account['id'] == $data->token_id) echo "selected='selecred'";?>>{{$account['alias']}}</option>
                     @endforeach
                </select>
            </div>
        
        </div>
        <div class="row">    
            <div class="form-group col-sm-2">
                <label class="control-label"><span class="red">*</span>模版名称</label>
            </div>        
            <div class="form-group col-sm-10">
                <input type="text" name="name" value="{{$data->name}}" id="name" datatype="*" nullmsg="请输入 模板名称" errormsg="请输入 模板名称"  class="form-control" >
            </div>
        
        </div>
        <div class="row">
            <div class="form-group col-sm-2">
                <label class="control-label">模板详情</label>
            </div>
            <div class="col-sm-10">
                <textarea name="content" id="content" class="form-control" rows="5">
                  {{$data->content}}
                </textarea>
            </div>
         </div>
    </div>
</div>
<input type="hidden" name="id" value="{{$data->id}}">
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
 console.log({{$data['token_id']}});
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
</script>
@stop