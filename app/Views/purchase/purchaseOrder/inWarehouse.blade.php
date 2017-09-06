@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">    
        <div class="panel-body">
           <div class="form-group col-lg-2">
                <strong>采购单号</strong>       
                <input class="form-control" id="p_id" placeholder="采购单号" name='post_coding' value="">
            </div>
            <div class="form-group purchase">

            </div>	
        </div> 
    </div>  

@stop

@section('pageJs')
<script type='text/javascript'>
    $(document).ready(function(){
    	javascript:document.getElementById("p_id").focus();
    	$(document).on('keydown', function (event) {
    	    if(event.keyCode == '13') {
                var p_id = $("#p_id").val();
                if(p_id==''){
                    p_id = $("#ajaxp_id").val();
                }
    	    	$.ajax({
                    url: "{{ route('ajaxInWarehouse') }}",
                    data: {id: p_id},
                    dataType: 'html',
                    type: 'get',
                    success: function (result) {
                        $(".purchase").html(result);
                        //if($("#ajaxp_id").val()==''){
                            $("#ajaxp_id").val($("#p_id").val());
                        //}
                        $("#p_id").val("");
                    }
                });
    	    }
    	});
    })


	$(document).on('click','.modify',function(){
        var data = ""
		$("input[name^='goodnum_']").each(function(){
			id = $(this).attr("name");
            if($(this).val()!=0&&($(this).attr("disabled")!='disabled')){
                id = id.substr(8);
                data += id+":"+$(this).val();
                data +=":"+$('#badnum_'+id).val()+",";
            }   
　　　　});

        $.ajax({
            url:"{{ route('updateArriveLog') }}",
            data:{data:data,p_id:$("#ajaxp_id").val()},
            dataType:'json',
            type:'get',
            success:function(result){
                if(typeof(result)=='string'){
                    alert("sku:"+result+"库位不存在");return;
                }
                $("#p_id").val(result);
                javascript:document.getElementById("p_id").focus();
                var e = jQuery.Event("keydown");//模拟一个键盘事件
                e.keyCode =13;//keyCode=13是回车
                $("#p_id").trigger(e); 
            }
        });                         
	});

</script>
@stop


