@extends('common.table')
@section('tableToolButtons')
    
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th>待入库sku</th>
    <th>待入库数量</th>
    <th>采购单ID</th>
    <th>采购条目ID</th>
    <th>仓库</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $_data)
    <?php //echo '<pre>';print_r($_data->productItem->all_warehouse_position);exit; ?>
        <tr>
            <td>{{ $_data->sku }}</td>
            <td>{{ $_data->purchase_num }}</td>
            <td>{{ $_data->purchase_order_id }}</td>
            <td>{{ $_data->id }}</td>
            <td>{{ $_data->warehouse?$_data->warehouse->name:'' }}</td>
            <td>
                <a href="" data-toggle="modal" data-target="#wpedit_{{$_data->id}}">
                    <span class="glyphicon glyphicon-pencil"></span> 入库
                </a>
            </td>
        </tr>

        <!-- 添加供应商模态框（Modal -->
            <form action="{{ route('newProductupdateArriveLog') }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="wpedit_{{$_data->id}}"  role="dialog" 
               aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" 
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                           选择库位入库
                        </h4>
                     </div>

                     <div class="modal-body">        
                        <!-- @foreach($_data->productItem->all_warehouse_position as $info_key=>$info)

                        <label>
                            <input type='radio' {{$info_key==0?'checked':''}} name='position' value='{{$info["warehouse_position_id"]}}' >{{$info['warehouse_name']}}{{$info['warehouse_position']}}
                        </label>
                        <br>
                            
                        @endforeach -->

                        <label>请输入库位：</label>
                        <br>
                        <input type='text' value='' name='position'>
                        <br>
                        <label for="color">入库数量</label>
                        <input class="form-control" id="num" placeholder="入库数量" name='num' value="{{$_data->purchase_num}}">
                     </div>
                     
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" 
                           data-dismiss="modal">关闭
                        </button>
                        <button type="submit" class="btn btn-primary" name='' value=''>
                           入库
                        </button>
                     </div>
                  </div>
            </div>
            </div>
            <input type='hidden' name='purchase_item_id' value='{{ $_data->id }}'>
            <input type='hidden' name='purchase_order_id' value='{{ $_data->purchase_order_id }}'>
        </form>
        <!-- 添加供应商模态框结束（Modal） -->

    @endforeach
@stop
