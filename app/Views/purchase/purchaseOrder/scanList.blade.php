<table class="table table-bordered table-striped table-hover sortable">
<thead>
<tr>
    <th>ID</th>
    <th>运单号</th>
    <th>运单状态</th>
    <th>关联采购单</th>
    <th>扫描人</th>
    <th>扫描时间</th>
    <th>操作</th>
</tr>
</thead>
<tbody>
@foreach($result as $_result)
    <tr>
        <td>{{$_result->id}}</td>
        <td class='post_coding'>{{$_result->post_coding}}</td>
        <td><?php if($_result->purchase_order_id==''){echo "未关联";}else{echo "已关联";} ?></td>
        <td><a target="_blank" href="{{ route('purchaseOrder.show', ['id'=>$_result->purchase_order_id]) }}">{{$_result->purchase_order_id}}</a></td>
        <td class='scan_person'></td>
        <td class='scan_time'>{{$_result->updated_at}}</td>
        <td>
            @if($_result->purchase_order_id!='')
            <a href="javascript:" class="btn btn-danger btn-xs delete_item" data-id="{{$_result->id}}">
                <span class="glyphicon glyphicon-trash"></span> 删除关联
            </a>
            @else
            
                <input type="button" value="绑定" onClick="binding()">
            
            @endif
        </td>
    </tr>
@endforeach
 </tbody>
</table>