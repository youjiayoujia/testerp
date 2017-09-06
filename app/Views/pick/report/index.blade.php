@extends('common.detail')
@section('detailBody')
<p><font color='red' size='5px'>今日拣货数:@if(count($data)){{ $data->sum('today_pick')}}@endif个&nbsp;&nbsp;&nbsp;&nbsp;今日拣货漏检总数:@if(count($data)){{ $data->sum('missing_pick')}}@endif个&nbsp;&nbsp;&nbsp;&nbsp;本月拣货完成总数: @if(count($data)){{ $data->sum('single') + $data->sum('singleMulti') + $data->sum('multi') }}@endif个&nbsp;&nbsp;&nbsp;&nbsp;本月拣货漏检总数: @if(count($data)){{ $data->filter(function($query){ return strtotime($query->day_time) > strtotime(date('Y-m', strtotime('now'))) &&  strtotime($query->day_time) < strtotime(date('Y-m', strtotime('+1 month'))); })->sum('missing_pick')}}@endif个</font></p>
<div class='row'>
    <div class='form-group col-lg-3'>
        <select name='warehouse_id' class='warehouse_id form-control'>
            <option value=''>仓库</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id}}" {{ isset($warehouseid) ? ($warehouseid == $warehouse->id ? 'selected' : '') : ''}}>{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group col-lg-3'>
        <input id="expected_date" class='form-control' name='expected_date' type="text" placeholder='日期查询' value="{{ isset($date) ? $date : ''}}">
    </div>
    <button type='button' class='btn btn-info search'>查找</button>
</div>
<div class='row'>
    <div class='form-group col-lg-1'>
        <button class="btn btn-primary btn-lg assign"
                data-toggle="modal"
                data-target="#all">
            拣货单标记拣货
        </button>
    </div>
    <div class='form-group col-lg-1'>
        <button class="btn btn-primary btn-lg change"
                data-toggle="modal"
                data-target="#all">
            拣货单产量转移
        </button>
    </div>
    <div class='form-group col-lg-1'>
        <button class="btn btn-primary btn-lg download">
            数据下载
        </button>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">拣货排行榜<a href="{{ route('pickReport.createData')}}">生成数据</a></div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>拣货人员</td>
                <td>拣货组</td>
                <td>今日已分配未完成:已分配(拣货单)</td>
                <td>超过24小时未完成拣货单</td>
                <td>本月总拣货完成数(各类型sku数)</td>
                <td>漏检sku数</td>
                <td>今日sku拣货数</td>
            </tr>
            </thead>
            <tbody>
            @if(count($data))
                @foreach($data->groupBy('user_id') as $userId => $block)
                <tr>
                    <td>{{$block->first()->user ? $block->first()->user->name : ''}}</td>
                    <td>{{$block->first()->warehouse ? $block->first()->warehouse->name : ''}}</td>
                    <td>
                        <a href="javascript:" data-userid="{{$userId}}" class='pick_undone'>{{$block->sum('today_picklist_undone')}}</a>:
                        <a href="javascript:" data-userid="{{$userId}}" class='pick'>{{$block->sum('today_picklist')}}</a>
                    </td>
                    <td><a href="javascript:" data-userid="{{$userId}}" class='twenty'>{{$block->sum('more_than_twenty_four')}}</a></td>
                    <td>{{count($monthModel) ? ($monthModel->get($userId)->sum('single') + $monthModel->get($userId)->sum('singleMulti') + $monthModel->get($userId)->sum('multi')) : ''}}
                    (单单:{{ count($monthModel) ? $monthModel->get($userId)->sum('single') : ''}},
                     单多:{{ count($monthModel) ? $monthModel->get($userId)->sum('singleMulti') : ''}},
                     多多:{{ count($monthModel) ? $monthModel->get($userId)->sum('multi') : ''}})
                    </td>
                    <td>{{count($monthModel) ? $monthModel->get($userId)->sum('missing_pick') : ''}}
                    ({{count($monthModel) ? 
                        ((($monthModel->get($userId)->sum('single') + $monthModel->get($userId)->sum('singleMulti') + $monthModel->get($userId)->sum('multi')) ? 
                                                round($monthModel->get($userId)->sum('missing_pick')/($monthModel->get($userId)->sum('single') + $monthModel->get($userId)->sum('singleMulti') + $monthModel->get($userId)->sum('multi'))*100,2) : 0))
                        : ''}}%)
                    </td>
                    <td>{{$block->sum('today_pick')}}</td>
                </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="all" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">拣货单信息修改</div>
                    <div class="panel-body">
                        <div class='row'>
                            <div class='form-group col-lg-3'>
                                <input class='form-control picklist_id' name='picklist_id' type="text" placeholder='拣货单号'>
                            </div>
                            <div class='form-group col-lg-3'>
                                <input class='form-control pick_by' name='pick_by' type="text" placeholder='拣货人员'>
                            </div>
                            <div class='form-group col-lg-3'>
                                <input class='fix' name='fix' type="radio">固定拣货人员
                            </div>
                            <div class='form-group col-lg-3'>
                                <button type='button' class='btn btn-success confirm'>确认</button>
                            </div>
                            <input type='hidden' class='buf'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.assign', function(){
        if($('.fix').prop('checked') == false) {
            pickBy = $('.pick_by').val('');
        }
        picklist = $('.picklist_id').val('');
        $('.buf').val(1);
    });

    $(document).on('click', '.change', function(){
        if($('.fix').prop('checked') == false) {
            pickBy = $('.pick_by').val('');
        }
        picklist = $('.picklist_id').val('');
        $('.buf').val(2);
    });

    $(document).on('click', '.confirm', function(){
        id = $('.buf').val();
        picklist = $('.picklist_id').val();
        pickBy = $('.pick_by').val();
        if(picklist && pickBy) {
            $.get(
                "{{route('pickList.changePickBy')}}",
                    {picklist:picklist, pickBy:pickBy, id:id},
                    function(result){
                        if(result == 'false') {
                            alert('拣货单号不存在')
                        }
                    }
                )
        } else {
            alert('拣货单号或拣货人员信息不全');
        }
        $('.assign').click();
    });

    $(document).on('keypress', function(event){
        if(event.keyCode == '13') {
            if($('.picklist_id').is(':focus')) {
                if($('.fix').prop('checked') == true) {
                    $('.confirm').click();
                }
                return true;
            }
            if($('.pick_by').is(':focus')) {
                if($('.picklist_id').val()) {
                    $('.confirm').click();
                }
                return true;
            }
        }
    })

    $(document).on('click', '.pick', function(){
        id = $(this).data('userid');
        location.href="{{ route('pickList.index')}}/?checkid=" + id;
    });

    $(document).on('click', '.pick_undone', function(){
        id = $(this).data('userid');
        location.href="{{ route('pickList.index')}}/?flag=undone&checkid=" + id;
    });

    $(document).on('click', '.twenty', function(){
        id = $(this).data('userid');
        location.href="{{ route('pickList.index')}}/?twenty=undone&checkid=" + id;
    });

    $('#expected_date').cxCalendar();

    $(document).on('click', '.search', function(){
        date = $('#expected_date').val();
        warehouseid = $('.warehouse_id').val();
        location.href="{{ route('pickReport.index')}}/?date=" + date + "&warehouseid=" + warehouseid;
    });

    $(document).on('click', '.download', function(){
        date = $('#expected_date').val();
        warehouseid = $('.warehouse_id').val();
        location.href="{{ route('pickReport.download')}}/?date=" + date + "&warehouseid=" + warehouseid;
    })
})
</script>