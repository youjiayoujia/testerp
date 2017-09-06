@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()">全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>平台</th>
    <th>订单号</th>
    <th>买家ID</th>
    <th>姓名</th>
    <th>邮箱</th>
    <th>邮编</th>
    <th>退款订单数</th>
    <th>订单总数</th>
    {{--<th>退款率</th>--}}
    <th>类型</th>
    {{--<th>备注</th>--}}
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $blacklist)
        @if($blacklist->type == 'CONFIRMED')
            <tr style="background: #D9FFFF">
        @elseif($blacklist->type == 'SUSPECTED')
            <tr style="background: #90EE90">
        @elseif($blacklist->type == 'WHITE')
            <tr style="background: white">
            {{--<tr style="background: #FFCC99">--}}
        @endif
                <td>
                    <input type="checkbox" name="tribute_id" value="{{$blacklist->id}}">
                </td>
                <td>{{ $blacklist->id }}</td>
                <td>{{ $blacklist->channel->name }}</td>
                <td>{{ $blacklist->ordernum }}</td>
                <td>{{ $blacklist->by_id }}</td>
                <td>{{ $blacklist->name }}</td>
                <td>{{ $blacklist->email }}</td>
                <td>{{ $blacklist->zipcode }}</td>
                <td>{{ $blacklist->refund_order }}</td>
                <td>{{ $blacklist->total_order }}</td>
                {{--<td>{{ $blacklist->refund_rate }}</td>--}}
                <td>{{ $blacklist->type_name }}</td>
                {{--<td>{{ $blacklist->remark }}</td>--}}
                <td>{{ $blacklist->updated_at }}</td>
                <td>{{ $blacklist->created_at }}</td>
                <td>
                    <a href="{{ route('orderBlacklist.show', ['id'=>$blacklist->id]) }}" class="btn btn-info btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 查看
                    </a>
                    <a href="{{ route('orderBlacklist.edit', ['id'=>$blacklist->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 编辑
                    </a>
                    <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                       data-id="{{ $blacklist->id }}"
                       data-url="{{ route('orderBlacklist.destroy', ['id' => $blacklist->id]) }}">
                        <span class="glyphicon glyphicon-trash"></span> 删除
                    </a>
                    <button class="btn btn-primary btn-xs dialog"
                            data-toggle="modal"
                            data-target="#dialog" data-table="{{ $blacklist->table }}" data-id="{{$blacklist->id}}">
                        <span class="glyphicon glyphicon-road"></span>
                    </button>
                </td>
            </tr>
    @endforeach
    <div class="panel panel-default">
        <div class="panel-heading"><strong>批量导入操作</strong></div>
        <div class="panel-body">
            <div class='row'>
                <form method="POST" action="{{ route('uploadBlacklist') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group col-lg-2">
                        <label for="name" class='control-label'>批量导入黑名单客户:</label>
                    </div>
                    <div class="form-group col-lg-2">
                        <input type='file' name='excel'>
                    </div>
                    <div class="form-group col-lg-1">
                        <button type='submit' class='btn btn-info btn-xs' value='submit'>submit</button>
                    </div>
                    <div class="form-group col-lg-2">
                        <a href='javascript:' class='downloadUpdateBlacklist'>格式下载(CSV)</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@section('doAction')
    <div class="panel panel-default">
        <div class="panel-heading">黑名单客户条件如下:</div>
        <div class="panel-body">
            <div class="col-lg-12 text-danger">
                <strong>SMT/eBay : 邮编+收货人姓名/收货人ID/邮箱相同</strong><br>
                <strong>AMZ : 邮编+收货人姓名/邮箱相同</strong><br>
                <strong>Wish : 邮编+收货人姓名</strong><br>
                <strong>1. 待确认黑名单客户(不拦截):提交退款订单个数超过5个,列表数据每星期日更新一次,已确认为黑名单客户数据跳过,颜色标识:淡橙色为'邮编+收货人姓名'搜索,淡绿色为'邮箱'搜索,黄色为'收货人ID'搜索;</strong><br>
                <strong>2. 已确认黑名单客户(拦截):退款率高于15%/其它恶意客户,颜色标识:红色背景;</strong><br>
                <strong>3. 导入待处理黑名单客户(拦截),颜色标识:无色背景;</strong><br>
                <strong>4. 疑似黑名单客户(拦截):当天平邮超过20个(SMT),订单超过4个(eBay);</strong><br>
                <strong>5. 白名单(不拦截)不再出现在疑似黑名单客户里.</strong><br>
            </div>
        </div>
    </div>
@stop
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            批量审核
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="shenhe" data-status="CONFIRMED" data-name="确认黑名单">确认黑名单</a></li>
            <li><a href="javascript:" class="shenhe" data-status="SUSPECTED" data-name="疑似黑名单">疑似黑名单</a></li>
            <li><a href="javascript:" class="shenhe" data-status="WHITE" data-name="白名单">白名单</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <a href='javascript:' class='btn btn-info exportAll' value='导出所有内单号'>导出所有内单号</a>
    </div>
    <div class="btn-group">
        <a href='javascript:' class='btn btn-info exportPart' value='导出勾选内单号'>导出勾选内单号</a>
    </div>
@parent
@stop
@section('childJs')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.downloadUpdateBlacklist').click(function(){
                location.href="{{ route('downloadUpdateBlacklist')}}";
            });

            $('.exportAll').click(function(){
                location.href = "{{ route('exportAll')}}";
            });

            $('.exportPart').click(function(){
                var checkbox = document.getElementsByName("tribute_id");
                var blacklist_ids = "";
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    blacklist_ids += checkbox[i].value+",";
                }
                blacklist_ids = blacklist_ids.substr(0,(blacklist_ids.length)-1);
                location.href = "{{ route('exportPart') }}?blacklist_ids=" + blacklist_ids;
            });
        });

        //批量审核
        $('.shenhe').click(function () {
            if (confirm("确认")) {
                var url = "{{route('listAll')}}";
                var checkbox = document.getElementsByName("tribute_id");
                var blacklist_ids = "";
                var blacklist_status = $(this).data('status');

                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    blacklist_ids += checkbox[i].value+",";
                }
                blacklist_ids = blacklist_ids.substr(0,(blacklist_ids.length)-1);
                $.ajax({
                    url : url,
                    data : {blacklist_ids:blacklist_ids,blacklist_status:blacklist_status},
                    dataType : 'json',
                    type : 'get',
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
