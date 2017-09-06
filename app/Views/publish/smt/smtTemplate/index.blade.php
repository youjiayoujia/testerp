@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">ID</th>
    <th>效果图</th>
    <th>平台</th> 
    <th>模板名称</th>
    <th>操作</th> 
@stop
@section('tableBody')
    @foreach ($data as $item)
    <tr>
         <td><input type='checkbox' name='single[]' class='single' value="{{$item->id}}"></td>      
         <td>{{$item->id}}</td>
         <td>{{$item->pic_path}}</td>
         <td>
              @if ($item->plat == 6)
                                                 速卖通
              @endif
         </td>
         <td>{{$item->name}}</td>       
         <td>
            <a href="{{ route('smtTemplate.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
            </a>
            <a href="javascript:" class="btn btn-warning btn-xs copy"
               data-id="{{ $item->id }}">
                <span class="glyphicon glyphicon--share-alt"></span> 复制                    
            </a>      
            <a href="javascript:" class="btn btn-danger btn-xs delete_item"
               data-id="{{ $item->id }}"
               data-url="{{ route('smtTemplate.destroy', ['id' => $item->id]) }}">
                <span class="glyphicon glyphicon-trash"></span> 删除                    
            </a>      
         </td>
    </tr>
    @endforeach
@stop
@section('childJs')
<script type="text/javascript">
    $('.copy').on('click',function(){
    	if (!confirm('确定要复制吗？')) {
    		return false;
    	}
		var id = $(this).data('id');
		var url = "{{route('smtTemplate.copyTemplate')}}";
		$.ajax({
			url:url,
			data:'id='+id,
			type:'post',
			dataType:'JSON',
			success: function (data) {
				if (data.status) {
					showtips(data.info);
					window.location.reload();
				} else {
					showtips(data.info, 'alert-warning');
				}
			}
		});
    })
</script>   
@stop