        <input type="hidden" value="{{$bang}}" id="bang">
        <div class="row">
             <div class="form-group col-lg-6">
             <strong>运单号:</strong>
            {{$postCoding}}
            <input type="hidden" id="post_coding" value="{{$postCoding}}">
            <input type="hidden" id="wuliu_id" value="<?php echo isset($wuliu_id)?$wuliu_id:0 ?>">
             </div>
             @if($postcodingNum >0)
             <div class="form-group col-lg-6">
             <strong>已关联采购单:</strong>
             <span id="guanlian">NO.{{$data['purchaseOrder']}}</span>
             </div>
             @else
             <div class="form-group col-lg-6">
             <strong>未关联采购单</strong>
             </div>
              @endif
        </div>   
        @if($postcodingNum ==0)
        <div class="row">
            <div class="form-group col-lg-4">
              <strong>增加关联采购单号</strong>
              <input  type="text" name="purchase_order_id" id="purchase_order_id" value="">
            </div>
            <div class="form-group col-lg-4">
            <strong>录入运费</strong>
              <input type="text" name="postage" id="postage" value="">
            </div>
            <div class="form-group col-lg-4">
              <input type="button" value="关联" onClick="binding()">
            </div>
        </div>
       @else
       <div class="row" id="po_{{$data['postcoding']->id}}">
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
                
                <tr>
                    <td>{{$data['postcoding']->id}}</td>
                    <td>{{$data['postcoding']->post_coding}}</td>
                    <td><?php if($data['purchaseOrder']==0){echo "未关联"; }else{echo "已关联";} ?></td>
                    <td><a target="_blank" href="{{ route('purchaseOrder.show', ['id'=>$data['postcoding']->purchase_order_id]) }}">{{$data['postcoding']->purchase_order_id}}</a></td>
                    <td>{{$data['postcoding']->user?$data['postcoding']->user->name:''}}</td>
                    <td>{{$data['postcoding']->updated_at}}</td>
                    <td>
                        <a href="javascript:" class="btn btn-danger btn-xs delete_item" data-id="{{$data['postcoding']->id}}">
                            <span class="glyphicon glyphicon-trash"></span> 删除关联
                        </a>
                    </td>
                </tr>
                
                 </tbody>
                </table>
       </div>
       @endif