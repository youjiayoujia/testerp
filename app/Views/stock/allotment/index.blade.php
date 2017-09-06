@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th class='sort' data-field='allotment_id'>调拨单号</th>
    <th>调出仓库</th>
    <th>调入仓库</th>
    <th>备注</th>
    <th>调拨人</th>
    <th>调拨状态</th>
    <th class='sort' data-field='check_man_id'>审核人</th>
    <th>审核状态</th>
    <th class='sort' data-field='check_time'>审核时间</th>
    <th>物流方式</th>
    <th>物流号</th>
    <th>物流费(￥)</th>
    <th>对单人</th>
    <th>对单时间</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $allotment)
        <tr>
            <td>{{ $allotment->id }}</td>
            <td>{{ $allotment->allotment_id }}</td>
            <td>{{ $allotment->outwarehouse ? $allotment->outwarehouse->name : '' }}</td>
            <td>{{ $allotment->inwarehouse ? $allotment->inwarehouse->name : '' }}</td>
            <td>{{ $allotment->remark }}</td>
            <td>{{ $allotment->allotmentByName ? $allotment->allotmentByName->name : '' }}</td>
            <td>{{ $allotment->status_name }}</td>
            <td>{{ $allotment->checkByName ? $allotment->checkByName->name : '' }}</td>
            <td>{{ $allotment->check_status == '0' ? '未审核' : ($allotment->check_status == '1' ? '未通过' : '已通过') }}</td>
            <td>{{ $allotment->check_time }}</td>
            <td>{{ $allotment->logistics->first() ? $allotment->logistics->first()->type : ''}}</td>
            <td>{{ $allotment->logistics->first() ? $allotment->logistics->first()->code : ''}}</td>
            <td>{{ $allotment->logistics->first() ? $allotment->logistics->first()->fee : ''}}</td>
            <td>{{ $allotment->checkformByName ? $allotment->checkformByName->name : '' }}</td>
            <td>{{ $allotment->checkform_time }}</td>
            <td>{{ $allotment->created_at }}</td>
            <td>
                <a href="{{ route('stockAllotment.show', ['id'=>$allotment->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span> 
                </a>
                @if($allotment->check_status == '0')
                    <a href="{{ route('stockAllotment.edit', ['id'=>$allotment->id]) }}" class="btn btn-warning btn-xs" title='编辑'>
                        <span class="glyphicon glyphicon-pencil"></span> 
                    </a>
                @endif
                @if($allotment->allotment_status == 'new' && $allotment->check_status == '0')
                    <a href="{{ route('allotment.check', ['id'=>$allotment->id]) }}" class="btn btn-success btn-xs" title='审核调拨单'>
                        <span class="glyphicon glyphicon-comment"></span>
                        
                    </a>
                @endif
                @if($allotment->check_status == '2')
                    <a href="javascript:" class="btn btn-success btn-xs print" title='生成拣货单'>
                        <span class="glyphicon glyphicon-print"></span>
                    </a>
                @endif
                @if($allotment->allotment_status == 'pick')
                    <a href="javascript:" class="btn btn-success btn-xs new" data-id="{{ $allotment->id }}" title='重置'>
                        <span class="glyphicon glyphicon-retweet"></span> 
                    </a>
                    <a href="{{ route('allotment.checkout', ['id'=> $allotment->id]) }}" class="btn btn-success btn-xs" data-id="{{ $allotment->id }}" title='确认出库'>
                        <span class="glyphicon glyphicon-hand-right"></span>
                    </a>
                @endif
                @if($allotment->check_status == '2' && ($allotment->allotment_status == 'out' || $allotment->allotment_status == 'check'))
                    @if($allotment->allotment_status != 'over')
                        <a href="{{ route('allotment.checkform', ['id'=>$allotment->id]) }}" class="btn btn-success btn-xs" title='对单'>
                            <span class="glyphicon glyphicon-eye-open"></span> 
                        </a>
                    @endif
                @endif
                @if($allotment->check_status != '2')
                    <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                       data-id="{{ $allotment->id }}"
                       data-url="{{ route('stockAllotment.destroy', ['id' => $allotment->id]) }}" title='删除'>
                        <span class="glyphicon glyphicon-trash"></span> 
                    </a>
                @endif
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $allotment->table }}" data-id="{{$allotment->id}}" title='日志'>
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
    @endforeach
    <iframe src='' id='iframe_print' style='display:none'></iframe>
@stop
@section('childJs')
    <script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
    <script type='text/javascript'>
        $(document).ready(function () {
            $(document).on('click', '.pick', function () {
                obj = $(this).parent().parent();
                tmp = $(this);
                id = $(this).data('id');
                $.ajax({
                    url: "{{ route('allotment.pick') }}",
                    data: {id: id},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        location.reload();
                    }
                });
            });

            $(document).on('click', '.new', function () {
                if(confirm('确定重置?')) {
                    id = $(this).data('id');
                    obj = $(this);
                    td = obj.parent();
                    $.ajax({
                        url: "{{ route('allotment.new') }}",
                        data: {id: id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            location.reload();
                        }
                    });
                }
            });

            $(document).on('click', '.print', function () {
                id = $(this).parent().parent().find('td:eq(0)').text();
                src = "{{ route('allotment.pick', ['id'=>'']) }}/" + id;
                $('#iframe_print').attr('src', src);
                $('#iframe_print').load(function () {
                    $('#iframe_print')[0].contentWindow.focus();
                    $('#iframe_print')[0].contentWindow.print();
                });
            });
        });
    </script>
@stop