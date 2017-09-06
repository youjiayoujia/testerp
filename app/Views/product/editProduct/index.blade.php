@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-filter"></i> 查询编辑状态
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ DataList::filtersEncode(['edit_status','=','picked']) }}">被选中</a></li>
                <li><a href="{{ DataList::filtersEncode(['edit_status','=','data_edited']) }}">资料已编辑</a></li>
                <li><a href="{{ DataList::filtersEncode(['edit_status','=','image_edited']) }}">图片已编辑</a></li>
                <li><a href="{{ DataList::filtersEncode(['edit_status','=','image_unedited']) }}">图片不编辑</a></li>
            </ul>
    </div>  
    <div class="btn-group" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-filter"></i> 查询审核状态
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ DataList::filtersEncode(['examine_status','=','pass']) }}">通过</a></li>
                <li><a href="{{ DataList::filtersEncode(['examine_status','=','notpass']) }}">未通过</a></li>
                <li><a href="{{ DataList::filtersEncode(['examine_status','=','']) }}">未审核</a></li>
                <li><a href="{{ DataList::filtersEncode(['examine_status','=','revocation']) }}">撤销</a></li>
            </ul>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    
    <th>ID</th>
    <th class="sort" data-field="model">MODEL</th>
    <th>分类</th>
    <th>图片</th>
    <th>选中shop</th>
    <th class="sort" data-field="c_name">中文名称</th>
    <th>材质</th>
    <th>线上供货商</th>
    <th>线上供货商地址</th>
    <th>拿货价</th>
    <th>选款人ID</th>
    <th>编辑状态</th>
    <th>审核状态</th>
    <th>审核不通过原因</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->model }}</td>
            <td>{{ $product->catalog->name }}</td>
            <td>@if($product->default_image>0)<a href="{{ asset($product->image->path) }}/{{$product->image->name}}"><img src="{{ asset($product->image->path) }}/{{$product->image->name}}" width="100px" ></a>@else无图片@endif</td>
            <td><?php if($product->amazonProduct)echo "amazon,";if($product->ebayProduct)echo "ebay,";if($product->aliexpressProduct)echo "aliexpress,";if($product->b2cProduct)echo "B2C,"; ?></td>
            <td>{{ $product->c_name }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->supplier->name }}</td>
            <td>{{ $product->supplier_info }}</td>
            <td>{{ $product->purchase_price }}</td>
            <td>{{ $product->upload_user }}</td>
            <?php switch ($product->edit_status) {
                case '':
                    ?>
                    <td>新品上传</td>
                    <?php
                    break;

                case 'canceled':
                    ?>
                    <td>取消</td>
                    <?php
                    break;

                case 'picked':
                    ?>
                    <td>选中</td>
                    <?php
                    break;

                case 'data_edited':
                    ?>
                    <td>资料已编辑</td>
                    <?php
                    break;

                case 'image_edited':
                    ?>
                    <td>图片已编辑</td>
                    <?php
                    break;

                case 'image_unedited':
                    ?>
                    <td>图片不编辑</td>
                    <?php
                    break;
            } ?>
            <?php switch ($product->examine_status) {
                case 'pass':
                    ?>
                    <td>通过</td>
                    <?php
                    break;

                case 'notpass':
                    ?>
                    <td class="notremark">未通过</td>
                    
                    <?php
                    break;

                case '':
                    ?>
                    <td>未审核</td>
                    <?php
                    break;

                case 'revocation':
                    ?>
                    <td>撤销</td>
                    <?php
                    break;

            } ?>
            <td>{{ $product->data_edit_not_pass_remark }}</td>
            <td>{{ $product->created_at }}</td>
            <td>
                <a href="{{ route('EditProduct.show', ['id'=>$product->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <?php if(($product->edit_status=='picked'||$product->edit_status=='data_edited'||$product->edit_status=="image_edited"||$product->edit_status=="image_unedited")&&$product->examine_status!='pass'){ ?>
                    <a href="{{ route('EditProduct.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 编辑资料
                    </a>
                <?php } if(($product->edit_status=="data_edited"||$product->edit_status=="image_edited")&&$product->examine_status!='pass'){ ?>
                    <a href="{{ route('productEditImage', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 编辑图片
                    </a>  
                <?php } ?>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $product->id }}"
                   data-url="{{ route('EditProduct.destroy', ['id' => $product->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop

@section('childJs')
    <script type="text/javascript">
    $('.examine_model').click(function () {
        if (confirm("确认审核?")) {
            var url = $(this).data('url');;
            var product_id = $(this).data('id');
            var status = $(this).data('status');
            $.ajax({
                url:url,
                data:{product_id:product_id,status:status},
                dataType:'json',
                type:'get',
                success:function(result){
                    
                }                    
            })
        } 
    });

    $(".notremark").hover(function(){
        $(".show_remark").css("display","block");
        },function(){
        //alert(45)
    });
    </script>
@stop