@extends('common.table')
@section('tableHeader') 
    <th>id</th>
    <th>账号</th>
    <th>服务模版ID</th>
    <th>产品组名称</th>
@stop
@section('tableBody')
    @foreach($data as $item)
    <tr>
        <td>{{$item->id}}</td>
        <td><?php echo $token[$item['token_id']]['alias'];?></td>
        <td>{{$item->serviceID}}</td>
        <td>{{$item->serviceName}}</td>
    </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <select class="form-control sx" name="token_id" id="token_id">
            <option value="">--全选--</option>
            @foreach($token as $row)
            <option value="{{$row['id']}}">{{$row['alias']}}</option>
            @endforeach
        </select>
    </div>
    <button type="button" id="service_synchronization" class="btn btn-success submit_btn ">同步</button>
    <button type="button" id="search" class="btn btn-success ">筛选</button>
@stop
@section('childJs')
<script type="text/javascript">
$(function(){
	$(document).on('click', '#service_synchronization', function(event) {
		event.preventDefault();
		var token_id = $('#token_id').val();
    	var text = '';
    	if(token_id == ''){
    		text = "确定同步全部帐号吗?";
        }else{
        	text = "确定同步该帐号吗?";
        }  
		if(confirm(text)){
			$.ajax({
				url:"{{route('smtProduct.getServiceTemplateList')}}",
				type:'POST',
				dataType:'json',
				data:'token_id='+token_id,
				beforeSend:function(){
					$('#service_synchronization').html('同步中...').addClass('disabled');
				},
				success:function(data){
					if (data.status) {
						showxbtips(data.info);
						window.location.href = "{{route('smtProduct.serviceManage')}}"+'?token_id='+token_id;
					}else {
						showxbtips(data.info, 'alert-warning');
					}
				},
				complete:function(){
					$('#service_synchronization').html('同步').removeClass('disabled');
				}
			});
		}else
			return false;
	});

	$("#search").on('click',function(){
		var token_id = $('#token_id').val();
		if(!token_id){
			alert('请选择帐号!');
		}

		window.location.href = "{{route('smtProduct.serviceManage')}}"+'?token_id='+token_id;
	});
})
</script>
@stop