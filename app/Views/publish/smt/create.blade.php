<style>
.row-border {
	border: 1px solid #ccc;
	border-radius: 4px;
	box-shadow: 3px 4px 3px rgba(238, 238, 238, 1);
	margin-bottom: 10px;
}

.proh {
	width: 100%;
	height: 30px;
}

.hideaccordion,.showaccordion {
	float: left;
	height: 18px;
	line-height: 18px;
	position: relative;
	padding: 6px;
}

.hideaccordion h1,.showaccordion h1 {
	font-size: 14px;
	font-weight: bold;
	color: #444;
}

.hideaccordion h1 i {
	cursor: pointer;
}

.probody {
	width: 100%;
	height: 100%;
	padding: 0 10px;
}

.pic-main {
	padding: 5px;
	border: 1px solid #ccc;
}

.pic-main li {
	margin: 5px;
	padding: 0px;
	border: 0px;
	width: 102px;
	text-align: right;
}

.form-group{
    margin-top:10px;	
}

/***Validform的样式--su20141125***/
.Validform_checktip {
	margin-left: 8px;
	line-height: 20px;
	height: 20px;
	overflow: hidden;
	color: #999;
	font-size: 12px;
}
/*.Validform_right{color:#71b83d;padding-left:20px;background:url(images/right.png) no-repeat left center;}
.Validform_wrong{color:red;padding-left:20px;white-space:nowrap;background:url(images/error.png) no-repeat left center;}
.Validform_loading{padding-left:20px;background:url(images/onLoad.gif) no-repeat left center;}*/
.Validform_error {
	background-color: #ffe7e7;
}

#Validform_msg {
	color: #7d8289;
	font: 12px/1.5 tahoma, arial, \5b8b\4f53, sans-serif;
	width: 280px;
	background: #fff;
	position: absolute;
	top: 0px;
	right: 50px;
	z-index: 99999;
	display: none;
	filter: progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135,
		Color='#999999');
	-webkit-box-shadow: 2px 2px 3px #aaa;
	-moz-box-shadow: 2px 2px 3px #aaa;
}

#Validform_msg .iframe {
	position: absolute;
	left: 0px;
	top: -1px;
	z-index: -1;
}

#Validform_msg .Validform_title {
	line-height: 25px;
	height: 25px;
	text-align: left;
	font-weight: bold;
	padding: 0 8px;
	color: #fff;
	position: relative;
	background-color: #000;
}

#Validform_msg a.Validform_close:link,#Validform_msg a.Validform_close:visited
	{
	line-height: 22px;
	position: absolute;
	right: 8px;
	top: 0px;
	color: #fff;
	text-decoration: none;
}

#Validform_msg a.Validform_close:hover {
	color: #cc0;
}

#Validform_msg .Validform_info {
	padding: 8px;
	border: 1px solid #000;
	border-top: none;
	text-align: left;
}
</style>

@extends('common.form')
@section('formAction') {{ route('smt.addProduct') }} @stop
@section('formAttributes') {{ "class=validate_form" }} @stop
@section('formBody')
<div class="panel panel-default">
    <div class="panel-heading">一般信息</div>
    <div class="panel-body"> 
        <div class="row">
            <div class="form-group col-sm-2">
             <label for="subject" class="right">请选择账号：</label>
            </div>

            <div class="form-group col-sm-2">
                <select name="token_id" id="token_id" class="form-control">
		                  @foreach($account as $item)
                         <option value="{{$item->id}}">{{$item->alias}}
                         </option>
                        @endforeach			                 
		         </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-2">
             <label for="subject" class="right">关键词：</label>
            </div>

            <div class="form-group col-sm-4">
                <input type="text" class="form-control" id="category_keyword" placeholder="关键词" name="category_keyword" />	                 
            </div>
            <div class="col-sm-4">
				<a class="btn btn-default btn-sm form-group" id="command_btn">推荐类目</a>
				<a class="btn btn-default btn-sm form-group" id="choose_btn">本地选择</a>
			</div>
        </div>
        <div class="row">
            <div class="col-sm-10 col-sm-offset-2 clear-fix">
			    <div id="category_select">
					<div class="col-sm-3 no-padding-left">
						<select size="10" class="form-control category_list" multiple>
						    @foreach($category_info as $item)
							<option value="{{$item->category_id}}" lang="{{$item->isleaf}}">{{$item->category_name}}</option>
						    @endforeach
						</select>
					</div>
				</div>
				<div class="hide" id="category_ajax_select">				    
				</div>
			</div>
        </div>
        <div class="row">
            <div class="form-group col-sm-2">
                <label for="subject" class="right">已选择分类：</label>
            </div>
            <div class="form-group col-sm-10">
    	        <input type="text" class="form-control" readonly="readonly" id="category_name"/>
    	        <input type="hidden" id="category_id" name="category_id" datatype="*" nullmsg="必须选择分类" />
				    
			</div>
        </div>
    </div>
</div>
@stop
@section('formButton')
    <div class="text-center">
        <button type="submit" class="btn  btn-primary btn-sm disabled " id="category_btn">确定选择</button>
    </div>

@show{{-- 表单按钮 --}}


@section('pageJs')


<script type="text/javascript">
$(function(){
	//本地分类异步获取
	$(document).on('change', '.category_list', function(){
		var a_isleaf = null;
		var a_category_id = $(this).val();		
		a_isleaf = $(this).find('option:selected').attr('lang');
		$(this).parents('.col-sm-3').nextAll().remove();
		//判断是否是末子叶,不是末子叶才异步
		if (a_isleaf == '0'){
			$.ajax({
				url: "{{ route('smt.showChildCategory') }}",
				data: 'category_id='+a_category_id,
				type: 'get',
				dataType: 'json',
				async: false,
				success:function(data){
					var input = '<div class="col-sm-3 no-padding-left">';
					input += '<select size="10" class="form-control category_list" multiple>';
				    
					$.each(data, function(index, el){
						input += '<option value="'+el.category_id+'" lang="'+el.isleaf+'">'+el.category_name+'</option>';
					});

					input += '</select>';
					input += '</div>';
					$('#category_select').append(input);
				}
			});
			if (!$('#category_btn').hasClass('disabled')){
				$('#category_btn').addClass('disabled');
			}
		}else{
			//末节点了，可以设置分类ID了
			var category_name = $('.category_list').map(function(){
				return $(this).find('option:selected').text();
			}).get().join('>>');
			$('#category_name').val(category_name);
			$('#category_id').val(a_category_id);
			$('#category_btn').removeClass('disabled');
		}
	});

	//本地选择按钮点击事件
	$(document).on('click', '#choose_btn', function(){
		if ($('#category_select').hasClass('hide')){ //有这个样式，说明之前是使用了推荐的
			
			$('#category_select').empty();
			$('#category_select').removeClass('hide');
			$('#category_ajax_select').addClass('hide');
			$('#category_name').val('');
			$('#category_id').val(''); //清空分类ID
			$.ajax({
				url: "{{ route('smt.showChildCategory') }}",
				data: 'category_id=0',
				type: 'POST',
				dataType: 'json',
				success:function(data){
					var input = '<div class="col-sm-3 no-padding-left">';
					input += '<select size="10" class="form-control category_list" multiple>';
				    
					$.each(data, function(index, el){
						input += '<option value="'+el.category_id+'" lang="'+el.isleaf+'">'+el.category_name+'</option>';
					});

					input += '</select>';
					input += '</div>';
					$('#category_select').append(input);
				}
			});
		}
	});

	//推荐类目点击
	$(document).on('click', '#command_btn', function(){
		var keyword = $.trim($('#category_keyword').val());
		var token_id = $('#token_id').val();
		if (keyword != '' && token_id != ''){
			if ($('#category_ajax_select').hasClass('hide')){
				$('#category_ajax_select').removeClass('hide');
				$('#category_select').addClass('hide');
			}
			$.ajax({
				url: '{{route('smt.showCommandCategoryList')}}',
				data: 'keyword='+keyword+'&token_id='+token_id,
				type: 'GET',
				dataType: 'json',
				success: function(data){
					$('#category_ajax_select').empty();
					var input = '<div class="col-sm-12 no-padding-left">';
					input += '<select class="form-control ajax_category_list" multiple size="10">';
					$.each(data, function(index, el){
						input += '<option value="'+el.id+'">'+el.name+'</option>';
					});
					input += '</select>';
					input += '</div>';
					$('#category_ajax_select').append(input);
					if (!$('#category_btn').hasClass('disabled')){
						$('#category_btn').addClass('disabled');
					}
				}
			});
		}else {
			alert('推荐类目必须输入关键词并选择账号');
		}
		return false;
	});

	//推荐类目出来的分类处理
	$(document).on('change', '.ajax_category_list', function(){
		var a_category_id = $(this).val();
		var category_name = $(this).find('option:selected').text();
		$('#category_name').val(category_name);
		$('#category_id').val(a_category_id);
		$('#category_btn').removeClass('disabled');
	});

	$('.hideaccordion h1 i').click(function() {
		if (this.className == 'icon-plus') {
			this.className = 'icon-minus';
			$(this).parents('.row-border').children('.probody').children('.procnt').css('display', 'none');
			$(this).parents('.row-border').children('.probody').children('.promsg').css('display', '');
		}else {
			this.className = 'icon-plus';
			$(this).parents('.row-border').children('.probody').children('.procnt').css('display', '');
			$(this).parents('.row-border').children('.probody').children('.promsg').css('display', 'none');
		}
	});

	//表单验证
	$('.validate_form').Validform({
	});

})


   
   
      


       
</script>
@stop