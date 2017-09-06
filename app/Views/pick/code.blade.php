@extends('layouts.default')
@section('body')
@foreach($model->pickListItem as $key => $single)
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>sku</label>
            <input type='text' class='form-control' value={{ $single->items->sku }}>
            <img src="{{ route('barcodeGen', ['content' => $single->items->sku])}}">
        </div>
    </div>
@endforeach
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.print', function () {
        id = $(this).data('id');
        src = "{{ route('pickList.print', ['id'=>'']) }}/" + id;
        $('#iframe_print').attr('src', src);
        $('#iframe_print').load(function () {
            $('#iframe_print')[0].contentWindow.focus();
            $('#iframe_print')[0].contentWindow.print();
        });
    });
})
</script>