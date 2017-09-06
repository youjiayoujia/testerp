@extends('common.table')
@section('tableHeader')
<th><input type='checkbox' name='select_all' class='select_all'></th>
<th class="sort" data-field="id">帐号</th>
<th>SKU</th>
<th>产品广告ID</th>
<th>侵权原因</th>
<th>商标名</th>
<th>知识产权编号</th>
<th>销售</th>
<th>联系人</th>
<th>电话</th>
<th>邮箱</th>
<th>导入时间</th>
<th>操作</th>
@stop
@section('tableToolButtons')
     <div class="btn-group">
            <form method="POST" action="{{ route('importCopyrightData') }}" enctype="multipart/form-data" id="add-lots-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" class="file" id="qualifications" placeholder="上传侵权数据" name="excel" value="">

            </form>

      </div>
      <div class="btn-group">        
        <a class="btn btn-success add-lots-of-copyright" href="javascript:void(0);">
            <i class="glyphicon glyphicon-plus"></i> 批量导入侵权数据
        </a>
        <a href="javascript:" class="btn btn-warning download-csv">侵权列表模版下载
            <i class="glyphicon glyphicon-arrow-down"></i>

        </a>
    </div>  
    <div class="btn-group">
        <a href='javascript:' class='btn btn-info exportAll' value='导出所有的列表'>导出所有的列表</a>
    </div>
    <div class="btn-group">
        <a href='javascript:' class='btn btn-info exportPart' value='导出勾选的列表'>导出勾选的列表</a>
    </div>
    <div class="btn-group">
        <a href='javascript:' class='btn btn-info btn-danger deletePart' value='删除勾选的数据'>删除勾选的数据</a>
    </div>    
@stop
@section('tableBody')
    @foreach($data as $copyright)
        <tr>
            <td>
                <input type="checkbox" name="copyright_id" value="{{ $copyright->id }}">
            </td>
            <td>{{ $copyright->account }}</td>
            <td>{{ $copyright->sku }}</td>
            <td>{{ $copyright->pro_id }}</td>
            <td>{{ $copyright->reason }}</td>
            <td>{{ $copyright->trademark }}</td>
            <td>{{ $copyright->ip_number }}</td>
            <td>{{ $copyright->seller }}</td>
            <td>{{ $copyright->contact_name }}</td>
            <td>{{ $copyright->phone }}</td>
            <td>{{ $copyright->email }}</td>
            <td>{{ $copyright->import_time }}</td>        
            <td>
                <a href="{{ route('copyright.show', ['id'=> $copyright->id]) }}" class="btn btn-info btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>               
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $copyright->id }}"
                   data-url="{{ route('copyright.destroy', ['id' => $copyright->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach   
@stop
@section('childJs')
<link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
<script src="{{ asset('js/multiple-select.js') }}"></script>
<script>
$(document).ready(function () {
	$('select[name="mixedSearchFields[filterSelects][plat]"]').change(function(){
		var plat = $(this).val();
		if(plat){
			$.ajax({
				url: "{{ route('copyright.getAllAccountByPlatID') }}",
				data: 'plat='+plat,
				type: 'POST',
				dataType: 'JSON',
				success: function(data){
					if(data.status){
						$('select[name="mixedSearchFields[filterSelects][account]"] option').remove();
						$.each(data.data,function(index,val) {
							$('select[name="mixedSearchFields[filterSelects][account]"]').append("<option value='"+val+"'>"+val+"</option>");
						})
					}
				}
			})
		}else{
			$('select[name="mixedSearchFields[filterSelects][account]"] option').remove();
		}
	});
	$(".add-lots-of-copyright").click(function(){
		var csv = $('input[name=excel]').val();
		if(csv == ''){
			alert('请先上传excel文件！');
            return false;
		}
		$('#add-lots-form').submit();
			
	});
	
	$('.download-csv').click(function(){
    	location.href="{{ route('downloadTemplate')}}";
    });  
       
    $('.exportAll').click(function(){
        location.href = "{{ route('exportAllData')}}";
    });

    $('.exportPart').click(function(){
        var checkbox = document.getElementsByName("copyright_id");
        var copyright_ids = "";
        for (var i = 0; i < checkbox.length; i++) {
            if(!checkbox[i].checked)continue;
            copyright_ids += checkbox[i].value+",";
        }
        copyright_ids = copyright_ids.substr(0,(copyright_ids.length)-1);
        if(!copyright_ids){
			alert('请先勾选数据!');
			return false;
        }
        location.href = "{{ route('exportPartData') }}?copyright_ids=" + copyright_ids;
    });

    $('.deletePart').click(function(){    	
		var copyright_ids = $('input[name="copyright_id"]:checked').map(function(){
			return $(this).val();
		}).get().join(',');
		if(!copyright_ids){
			alert('请先勾选数据!');
			return false;
		}

		if(confirm('您确定要进行此操作的?')){
			location.href = "{{ route('deletePartData') }}?copyright_ids=" + copyright_ids;
		}
		
    })
});
</script>
@stop