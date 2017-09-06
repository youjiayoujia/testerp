@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="panel panel-primary">
        <div class="panel-heading"><strong>回复:</strong></div>
        <div class="panel-body">
            <form action="{{ route('message.reply', ['id'=>$message->id]) }}" method="POST" class="reply-content" ;>
                {!! csrf_field() !!}
                <input type="hidden" name="id" value="{{$message->id}}" type="hidden">

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <select class="form-control" onchange="changeChildren($(this));">
                                <option>请选择一级类型</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <select class="form-control" id="children" onchange="changeTemplateType($(this));">
                                <option>请选择类型</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            @if($message->drives == 'amazon')
                                <select class="form-control" id="templates" onchange="changeTemplate($(this),'email');">
                            @else
                                <select class="form-control" id="templates" onchange="changeTemplate($(this),'text');">
                             @endif
                                <option>请选择模版</option>
                            </select>
                        </div>
                    </div>
{{--                   <div class="col-lg-2" class="loadingDiv">
                        <img src="{{ asset('loading.gif') }}" width="30" />
                    </div>--}}
                </div>
                <div class="row" style="display: none;">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" class="form-control" name="to" value="{{ $message->from_name }}"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" id="to_email" class="form-control" name="to_email"
                                   value="{{ $message->from }}"/>
                        </div>
                    </div>
                </div>
                <div class="row" style="display: none;">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" value="Re: {{ $message->subject }}"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="templateContent">
                        <div class="form-group">

                            <textarea class="form-control"  id="textcontent"
                             rows="16" name="content" style="width:100%;height:400px;"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            @if($is_ali_msg_option)
                                <div class="col-lg-4">
                                    <div class="row">

                                    @if($message->Order)
                                        @if($message->Order->status == 'REVIEW')
                                                <input type="hidden" id="do-chaeck" class="is-need-operate-order" value="true">
                                                <input type="hidden" name="order-id" id="order-id" class="option-order-id" value="{{$is_ali_msg_option}}">

                                                <small class="text-danger glyphicon glyphicon-asterisk"></small><label>订单操作</label>

                                            <button type="button" id="do-review-order" class="btn btn-success btn-xs">审核</button>
                                            <button type="button" class="btn btn-danger btn-xs" data-target="#withdrawOrder" data-toggle="modal" >撤单</button>

                                            <div class="modal fade" id="withdrawOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="POST">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                <h4 class="modal-title" id="myModalLabel">撤单</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="form-group col-lg-6">
                                                                        <label for="withdraw" class='control-label'>撤单原因</label>
                                                                        <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                                                        <select class="form-control" name="withdraw" id="withdraw">
                                                                            <option value="NULL">==选择原因==</option>
                                                                            @foreach(config('order.withdraw') as $withdraw_key => $withdraw)
                                                                                <option value="{{ $withdraw_key }}">
                                                                                    {{ $withdraw }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-lg-6">
                                                                        <label for="withdraw_reason" class='control-label'>原因</label>
                                                                        <textarea class="form-control" rows="3" name='withdraw_reason' id="withdraw_reason"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                                                <button type="button" class="btn btn-primary" id="do-withdraw-order">提交</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        @endif
                                    @endif
                                    </div>
                                    <div class="row">
                                        <button id="save" type="button" class="btn btn-primary from-submit">回复</button>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" id="do-chaeck" class="is-need-operate-order" value="false">
                                <button id="save" type="button" class="btn btn-primary from-submit">回复</button>
                            @endif

                            @if($driver == 'wish') <!--wish消息特殊操作-->
                                <a class="btn btn-warning " onclick="wishSupportReplay({{$message->id}})" >Apeal To Wish Support</a>
                            @endif
                            @if($driver == 'aliexpress')
                                <div style="float: right;">
                                <?php
                                    for ($i = 0; $i < 54; $i++) {
                                        echo '<img class="aliimg" id="ali_' . str_pad($i, 3, "0", STR_PAD_LEFT) . '" onClick="setImg(this.id)" width="20" src="http://i02.i.aliimg.com/wimg/feedback/emotions/' . $i . '.gif" />&nbsp;';
                                        if (($i + 1) % 9 == 0) echo '<br />';
                                    }
                                ?>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <input type="hidden" id="tem_type" name="type_id">
            </form>
        </div>
    </div>
<script>

</script>
