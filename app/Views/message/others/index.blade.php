@extends('layouts.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"> 速卖通批量留言（订单留言）</div>
        <div class="panel-body">
            <form action="{{route('doSendAliexpressMessages')}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        上传文件:
                        <a href="{{route('aliexpressCsvFormat')}}" class=" download-csv">Excel格式
                            <i class="glyphicon glyphicon-arrow-down"></i>
                        </a>
                    </div>
                    <div class="panel-body">
                        <input type="file" class="file" placeholder="excel表格" name="excel" value="">
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        回复内容：
                    </div>
                    <div class="panel-body">
                        <textarea class="form-control" rows="10" name="comments"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">提交</button>
                <button type="button" class="btn btn-default">取消</button>
            </form>
        </div>
    </div>
@stop