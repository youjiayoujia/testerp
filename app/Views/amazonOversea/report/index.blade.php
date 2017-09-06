@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>fba地址</th>
    <th>planId</th>
    <th>shipmentId</th>
    <th>referenceId</th>
    <th>发货名称</th>
    <th>状态</th>
    <th>打印状态</th>
    <th>入库状态</th>
    <th>箱数</th>
    <th>发货地址</th>
    <th>渠道帐号</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $report)
        <tr>
            <td>{{ $report->id }}</td>
            <td>{{ $report->shipping_address }}</td>
            <td>{{ $report->plan_id }}</td>
            <td>{{ $report->shipment_id }}</td>
            <td>{{ $report->reference_id }}</td>
            <td>{{ $report->shipment_name }}</td>
            <td>{{ config('setting.oversea.status')[$report->status] }}</td>
            <td>{{ config('setting.oversea.print_status')[$report->print_status] }}</td>
            <td>{{ $report->inStock_status ? $report->inStock_status : '暂无入库状态' }}</td>
            <td>{{ $report->quantity }}</td>
            <td>{{ $report->shipping_address }}</td>
            <td>{{ $report->account ? $report->account->account : '' }}</td>
            <td>{{ $report->created_at }}</td>
            <td>
                <a href="{{ route('report.show', ['id'=>$report->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                @if($report->status == 'NEW')
                <a href="{{ route('report.check', ['id' => $report->id]) }}" class="btn btn-success btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 审核
                </a>
                @endif
                @if($report->status == 'NEW')
                <a href="{{ route('report.edit', ['id'=>$report->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                @endif
                @if($report->status != 'NEW' && $report->status != 'FAIL' )
                <a href="javascript:" class="btn btn-success btn-xs print">
                    <span class="glyphicon glyphicon-pencil"></span>生成拣货单
                </a>
                @endif
                @if($report->status == 'PICKING' || $report->status == 'PACKING')
                <a href="{{ route('report.package', ['id'=>$report->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 包装
                </a>
                @endif
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $report->id }}"
                   data-url="{{ route('report.destroy', ['id' => $report->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
    <iframe src='' id='iframe_print' style='display:none'></iframe>
@stop
@section('tableToolButtons')
@parent
@stop
@section('childJs')
<script type='text/javascript'>
    $(document).ready(function () {
        $(document).on('click', '.print', function () {
            id = $(this).parent().parent().find('td:eq(0)').text();
            src = "{{ route('report.pick', ['id'=>'']) }}/" + id;
            $('#iframe_print').attr('src', src);
            $('#iframe_print').load(function () {
                $('#iframe_print')[0].contentWindow.focus();
                $('#iframe_print')[0].contentWindow.print();
            });
        });
    });
</script>
@stop