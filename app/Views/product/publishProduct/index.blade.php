@extends('common.table')
@section('tableToolButtons') 
<div class="btn-group" role="group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="glyphicon glyphicon-filter"></i> 发布状态查询
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a href="">待发布</a></li>
        <li><a href="">发布中</a></li>
        <li><a href="">发布成功</a></li>
        <li><a href="">发布失败</a></li>
    </ul>
</div> 
<div class="btn-group">
    <a class="btn btn-primary" href="">
        暂不发布
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-primary" href="">
        批量发布
    </a>
</div>

@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>ID</th>
    <th class="sort" data-field="model">MODEL</th>
    <th>分类</th>
    <th>图片</th>
    <th>选中shop</th>
    <th class="sort" data-field="c_name">中文名称</th>
    <th>材质</th>
    <th>线上供货商</th>
    <th>线上供货商链接</th>
    <th>线下供货商</th>
    <th>图片URL</th>
    <th>拿货价</th>
    <th>参考现货数量</th>
    <th>选款人ID</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $product)
        <tr>
            <td>
                @if($product->status)
                <input type="checkbox" name="tribute_id"  value="{{$product->id}}" isexamine="1" >
                @else
                <input type="checkbox" name="tribute_id"  value="{{$product->id}}" isexamine="0" >
                @endif
            </td>
            <td>{{ $product->id }}</td>
            <td>{{ $product->model }}</td>
            <td>{{ $product->catalog->name }}</td>
            <td>@if($product->default_image>0)<a href="{{ asset($product->image->path) }}/{{$product->image->name}}"><img src="{{ asset($product->image->path) }}/{{$product->image->name}}" width="100px" ></a>@else无图片@endif</td>
            <td><?php if($product->amazonProduct)echo "amazon,";if($product->ebayProduct)echo "ebay,";if($product->aliexpressProduct)echo "aliexpress,";if($product->b2cProduct)echo "B2C,"; ?></td>
            <td>{{ $product->c_name }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->supplier->name }}</td>
            <td><a href="{{$product->purchase_url}}" >链接</td>
            <td>线下供货商</td>
            <td>无</td>
            <td>{{ $product->purchase_price }}</td>
            <td>待确定</td>
            <td>{{ $product->upload_user }}</td>
            <td>{{ $product->created_at }}</td>
            <td>
                <a href="" class="btn btn-info btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 发布
                </a> 
                <a href="" class="btn btn-info btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 暂不发布
                </a> 
            </td>
        </tr>
    @endforeach
@stop
@section('childJs')
    <script type="text/javascript">    
        //批量审核
        $('.shenhe').click(function () {
            if (confirm("确认"+$(this).data('name')+"?")) {
                var url = "{{route('productExamineAll')}}";
                var checkbox = document.getElementsByName("tribute_id");
                var product_ids = "";
                var examine_status = $(this).data('status');
                
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    product_ids += checkbox[i].value+",";
                }
                product_ids = product_ids.substr(0,(product_ids.length)-1);
                $.ajax({
                    url:url,
                    data:{product_ids:product_ids,examine_status:examine_status},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        window.location.reload();             
                    }                  
                })     
            }
                 
        });

        //全选
        function quanxuan()
        {
          var collid = document.getElementById("checkall");
          var coll = document.getElementsByName("tribute_id");
          if (collid.checked){
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = true;
          }else{
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = false;
          }
        }

        
    </script>
@stop