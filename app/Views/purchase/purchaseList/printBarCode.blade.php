@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">条码号</div>
        <div class="panel-body">
            <div class="form-group col-lg-4">
                <strong>生成条码</strong>:
                {{$model->bar_code}}
            </div>
        </div>
    </div>
   
@stop
