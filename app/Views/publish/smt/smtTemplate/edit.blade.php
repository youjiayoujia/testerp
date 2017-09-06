<style>
.form-group{
    margin-top:10px;
}

.red{
    color: #dd5a43 !important;
}
</style>
@extends('common.form')
@section('formAction') {{ route('smtTemplate.store') }} @stop
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
                            <option value="6" >速卖通</option>
                       </select>
                 </div>
            </div>
        </div>
        <div class="row">    
            <div class="form-group col-sm-2">
                <label class="control-label"><span class="red">*</span>模版名称</label>
            </div>        
            <div class="form-group col-sm-10">
                <input type="text" name="name" value="{{$data->name}}" id="name" datatype="*" nullmsg="请输入 模板名称" errormsg="请输入 模板名称"  class="form-control">
            </div>
        
        </div>
        <div class="row">
            <div class="form-group col-sm-2">
                <label class="control-label">效果图</label>
            </div>
            <div class="form-group col-sm-5">
                    <a class="btn btn-primary btn-sm" id="uploadPic">上传</a>
                    <div class="img">
                        @if ($data->pic_path)
                            <!--图片显示,注意图片路径问题-->
                            <a href="javascript: viod(0);">
                                <img src="{{$data->pic_path}}" id="pic_show" alt="{{$data->pic_path}}" width="50" height="50"/>
                            </a>

                        @endif
                        <input type="hidden" name="pic_path" id="pic_path" />
                    </div>
                </div>    
        </div>
        <div class="row">
            <div class="form-group col-sm-2">
                <label class="control-label">模板详情</label>
            </div>
            <div class="col-sm-10">
                <textarea name="content" id="content" class="form-control" rows="5">
                  <?php echo htmlspecialchars_decode($data['content']);?>
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
<script type="text/javascript">
/**自定义kindeditor插件结束**/

KindEditor.ready(function (K) {
    var editor = K.create("#content", {
        'uploadJson': '{{route('uploadToProject',['_token' => csrf_token()])}}',
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
            'emoticons', 'link', 'unlink', 'module'],
        "htmlTags": false, //要过滤style中的样式的话，直接不用写这句
        "afterBlur": function(){this.sync();} //必须，不然提交不到
    });

    var editor2 = K.editor({
        allowFileManager: false,
        uploadJson: '{{route('uploadToProject',['_token' => csrf_token()])}}'
    });

    //图片上传，路径应该要处理下
    K('#uploadPic').click(function(){
        editor2.loadPlugin('image', function() {
            editor2.plugin.imageDialog({
                showRemote: false,
                clickFn : function(url, title, width, height, border, align) {
                	var input_img = '<img src="'+url +'" width="50" height="50">';
                	$('.img').append(input_img);
                    editor2.hideDialog();
            	}
            });
        });
    });
})

    
</script>
@stop