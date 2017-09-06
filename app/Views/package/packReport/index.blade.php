@extends('common.detail')
@section('detailBody')
<div class='row'>
    <div class='form-group col-lg-3'>
        <input id="expected_date" class='form-control' name='expected_date' type="text" placeholder='日期查询' value="{{ isset($daytime) ? $daytime : '' }}">
    </div>
    <button type='button' class='btn btn-info search'>查找</button>
</div>
<div class='row'>
    <div class='form-group col-lg-1'>
        <button class="btn btn-primary btn-lg download">
            数据下载
        </button>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">包装排行榜<a href="{{ route('packReport.createData')}}">生成数据</a></div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>ID</td>
                <td>姓名</td>
                <td>仓库</td>
                <td>昨天发货数</td>
                <td>今天发货数</td>
                <td>当月发货数(各类型订单数量)</td>
                <td>总工时</td>
                <td>当月平均发货数</td>
                <td>当月发错货数</td>
                <td>时间</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody>
            @if(count($data))
                @foreach($data->groupBy('user_id') as $userId => $block)
                <tr>
                    <td>{{ $block->first()->id }}</td>
                    <td data-id="{{$userId}}">{{$block->first()->user ? $block->first()->user->name : ''}}</td>
                    <td>{{$block->first()->warehouse ? $block->first()->warehouse->name : ''}}</td>
                    <td>{{$block->sum('yesterday_send') }}</td>
                    <td>{{$block->sum('single') + $block->sum('singleMulti') + $block->sum('multi')}}</td>
                    <td>{{count($monthModel) ? ($monthModel->get($userId)->sum('single') + $monthModel->get($userId)->sum('singleMulti') + $monthModel->get($userId)->sum('multi')) : 0}}
                    (
                        单单:{{ count($monthModel) ? $monthModel->get($userId)->sum('single') : 0}},
                        单多:{{ count($monthModel) ? $monthModel->get($userId)->sum('singleMulti') : 0}},
                        多多:{{ count($monthModel) ? $monthModel->get($userId)->sum('multi') : 0}}
                    )
                    </td>
                    <td>{{ count($monthModel) ? $monthModel->get($userId)->first()->all_worktime : 0 }}</td>
                    <td>{{ count($monthModel) ? floor(($monthModel->get($userId)->sum('single') + $monthModel->get($userId)->sum('singleMulti') + $monthModel->get($userId)->sum('multi'))/ceil((strtotime('now') - strtotime(date('Y-m', strtotime('now'))))/(strtotime('+1 day') - strtotime('now')) )) : 0}}</td>
                    <td>{{ count($monthModel) ? $monthModel->get($userId)->first()->error_send : 0}}</td>
                    <td>{{ date('Y-m', strtotime($block->first()->day_time))}}</td>
                    <td>
                        <button class="btn btn-primary btn-lg change"
                                data-toggle="modal"
                                data-target="#all">
                                <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                    </td>
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
                                <input class='form-control time' type="text" placeholder='总工时'>
                            </div>
                            <div class='form-group col-lg-3'>
                                <input class='form-control quantity' type="text" placeholder='总发错货数'>
                            </div>
                            <div class='form-group col-lg-3'>
                                <button type='button' class='btn btn-success confirm'>确认</button>
                            </div>
                            <input type='hidden' class='date'>
                            <input type='hidden' class='userid'>
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
    $('#expected_date').cxCalendar();

    $(document).on('click', '.confirm', function(){
        time = $('.time').val();
        quantity = $('.quantity').val();
        date = $('.date').val();
        userid = $('.userid').val();
        if(time && quantity) {
            $.get(
                "{{ route('packReport.changeData')}}",
                {date:date, time:time, quantity:quantity, userid:userid},
                function(result){
                    if(result == 'false') {
                        alert('id有误');
                        return false;
                    }
                    location.reload();
                }
                )
        } else {
            alert('请填写完整');
        }
        $('.change').click();
    });

    $(document).on('click', '.change', function(){
        time = $(this).parent().prev().text();
        userid = $(this).parent().parent().find('td:eq(1)').data('id');
        $('.date').val(time);
        $('.userid').val(userid);
    });

    $(document).on('click', '.search', function(){
        time = $('#expected_date').val();
        location.href = "{{ route('packReport.index')}}/?report=" + time;
    })

    $(document).on('click', '.download', function(){
        date = $('#expected_date').val();
        location.href="{{ route('packReport.download')}}/?date=" + date;
    })
})
</script>