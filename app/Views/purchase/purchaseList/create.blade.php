@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-body">
           <div class="form-group col-lg-2">
                <strong>运单号</strong>
               
                <input class="form-control" id="p_id" placeholder="订单号" name='post_coding' value="">
            </div>
        </div> 

        <div class="panel-body">
        	<div class="form-group purchase">

        	</div>
        </div> 
        
    </div>

    <div class="panel panel-default"> 
    	<div class="panel-body">
		    <div class="form-group col-lg-2">
		            <strong>包裹运单号查询</strong>

		    </div>
		    <div class="form-group col-lg-12">
		    	运单号：<input type="text" id="trackingNo">
		    	采购单号：<input type="text" id="po_id">
		    	状态
		    	<select name="" id="status">
		    		<option value="2"></option>
		    		<option value="0">未关联</option>
		    		<option value="1">已关联</option>
		    	</select>
		    	扫单时间<input type="text" id='date_from' class='datetimepicker_dark'>--<input type="text" id='date_to' class='datetimepicker_dark'>
		    	<button class="search">查询</button>
                <button class="export">导出</button>
		    </div>

		    <br>
		    <div class='scan'>
				
		    </div>
        </div>
    </div>  
@stop
@section('pageJs')
<script src="{{ asset('js/jquery.datetimepicker.full.js') }}"></script>
<script type='text/javascript'>
$('.datetimepicker_dark').datetimepicker({theme:'dark'})
    $(document).ready(function(){
    	javascript:document.getElementById("p_id").focus();
    	$(document).on('keydown', function (event) {
    	    if(event.keyCode == '13') {
                var p_id = $("#p_id").val();
                if(p_id==''){
                    p_id = $("#ajaxp_id").val();
                }

    	    	$.ajax({
                    url: "{{ route('ajaxScan') }}",
                    data: {id: p_id},
                    dataType: 'html',
                    type: 'get',
                    success: function (result) {
                        $(".purchase").html(result);
                        if($("#bang").val()==1){
                            $("#p_id").attr("disabled","disabled");
                        }
                    }
                });
    	    }
    	});
    })

	function binding(){
	   var postage = $('#postage').val();
	   var purchaseOrderId = $('#purchase_order_id').val();
	   var postCoding = $('#post_coding').val();
	   var wuliu_id = $("#wuliu_id").val();
	   $.ajax({
                url: "{{ route('binding') }}",
                data: {postage: postage,purchaseOrderId:purchaseOrderId,postCoding:postCoding,wuliu_id:wuliu_id},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if(result == 1){
						alert('绑定成功');
                        $("#p_id").removeAttr("disabled");
                        window.location.reload();
					}
                    if(result == 2){
                        alert('绑定失败');
                    }
                }
        });
	}

    $(document).on('click', '.export', function(){
        str = '';
        $.each($('.post_coding'), function(){
            str += $(this).text() + '.' + $(this).parent().find(".scan_person").text() + '.' + $(this).parent().find(".scan_time").text() + '|';
        });
        location.href="{{ route('purchaseList.export', ['str' => ''])}}/" + str;
    });

	$(document).on('click','.search',function(){
        var trackingNo = $("#trackingNo").val();
        var po_id = $("#po_id").val();
        var status = $("#status").val();
        var date_from = $("#date_from").val();
        var date_to = $("#date_to").val()
        $.ajax({
                url: "{{ route('trackingNoSearch') }}",
                data: {trackingNo: trackingNo,po_id:po_id,status:status,date_from:date_from,date_to:date_to},
                dataType: 'html',
                type: 'get',
                success: function (result) {
                   $(".scan").html(result);
                }
        });
    });

    $(document).on('click','.delete_item',function(){
        if (confirm("确认删除关联?")) {
            var id = $(this).data("id");
            $.ajax({
                    url: "{{ route('deletePostage') }}",
                    data: {id:id},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        $("#po_"+id).css("display","none");
                        $("#guanlian").html("");
                    }
            });
        }  
    });

</script>
@stop