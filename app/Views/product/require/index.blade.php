@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all[]' class='select_all'></th>
    <th class='sort' data-field='id'>ID</th>
    <th>产品名</th>
    <th>品类</th>
    <th>货源地(省)</th>
    <th>货源地(市)</th>
    <th>类似款sku</th>
    <th>竞争产品url</th>
    <th class='sort' data-field='expected_date'>期待上传时间</th>
    <th>采购人</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>处理状态</th>
    <th>处理者id</th>
    <th class='sort' data-field='handle_time'>处理时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $productRequire)
        <tr>
            <td><input type='checkbox' name='select[]' class='select_single'></td>
            <td>{{ $productRequire->id }}</td>     
            <td>{{ $productRequire->name }}</td>
            <td>{{ $productRequire->catalogByName ? $productRequire->catalogByName->name : '' }}</td>
            <td>{{ $productRequire->province }}</td>
            <td>{{ $productRequire->city }}</td>
            <td>{{ $productRequire->similar_sku }}</td>
            <td>{{ $productRequire->competition_url }}</td>
            <td>{{ $productRequire->expected_date }}</td>
            <td>{{ $productRequire->purchase ? $productRequire->purchase->name : '' }}</td>
            <td>{{ $productRequire->created_at }}</td>
            <td>{{ $productRequire->status ? ($productRequire->status == '1' ? '未找到' : ($productRequire->status == '2' ? '已找到' : ('已创建'))) : '新需求'}}</td>
            <td>{{ $productRequire->userName ? $productRequire->userName->name : '' }}</td>
            <td>{{ $productRequire->handle_time }}</td>
            <td>
                <a href="{{ route('productRequire.show', ['id'=>$productRequire->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                @if(!$productRequire->status)
                <a href="{{ route('productRequire.edit', ['id'=>$productRequire->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href='javascript:' class='btn btn-primary btn-xs process' data-status='2' data-id="{{ $productRequire->id }}">
                    <span class="glyphicon glyphicon-eye-open"></span>找到
                </a>
                <a href='javascript:' class='btn btn-primary btn-xs process' data-status='1' data-id="{{ $productRequire->id }}">
                    <span class="glyphicon glyphicon-eye-open"></span>未找到
                </a>
                @endif
                @if($productRequire->status==2)
                <a href="{{ route('product.create', ['id'=>$productRequire->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 创建model
                </a>
                @endif
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $productRequire->id }}"
                   data-url="{{ route('productRequire.destroy', ['id' => $productRequire->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> @if($chose_status){{$chose_status}}@else查询当前状态@endif
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="{{ DataList::filtersEncode(['status','=','0']) }}">未处理</a></li>
            <li><a href="{{ DataList::filtersEncode(['status','=','1']) }}">未找到</a></li>
            <li><a href="{{ DataList::filtersEncode(['status','=','2']) }}">已找到</a></li>
        </ul>
</div>  
<div class="btn-group" role="group">
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="glyphicon glyphicon-filter"></i> 批量处理
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a href="javascript:" class='quantity_process' data-status='1'>未找到</a></li>
        <li><a href="javascript:" class='quantity_process' data-status='2'>已找到</a></li>
    </ul>
</div>
@parent
<div class="btn-group">
    <a class="btn btn-info" href="{{ route('productRequire.getExcel') }}">
        获取excel
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-success" href="{{ route('productRequire.importByExcel') }}">
        excel导入
    </a>
</div>
@stop
@section('childJs')
<script type='text/javascript'>
    $(document).ready(function(){
        $('.process').click(function(){
            status = $(this).data('status');
            id = $(this).data('id');
            $.ajax({
                url:"{{ route('productRequire.ajaxProcess')}}",
                data:{status:status, id:id},
                dataType:'json',
                type:'get',
                success:function(result) {
                    location.reload();
                }
            })
        });

        $('.select_all').click(function(){
            if($(this).prop('checked') == true) {
                $.each($('.select_single'), function(){
                    $(this).prop('checked', true);
                })
            } else {
                $.each($('.select_single'), function(){
                    $(this).prop('checked', false);
                })
            }
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

        $('.quantity_process').click(function(){
            status = $(this).data('status');
            buf = new Array();
            i = 0;
            $.each($('.select_single:checked'), function(){
                buf[i] = $(this).parent().next().text();
                i++;
            });
            $.ajax({
                url:"{{ route('productRequire.ajaxQuantityProcess')}}",
                data:{buf:buf, status:status},
                dataType:'json',
                type:'get',
                success:function(result) {
                    location.reload();
                }
            })
        });
    })
</script>
@stop