@for($i = 0; $i < $quantity; $i++)
<table class="table table-bordered table-striped table-hover sortable">
    <tbody>
        @foreach($model->items as $key => $single)
            @for($j=0; $j<$single->quantity; $j++)
            <tr>
                @if($key == 0 && $j == 0)
                <td rowspan="{{ $model->items->sum('quantity') }}">包裹{{$i+1}}</td>
                @endif
                <td class='item_id' data-itemid="{{ $single->item_id }}"><font>{{ $single->item ? $single->item->sku : '包裹item有问题'}}</font></td>
                <td><input type='radio' name="sku{{$key}}{{$j}}" {{ $i == 0 ? 'checked' : ''}}></td>
            </tr>
            @endfor
        @endforeach
    </tbody>
</table>
@endfor
<button type='button' class='btn btn-primary split_button'>确认拆分</button>