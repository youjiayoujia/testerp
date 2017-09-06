@extends('common.table')
@section('tableToolButtons')

    <div class="btn-group btn-info" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 批量修改属性
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="batchedit" data-name="name">中英文资料</a></li>
            <li><a href="javascript:" class="batchedit" data-name="quality_standard">质检</a></li>
            <li><a href="javascript:" class="batchedit" data-name="package_limit">包装方式</a></li>
            <li><a href="javascript:" class="batchedit" data-name="purchase_url">参考链接</a></li>
            <li><a href="javascript:" class="batchedit" data-name="declared">申报资料</a></li>
        </ul>
    </div>
    <!-- <div class="btn-group">
        <a href="javascript:" data-channel="1" data-name="Amazon" class="btn btn-success choseShop">
            选中
        </a>
    </div>

    <div class="btn-group" role="group">
        <div class="form-group" style="margin-bottom:0px">
            <select id="ms" class="js-example-basic-multiple" multiple="multiple" name="select_channel" style="width:200px">
                <option value="1" class='aa'>Amazon</option>
                <option value="2" class='aa'>EBay</option>
                <option value="3" class='aa'>速卖通</option>
                <option value="4" class='aa'>B2C</option>
            </select>
        </div>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            批量审核
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="shenhe" data-status="pass" data-name="审核通过">通过</a></li>
            <li><a href="javascript:" class="shenhe" data-status="notpass" data-name="审核不通过">不通过</a></li>
            <li><a href="javascript:" class="shenhe" data-status="revocation" data-name="撤销审核">撤销审核</a></li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 查询审核状态
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="{{ DataList::filtersEncode(['examine_status','=','']) }}">未审核</a></li>
            <li><a href="{{ DataList::filtersEncode(['examine_status','=','pass']) }}">审核通过</a></li>
            <li><a href="{{ DataList::filtersEncode(['examine_status','=','notpass']) }}">审核不通过</a></li>
            <li><a href="{{ DataList::filtersEncode(['examine_status','=','revocation']) }}">撤销审核</a></li>
        </ul>
    </div> -->

    <!-- <div class="btn-group" role="group">
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
    </div> -->
    {{--@can('check','product_admin,product_staff|add')--}}
    <!-- <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div> -->
    {{--@endcan--}}
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>ID</th>
    <th class="sort" data-field="model">MODEL</th>
    <th>图片</th>
    <th>产品名称</th>
    <th>英文名</th>
    <th>供应商</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $product)
        <tr>
            <td>
                @if($product->status)
                    <input type="checkbox" name="tribute_id" value="{{$product->id}}" isexamine="1">
                @else
                    <input type="checkbox" name="tribute_id" value="{{$product->id}}" isexamine="0">
                @endif
            </td>
            <td>{{ $product->id }}</td>
            <td>{{ $product->model }}</td>
            <td><img src="{{ asset($product->dimage) }}" width="100px"></td>
            <td>{{ $product->c_name }}<br>分类：{{ $product->catalog?$product->catalog->all_name:'' }}<br>开发时间：{{ $product->created_at }}<br></td>    
            <td>{{ $product->name }}</td>
            
            <td>{{ $product->supplier?$product->supplier->name:'无' }}</td>
            <td>{{ $product->created_at }}</td>
            <td>
                <a href="{{ route('product.show', ['id'=>$product->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                {{--@can('check','product_admin,product_staff|edit')--}}
                <a href="{{ route('product.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                {{--@endcan--}}
                <!-- <a href="{{ route('productMultiEdit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 小语言
                </a> -->
                <?php if(($product->edit_status == 'picked' || $product->edit_status == 'data_edited' || $product->edit_status == "image_edited" || $product->edit_status == "image_unedited") && $product->examine_status != 'pass'){ ?>
                {{--<a href="{{ route('EditProduct.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑资料
                </a>--}}

                <?php } ?>
                <!-- <a data-toggle="modal" data-target="#switch_purchase_{{$product->id}}" title="转移采购负责人" class="btn btn-info btn-xs" id="find_shipment">
                    <span class="glyphicon glyphicon-user">转移采购员</span> -->

                <a href="{{ route('createImage', ['model'=>$product->model]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑图片
                </a>
                <?php //if($product->edit_status == "image_unedited" || $product->edit_status == "image_edited"){ ?>
                <!-- <a href="{{ route('ExamineProduct.edit', ['id'=>$product->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看并审核
                </a> -->
                <?php //} ?>
{{--@can('check','product_admin,product_staff|delete')--}}
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $product->id }}"
                   data-url="{{ route('product.destroy', ['id' => $product->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
{{--@endcan--}}
            </td>
        </tr>

        <!-- 模态框（Modal） -->
        <form action="/product/changePurchaseAdmin/{{$product->id}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="switch_purchase_{{$product->id}}"  role="dialog" 
               aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" 
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                           转移采购负责人
                        </h4>
                     </div>
                     
                     <div>当前采购负责人:{{$product->purchaseAdminer?$product->purchaseAdminer->name:'无负责人'}}</div>
                     <div>转移至：</div>
                     <div><select class='form-control purchase_adminer' name="purchase_adminer" id="{{$product->id}}"></select></div>
                     或者：
                     <input type="text" value='' name='manual_name' id='manual_name'>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" 
                           data-dismiss="modal">关闭
                        </button>
                        <button type="submit" class="btn btn-primary" name='edit_status' value='image_unedited'>
                           提交
                        </button>
                     </div>
                  </div>
            </div>
            </div>
        </form>
        <!-- 模态框结束（Modal） -->
    @endforeach
@stop
@section('childJs')
    <link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
    <script src="{{ asset('js/multiple-select.js') }}"></script>
    <script type="text/javascript">
        //$('#ms').multipleSelect();
        $(".js-example-basic-multiple").select2();
        //批量选中
        $('.choseShop').click(function () {
            var channel_ids = "";
            $("#ms option:selected").each(function () {
                channel_ids += $(this).attr("value") + ",";
            });

            channel_ids = channel_ids.substr(0, (channel_ids.length) - 1);
            if (confirm("确认选中?")) {
                var url = "{{route('beChosed')}}";
                var checkbox = document.getElementsByName("tribute_id");
                var product_ids = "";

                for (var i = 0; i < checkbox.length; i++) {
                    if (!checkbox[i].checked)continue;
                    product_ids += checkbox[i].value + ",";
                }
                product_ids = product_ids.substr(0, (product_ids.length) - 1);
                $.ajax({
                    url: url,
                    data: {product_ids: product_ids, channel_ids: channel_ids},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        window.location.reload();
                    }
                })
            }
        });
        
        

        $('.batchedit').click(function () {
            var checkbox = document.getElementsByName("tribute_id");
            var product_ids = "";
            var param = $(this).data("name");
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                product_ids += checkbox[i].value + ",";
            }
            product_ids = product_ids.substr(0, (product_ids.length) - 1);
            var url = "{{ route('productBatchEdit') }}";
            window.location.href = url + "?product_ids=" + product_ids + "&param=" + param;
        });

        //批量审核
        $('.shenhe').click(function () {
            if (confirm("确认" + $(this).data('name') + "?")) {
                var url = "{{route('productExamineAll')}}";
                var checkbox = document.getElementsByName("tribute_id");
                var product_ids = "";
                var examine_status = $(this).data('status');

                for (var i = 0; i < checkbox.length; i++) {
                    if (!checkbox[i].checked)continue;
                    product_ids += checkbox[i].value + ",";
                }
                product_ids = product_ids.substr(0, (product_ids.length) - 1);
                $.ajax({
                    url: url,
                    data: {product_ids: product_ids, examine_status: examine_status},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        window.location.reload();
                    }
                })
            }
        });
        //全选
        function quanxuan() {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("tribute_id");
            if (collid.checked) {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            } else {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }
    </script>
@stop