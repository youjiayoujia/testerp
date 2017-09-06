@extends('common.form')
@section('formAction')@stop
@section('formBody')
<div class='row'>
    <div class="col-lg-12">
        <table class="table table-bordered table-striped table-hover sortable">
            <thead>
            <tr>
                <th><input type='checkbox' name='select_all[]' class='select_all'></th>
                <th>Package ID</th>
                <th>订单号</th>
                <th>Shop</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody>
            @foreach($packages as $model)
            <tr>
                <td><input type='checkbox' name='select[]' class='select_single'></td>
                <td>{{$model->id}}</td>
                <td>{{$model->order ? $model->order->id : ''}}</td>
                <td>{{$model->channel->name}}</td>
                <td>{{$model->status_name}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<?php echo $packages->render(); ?>
@stop
@section('formButton')
<button href="javascript:" class="btn btn-success quantity_process">发货</button>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.select_all').click(function(){
        if($(this).prop('checked') == true) {
            $.each($('.select_single'), function(){
                $(this).prop('checked', true);
            })
        } else {
            $.each($('.select_single'), function(){
                $(this).prop('checked', false);
            })
        }
    });

    $('.quantity_process').click(function(){
        buf = new Array();
        i = 0;
        $.each($('.select_single:checked'), function(){
            buf[i] = $(this).parent().next().text();
            i++;
        });
        $.ajax({
            url:"{{ route('package.ajaxQuantityProcess')}}",
            data:{buf:buf},
            dataType:'json',
            type:'get',
            success:function(result) {
                if(result) {
                    location.reload();
                }
            }
        })
    });
});
</script>
@stop
