@extends('common.form')
@section('formAction') {{ route('spu.MultiUpdate') }} @stop
@section('formBody')
<script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
<script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
<script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
<link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">
<ul class="nav nav-tabs" id="myTab">
    @foreach($channels as $channel)
        <li name="{{$channel->id}}" class="mychannel">
            <a href="#{{$channel->name}}" class>
                {{$channel->name}}
            </a>
        </li>
    @endforeach
</ul>
<br>

<div class="tab-content">
    @foreach($channels as $key=>$channel)
    <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" id="{{$channel->name}}">      
        <label for="" >渠道：{{$channel->name}}</label>
        <div>请选择编辑的语言</div>
            <ul class="dowebok">
                @foreach($languages as $la_name=>$language)
                    <li><input type="radio" name="info[{{$channel->id}}][language]" class="change_language" data-labelauty="{{$language}}" value="{{$la_name}}" {{$la_name=='en'?'checked':''}}></li>
                @endforeach    
            </ul>
        <div class="row">
            <div class="form-group col-lg-12">
                <input type='text' class="form-control {{$channel->id}}_myname" id="" placeholder="标题" name='info[{{$channel->id}}][name]' value="{{$key?'':$default['en_name']}}">
            </div>
        
            <div class="form-group  col-lg-12">    
                <input type='text' class="form-control {{$channel->id}}_mykeywords" id="" placeholder="关键词" name='info[{{$channel->id}}][keywords]' value="{{$key?'':$default['en_keywords']}}">
            </div>
        </div> 
        <div class="row">
            <div class="col-lg-12" id="templateContent_{{$key}}">
                <label for="" >描述：</label>
                <div class="form-group">
                    <textarea class="form-control {{$channel->id}}_mydescription privacy" id="editor_{{$key}}" rows="16" placeholder="标题" name="info[{{$channel->id}}][description]" style="width:100%;height:400px;">{{ $key?'':$default['en_description']}}</textarea>
                </div>
            </div>
        </div>
        <script type="text/javascript" charset="utf-8"> var um_{{$key}} = UM.getEditor('editor_{{$key}}'); </script>
    </div>
    @endforeach
</div>

<input type="hidden" value="{{$id}}" name="spu_id">

@stop
@section('pageJs')

<script src="{{ asset('js/jquery-labelauty.js') }}"></script>
<link href="{{ asset('css/jquery-labelauty.css') }}" rel="stylesheet">
<script type="text/javascript">
    $(':input').labelauty();
    $(function () { 
        $('#myTab a:first').tab('show');//初始化显示哪个tab 
        $('#myTab a').click(function (e) { 
          e.preventDefault();//阻止a链接的跳转行为 
          $(this).tab('show');//显示当前选中的链接及关联的content 
        }) 
      })

    $(".change_language").click(function(){
        var language = $(this).val();
        var channel_id = $("#myTab li.active").attr("name");
        $.ajax({
            url: "{{ route('spu.Info') }}",
            data: {language:language,channel_id:channel_id,spu_id:{{$id}}},
            dataType: 'json',
            type: 'get',
            success: function (result) {
                //alert(channel_id);
                $("."+channel_id+"_myname").val(result['name']);
                $("."+channel_id+"_mykeywords").val(result['keywords']);
                $("."+channel_id+"_mydescription").html(result['description']);
            }
        });
    })

    $(".mychannel").click(function(){
        var language = $(".tab-pane.active").find(".change_language:checked").val();
        var channel_id = $(this).attr('name');
        $.ajax({
            url: "{{ route('spu.Info') }}",
            data: {language:language,channel_id:channel_id,spu_id:{{$id}}},
            dataType: 'json',
            type: 'get',
            success: function (result) {
                $("."+channel_id+"_myname").val(result['name']);
                $("."+channel_id+"_mykeywords").val(result['keywords']);
                $("."+channel_id+"_mydescription").html(result['description']);
            }
        });
    })

    var pendingRequests = {};
      jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
       var key = options.url;
       //console.log(key);
       if (!pendingRequests[key]) {
        pendingRequests[key] = jqXHR;
       }else{
        //jqXHR.abort(); //放弃后触发的提交
        pendingRequests[key].abort(); // 放弃先触发的提交
       }
     
       var complete = options.complete;
       options.complete = function(jqXHR, textStatus) {
        pendingRequests[key] = null;
        if (jQuery.isFunction(complete)) {
        complete.apply(this, arguments);
        }
       };
      });

    $(".privacy").blur(function(){
        var text = $(this).children().text();
        $.ajax({
            url: "{{ route('spu.checkPrivacy') }}",
            data: {text:text},
            dataType: 'json',
            type: 'get',
            success: function (result) {
                if(result){
                    alert('字符'+result+'侵权');
                }else{
                    $(".btn-success").removeAttr('disabled');
                }
            }
        });
    })

    $(".privacy").focus(function(){
        $(".btn-success").attr('disabled','disabled');
    })

</script>
@stop
<style>
.dowebok ul { list-style-type: none;}
.dowebok li { display: inline-block;}
.dowebok li { margin: 10px 0;}
input.labelauty + label { font: 12px "Microsoft Yahei";}
.edui-container {width:1850px !important;z-index: 999}
.edui-body-container {width:1850px !important;z-index: 999}
</style>