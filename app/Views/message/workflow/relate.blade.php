
<div class="panel panel-danger">
    <div class="panel-heading"><p class="glyphicon glyphicon-tag"></p>手动关联订单
    </div>
    <div class="panel-body">
        <p>ERP系统中没找到此消息关联的订单 /(ㄒoㄒ)/~~</p>
{{--        <form action="{{ route('message.setRelatedOrders', ['id'=>$message->id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row form-group">
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="numbers" placeholder="填写订单号,多个用英文逗号分隔"/>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-danger">
                        <span class="glyphicon glyphicon-link"></span> 关联订单
                    </button>
                </div>
                <div class="col-lg-6 text-right">
                    <button class="btn btn-warning" type="button" onclick="if(confirm('确认无需关联订单?')){location.href='{{ route('message.notRelatedOrder', ['id'=>$message->id]) }}'}">
                        <span class="glyphicon glyphicon-minus-sign"></span> 无需关联订单
                    </button>
                </div>
            </div>
        </form>--}}
    </div>
</div>