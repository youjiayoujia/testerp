<div class="form-group col-lg-2">
    <label>箱号</label>
    <input type='text' class="form-control" value="{{ $model->boxNum }}">
    <input type='hidden' class='boxId' value="{{ $model->id }}">
</div>
<div class="form-group col-lg-2">
    <label>sku数量</label>
    <input type='text' class="form-control box_quantity" value="0">
</div>
<div class="form-group col-lg-2">
    <label>重量</label>
    <input type='text' class="form-control box_weight" value=0>
</div>
<div class="btn-group">
    <button type='button' class="btn btn-success btn-lg box_info"
            data-toggle="modal"
            data-target="#box_info">
        装箱结束
    </button>
</div>