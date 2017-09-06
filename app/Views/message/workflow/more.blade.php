<div class="row"
     @if(request()->session()->get('workflow')=='keeping')
     style="display: none;"
     @endif
     id="more">
    <div class="col-lg-8">
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>操作</strong></div>
            <div class="panel-body">
                <div class="row form-group">
                    <div class="col-lg-6">
                        <form action="" method="POST">
                            {!! csrf_field() !!}
                            <div class="input-group">
                                <select class="form-control customer-id" name="assign_id" style="width: 160px;">
                                    <option value="">请选择客服</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <span>
                                <button class="btn btn-success option-group" do="other-customer" type="button">
                                 <span class="glyphicon glyphicon-random"></span> 转交
                                </button>
                              </span>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6 text-right">
                        <button class="btn btn-warning option-group" do="no-reply" type="button">
                            <span class="glyphicon glyphicon-minus-sign"></span> 无需回复
                        </button>
                        @if(request()->session()->get('workflow')=='keeping')
                            <button class="btn btn-warning option-group" do="next-time" type="button">
                                <span class="glyphicon glyphicon-minus-sign"></span> 跳到下一封
                            </button>
                        @endif
                    </div>
                </div>
                <script type="text/javascript">
                    function setImg(id) {
                        var value = $('#textcontent').val();
                        $('#textcontent').val(value + " /:" + id.replace('ali_', ''));
                    }
                </script>
                @if(request()->session()->get('workflow')=='keeping')
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-danger option-group" do="workflow-stop" type="button">
                            <span class="glyphicon glyphicon-minus-sign"></span> 终止工作流
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>