@extends('common.form')
@section('formAction') @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID:</label>
            <input type='text' class='form-control modelId' value="{{ $model->id }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>调出仓库</label>
            <input type='text' class='form-control' value="{{ $model->outWarehouse ? $model->outWarehouse->name : '' }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>调入仓库</label>
            <input type='text' class='form-control' value="{{ $model->inWarehouse ? $model->inWarehouse->name : '' }}">
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-2'>
            <input type='text' class='form-control searchsku' placeholder='sku'>
        </div>
        <div class='form-group col-lg-8'>
            <div class='col-lg-2'>
                <button type='button' class='btn btn-info search'>确认</button>
                <button type='button' class='btn btn-warning createbox'><i class="glyphicon glyphicon-plus"></i> 新建装箱信息</button>
            </div>
            <div class='col-lg-2'>
                <input type='text' class='form-control boxnum' placeholder='箱号'>
            </div>
            <div class='buf'>

            </div>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-8'>
            <font color='red' size='7px' class='notFindSku'></font>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">已扫描信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-3'>sku</td>
                    <td class='col-lg-2'>箱号</td>
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
                    <td class='col-lg-1'>应拣数量</td>
                    <td class='col-lg-1'>实拣数量</td>
                    <td class='col-lg-2'>状态</td>
                </thead>
                <tbody class='old'>
                @foreach($forms as $key => $form)
                    <tr data-id="{{ $form->id }}" data-weight="{{ $form->item ? $form->item->weight : 0}}">
                        <td class='col-lg-2'>{{ $form->id }}</td>
                        <td class='col-lg-2 sku' data-id="{{ $form->item_id}}">{{ $form->item ? $form->item->sku : '' }}</td>
                        <td class='col-lg-1 quantity'>{{ $form->quantity}}</td>
                        <td class='col-lg-1 inboxed_quantity'>{{ $form->inboxed_quantity }}</td>
                        <td class='col-lg-2 status'>
                        @if($form->inboxed_quantity != $form->quantity)
                        <font color='red'>待装箱</font></td>
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
    <button type='button' class="btn btn-success submit">结束本次装箱</button>
    <button type='button' href="{{ route('overseaAllotment.inboxOver')}}"class="btn btn-success inboxover">装箱完成并出库</button>
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
    $(document).on('click', '.submit', function(){
        modelid = $('.modelId').val();
        str = '|';
        $.each($('.new tr'), function(){
            boxnum = $(this).find('.new_boxnum').text();
            str += boxnum + '.' + $(this).find('.new_sku').data('id') + '.' + $(this).find('.new_sku').text() + '.'+ $(this).find('.new_quantity').text() + '|';
        });
        location.href="{{ route('overseaAllotment.inboxStore', ['str' => ''])}}/" + str + '/' + modelid;
    })

    $(document).on('click', '.inboxover', function(){
        if(confirm('确定结束装箱并出库?')) {
            modelid = $('.modelId').val();
            str = '|';
            $.each($('.new tr'), function(){
                boxnum = $(this).find('.new_boxnum').text();
                str += boxnum + '.' + $(this).find('.new_sku').data('id') + '.' + $(this).find('.new_sku').text() + '.'+ $(this).find('.new_quantity').text() + '|';
            });
            location.href="{{ route('overseaAllotment.inboxOver', ['str' => ''])}}/" + str + '/' + modelid;
        }
    })

    $(document).on('click', '.search', function(){
        $('.notFindSku').text('');
        $('.buf').html('');
        searchsku = $('.searchsku').val();
        boxnum = $('.boxnum').val();
        itemid = 0;
        if(!boxnum) {
            alert('请新建装箱信息');
            $('.searchsku').val('');
            return false;
        }
        flag = 0;
        $.each($('.old tr'), function(){
            tmp = $(this);
            inboxed_quantity = parseInt(tmp.find('.inboxed_quantity').text());
            quantity = parseInt(tmp.find('.quantity').text());
            if(tmp.find('.sku').text() == searchsku && quantity >  inboxed_quantity) {
                flag = 1;
                itemid = tmp.find('.sku').data('id');
                tmp.find('.inboxed_quantity').text(parseInt(tmp.find('.inboxed_quantity').text()) + 1);
                if(parseInt(tmp.find('.inboxed_quantity').text()) == quantity) {
                    tmp.find('.status').text('数量已匹配')
                }
                return false;
            }
        });
        if(!flag) {
            $('.notFindSku').text('sku不存在或者该对应的拣货单上sku已满');
            $('.searchsku').val('');
            $('.searchsku').focus();
            return false;
        }
        if(searchsku && boxnum) {
            flag = 0;
            $.each($('.new tr'),function(){
                if(searchsku == $(this).find('.new_sku').text() && boxnum == $(this).find('.new_boxnum').text()) {
                    flag = 1;
                    $(this).find('.new_quantity').text((parseInt($(this).find('.new_quantity').text()) + 1));
                    return false;
                }
            })
            if(flag == 1) {
                $('.searchsku').val('');
                $('.searchsku').focus();
                return false;
            }
            str = "<tr><td class='new_sku' data-id='"+itemid+"'>"+searchsku+"</td><td class='new_boxnum'>"+boxnum+"</td><td class='new_quantity'>1</td><td><button type='button' class='new_del_item btn btn-info'>撤销</button>";
            $('.new').append(str);
            $('.searchsku').val('');
            $('.searchsku').focus();
            return false;
        } else {
            return false;
        }
    });

    $(document).on('click', '.new_del_item', function(){
        block = $(this).parent().parent();
        new_quantity = block.find('.new_quantity').text();
        searchsku = block.find('.new_sku').text();
        $.each($('.old tr'), function(){
            tmp = $(this);
            inboxed_quantity = parseInt(tmp.find('.inboxed_quantity').text());
            quantity = parseInt(tmp.find('.quantity').text());
            if(tmp.find('.sku').text() == searchsku && (parseInt(inboxed_quantity) >= parseInt(new_quantity))) {
                tmp.find('.inboxed_quantity').text(parseInt(inboxed_quantity) - parseInt(new_quantity));
                tmp.find('.status').html("<font color='red'>待装箱</font>");
                return false;
            }
        });
        $(this).parent().parent().remove();
    });

    $(document).on('click', '.createbox', function(){
        id = $('.modelId').val();
        boxnum = $('.boxnum').val();
        if(!boxnum) {
            $.get("{{route('overseaBox.createbox')}}",
                {id:id},
                function(result){
                    if(result) {
                        $('.boxnum').val(result);
                        $('.buf').html("<font color='green' size='5px'>箱子创建成功</font>");
                    } else {
                        alert('箱子创建失败');
                    }
                });
        } else {
            if(confirm('确认新建箱子信息')) {
                $.get("{{route('overseaBox.createbox')}}",
                    {id:id},
                    function(result){
                        if(result) {
                            $('.boxnum').val(result);
                            $('.buf').html("<font color='green' size='5px'>箱子创建成功</font>");
                        } else {
                            alert('箱子创建失败');
                        }
                    });
            }
        }
    });
});
</script>
@stop