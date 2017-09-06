@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>调拨单号</th>
    <th>调出仓库</th>
    <th>调入仓库</th>
    <th>头程物流</th>
    <th>调拨人</th>
    <th>状态</th>
    <th>审核人</th>
    <th>审核状态</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $overseaAllotment)
        <tr>
            <td>{{ $overseaAllotment->id }}</td>
            <td>{{ $overseaAllotment->allotment_num }}</td>
            <td>{{ $overseaAllotment->outWarehouse ? $overseaAllotment->outWarehouse->name : ''}}</td>
            <td>{{ $overseaAllotment->inWarehouse ? $overseaAllotment->inWarehouse->name : ''}}</td>
            <td>{{ $overseaAllotment->logistics ? $overseaAllotment->logistics->name : '' }}</td>
            <td>{{ $overseaAllotment->allotmentBy ? $overseaAllotment->allotmentBy->name : ''}}</td>
            <td>{{ $overseaAllotment->status_name }}</td>
            <td>{{ $overseaAllotment->checkBy ? $overseaAllotment->checkBy->name : ''}}</td>
            <td>{{ $overseaAllotment->check_status == 'new' ? '未审核' : ($overseaAllotment->check_status == 'fail' ? '未审核' : '已审核')}}</td>
            <td>{{ $overseaAllotment->created_at }}</td>
            <td>
                <a href="{{ route('overseaAllotment.show', ['id'=>$overseaAllotment->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                @if($overseaAllotment->check_status == 'new')
                <a href="{{ route('overseaAllotment.edit', ['id'=>$overseaAllotment->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                @endif
                @if($overseaAllotment->check_status == 'new')
                    <a href="{{ route('overseaAllotment.check', ['id'=>$overseaAllotment->id]) }}" class="btn btn-success btn-xs" title='审核调拨单'>
                        <span class="glyphicon glyphicon-comment"></span>
                    </a>
                @endif
                @if($overseaAllotment->check_status == 'pass')
                    <a href="javascript:" class="btn btn-success btn-xs print" title='打印拣货单'>
                        <span class="glyphicon glyphicon-print"></span>
                    </a>
                @endif
                @if(in_array($overseaAllotment->status, ['pick','inboxing']))
                    <a href="{{route('overseaAllotment.inboxed', ['id' => $overseaAllotment->id])}}" class="btn btn-success btn-xs" title='装箱'>
                        <span class="glyphicon glyphicon-gift"></span>
                    </a>
                @endif
                @if(in_array($overseaAllotment->status, ['inboxed', 'out']))
                    <a href="{{route('overseaAllotment.returnAllInfo', ['id' => $overseaAllotment->id])}}" class="btn btn-success btn-xs" title='回填箱子信息并出库'>
                        <span class="glyphicon glyphicon-folder-close"></span>
                    </a>
                @endif
                @if($overseaAllotment->status == 'out')
                    <a href="{{route('overseaAllotment.allotmentInStock', ['id' => $overseaAllotment->id])}}" class="btn btn-success btn-xs" title='调拨单已入库'>
                        <span class="glyphicon glyphicon-sort-by-attributes"></span>
                    </a>
                @endif
                @if(in_array($overseaAllotment->status, ['out', 'over']))
                <a href="javascript:" class="btn btn-success btn-xs printBox" title='打印装箱单'>
                    <span class="glyphicon glyphicon-folder-open"></span>
                </a>
                @endif
            </td>
        </tr>
    @endforeach
    <iframe src='' id='iframe_print' style='display:none'></iframe>
@stop
@section('childJs')
    <script type='text/javascript'>
        $(document).ready(function () {
            $(document).on('click', '.print', function () {
                id = $(this).parent().parent().find('td:eq(0)').text();
                src = "{{ route('overseaAllotment.pick', ['id'=>'']) }}/" + id;
                $('#iframe_print').attr('src', src);
                $('#iframe_print').load(function () {
                    $('#iframe_print')[0].contentWindow.focus();
                    $('#iframe_print')[0].contentWindow.print();
                });
            });

            $(document).on('click', '.printBox', function () {
                id = $(this).parent().parent().find('td:eq(0)').text();
                src = "{{ route('overseaAllotment.printBox', ['id'=>'']) }}/" + id;
                $('#iframe_print').attr('src', src);
                $('#iframe_print').load(function () {
                    $('#iframe_print')[0].contentWindow.focus();
                    $('#iframe_print')[0].contentWindow.print();
                });
            });
        });
    </script>
@stop