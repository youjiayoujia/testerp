@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="priority">优先级</th>
    <th>物流方式简码</th>
    <th>物流方式名称</th>
    <th>仓库</th>
    <th>物流商</th>
    <th>物流商物流方式</th>
    <th>面单</th>
    <th>驱动名</th>
    <th>对接方式</th>
    {{--<th class="sort" data-field="pool_quantity">号码池数量(未用/已用/总数)</th>--}}
    <th>物流编码</th>
    <th>平邮or快递</th>
    <th class="sort" data-field="is_enable">是否启用</th>
    <th class="sort" data-field="is_confirm">面单是否确认</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $logistics)
        <tr class="dark-{{ $logistics->enable_color }}">
            <td>{{ $logistics->id }}</td>
            <td>{{ $logistics->priority != 0 ? $logistics->priority : '' }}</td>
            <td>{{ $logistics->code }}</td>
            <td>{{ $logistics->name }}</td>
            <td>{{ $logistics->warehouse ? $logistics->warehouse->name : '' }}</td>
            <td>{{ $logistics->supplier ? $logistics->supplier->name : '' }}</td>
            <td>{{ $logistics->type }}</td>
            <td>{{ $logistics->template ? $logistics->template->name : '未选择' }}</td>
            <td>{{ $logistics->driver }}</td>
            <td>{{ $logistics->docking == 'CODE' ? $logistics->docking_name . '(' . explode('/', $logistics->pool_quantity)[0] . ')' : $logistics->docking_name }}</td>
            {{--<td>{{ $logistics->pool_quantity }}</td>--}}
            <td>{{ $logistics->logistics_code }}</td>
            <td>{{ $logistics->is_express == '1' ? '快递' : '平邮' }}</td>
            <td>{{ $logistics->is_enable == '1' ? '是' : '否' }}</td>
            <td>{{ $logistics->is_confirm == '1' ? '是' : '否' }}</td>
            <td>{{ $logistics->created_at }}</td>
            <td>{{ $logistics->updated_at }}</td>
            <td>
                <a href="{{ route('logistics.show', ['id'=>$logistics->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logistics.edit', ['id'=>$logistics->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                {{--<a href="javascript:" class="btn btn-danger btn-xs delete_item"--}}
                   {{--data-id="{{ $logistics->id }}"--}}
                   {{--data-url="{{ route('logistics.destroy', ['id' => $logistics->id]) }}">--}}
                    {{--<span class="glyphicon glyphicon-trash"></span> 删除--}}
                {{--</a>--}}
                @if($logistics->is_enable == '1')
                    <a href="javascript:" class="btn btn-primary btn-xs enable" data-id="{{ $logistics->id }}">
                        <span class="glyphicon glyphicon-pencil"></span> 停用
                    </a>
                @elseif($logistics->is_enable == '0')
                    <a href="javascript:" class="btn btn-primary btn-xs enable" data-id="{{ $logistics->id }}">
                        <span class="glyphicon glyphicon-pencil"></span> 启用
                    </a>
                @endif
                <a href="javascript:" class="btn btn-success btn-xs copy" data-id="{{ $logistics->id }}">
                    <span class="glyphicon glyphicon-pencil"></span> 复制
                </a>
                @if($logistics->docking == 'CODE' || $logistics->docking == 'CODEAPI')
                    <a href="{{ route('logisticsCode.one', ['id'=>$logistics->id]) }}" class="btn btn-success btn-xs">
                        <span class="glyphicon glyphicon-import"></span> 号码池
                    </a>
                @endif
                <a href="{{ route('logisticsZone.one', ['id'=>$logistics->id]) }}" class="btn btn-success btn-xs">
                    <span class="glyphicon glyphicon-usd"></span> 分区报价
                </a>
                <a href="{{ route('logisticsRule.one', ['id'=>$logistics->id]) }}" class="btn btn-success btn-xs">
                    <span class="glyphicon glyphicon-usd"></span> 分配规则
                </a>
                <a class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $logistics->table }}" data-id="{{$logistics->id}}">
                    <span class="glyphicon glyphicon-road"></span> 日志
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('childJs')
    <script type="text/javascript">
        //复制
        $('.copy').click(function () {
            if (confirm("确认复制?")) {
                var logistics_id = $(this).data('id');
                $.ajax({
                    url: "{{ route('logistics.createData') }}",
                    data: {logistics_id: logistics_id},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        window.location.reload();
                    }
                });
            }
        });

        //启用停用
        $('.enable').click(function () {
            if (confirm("是否确认?")) {
                var logistics_id = $(this).data('id');
                $.ajax({
                    url: "{{ route('updateEnable') }}",
                    data: {logistics_id: logistics_id},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        window.location.reload();
                    }
                });
            }
        });
    </script>
@stop
