@extends('common.form')
@section('formAction')@stop
@section('formBody')
<div class='form-group'>
    <div class='input-group col-lg-2'>
        <input type='text' class='form-control sku' placeholder='sku'>
        <div class='input-group-btn'>
            <button type='button' class='btn btn-info searchSku'>sku查询</button>
        </div>
    </div>
    </div>
    <div class='form-group'>

    <div class='input-group col-lg-2'>
        <input type='text' class='form-control position' placeholder='库位'>
        <div class='input-group-btn'>
            <button type='button' class='btn btn-info searchPosition'>库位查询</button>
        </div>
    </div>
    </div>
    <div class='buf'>

    </div>
@stop
@section('formButton')@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.change_position', function(){
        block = $(this).parent().parent();
        block.find('td:eq(1)').html("<select class='select_position form-control'></select>");
        $('.select_position').select2({
            ajax: {
                url: "{{ route('stock.ajaxGetByPosition') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    position: params.term, // search term
                    page: params.page,
                    warehouse_id: block.find('td:eq(0)').data('warehouseid'),
                    sku: block.find('td:eq(2)').data('itemid'),
                  };
                },
                results: function(data, page) {
                    if((data.results).length > 0) {
                        var more = (page * 20)<data.total;
                        return {results:data.results,more:more};
                    } else {
                        return {results:data.results};
                    }
                }
            },
        });
    });

    $('.searchSku').click(function(){
        sku = $('.sku').val();
        if(sku) {
            $.get(
                "{{ route('stock.getSingleSku')}}",
                {sku:sku},
                function(result){
                    if(result == 'false') {
                        alert('sku不存在');
                        $('.sku').val('');
                        $('.buf').html('');
                        exit;
                    }
                    $('.buf').html('');
                    $('.buf').html(result);
                }
            );
            $('.sku').val('');
            $('.position').val('');
        }
    });

    $('.searchPosition').click(function(){
        position = $('.position').val();
        if(position) {
            $.get(
                "{{ route('stock.getSinglePosition')}}",
                {position:position},
                function(result){
                    if(result == 'false') {
                        alert('库位不存在');
                        $('.positon').val('');
                        $('.buf').html('');
                        exit;
                    }
                    $('.buf').html('');
                    $('.buf').html(result);
                }
            );
            $('.sku').val('');
            $('.position').val('');
        }
    });

    $(document).on('keypress', function(event) {
        if(event.keyCode == '13') {
            if($('.position').val()) {
                $('.position').next().find(':button').click();
            } else {
                $('.sku').next().find(':button').click();
            }
        }
    });

    $(document).on('change', '.select_position', function(){
        position = $(this).val();
        id = $(this).parent().parent().find('td:eq(0)').data('id');
        $.get(
            "{{ route('stock.changePosition')}}",
            {id:id,position:position},
            function(result){
                if(!result) {
                    alert('库位修改出错,库位或sku有问题');
                }
            }
        )
    });
});
</script>
@stop