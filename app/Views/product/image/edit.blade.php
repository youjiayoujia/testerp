@extends('common.form')
@section('formAction')  {{ route('productImage.update', ['id' => $model->id]) }}  @stop
@section('formBody')
		<input type="hidden" name="_method" value="PUT"/>
		<div class="row">
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
							{{$model->product->model}}公共图片
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
									 			<input type="text" value="{{$model->product->model}}" disabled="" class="form-control" id="model" />

											</div>
								 		</div>
									</div>
							 	</div>
							</div>
							<ul class="dowebok">
								@foreach($labels as $label)
									@if($label->group_id==1)
										<li><input type="radio" name="is_link" data-labelauty="{{$label->name}}" value="{{$label->id}}" {{ in_array($label->id, $label_arr)? 'checked' : '' }}></li>
									@endif  
								@endforeach
							</ul>
							<ul class="dowebok">
								@foreach($labels as $label)
									@if($label->group_id==2)
										<li><input type="checkbox" name="image_type[]" data-labelauty="{{$label->name}}" value="{{$label->id}}" {{ in_array($label->id, $label_arr)? 'checked' : '' }}></li>
									@endif  
								@endforeach
							</ul>
							<img src="{{ asset($model->path) }}/{{$model->name}}" width="600px" >
						</div>
					</div>
			
		
@stop
 
@section('pageJs')
		<link href="{{ asset('css/fileinput.css') }}" rel="stylesheet">
		<link href="{{ asset('css/jquery-labelauty.css') }}" rel="stylesheet">
		<script src="{{ asset('js/fileinput.js') }}"></script>
		<script src="{{ asset('js/jquery-labelauty.js') }}"></script>
		<script type="text/javascript">

				$("#file-1").fileinput({   
						//uploadAsync: false,   
						uploadUrl: "{{route('productImage.store')}}",
						uploadExtraData: function() {
							return {
									is_link:$('input[name="is_link"]:checked').val(),
									image_type: $('input[name="image_type"]:checked').val(),
									model:$("#model").val(),
							};
						}
						
				});
			
				$(':input').labelauty();
				
				$("#file-1").on("fileuploaded", function(event, data, previewId, index) {
						//alert(data.files);
						//console.log(data);
				});
		</script>
@stop
 
<style>
.dowebok ul { list-style-type: none;}
.dowebok li { display: inline-block;}
.dowebok li { margin: 10px 0;}
input.labelauty + label { font: 12px "Microsoft Yahei";}
</style>
 
 
 