@extends('common.detail')
@section('detailBody')
<div class='row'>
    <div class='form-group col-lg-2'>
        <label for='remark'>更新物流面单重新打印:</label>
    </div>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control change_package_id' placeholder='package_id'>
    </div>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control change_trackno' placeholder='trackno'>
    </div>
    <div class='form-group col-lg-2'>
        <select class='form-control logistics' name='new_logistic'>
        @foreach($logistics as $logistic)
            <option value="{{ $logistic->id }}">{{ $logistic->code }}</option>
        @endforeach
        </select>
    </div>
    <div class='form-group'>
        <button type='button' class='btn btn-info change_print'>重新打印</button>
    </div>
    <div class='row'>
        <iframe id='barcode' style='display:none'></iframe>
    </div>
</div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<script type='text/javascript'>
$(document).ready(function(){
    $('.change_print').click(function(){
        package_id = $('.change_package_id').val();
        trackno = $('.change_trackno').val();
        logistics_id = $('.logistics').val();
        if(logistics_id && (package_id || trackno)) {
            $.ajax({
                url:"{{ route('package.ajaxUpdatePackageLogistics')}}",
                data:{package_id:package_id,trackno:trackno,logistics_id:logistics_id},
                dataType:'json',
                type:'get',
                success:function(result){
                    if(result) {
                        $('#barcode').attr('src', ("{{ route('templateMsg', ['id'=>''])}}/"+result));
                        $('#barcode').load(function(){
                            $('#barcode')[0].contentWindow.focus();
                            $('#barcode')[0].contentWindow.print();
                        });
                    } else {
                        alert('未找到对应包裹');
                    }
                }
            })
        }
    });
})
</script>