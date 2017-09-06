@extends('layouts.default')
@section('content')

    <div class="row">
        @include('message.workflow.content')

    </div>
    @if($model->required)
        @foreach($model->replies as $reply)
            <div class="panel panel-info">
                <div class="panel-heading">
                    <strong>{{ $reply->title }}</strong><br/>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 pre-scrollable">
                            {!! nl2br($reply->content) !!}
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <small>
                        {{ $reply->created_at }} by <strong><u>{{ $model->assigner->name }}</u></strong>
                        from {{ '<'.$model->to.'>' }}
                    </small>
                </div>
            </div>
        @endforeach
    @else
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-warning">
                    此条信息被 <strong><u>{{ $model->assigner->name ? $model->assigner->name : ''}}</u></strong> 标注为无需回复
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">日志信息</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <strong>创建时间</strong>: {{ $model->created_at }}
                        </div>
                        <div class="col-lg-6">
                            <strong>更新时间</strong>: {{ $model->updated_at }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop