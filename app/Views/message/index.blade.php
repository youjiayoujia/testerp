@extends('common.table')
@section('tableToolButtons')
    <script src="{{ asset('plugins/pace/pace.min.js') }}"></script>
    <link href="{{ asset('plugins/pace/dataurl.css') }}" rel="stylesheet" />
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route('message.startWorkflow') }}">
            <i class="glyphicon glyphicon-play"></i> 开始工作流
        </a>

        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-pushpin"></i>
                我的
                @if(request()->user()->process_messages > 0)
                    <span class="badge">{{ request()->user()->process_messages }}</span>
                @endif
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ DataList::filtersEncode([['assign_id','=',request()->user()->id],['status','=','PROCESS']], true) }}">
                        待处理
                        @if(request()->user()->process_messages > 0)
                            <span class="badge">{{ request()->user()->process_messages }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ DataList::filtersEncode([['assign_id','=',request()->user()->id],['status','=','COMPLETE']], true) }}">
                        已处理
                    </a>
                </li>
            </ul>
        </div>
    </div>
@stop
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'> </th>
    <th class="sort" data-field="id">ID</th>
    <th>渠道</th>
    <th>账号别名</th>
    <th>主题</th>
    <th>标签</th>
    <th>平台订单号</th>
{{--    <th>状态</th>--}}
    <th>用户昵称</th>
    <th>用户ID</th>
    <th class="sort" data-field="date">发信日期</th>
    <th>客服</th>
{{--    <th class="sort" data-field="created_at">创建日期</th>--}}
    {{--<th class="sort" data-field="updated_at">更新日期</th>--}}
    <th>延迟</th>
    <th>AutoReply</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $message)
        <tr>
            <td>
                <input type="checkbox" name="check-message" value="{{$message->id}}" class="checked-message">
            </td>
            <td>{{ $message->id }}</td>
            <td>{{ $message->ChannelName}}</td>
            <td>{{ $message->account->alias }}</td>
            <td>
                <strong>{!! $message->subject !!}</strong>
            </td>
            <td>
               {{ str_limit($message->labels,30) }}
            </td>
            <td>
                {{$message->channel_order_number}}
            </td>
{{--            <td>{{ $message->status_text }}</td>--}}
            <td>{{ $message->from_name }}</td>
            <td>{{ str_limit($message->from,15)}}</td>
            <td>{{ $message->msg_time }}</td>
            <td>{{ $message->assign_id ? $message->assigner->name : '未分配' }}</td>
{{--            <td>{{ $message->created_at }}</td>--}}
{{--            <td>{{ $message->updated_at }}</td>--}}
            <td>
                <?php
                if($message->status == 'COMPLETE'){
                ?>
                {{ ceil((strtotime($message->updated_at)-strtotime($message->created_at))) }}
                <?php
                }else{
                ?>
                {{ $message->delay }}
                <?php
                }
                ?>
            </td>
            <td>
                {{$message->AutoReplyStatus}}
            </td>

            <td>
                @if($message->status == 'UNREAD')
                    <a href="{{ route('message.process', ['id'=>$message->id]) }}" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-play"></span>
                    </a>
                @endif
                @if($message->status == 'PROCESS' and $message->assign_id == request()->user()->id)
                    <a href="{{ route('message.process', ['id'=>$message->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pause"></span>
                    </a>
                @endif
                @if($message->status == 'COMPLETE' or request()->user()->group == 'super')
                    <a href="{{ route('message.show', ['id'=>$message->id]) }}" class="btn btn-info btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span>
                    </a>
                @endif
                @if($message->status == 'PROCESS' and $message->assign_id == request()->user()->id)
                    <button class="btn btn-warning btn-xs" style="background-color: #88775A;border-color: #FFFFFF;" type="button" onclick="if(confirm('确认无需回复?')){location.href='{{ route('message.notRequireReply_1', ['id'=>$message->id]) }}'}">
                        <span class="glyphicon glyphicon-minus-sign"></span>
                    </button>
                @endif
                @if($message->status == 'UNREAD')
                    <button class="btn btn-warning btn-xs" style="background-color: #88775A;border-color: #FFFFFF;" type="button" onclick="if(confirm('确认无需回复?')){location.href='{{ route('message.notRequireReply_1', ['id'=>$message->id]) }}'}">
                        <span class="glyphicon glyphicon-minus-sign"></span>
                    </button>
                @endif
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{$message->table}}" data-id="{{$message->id}}">
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
@section('doAction')
    <div class="btn-group dropup">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            批量操作
            <span class="caret"></span>
        </button>

        <ul class="dropdown-menu">
            <li><a href="javascript:void(0)" class="examine" onclick="doNotRequire()" >无需回复</a></li>
        </ul>
    </div>
@stop
    <link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
    <script src="{{ asset('js/multiple-select.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#select-account-id').select2();
        });
        function getAccountList(accountid){
             if(accountid.val() != 'none'){
                 window.location.href=accountid.val();
             }
        }
        function doNotRequire() {
            var ids = '';
            $.each($('.checked-message:checked'), function () {
                 ids += ',' + $(this).val();
            });

            if(_.isEmpty(ids)){
                alert('请先选中需要操作的消息')
                return;
            }
            ids = _.trimLeft(ids, ',');
            if(confirm('你确认需要把选中的批量改为：无需回复状态吗？')){
                location.href = '{{route('changeMultipleStatus')}}?ids='+ids;
            }
        }
    </script>
@stop