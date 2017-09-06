@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 批量修改属性
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="batchedit" data-name="weight">批量修改质检</a></li>
            <li><a href="javascript:" class="batchedit" data-name="purchase_price">参考成本</a></li>
            <li><a href="javascript:" class="batchedit" data-name="status">SKU状态</a></li>
            <li><a href="javascript:" class="batchedit" data-name="package_size">体积</a></li>
            <li><a href="javascript:" class="batchedit" data-name="name">中英文资料</a></li>
            <li><a href="javascript:" class="batchedit" data-name="declared_value">申报价值</a></li>
            <li><a href="javascript:" class="batchedit" data-name="wrap_limit">包装方式</a></li>
            <li><a href="javascript:" class="batchedit" data-name="catalog">分类</a></li>
            <li><a href="javascript:" class="batchdelete" data-name="catalog">批量删除</a></li>
            <li><a href="javascript:" class="" data-toggle="modal" data-target="#myModal">上传表格修改状态</a></li>
        </ul>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>图片</th>
    <th class="sort" data-field="sku">产品名称</th>
    <th class="sort" data-field="c_name">sku</th>
    <th>重量</th>
    <th>默认仓库</th>
    <th>申报资料</th>
    <th>注意事项</th>
    <th>小计</th>
    <th>状态</th>
    <th>采购负责人</th>
    <th>开发负责人</th>
    <th>售价</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $item)
        <tr>
            <td><input type="checkbox" name="tribute_id" value="{{$item->id}}"></td>
            <td>
                <!-- 100条sql -->
                <img src="{{ asset($item->product?$item->product->dimage:'') }}" width="100px" data-toggle="modal" data-target="#imgModal_{{$item->id}}" style="cursor:pointer;">
                <br><br>
                <div style='text-align:center'>
                    <a href='' data-toggle="modal" data-target="#imgModal_{{$item->id}}">[{{$item->product?count($item->product->shape):0}}
                        ]<a></div>
            </td>
            <td>{{ $item->c_name }}<br>物品分类：{{ $item->catalog?$item->catalog->all_name:'' }}<br>
                @foreach($item->product->logisticsLimit as $logistics)
                    @if($logistics->ico)<img width="30px" src="{{config('logistics.limit_ico_src').$logistics->ico}}" />@else{{$logistics->name}} @endif
                @endforeach
                <br>
                开发时间：{{ $item->created_at }}<br>
                【包装方式：
                <?php if($item->product){ ?>
                @foreach($item->product->wrapLimit as $wrap)
                    {{$wrap->name}}<br>
                @endforeach
                】
                <?php } ?>
                <br>
                【收货包装：
                    {{$item->recieveWrap?$item->recieveWrap->name:''}}
                】
            </td>
            <td>{{ $item->sku }}</td>
            <td>{{ $item->weight }}kg</td>
            <td>{{ isset($item->warehouse)?$item->warehouse->name:'' }}</td>
            <td>{{ $item->product?$item->product->declared_en:'' }}
                <br>{{ $item->product?$item->product->declared_cn:'' }}<br>
                ${{$item->declared_value}}
            </td>
            <td>{{$item->product?$item->product->notify:''}}</td>
            <td> 
                <br>
                <div>7天销量：{{$item->getsales('-7 day')}}</div>
                <div>14天销量：{{$item->getsales('-14 day')}}</div>
                <div>28天销量：{{$item->getsales('-28 day')}}</div>
                <div>建议采购值：{{$item->createOnePurchaseNeedData()['need_purchase_num']}}</div>
                <div>
                    库存周数：{{$item->getsales('-7 day')==0?0:($item->available_quantity+$item->normal_transit_quantity)/$item->getsales('-7 day')}}</div>
            </td>
            <td>{{ config('item.status')[$item->status]}}</td>
            <td>{{ $item->purchaseAdminer?$item->purchaseAdminer->name:''}}</td>
            <td>
                @if($item->product)
                    @if($item->product->spu)
                        {{ $item->product->spu->Developer?$item->product->spu->Developer->name:''}}
                    @endif
                @endif
            </td>
            <td>{{--<button class ="btn btn-success" >计算</button>--}}
                <a href="javascript:" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal_{{$item->id}}">
                    计算
                </a>


                <div class="modal fade" id="myModal_{{$item->id}}" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document" style="width:710px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">计算售价</h4>
                            </div>
                            <div class="modal-body">
                                <form id="compute-form-{{$item->id}}">
                                    <div class="form-group form-inline sets" id="setkey_0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td colspan="3">
                                                    当前分类：{{ $item->catalog?$item->catalog->all_name:'' }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">产品名称：{{ $item->c_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    利润率：
                                                    <div class="input-group">
                                                        <input type="text" class="form-control " style="width:125px" id="profit-{{$item->id}}" value="20">
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    反推价格：
                                                    <div class="input-group">
                                                        <input type="text" class="form-control " style="width:125px" id="target-price-{{$item->id}}" value="">
                                                        <span class="input-group-addon">$</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>产品重量：
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="weight-{{$item->id}}" value="{{$item->weight}}" style="width: 110px;" disabled>
                                                        <span class="input-group-addon">Kg</span>
                                                    </div>

                                                </td>
                                                <td colspan="2">
                                                    渠道名称：
                                                    <select class="form-control" id="channel-{{$item->id}}" style="width: 160px;">
                                                        <option value="none">请选择</option>
                                                        @foreach($Compute_channels as $channel)
                                                            <option value="{{$channel->name}}">{{$channel->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    物流分类：
                                                    <select class="form-control logistics-catalog-{{$item->id}}" style="width: 150px;" name="logistics-catalog-{{$item->id}}" id="logistics-catalog-{{$item->id}}" onchange="changeSelectVlaue($(this),'catalog',{{$item->id}})">
                                                        <option value="none">请选择</option>
                                                    </select>
                                                    <script>
                                                        $('#logistics-catalog-{{$item->id}}').select2({
                                                            ajax: {
                                                                url: "{{ route('ajaxReutrnCatalogs') }}",
                                                                dataType: 'json',
                                                                delay: 250,
                                                                data: function (params) {
                                                                    return {
                                                                        name: params.term,
                                                                    };
                                                                },
                                                                results: function (data, page) {
                                                                }
                                                            },
                                                        });
                                                    </script>
                                                </td>
                                                <td>
                                                    物流：
                                                    <select class="form-control" onchange="changeSelectVlaue($(this),'logistics',{{$item->id}})" id="logistics-{{$item->id}}" style="width: 150px;">
                                                        <option value="none">请选择</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    物流分区：
                                                    <select class="form-control" name="division" id="zones-{{$item->id}}">
                                                        <option value="none">请选择</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                            </tr>
                                        </table>
                                        <div class=" text-right">
                                            <input type="button" value="取 消" class="form-control btn-default" data-dismiss="modal" aria-label="Close">
                                            <input type="button" name="查询" class="form-control btn-primary" placeholder="属性名" value="查 询" onclick="doComputePrice({{$item->id}})">
                                        </div>
                                        <br/>
                                        <table class="table table-bordered table-striped table-hover sortable" style="display: none;" id="result-table-{{$item->id}}">
                                            <thead>
                                            <tr>
                                                <th>序号</th>
                                                <th>渠道名</th>
                                                <th>大PP价格（单位：美元）</th>
                                                <th>小PP价格（单位：美元）</th>
                                                <th>反推利润率</th>
                                            </tr>
                                            </thead>
                                            <tbody id="result-price-{{$item->id}}">

                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </td>

            <td>
                <a href="{{ route('item.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                <a href="{{ route('item.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <a href="{{ route('item.print', ['id'=>$item->id]) }}" class="btn btn-primary btn-xs" data-id="{{ $item->id }}">
                    <span class="glyphicon glyphicon-print"></span>
                </a>
                <a data-toggle="modal" data-target="#add_supplier_{{$item->id}}" title="添加备选供应商" class="btn btn-success btn-xs">
                    <span class="glyphicon glyphicon-shopping-cart"></span>
                </a>
                <a data-toggle="modal" data-target="#switch_purchase_{{$item->id}}" title="转移采购负责人" class="btn btn-info btn-xs" id="find_shipment">
                    <span class="glyphicon glyphicon-user"></span>
                </a>
                <a data-toggle="modal" data-target="#add_to_new{{$item->id}}" title="转新品操作" class="btn btn-info btn-xs" id="add_to_new">
                    <span class="glyphicon glyphicon-camera"></span>
                </a>
                <a data-toggle="modal" data-target="#question_{{$item->id}}" title="常见问题" class="btn btn-info btn-xs" id="ques">
                    <span class="glyphicon glyphicon-question-sign"></span>
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route('item.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $item->table }}" data-id="{{$item->id}}" title='日志'>
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
        <tr>  
            <th colspan='3'>仓库</th>
            <th colspan='3'>可用库存</th>
            <th colspan='2'>实库存</th>
            <th colspan='2'>在途</th>
            <th colspan='2'>特采在途</th>
            <th colspan='2'>缺货</th>    
        </tr>
        @foreach($warehouses as $warehouse)
            <tr>
                <td colspan='3'>{{$warehouse->name}}</td>
                <td colspan='3'>{{$item->getStockQuantity($warehouse->id,1)}}</td>
                <td colspan='2'>{{$item->getStockQuantity($warehouse->id)}}</td>
                <td colspan='2'>{{$item->transit_quantity[$warehouse->id]['normal']}}</td>
                <td colspan='2'>{{$item->transit_quantity[$warehouse->id]['special']}}</td>
                <td colspan='2'>{{$item->warehouse_out_of_stock[$warehouse->id]['need']}}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan='3'>总计</td>
            <td colspan='3'>{{$item->available_quantity}}</td>
            <td colspan='2'>{{$item->all_quantity}}</td>
            <td colspan='2'>{{$item->normal_transit_quantity}}</td>
            <td colspan='2'>{{$item->special_transit_quantity}}</td>
            <td colspan='2'>{{$item->out_of_stock?$item->out_of_stock:0}}</td>
        </tr>

        <tr>  
            <th colspan='2'>渠道</th>
            <th colspan='4'>7天销量</th>
            <th colspan='4'>14天销量</th>
            <th colspan='4'>28天销量</th>    
        </tr>
        @foreach($channels as $channel)
            <tr>
                <td colspan='2'>{{$channel->name}}</td>
                <td colspan='4'>{{$item->getChannelSales('-7 day')[$channel->id]}}</td>
                <td colspan='4'>{{$item->getChannelSales('-14 day')[$channel->id]}}</td>
                <td colspan='4'>{{$item->getChannelSales('-28 day')[$channel->id]}}</td>
            </tr>
        @endforeach

        <!-- 图片模态框（Modal -->
        <div class="modal fade" id="imgModal_{{$item->id}}" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width:800px">
                <div class="modal-content">

                    <div class="modal-body">
                        <!-- 50条sql -->
                        @if($item->product)
                            @foreach($item->product->shape as $image)
                                <a href="{{ asset($image) }}" target='_blank'><img src="{{ asset($image) }}" width="244px"></a>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
        </div>
        <!-- 图片模态框结束（Modal） -->

        <!-- 模态框（Modal）转采购负责人 -->
        <form action="/item/changePurchaseAdmin/{{$item->id}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="switch_purchase_{{$item->id}}" role="dialog"
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
                        <div class="modal-body">
                            <div>当前采购负责人:{{$item->purchaseAdminer?$item->purchaseAdminer->name:'无负责人'}}</div>
                            <br>
                            <div>
                                转移至：<select class='form-control purchase_adminer' name="purchase_adminer" id="{{$item->id}}"></select>
                                或者：<input type="text" value='' placeholder="姓名" name='manual_name' id='manual_name'>
                            </div>
                        </div>
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
        <!-- 模态框转采购负责人结束（Modal） -->

        <!-- 模态框（Modal）转新品操作 -->
        <form action="/item/changeNewSku/{{$item->id}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="add_to_new{{$item->id}}"  role="dialog" 
               aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" 
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                           转新品
                        </h4>
                     </div>
                     <div class="modal-body">
                         <div>当前状态:{{config('item.new_status')[$item->new_status]}}</div><br>
                         <div>更改状态
                            <select class='form-control' name="new_status" id="">
                                <option value='1'>转新品</option>
                                <option value='0'>取消转新品</option>
                            </select>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" 
                           data-dismiss="modal">关闭
                        </button>
                        <button type="submit" class="btn btn-primary">
                           提交
                        </button>
                     </div>
                  </div>
            </div>
            </div>
        </form>
        <!-- 模态框（Modal）转新品操作 -->

        <!-- 模态框（Modal）提问 -->
        <form action="/item/question/{{$item->id}}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="question_{{$item->id}}" role="dialog"
                 aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close"
                                    data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title" id="myModalLabel">
                                常见问题提问
                            </h4>
                        </div>

                        <div class="modal-body">


                            <div>向
                                <select name='question_group'>
                                    @foreach(config('product.question.types') as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                                分组提问
                            </div>
                            <br>
                            <div><textarea rows="3" cols="88" name='question_content'></textarea></div>
                            <div><input type='file' name='uploadImage'><span style="color:red">(文件为图片格式)</span></div>
                        </div>

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
        <!-- 模态框提问结束（Modal） -->

        <!-- 添加供应商模态框（Modal -->
        <form action="/item/addSupplier/{{$item->id}}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="add_supplier_{{$item->id}}" role="dialog"
                 aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close"
                                    data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title" id="myModalLabel">
                                添加备选供应商
                            </h4>
                        </div>

                        <div class="modal-body">
                            <select id="supplier_id" style="width:550px" class="form-control supplier" name="supplier_id">
                                <option value="{{$item->supplier?$item->supplier->id:0}}">{{$item->supplier?$item->supplier->name:''}}</option>
                            </select>
                        </div>

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
        <!-- 添加供应商模态框结束（Modal） -->

    @endforeach

    <!-- 模态框（Modal） -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        上传表格修改sku状态
                    </h4>
                </div>

                <div class="modal-body">
                    <form action="{{ route('item.uploadSku') }}" method="post" enctype="multipart/form-data">
                        <div>
                            <span>状态选择:</span>
                            <select class="form-control" id="spu_status" style="width: 160px;" name="spu_status">
                                <option value="none">请选择</option>
                                @foreach(config('item.status') as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="upload">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"
                                            data-dismiss="modal">关闭
                            </button>
                            <button type="submit" class="btn btn-primary">
                                提交
                            </button>
                        </div>
                    </form>
                </div>             
            </div>
        </div>
    </div>

@stop

@section('childJs')
    <link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
    <script src="{{ asset('js/multiple-select.js') }}"></script>
    <script type="text/javascript">
        $('.batchedit').click(function () {
            var checkbox = document.getElementsByName("tribute_id");
            var item_ids = "";
            var param = $(this).data("name");
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                item_ids += checkbox[i].value + ",";
            }
            item_ids = item_ids.substr(0, (item_ids.length) - 1);
            if(item_ids==''){
                alert('请选择sku');return;
            }
            var url = "{{ route('batchEdit') }}";
            window.location.href = url + "?item_ids=" + item_ids + "&param=" + param;
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

        $('.batchdelete').click(function () {
            if (confirm("确认删除?")) {
                var url = "{{route('item.batchDelete')}}";

                var checkbox = document.getElementsByName("tribute_id");
                var item_ids = "";
                for (var i = 0; i < checkbox.length; i++) {
                    if (!checkbox[i].checked)continue;
                    item_ids += checkbox[i].value + ",";
                }
                item_ids = item_ids.substr(0, (item_ids.length) - 1);
                if(item_ids==''){
                    alert('请选择sku');return;
                }
                $.ajax({
                    url: url,
                    data: {item_ids: item_ids},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        window.location.reload();
                    }
                })
            }
        });

        /*ajax调取采购负责人*/
        $('.purchase_adminer').select2({
            //alert(1);return;
            ajax: {
                url: "{{ route('item.ajaxSupplierUser') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        user: params.term,
                        item_id: $(this).attr('id'),
                    };
                },
                results: function (data, page) {

                }
            },
        });

        $('.suppliers').select2({
            
            ajax: {
                url: "{{ route('ajaxSupplier') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        supplier: params.term,
                    };
                },
                results: function (data, page) {

                }
            },
        });

        $(document).on('change', '.sectiongangeddouble_first', function () {
            val = $(this).val();
            $.get(
                "{{ route('item.sectionGangedDouble')}}",
                {val: val},
                function (result) {
                    $('.sectiongangeddouble_second').html(result);
                }
            )
        })

        function changeSelectVlaue(selected, type, productId) {
            var id = selected.val();
            if (id) {
                $.ajax({
                    url: "{{route('product.ajaxReturnLogistics')}}",
                    data: {id: id, type: type},
                    dataType: 'json',
                    type: 'get',
                    success: function ($returnInfo) {
                        if ($returnInfo != {{config('status.ajax.fail')}}) {
                            switch (type) {
                                case 'catalog':
                                    $('#logistics-' + productId).html('');
                                    $('#logistics-' + productId).append('<option value="none"> 请选择 </option>');
                                    $.each($returnInfo, function (index, item) {
                                        $('#logistics-' + productId).append('<option value="' + item.id + '">' + item.code + '</option>');
                                    });
                                case 'logistics':
                                    $('#zones-' + productId).html('');
                                    $('#zones-' + productId).append('<option value="none"> 请选择 </option>');
                                    $.each($returnInfo, function (index, item) {
                                        $('#zones-' + productId).append('<option value="' + item.id + '">' + item.zone + '</option>');
                                    });
                                default:
                                    return false;
                            }
                        }
                    }
                });
            }
        }

        /**
         * 计算价格
         * @param productId
         */
        function doComputePrice(productId) {

            var zone_id = $('#zones-' + productId).val();
            var channel_id = $('#channel-' + productId).val();
            var profit_id = $('#profit-' + productId).val();
            var product_weight = $('#weight-' + productId).val();
            var target_price = $('#target-price-' + productId).val();

            if (zone_id == 'none') {
                alert('物流分区不能为空');
                return false;
            }
            if (profit_id == '') {
                alert('利润率不能为空');
                return false;
            }
            if (product_weight == '') {
                alert('产品重量不能为空');
                return false;
            }

            var html = '';
            $.ajax({
                url: "{{  route('product.ajaxReturnPrice') }}",
                dataType: 'json',
                'type': 'get',
                data: {
                    product_id: productId,
                    zone_id: zone_id,
                    channel_id: channel_id,
                    profit_id: profit_id,
                    product_weight: product_weight,
                    target_price: target_price
                },
                success: function (returnInfo) {

                    if (returnInfo['status'] == 1) {
                        $.each(returnInfo['data'], function (i, item) {

                            if (item.channel_price_big != false) {

                            }
                            var channel_rate_price_big = (item.channel_price_big != false) ? '<font color="red">(' + item.channel_price_big + ')</font>' : '';
                            var channel_rate_price_small = (item.channel_price_small != false) ? '<font color="red">(' + item.channel_price_small + ')</font>' : '';

                            html += '<tr>';
                            html += '<td>' + (i + 1) + '</td><td>' + item.channel_name + '</td><td>' + item.sale_price_big + channel_rate_price_big + '</td><td>' + item.sale_price_small + channel_rate_price_small + '</td>';
                            /*html += '<td>'+item.profit_id+'->'+item.sale_price_small+'</td>';*/
                            html += '<td>' + item.profitability.profit + '</td>';
                            html += '</tr>';

                            $('#result-price-' + productId).html(html);
                            $('#result-table-' + productId).show();
                        });
                    } else {
                        alert('出错了，请检查下物流分区，汇率是否有误 ');
                    }
                },
                error: function () {
                    alert('参数不完整，计算失败；品类渠道的税率是否编辑？');

                }
            });

        }

    </script>
@stop