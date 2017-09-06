@extends('common.detail')
@section('detailBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID</label>
            <input type='text' class='form-control' value={{ $model->picknum }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>类型</label>
            <input type='text' class='form-control' value={{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>创建人</label>
            <input type='text' class='form-control' value={{ $model->pickByName ? $model->pickByName->name : '' }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>创建时间</label>
            <input type='text' class='form-control' value={{ $model->pick_at }}>
        </div>
    </div>
    <table class='table table-striped'>
        <tbody>
            <tr>
                <td>包裹总数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'all']) }}">{{ $model->package()->withTrashed()->count() }}</a></td>
                <td>异常包裹数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'ERROR']) }}">{{ $model->package()->withTrashed()->where('status', 'ERROR')->count() }}</a></td>
            </tr>
            <tr>
                <td>未包装总数:</td><td>{{ $model->package()->withTrashed()->whereIn('status', ['PICKING', 'NEW'])->count() }} <button type='button' href="javascript:" class='btn btn-info print' data-id="{{ $model->id }}" data-user="{{$user}}">打印</button></td>
                <td>已打印(拣货中)总数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'PICKING']) }}">{{ $model->package()->withTrashed()->where('status', 'PICKING')->count() }}</a></td>
            </tr>
            <tr>
                <td>已包装总数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'PACKED']) }}">{{ $model->package()->withTrashed()->where('status', 'PACKED')->count() }}</a></td>
                <td>已发货总数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'SHIPPED']) }}">{{ $model->package()->withTrashed()->where('status', 'SHIPPED')->count() }}</a></td>
            </tr>
        </tbody>
    </table>
    <div class='row col-lg-3'>
        <h3>打印日记：(最新5条)</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>打印人</th>
                <th>打印时间</th>
            </tr>
            </thead>
            @foreach($five as $single)
                <tr>
                    <td>{{ $single->user ? $single->user->name : '' }}</td>
                    <td>{{ $single->created_at }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <iframe src='' id='iframe_print' style='display:none'></iframe>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.print', function () {
        id = $(this).data('id');
        user = $(this).data('user');
        $.get(
                "{{ route('pickList.printInfo')}}",
                {user:user, id:id},
                function(result){
                }
            );
        src = "{{ route('pickList.print', ['id'=>'']) }}/" + id;
        $('#iframe_print').attr('src', src);
        $('#iframe_print').load(function () {
            $('#iframe_print')[0].contentWindow.focus();
            $('#iframe_print')[0].contentWindow.print();
        });
    });
})
</script>