@extends('common.detail')
@section('detailTitle')@parent(<font color='red'>填入包裹ID或者追踪号</font>)@stop
@section('detailBody')
<div class='row'>
    <div class='form-group col-lg-1'>
        <label for='remark'>原面单重新打印:</label>
    </div>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control re_package_id' placeholder='package_id'>
    </div>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control re_trackno' placeholder='trackno'>
    </div>
    <div class='form-group'>
        <button type='button' class='btn btn-info re_print'>重新打印</button>
    </div>
    <div class='row'>
        <iframe id='barcode' style='display:none'></iframe>
    </div>
</div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<script type='text/javascript'>
$(document).ready(function(){
    $('.re_print').click(function(){
        package_id = $('.re_package_id').val();
        if(package_id) {
            $('#barcode').attr('src', ("{{ route('templateMsg', ['id'=>''])}}/"+package_id));
            $('#barcode').load(function(){
                $('#barcode')[0].contentWindow.focus();
                $('#barcode')[0].contentWindow.print();
            });
            return false;
        }
        trackno = $('.re_trackno').val();
        if(trackno) {
            $.ajax({
                url:"{{ route('package.ajaxReturnPackageId') }}",
                data:{trackno:trackno},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result == false) {
                        alert('对应的物流追踪号无法对应包裹');
                        return false;
                    } else {
                        $('#barcode').attr('src', ("{{ route('templateMsg', ['id'=>''])}}/"+result));
                        $('#barcode').load(function(){
                            $('#barcode')[0].contentWindow.focus();
                            $('#barcode')[0].contentWindow.print();
                        });
                    }
                }
            })
        }

    })
})
</script>