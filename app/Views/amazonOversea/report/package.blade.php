@extends('common.form')
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID:</label>
            <input type='text' class='form-control modelId' value="{{ $model->id }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>发货名称:</label>
            <input type='text' class='form-control' value="{{ $model->shipment_name }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>发货地址</label>
            <input type='text' class='form-control' value={{ $model->shipping_address }}>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>plan Id:</label>
            <input type='text' class='form-control' value="{{ $model->plan_id }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>shipment Id:</label>
            <input type='text' class='form-control' value="{{ $model->shipment_id }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>reference Id:</label>
            <input type='text' class='form-control' value="{{ $model->reference_id }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>reference Id:</label>
            <input type='text' class='form-control' value="{{ $model->reference_id }}">
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-2'>
            <input type='text' class='form-control searchsku' placeholder='sku'>
        </div>
        <div class='form-group col-lg-2'>
            <button type='button' class='btn btn-info search'>确认</button>
            <button type='button' class='btn btn-warning createBox'><i class="glyphicon glyphicon-plus"></i> 新建装箱信息</button>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-8'>
            <font color='red' size='7px' class='notFindSku'></font>
        </div>
    </div>
    <div class='row box' data-flag='false'>
        
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">已扫描信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-3'>sku</td>
                    <td class='col-lg-3'>注意事项</td>
                    <td class='col-lg-2'>数量</td>
                    <td class='col-lg-2'>按钮</td>
                </thead>
                <tbody class='new'>
                
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">未扫描信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-2'>ID</td>
                    <td class='col-lg-2'>sku</td>
                    <td class='col-lg-2'>fnsku</td>
                    <td class='col-lg-2'>注意事项</td>
                    <td class='col-lg-1'>应拣数量</td>
                    <td class='col-lg-1'>实拣数量</td>
                    <td class='col-lg-2'>状态</td>
                </thead>
                <tbody class='old'>
                @foreach($forms as $key => $form)
                    <tr data-id="{{ $form->id }}" data-weight="{{ $form->item ? $form->item->weight : 0}}">
                        <td class='col-lg-2'>{{ $form->id }}</td>
                        <td class='col-lg-2 sku' data-id="{{ $form->item_id}}">{{ $form->item ? $form->item->sku : '' }}</td>
                        <td class='col-lg-2'>{{ $form->fnsku }}</td>
                        <td class='col-lg-2 remark'>{{ $form->item ? $form->item->remark : '' }}</td>
                        <td class='col-lg-1 report_quantity'>{{ $form->report_quantity}}</td>
                        <td class='col-lg-1 inbox_quantity'>{{ $form->inbox_quantity }}</td>
                        <td class='col-lg-2 status'>
                        @if($form->inbox_quantity != $form->report_quantity)
                        <font color='red'>包装中</font></td>
                        @else
                        <font>数量已匹配</font>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="box_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">箱子信息</div>
                    <div class="panel-body">
                        <div class='form-group col-lg-6'>
                            <label>体积(cm3):</label>
                            <input type='text' class='form-control box_volumn' name='volumn' placeholder='a*v*c'>
                        </div>
                        <div class='form-group col-lg-6'>
                            <label>重量(kg):</label>
                            <input type='text' class='form-control box_actWeight' name='weight'>
                        </div>
                        <div class='form-group col-lg-6'>
                            <a href="javascript:" class='btn btn-info box_sub'>提交</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <iframe id='barcode' style='display:none'></iframe>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">包装完成</button>
    <button type="reset" class="btn btn-default">取消</button>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).on('keypress', function (event) {
    if(event.keyCode == '13') {
        $('.search').click(); 
        return false;
    }
});

$(document).ready(function(){
    $(document).on('click', '.box_sub', function(){
        volumn = $('.box_volumn').val();
        weight = $('.box_actWeight').val();
        boxId = $('.boxId').val();
        $.get(
            "{{ route('box.boxSub')}}",
            {volumn:volumn,weight:weight,boxId:boxId},
            function(result){
                $('.box_volumn').val('');
                $('.box_actWeight').val('');
            });
        $('.box_info').click();
    });

    $(document).on('click', '.cz', function(){
        block = $(this).parent().parent();
        id = block.data('id');
        boxId = $('.boxId').val();
        itemId = block.data('itemid');
        sku = block.find('.sku').text();
        weight = block.data('weight');
        $.get("{{route('report.ctrlZ')}}",
          {id:id, boxId:boxId, itemId:itemId},
          function(result){
            if(result) {
                $('.box_quantity').val(parseInt($('.box_quantity').val()) - 1);
                $('.box_weight').val(parseFloat($('.box_weight').val()) - parseFloat(weight));
                $.each($('.old tr'), function(){
                    tmp = $(this);
                    if(tmp.find('.sku').text() == sku) {
                        tmp.find('.inbox_quantity').text(parseInt(tmp.find('.inbox_quantity').text()) - 1);
                    }
                });
            }
        });
        block.remove();
    });

    $(document).on('click', '.createBox', function(){
        id = $('.modelId').val();
        if(!$('.boxId').val()) {
            $.get("{{route('report.createBox')}}",
                {id:id},
                function(result){
                    if(result) {
                        $('.box').html(result);
                    }
                    $('.box').attr('data-flag', 'true');
                },'html');
        } else {
            if(confirm('确认新建箱子信息')) {
                $.get("{{route('report.createBox')}}",
                    {id:id},
                    function(result){
                        if(result) {
                            $('.box').html(result);
                        }
                    },'html');
                $('.box').attr('data-flag', '1');
                $('.new').html('');
            }
        }
    });

    $(document).on('click', '.search', function(){
        val = $('.searchsku').val();
        $('.notFindSku').text('');
        extern_flag = 0;
        out_js = 0;
        $('.searchsku').val('');
        $('.searchsku').focus();
        if(val) {
            if(!$('.boxId').val()) {
                alert('请新建装箱信息');
                return false;
            }
            $.each($('.old tr'), function(){
                tmp = $(this);
                inbox_quantity = parseInt(tmp.find('.inbox_quantity').text());
                report_quantity = parseInt(tmp.find('.report_quantity').text());
                if(tmp.find('.sku').text() == val && report_quantity >  inbox_quantity) {
                    out_js = 1;
                    if((parseFloat($('.box_weight').val()) + parseFloat(tmp.data('weight'))) > 20) {
                        $('.notFindSku').text('重量超过20kg,请重换箱子');
                        return false;
                    }
                    extern_flag = 1;
                    tmp.find('.inbox_quantity').text(parseInt(tmp.find('.inbox_quantity').text()) + 1);
                    if(parseInt(tmp.find('.inbox_quantity').text()) == report_quantity) {
                        tmp.find('.status').text('数量已匹配')
                    }
                    id = tmp.data('id');
                    sku = tmp.find('.sku').text();
                    item_id = tmp.find('.sku').data('id');
                    box_id = $('.boxId').val();
                    $.ajax({
                        url:"{{ route('report.reportFormUpdate')}}",
                        data:{id:id, itemId:item_id,boxId:box_id},
                        dataType:'json',
                        type:'get',
                        success:function(result) {
                            if(!result) {
                                return false;
                            }
                            $('.box_weight').val(parseFloat($('.box_weight').val())+parseFloat(result));
                            $('.box_quantity').val(parseInt($(".box_quantity").val()) + 1);
                        }
                    });
                    arr = new Array();
                    i=0;
                    str = '';
                    $.each($('.old tr'), function(){
                        if($(this).data('id') == id) {
                            arr[i] = "<td class='sku'>"+$(this).find('.sku').text()+"</td><td class='remark'>"+$(this).find('.remark').text()+"</td><td class='quantity'>1</td>";
                            i++;
                        }
                    });
                    len = arr.length;
                    for(j=0;j<len;j++) {
                        str = "<tr data-id='" + tmp.data('id') + "' data-itemId='"+tmp.find('.sku').data('id')+"' data-boxid='"+$('.modelId').val()+"' data-weight='"+tmp.data('weight')+"'>" + arr[j] + "<td class='col-lg-1'><button type='button' class='cz btn btn-info'>撤销</button></td></tr>";
                    }
                    $('.new').append(str);
                    return false;
                }
            });
        }
        if(out_js) {
            return false;
        }
        if(!extern_flag) {
            $('.notFindSku').text('sku不存在或者该对应的拣货单上sku已满');
        }
    });
});
</script>
@stop