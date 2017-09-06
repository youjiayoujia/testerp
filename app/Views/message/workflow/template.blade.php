
<div class="row message-template" style="display: none;">
    @if($driver == 'wish')
            <div class="col-lg-12">

                @include('message.workflow.wish_order_detail')
            </div>
    @endif
    <div class="col-lg-8">
        @include('message.workflow.content')
        @include('message.workflow.reply')

    </div>
    <div class="col-lg-4">
        @include('message.workflow.operate')
        @if($message->related)
            @include('message.workflow.orders')
        @else
            @include('message.workflow.relate')
        @endif
    </div>
</div>