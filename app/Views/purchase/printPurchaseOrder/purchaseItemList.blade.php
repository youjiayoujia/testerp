<table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
        	<th>model</th>
            <th>ID</th>
            <th>size*采购数量</th>
            <th>单价</th>
            <th>供应商</th>
            <th>采购地址+电话</th>
            <th>总价</th>
            <th>图片</th>
        </tr>
    </thead>
    @foreach($data as $key=>$vo)      
             @foreach($vo['item'] as $k=>$v)
             <tbody> 
             <tr>
             <td rowspan="{{$k}}">@if($k==0){{$vo['spu_colour']}}@endif</td>
             	<td>{{$v->id}}</td>
                <td>{{$v['size']}}*{{$v->purchase_num}}</td>
                <td>￥{{$v->item->purchase_price}}</td>
                <td>{{$v->supplier->name}}</td>
                <td>
                @if($v->supplier->type == 1)
                {{$v->supplier->url}}+{{$v->supplier->telephone}}
                @else
                {{$v->supplier->province}}{{$v->supplier->city}}{{$v->supplier->address}}+{{$v->supplier->telephone}}
                @endif
                </td>
                <td>￥{{$v->item->purchase_price * $v->purchase_num}}</td>
               <td rowspan="{{$k}}">@if($k==0)<img src="/{{$vo['img']}}" height="50px"/>@endif</td>
                </tr>
            </tbody>
             @endforeach  
    @endforeach
</table> 

