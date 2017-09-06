@extends('common.detail')
    @section('detailBody')
  <div class="container">
   <form enctype="multipart/form-data" method="POST" action="upload-handler" class="form">
    <div class="alert alert-info">
     <strong>请在这里上传产品公共图</strong>
     <ul>
      <li>“实拍图”和“链接图”请根据实际情况选择；</li>
      <li>图片类型包括Logo，Size，色卡，请在下方选择；</li>
     </ul>
    </div>
    <div class="panel panel-primary parent-sku-panel">
     <div class="panel-heading">
      {{$model}}公共图片
     </div>
     <div class="panel-body">
      <div class="panel panel-default image-panel">
       <div class="panel-body">
        <div class="row">
         <div class="col-md-6">
          <div class="input-group">
           <div class="input-group-addon">
            <span class="glyphicon glyphicon-tag"></span>&nbsp;SKU:
           </div>
           <input type="text" value="{{$model}}" disabled="" class="form-control" id="model" />

          </div>
         </div>
        </div>
       </div>
      </div>
      <ul class="dowebok">
        <?php $i=0; ?>
        @foreach($labels as $label)
          @if($label->group_id==1)
            <li><input type="radio" name="is_link" data-labelauty="{{$label->name}}" value="{{$label->id}}" {{ $i==0? 'checked' : '' }}></li>
            <?php $i++; ?>
          @endif  
          
        @endforeach
      </ul>
      <ul class="dowebok">
        <?php $j=0; ?>
        @foreach($labels as $label)
            @if($label->group_id==2)
                <li><input type="checkbox" name="image_type" data-labelauty="{{$label->name}}" value="{{$label->id}}" {{$j==0?'checked':''}}></li>
                <?php $j++; ?>
            @endif
              
        @endforeach
      </ul>
      <div class="form-group">
        <input id="file-1" class="file" type="file" multiple data-preview-file-type="any" data-sku="ssdf" input-name="dlo">
      </div>
    </div>  
<div class="panel panel-default">
    @foreach($images as $image)
        <div class="panel-body preview-panel">
            <div class="file-preview-frame cover-container ui-droppable" name="{{$image->id}}">
                <img class="file-preview-image ui-draggable ui-draggable-handle" src="{{ asset($image->path) }}/{{$image->name}}" title="{{$image->name}}" alt="{{$image->name}}">
                <input type="hidden" class="fileId" value="5742cf661ce0ac104f064c68">
                <div class="btn-group tags tags_{{$image->id}}">
                    <button type="button" class="btn btn-xs photoBtn btn-default active" data-tag="photo" data-id="{{$image->id}}" title="默认图"><span class="glyphicon glyphicon-eye-open"></span></button>
                    <button type="button" class="btn btn-xs photoBtn btn-success active" data-tag="photo" title="实拍图"><span class="glyphicon glyphicon-picture"></span></button>
                    <button type="button" class="btn btn-xs linkBtn btn-default" data-tag="link" title="链接图"><span class="glyphicon glyphicon-link"></span></button>
                    <button type="button" class="btn btn-xs shapeBtn btn-success active" data-tag="shape" title="外观图"><span class="glyphicon glyphicon-tree-deciduous"></span></button>
                    <button type="button" class="btn btn-xs frontBtn btn-success active" data-tag="front" title="正面图"><span class="glyphicon glyphicon-home"></span></button>
                </div>
                <div class="btn-group operations operations_{{$image->id}}"><button class="btn btn-xs btn-danger delBtn" title="删除"><span class="glyphicon glyphicon-trash"></span></button>
                </div>
            </div>
        </div> 
    @endforeach
</div>
@stop

@section('pageJs')
    <link href="{{ asset('css/fileinput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-labelauty.css') }}" rel="stylesheet">
    <script src="{{ asset('js/fileinput.js') }}"></script>
    <script src="{{ asset('js/jquery-labelauty.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() { 
            
        }); 

        
        $(".photoBtn").click(function(){
            var id = $(this).data("id");
            $.ajax({
                url: "{{ route('imageLable') }}",
                data:{id:id},
                dataType: "html",
                type:'get',
                success:function(result){
                    if(result==0){
                        $(".ajaxinsert").html('');
                    }else{
                        $(".ajaxinsert").html(result);  
                    }
                    
                }
            });  
        })

        $(".file-preview-frame").mouseover(function(){
            var id = $(this).attr('name');
            $(".cover-container .tags_"+id).css("display","inline");
            $(".cover-container .operations_"+id).css("display","inline");
        })

        $(".file-preview-frame").mouseleave(function(){
            var id = $(this).attr('name');
            $(".cover-container .tags_"+id).css("display","none");
            $(".cover-container .operations_"+id).css("display","none");
        })
        
        $("#file-1").fileinput({
            //uploadAsync: false,  
            uploadUrl: "{{route('productImage.store')}}",
            uploadAsync: true,
            overwriteInitial: false,
            initialCaption: "请选择产品图（支持多选）",
            dropZoneTitle: '',
            initialPreviewAsData: true,
            // identify if you are sending preview data only and not the raw markup
            initialPreviewFileType: 'image',
            // image is the default and can be overridden in config below
            layoutTemplates: {
                actionDelete: '<button type="button" class="kv-file-remove {removeClass}" ' + 'title="{removeTitle}" {dataUrl}{dataKey}>{removeIcon}</button>\n',
                actionUpload: '<button type="button" class="kv-file-upload {uploadClass}" title="{uploadTitle}">' + '{uploadIcon}</button>',
            },
            uploadExtraData: function() {
                var str = document.getElementsByName("image_type");
                var chestr = "";
                for (i = 0; i < str.length; i++) {
                    if (str[i].checked == true) {
                        chestr += str[i].value + ",";
                    }
                }
                return {
                    is_link: $('input[name="is_link"]:checked').val(),
                    image_type: chestr,
                    model: $("#model").val(),
                };
            }
        });

$(':input').labelauty();

$("#file-1").on("fileuploaded",
function(event, data, previewId, index) {
    //alert(data.files);
});
    </script>
@stop
 
<style>
.dowebok ul { list-style-type: none;}
.dowebok li { display: inline-block;}
.dowebok li { margin: 10px 0;}
input.labelauty + label { font: 12px "Microsoft Yahei";}
.file-preview-frame {
    border: 1px solid #ddd;
    box-shadow: 1px 1px 5px 0 #a2958a;
    display: table;
    float: left;
    height: 160px;
    margin: 8px;
    padding: 6px;
    text-align: center;
    vertical-align: middle;
}
.file-preview-image {
    height: 160px;
}
.cover-container {
    position: relative;
}
.cover-container .tags {
    display: none;
    position: absolute;
    left: 6px;
    top: 6px;
}
.cover-container .operations {
    display: none;
    position: absolute;
    right: 6px;
    top: 6px;
}
.panel-default {
    border-color: #ddd;
    margin: 0 18px 0 18px;
}

</style>

 