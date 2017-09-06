<div class="panel panel-info">
    <div class="panel-heading">
        <strong>描述：{{ str_limit($message->subject,150) }}</strong><br/>
        <small>
            {{ $message->date }} by <i>{{ $message->from_name }}</i> from {{ '<'.$message->from.'>' }}
        </small>
        To:{{$message->MessageAccountName}}
        <a href="javascript:" class="close" data-toggle="modal" data-target="#myModal">
            <small class="glyphicon glyphicon-list"></small>
        </a>


    </div>
	
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                    <?php echo $content; ?>
            </div>
        </div>
        @if(count($message->message_attanchments) > 0)
            <hr>
            @foreach($message->message_attanchments as $attanchment)
                <div class="row">
                    <div class="col-lg-12">
                        <strong>附件</strong>:
                        <a href="{{ $attanchment['filepath'] }}" target="_blank">{{ $attanchment['filename'] }}</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width:1000px;" role="document" style="width:1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">邮件历史</h4>
            </div>
            <div class="modal-body">

                @foreach($message->histories->take(5) as $history)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Message <a href="{{ url('message',$history->id)}}">#{{ $history->id }}</a></strong>

                            <br/>
                        </div>
                        <div class="panel-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <strong>{{ $history->subject }}</strong><br/>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="{{ route('message.content', ['id'=>$history->id]) }}"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <small>
                                        {{ $history->date }} by <strong>{{ $history->from_name }}</strong>
                                        from {{ '<'.$history->from.'>' }}
                                    </small>
                                </div>
                            </div>
                            @if($history->required)
                                @foreach($history->replies as $reply)
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <strong>{{ $reply->title }}</strong><br/>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 pre-scrollable">
                                                    {!! $reply->content !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <small>
                                                {{ $reply->created_at }} by
                                                <strong>{{ $history->assigner->name }}</strong>
                                                from {{ '<'.$history->to.'>' }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-warning">
                                            此条信息被 <strong><u>{{ $history->assigner->name }}</u></strong> 标注为无需回复
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>