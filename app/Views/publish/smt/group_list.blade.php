<?php
/**
 * 产品分组-列表页
 */
?>
@extends('common.table')
@section('tableHeader') 
    <th>id</th>
    <th>账号</th>
    <th>产品组ID</th>
    <th>产品组名称</th>
@stop
@section('tableBody')
    <?php foreach($group as $g):?>
    	<tr>
    		<td><?php echo $g['id'];?></td>
    		<td><?php echo $token[$g['token_id']]['alias']?></td>
    		<td><?php echo $g['group_id']?></td>
    		<td><?php echo $g['group_name']?></td>
    	</tr>
    	<?php if (array_key_exists('child', $g)):?>
    		<?php foreach($g['child'] as $c):?>
    			<tr>
    				<td><?php echo $c['id'];?></td>
    				<td><?php echo $token[$c['token_id']]['alias']?></td>
    				<td><?php echo '&nbsp;|--'.$c['group_id']?></td>
    				<td><?php echo '&nbsp;|--'.$c['group_name']?></td>
    			</tr>
    		<?php endforeach;?>
    	<?php endif;?>
    <?php endforeach;?>
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
    <button type="button" id="synchronization" class="btn btn-success submit_btn ">同步</button>
    <button type="button" id="search" class="btn btn-success ">筛选</button>
@stop
@section('childJs')
    <script type="text/javascript">
    	$('#synchronization').on('click',function(){
    		var val = $('#token_id').val();
        	var text = '';
        	if(val == ''){
        		text = "确定同步全部帐号吗?";
            }else{
            	text = "确定同步该帐号吗?";
            }  
			if(confirm(text)){
				$.ajax({
					url:"{{route('smtProduct.getProductGroup')}}",
					type:'post',
					dataType:'json',
					data:'token_id='+val,
					beforeSend:function(){
						$('#synchronization').html('同步中...').addClass('disabled');
					},
					success:function(data){
						console.log(data);
						if (data.status) {
							showxbtips(data.info);
							window.location.href = "{{route('smtProduct.groupManage')}}"+'?token_id='+val;
						}else {
							showxbtips(data.info, 'alert-warning');
						}
					},
					complete:function(){
						$('#synchronization').html('同步').removeClass('disabled');
					}
				})
			}else{
			}
        });

		$("#search").on('click',function(){
			var val = $('#token_id').val();
        	var text = '';
        	if(val == ''){
        		alert('请选择帐号!');
        		return false;
            }
            window.location.href = "{{route('smtProduct.groupManage')}}"+'?token_id='+val;
		});
    </script>
@stop
						
	
