@extends('common.detail')
@section('detailBody')
<div class="row">
    <div class="form-group col-sm-3">
        <label for="盘点表id" class='control-label'>盘点表id</label>
        <input type='text' class="form-control " placeholder="盘点表id" value="{{ $model->taking_id }}" readonly>
    </div>
    <div class="form-group col-sm-3">
        <label for="调整人" class='control-label'>调整人</label>
        <input type='text' class="form-control " placeholder="调整人" value="{{ $model->adjustmentByName ? $model->adjustmentByName->name : '' }}" readonly>
    </div>
    <div class="form-group col-sm-3">
        <label for="调整时间" class='control-label'>调整时间</label>
        <input type='text' class="form-control " placeholder="调整时间" value="{{ $model->adjustment_time }}" readonly>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">盘点表信息</div>
    <div class="panel-body">
            <div class='row'>
                <div class="form-group col-lg-1">
                    <label for="ID" class='control-label'>ID</label>
                </div>
                <div class="form-group col-lg-2">
                    <label for="sku" class='control-label'>sku</label>
                </div>
                <div class="form-group col-lg-2">
                    <label for="仓库" class='control-label'>仓库</label>
                </div>
                <div class="form-group col-lg-2">
                    <label for="库位" class='control-label'>库位</label>
                </div>
                <div class="form-group col-lg-1">
                    <label for="总数量" class='control-label'>总数量</label>
                </div>
                <div class="form-group col-lg-2">
                    <label for="实盘数量" class='control-label'>实盘数量</label>
                </div>
                <div class="form-group col-lg-1">
                    <label for="状态" class='control-label'>状态</label>
                </div>
                <div class="form-group col-lg-1">
                    <label for="调整数量" class='control-label'>调整数量</label>
                </div>
            </div>
        @foreach($stockTakingForms as $stockTakingForm)
            <div class='row'>
                <div class="form-group col-lg-1">
                    <input type='text' name='arr[id][]' class='form-control' value="{{ $stockTakingForm->id }}" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <input type='text' name='arr[sku][]' class='form-control' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->item ? $stockTakingForm->stock->item->sku : '' : '' }}" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <input type='text' name='arr[warehouse_id][]' class='form-control' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->warehouse ? $stockTakingForm->stock->warehouse->name : '' : '' }}" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <input type='text' name='arr[warehouse_position_id][]' class='form-control' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->position ? $stockTakingForm->stock->position->name : '' : '' }}" readonly>
                </div>
                <div class="form-group col-lg-1">
                    <input type='text' name='arr[all_quantity][]' class='form-control all_quantity' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->all_quantity : '' }}" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <input type='text' name='arr[quantity][]' class='form-control quantity' placeholder='实盘数量' value="{{$stockTakingForm->quantity}}" readonly>
                </div>
                <div class="form-group col-lg-1">
                    <input type='text' name='arr[stock_taking_status][]' class='form-control stock_taking_status' placeholder='状态' value="{{$stockTakingForm->stock_taking_status == 'more' ? '盘盈' : '盘亏'}}" readonly>
                </div>
                <div class="form-group col-lg-1">
                    <input type='text' name='arr[adjust_quantity][]' class='form-control adjust_quantity' placeholder='调整数量' value="{{ abs($stockTakingForm->quantity - ($stockTakingForm->stock ? $stockTakingForm->stock->all_quantity : '')) }}" readonly>
                </div>
            </div>
            <?php echo $stockTakingForms->render(); ?>
        @endforeach
    </div>
</div>
@stop
