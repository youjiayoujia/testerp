@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-filter"></i> 过滤
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ DataList::filtersEncode(['status','=','0']) }}">未编辑产品</a></li>
                <li><a href="{{ DataList::filtersEncode(['status','=','1']) }}">未编辑图片</a></li>
                <li><a href="{{ DataList::filtersEncode(['status','=','2']) }}">待审核</a></li>
                <li><a href="{{ DataList::filtersEncode(['status','=','3']) }}">已审核</a></li>
            </ul>
    </div>  
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    
    <th>ID</th>
    <th>产品信息</th>
    <th>净重(kg)</th>
    <th>线上供货商</th>
    <th>线上供货商地址</th>
    <th>线上供货商货号</th>
    <th>泽尚拿货价</th>
    <th>销售价(USD)</th>
    <th>选款人ID</th>
    <th>状态</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $amazonProduct)
        <tr>
            <td>{{ $amazonProduct->product_id }}</td>
            <td>{{ $amazonProduct->choies_info }}</td>
            <td>{{ $amazonProduct->weight }}</td>
            <td>{{ $amazonProduct->product->supplier->name }}</td>
            <td>{{ $amazonProduct->supplier_info }}</td>
            <td>{{ $amazonProduct->supplier_sku }}</td>
            <td>{{ $amazonProduct->purchase_price }}</td>
            <td>{{ $amazonProduct->choies_info }}</td>
            <td>{{ $amazonProduct->product->upload_user }}</td>
            <?php switch ($amazonProduct->status) {
                case '0':
                    ?>
                    <td>未编辑资料</td>
                    <?php
                    break;

                case '1':
                    ?>
                    <td>已编辑资料</td>
                    <?php
                    break;

                case '2':
                    ?>
                    <td>已编辑图片</td>
                    <?php
                    break;

                case '3':
                    ?>
                    <td>审核通过</td>
                    <?php
                    break;
            } ?>
            <td>{{ $product->created_at }}</td>
            <td>
                <a href="{{ route('EditProduct.show', ['id'=>$product->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <?php if($product->status==2){ ?>
                    <a href="javascript:" class="btn btn-info btn-xs examine_model"
                           data-id="{{ $product->id }}"
                           data-url="{{route('examineAmazonProduct')}}"
                           data-status="3" >
                            <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$product->id}}'>审核</span>
                    </a>
                    <a href="javascript:" class="btn btn-info btn-xs examine_model"
                           data-id="{{ $product->id }}"
                           data-url="{{route('examineAmazonProduct')}}"
                           data-status="0" >
                            <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$product->id}}'>审核不通过</span>
                    </a>
                <?php }elseif($product->status==3){ ?>
                    <a href="javascript:" class="btn btn-info btn-xs examine_model"
                           data-id="{{ $product->id }}"
                           data-url="{{route('examineAmazonProduct')}}"
                           data-status="0" >
                            <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$product->id}}'>撤销审核</span>
                    </a>
                <?php }elseif($product->status==0){ ?>
                    <a href="{{ route('EditProduct.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑资料
                </a>
                <?php }elseif($product->status==1){ ?>
                    <a href="{{ route('amazonProductEditImage', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
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
        //单个审核
        $('.examine_model').click(function () {
            var product_id = $(this).data('id');
            var status = $(this).data('status');
            if (confirm("确认?")) {
                var url = "{{route('examineAmazonProduct')}}";
                $.ajax({
                    url:url,
                    data:{product_ids:product_id,status:status},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        window.location.reload();                    
                    }                  
                })
            }
        });
        </script>
@stop