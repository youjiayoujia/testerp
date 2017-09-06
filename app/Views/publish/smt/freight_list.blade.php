<?php
/**
 * 运费模版-列表页
 */
?>
@extends('common.table')
@section('tableHeader') 
    <th>id</th>
    <th>账号</th>
    <th>运费模版ID</th>
    <th>运费模版名称</th>
    <th>是否默认</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
    		<td>{{$item->id}}</td>
    		<td><?php echo $token[$item->token_id]['alias'];?></td>
    		<td>{{$item->templateId}}</td>
    		<td>{{$item->templateName}}</td>
    		<td>
    		  @if($item->default == 0)否
    		  @else <span style="color:red;">是</span>
    		  @endif
    		  <a href="{{ route('smtProduct.getFreightDetailById', ['id'=>$item->id]) }}" class="btn btn-primary btn-xs" style="margin-left:40px;">
                        <span class="glyphicon glyphicon-eye-open"></span> 查看
              </a>
    		</td>
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
    <button type="button" id="synchronization" class="btn btn-success submit_btn ">同步</button>
    <button type="button" id="search" class="btn btn-success submit_btn">筛选</button>
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
					url:"{{route('smtProduct.getFreightTemplateList')}}",
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
							window.location.href = "{{route('smtProduct.freightManage')}}"+'?token_id='+val;
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
    		var token_id = $('#token_id').val();
    		if(!token_id){
    			alert('请选择帐号!');
    		}

    		window.location.href = "{{route('smtProduct.freightManage')}}"+'?token_id='+token_id;
    	})
    </script>
@stop
						
	
