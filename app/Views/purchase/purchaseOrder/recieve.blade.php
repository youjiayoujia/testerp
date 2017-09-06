@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">    
        <div class="panel-body">
           <div class="form-group col-lg-2">
                <strong>收货</strong>       
                <input class="form-control" id="p_id" placeholder="采购单号" name='post_coding' value="">
                <button class="item_receive">展开单据</button>
            </div>
            <div class="form-group purchase">

            </div>	
        </div> 
    </div>  

    <div class="panel panel-default">    
        <div class="panel-body">
           <div class="form-group col-lg-2">
                <strong>入库</strong>       
                <input class="form-control" id="p_id1" placeholder="采购单号" name='post_coding' value="">
                <button class="item_inwarehouse">展开单据</button>
            </div>
            <div class="form-group purchase col-lg-2">
                
            </div>  
        </div> 
    </div> 
<input type="hidden" value="" id="ajaxp_id"> 
<input type="hidden" value="" id="ajaxp_id_forever"> 
@stop

@section('pageJs')
<script type='text/javascript'>
    $(document).ready(function(){
    	//javascript:document.getElementById("p_id").focus();
    	$(document).on('click','.item_receive', function (event) {  
                var p_id = $("#p_id").val();
                if(p_id==''){
                    p_id = $("#ajaxp_id").val();
                }
                var url = "{{ route('ajaxRecieve') }}";
                window.location.href= url+"?id="+p_id;              
    	});

        $(document).on('click','.item_inwarehouse', function (event) {
            //if(event.keyCode == '13') {
                var p_id = $("#p_id1").val();
                if(p_id==''){
                    p_id = $("#ajaxp_id").val();
                }
                var url = "{{ route('ajaxInWarehouse') }}";
                window.location.href= url+"?id="+p_id; 
                /*$.ajax({
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
                });*/
            //}
        });
    })


</script>
@stop


