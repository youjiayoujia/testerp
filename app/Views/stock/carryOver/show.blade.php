@extends('common.detail')
@section('detailBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>月结时间</label>
            <input type='text' class='form-control' value="{{ $model->date }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>结转时间</label>
            <input type='text' class='form-control' value="{{ $model->created_at }}">
        </div>
    </div>
    <table class='table table-bordered'>
        <thead>
            <th>sku</th>
            <th>仓库</th>
            <th>库位</th>
            <th>期初数量</th>
            <th>期初金额</th>
            <th>期末数量</th>
            <th>期末金额</th>
        </thead>
        <tbody>
            @foreach($forms as $form)
            <tr>
                <td>{{ $form->stock ? $form->stock->item ? $form->stock->item->sku : '' : '' }}</td>
                <td>{{ $form->stock ? $form->stock->warehouse ? $form->stock->warehouse->name : '' : '' }}</td>
                <td>{{ $form->stock ? $form->stock->position ? $form->stock->position->name : '' : ''}}</td>
                <td>{{ $form->begin_quantity }}</td>
                <td>{{ $form->begin_amount }}</td>
                <td>{{ $form->over_quantity }}</td>
                <td>{{ $form->over_amount }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <?php echo $forms->render(); ?>
@stop