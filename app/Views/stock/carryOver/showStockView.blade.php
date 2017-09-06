@extends('common.form')
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>时间</label>
            <input type='text' class='form-control' value="{{ $stockTime }}">
        </div>
    </div>
    <table class='table table-bordered'>
        <thead>
            <th>sku</th>
            <th>仓库</th>
            <th>库位</th>
            <th>数量</th>
            <th>金额</th>
        </thead>
        <tbody>
            @foreach($carryOvers as $key => $carryOver)
            <tr>
                <td>{{ $carryOver->stock ? $carryOver->stock->item ? $carryOver->stock->item->sku : '' : '' }}</td>
                <td>{{ $carryOver->stock ? $carryOver->stock->warehouse ? $carryOver->stock->warehouse->name : '' : '' }}</td>
                <td>{{ $carryOver->stock ? $carryOver->stock->position ? $carryOver->stock->position->name : '' : ''}}</td>
                <td>{{ $carryOver->over_quantity }}</td>
                <td>{{ $carryOver->over_amount }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <?php echo $carryOvers->render(); ?>
@stop
@section('formButton')
@stop