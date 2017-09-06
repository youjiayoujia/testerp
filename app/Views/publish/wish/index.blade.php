<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-06-08
 * Time: 14:56
 */
?>
@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>主图</th>
    <th>账号</th>
{{--
    <th>产品ID</th>
--}}
    <th>SKU</th>
    <th>标题</th>
    <th>Tags</th>
{{--
    <th class="sort" data-field="number_sold">售出量</th>
--}}
    <th>创建人员</th>
    <th>刊登时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $wishProduct)
        <tr>
            <td>{{  $wishProduct->id }}</td>
            <td>
                <?php
                if (!empty($wishProduct->extra_images)) {
                    $picArr = explode('|', $wishProduct->extra_images);
                    $onePic = !empty($picArr) ? $picArr[0] : "";
                } else {
                    $onePic = '';
                }
                ?>
                @if(!empty($onePic))
                    <a target="_blank" href="{{$onePic}}"><img style="width:50px;height:50px;" src="{{$onePic}}"></a>
                @endif
            </td>
            <td>
                @if(isset($wishProduct->channelAccount->account))
                {{  $wishProduct->channelAccount->alias }}
                @endif
            </td>
{{--
            <td>{{  $wishProduct->productID }}</td>
--}}
            <td><?php
                foreach ($wishProduct->details as $detail) {
                    echo $detail->sku . '<br/>';
                }
                ?></td>
            <td>{{  $wishProduct->product_name }}</td>
            <td>{{$wishProduct->tags}}</td>
{{--
            <td >{{  $wishProduct->number_sold }}</td>
--}}
            <td>
                @if(isset( $wishProduct->operator->name))
                    {{ $wishProduct->operator->name }}
                @endif
            </td>
            <td>{{  $wishProduct->publishedTime }}</td>

            <td>
                {{--  <a href="{{ route('wish.show', ['id'=>$wishProduct->id]) }}" class="btn btn-info btn-xs">
                      <span class="glyphicon glyphicon-eye-open"></span> 查看
                  </a>--}}
                @if($wishProduct->product_type_status==2)
                    <a href="{{ route('wish.editOnlineProduct', ['id'=>$wishProduct->id]) }}"
                       class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 编辑在线信息
                    </a>
                        <a onclick="operator('<?php echo $wishProduct->id;  ?>' ,'disable',this)" class="btn btn-danger btn-xs  <?php   if(!$wishProduct->status==1){echo "hidden"; }      ?>">
                            <span class="glyphicon glyphicon-pencil "></span> 下架
                        </a>

                        <a onclick="operator('<?php echo $wishProduct->id;  ?>' ,'enable',this)"  class="btn btn-success btn-xs <?php   if($wishProduct->status==1){echo "hidden"; }      ?>">
                            <span class="glyphicon glyphicon-pencil  "></span> 上架
                        </a>

                @else
                    <a href="{{ route('wish.edit', ['id'=>$wishProduct->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 编辑草稿信息
                    </a>
                @endif

                {{-- <a href="{{ route('wish.editOnlineProduct', ['id'=>$wishProduct->id]) }}" class="btn btn-warning btn-xs">
                     <span class="glyphicon glyphicon-pencil"></span> 编辑在线广告
                 </a>--}}
                {{-- <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                    data-id="{{ $wishProduct->id }}"
                    data-url="{{ route('wish.destroy', ['id' => $wishProduct->id]) }}">
                     <span class="glyphicon glyphicon-trash"></span> 删除
                 </a>--}}
            </td>
        </tr>
    @endforeach
@stop
@section('childJs')
<script type="text/javascript">

    function operator(id,type,e){
        var mark = e;

        $.ajax({
            url : "{{ route('wish.ajaxOperateOnlineProduct') }}",
            data : {id : id,type:type},
            dataType : 'json',
            type : 'get',
            success : function(result) {
                if(result.status==1){
                    if(type=='disable'){
                        $(e).next().removeClass('hidden');
                        $(e).addClass('hidden')
                    }
                    if(type=='enable'){
                        $(e).prev().removeClass('hidden');
                        $(e).addClass('hidden')
                    }
                    alert(result.info);
                }else{
                    alert(result.info);
                }
            }
        });
    }

</script>
@stop