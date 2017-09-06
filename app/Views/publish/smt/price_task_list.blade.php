@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th>帐号</th>
    <th>物流渠道</th>  
    <th>粉、液、电、物流渠道</th> 
    <th>调价幅度</th>
    <th>限价金额</th>
    <th>调价状态</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
           <td><input type="checkbox" name="ids[]" value="{{$item->id}}" /> </td>
           <td>{{$accountInfoArr[$item->token_id]}}</td>
           <td>{{$shipmentArr[$item->shipment_id]}}</td>
           <td>{{$shipmentArr[$item->shipment_id_op]}}</td>
           <td>{{$item->percentage}}%</td>
           <td>{{$item->re_pirce}}</td>
           <td>
                @if($item->status == 1)<span style="color:red;">未调价</span>
                @else 已调价
                @endif
           </td>
           <td>{{$item->created_at}}</td>
           <td>
                 <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route('smtPriceTask.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除                    
               </a>     
           </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group">
            <a class="btn btn-success" href="javascript:void(0)" data-toggle="modal"
                    data-target="#setPriceTask"
                    title="生成调价任务">
                生成调价任务
            </a>
     </div>
     <div class="btn-group">
            <a class="btn btn-success" href="javascript:void(0)" id="do_task">
                执行调价任务
            </a>
    </div>
    <div class="btn-group">
            <a class="btn btn-success batch_operate" href="javascript:void(0)">
               批量删除
            </a>
    </div> 
     <div class="modal fade" id="setPriceTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('smtPriceTask.createPriceTask')}}" method="POST" id="setPriceTaskForm">
                {!! csrf_field() !!}
                 <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">生成调价任务</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-2">
                            <label for="account" class='control-label'>帐号:</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        </div>
                        <div class="form-group col-lg-6">                    
                            <select name="token_id" class="form-control">
                                <option value="">请选择账号</option>
                                @foreach($accountInfo as $val)
                                    <option value="{{$val['id']}}">{{$val['alias']}}</option>
                                @endforeach                                                        
                            </select>                              
                         </div>
                    </div>
                    <div class="row">                       
                        <label class="col-lg-2">产品分组:</label>                                          
                        <div class="form-group col-lg-4">
                            <select name="groupId" class="form-control">
                                <option value="">=所有分组=</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-lg-2">物流方式:</label>
                        <div class="form-group col-lg-6">
                            <select name="shipment_id" class="form-control">
                                <option value="">请选择物流</option>
                                  @foreach($logisticsInfo as $logistic)
                                    <option value="{{$logistic['id']}}">{{$logistic['code']}}</option>
                                @endforeach   
                            </select>
                        </div> 
                    </div>
                    <div class="row">
                        <label class="col-lg-2">粉、液、电:</label>
                        <div class="form-group col-lg-6">
                            <select name="shipment_id_op" class="form-control">
                                <option value="">请选择物流</option>
                                @foreach($logisticsInfo as $logistic)
                                   <option value="{{$logistic['id']}}">{{$logistic['code']}}</option>
                                @endforeach     
                            </select>
                        </div> 
                    </div>
                    <div class="row">
                         <label class="col-lg-2">利润率:</label>
                         <div class="col-lg-4">
                            <input type="text" name="percentage" class="form-control">
                            
                         </div>
                         <label style="float:left;width:5px;">%</label>
                    </div>
                    <div class="row" style="margin-top:10px;">                   
                            <label class="col-lg-2">限价金额:</label>
                            <div class="col-lg-1">
                                <input type="checkbox" class="control-label" id="is_re_pirce" style="float:left"/>                              
                            </div>
                            <div class="col-lg-4">
                                 <input type="text" class="form-control hidden" placeholder="输入限价金额" id="re_pirce" size="10" name="re_pirce"/>
                            </div>
                     
                    </div>
                </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="submit">提交</button>
                </div>
               </form>
            </div>
        </div>
      </div>        
@stop
@section('childJs')
<link href="{{ asset('plugins/layer/skin/layer.css') }}" rel="stylesheet">
<script src="{{ asset('plugins/layer/layer.js') }}"></script>
<script type="text/javascript">
    $(".batch_operate").click(function(){
        if (confirm('确定要批量删除数据吗？')){
            var Ids = $('input[name="ids[]"]:checked').map(function() {
                return $(this).val();
            }).get().join(',');
            if (Ids == ''){
                alert('请勾选需要的数据');
                return false;
            }
            $.ajax({
                url: "{{route('smtPriceTask.batchDelete')}}",
                data: 'Ids='+Ids,
                type: 'POST',
                dataType: 'json',
    
                success: function(data){
                    var str='';
                    if (data.data){
                        $.each(data.data, function(index, el){
                            str += el+';';
                        });
                    }
                    if (data.status) { //成功
                        showxbtips(data.info+str);
                    }else {
                        showxbtips(data.info+str, 'alert-warning');
                    }
                    window.location.reload();
                }
    
    
            });
        }
    });

    $('#is_re_pirce').click(function(){
        if($('#is_re_pirce').is(':checked'))
        {
            $('#re_pirce').removeClass('hidden');
        }
        else
        {
            $('#re_pirce').addClass('hidden');
        }
    });
    
    $('#submit').click(function(){
        var data = $('#setPriceTaskForm').serialize();
        var ii = layer.load('生成任务中...');
    	$.ajax({
            url: "{{route('smtPriceTask.createPriceTask')}}",
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function(data){
                layer.close(ii);
                window.location.reload();
            }
        });
    	$('#setPriceTask').modal('toggle');
    })
    
     $('#do_task').click(function(){
        if (confirm('确定要执行该条数据吗？')) {
            var Ids = $('input[name="ids[]"]:checked').map(function () {
                return $(this).val();
            }).get().join(',');
            if (Ids == '') {
                alert('请勾选需要的数据');
                return false;
            }

            var ii = layer.load('执行中')
            $.ajax({
                url: "{{route('smtPriceTask.getSmtPriceTask')}}",
                data: 'do_task=yes&Ids='+Ids,
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    layer.close(ii);
                    window.location.reload();
                }
            });
        }
    })
</script>
@stop