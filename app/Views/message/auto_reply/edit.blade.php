@extends('common.form')
@section('formAction') {{ route(request()->segment(1).'.update', ['id' => $model->id]) }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="create_by" value="{{request()->user()->id}}">
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
        <div class="form-group col-md-3">
            <label for="color">规则名称：</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" placeholder="中文自动回复规则描述" name='name' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-2">
            <label for="color">作用渠道：</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="channel_id" class="form-control">
                <option value=""> 请选择 </option>
                @foreach($channels as $channel)
                    <option value="{{$channel->id}}" {{$model->channel_id == $channel->id ? 'selected' : ''}}> {{$channel->name}} </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="color">开启状态：</label><small class="text-danger glyphicon glyphicon-asterisk"></small>

            <select name="status" class="form-control">
                <option value="OFF" {{$model->status=='OFF' ? 'selected' : ''}}> OFF </option>
                <option value="ON" {{$model->status=='ON' ? 'selected' : ''}}> ON </option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            <label for="color">客户消息同时包括:</label>
            <input class="form-control" placeholder="只允许添加必要的空格,多条用英文逗号隔开','" name='message_keywords' value="{{ old('message_keywords') ? old('message_keywords') : $model->message_keywords}}">
        </div>
    </div>
{{--    <div class="row">
        <div class="form-group col-md-12">
            <label for="color">客服消息同时包括:</label>
            <input class="form-control" placeholder="只允许添加必要的空格,多条用英文逗号隔开','" name='reply_keywords' value="{{ old('reply_keywords') ? old('reply_keywords') : $model->reply_keywords}}">
        </div>
    </div>--}}
    <div class="row">
        <div class="form-group col-md-12">
            <label for="color">主题中同时包括:</label>
            <input class="form-control" placeholder="只允许添加必要的空格,多条用英文逗号隔开','" name='label_keywords' value="{{ old('label_keywords') ? old('label_keywords') : $model->label_keywords}}">
        </div>
    </div>
{{--    <div class="row">
        <div class="form-group col-lg-1">
            <select name="type_time_filter" class="form-control">
                <option value="OFF" {{$model->type_time_filter=='OFF' ? 'selected' : ''}}> OFF </option>
                <option value="ON" {{$model->type_time_filter=='ON' ? 'selected' : ''}}> ON </option>
            </select>
        </div>
        <div class="col-lg-5">
            <code for="name" class="control-label">平邮发货时间区间</code>
        </div>
        <div class="col-lg-3">
            <input type="text" value="{{old('filter_start_time') ? old('filter_start_time') : $model->filter_start_time}}" class="form-control datetime_select" name="filter_start_time" placeholder="开始时间">
        </div>
        <div class="col-lg-3">
            <input type="text" value="{{old('filter_end_time') ? old('filter_start_time') : $model->filter_end_time}}" class="form-control datetime_select" name="filter_end_time" placeholder="结束时间">
        </div>
    </div>--}}
    <div class="row">
        <div class="form-group col-lg-1">
            <select name="type_shipping_one_month" class="form-control">
                <option value="OFF" {{$model->type_shipping_one_month=='OFF' ? 'selected' : ''}}> OFF </option>
                <option value="ON" {{$model->type_shipping_one_month=='ON' ? 'selected' : ''}}> ON </option>
            </select>
        </div>
        <div class="col-lg-10">
            <code for="name" class="control-label">SMT: 平邮已发货订单，据发货时间一个月之内</code>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-1">
            <select name="type_shipping_one_two_month" class="form-control">
                <option value="OFF" {{$model->type_shipping_one_two_month=='OFF' ? 'selected' : ''}}> OFF </option>
                <option value="ON" {{$model->type_shipping_one_two_month=='ON' ? 'selected' : ''}}> ON </option>
            </select>
        </div>
        <div class="col-lg-10">
            <code for="name" class="control-label">SMT：平邮已发货订单，发货时间1-2个月之间</code>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-1">
            <select name="type_shipping_fifty_day" class="form-control">
                <option value="OFF" {{$model->type_shipping_fifty_day=='OFF' ? 'selected' : ''}}> OFF </option>
                <option value="ON" {{$model->type_shipping_fifty_day=='ON' ? 'selected' : ''}}> ON </option>
            </select>
        </div>
        <div class="col-lg-10">
            <code for="name" class="control-label">Wish： 超过50天客户没有收到</code>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-1">
            <select name="type_within_tuotou" class="form-control">
                <option value="OFF" {{$model->type_within_tuotou=='OFF' ? 'selected' : ''}}> OFF </option>
                <option value="ON" {{$model->type_within_tuotou=='ON' ? 'selected' : ''}}> ON </option>
            </select>
        </div>
        <div class="col-lg-10">
            <code for="name" class="control-label">Wish：在平台预计妥投时间之内</code>
        </div>
    </div>

    <!--ebay-->
{{--    <div class="row">
        <div class="form-group col-lg-1">
            <select name="type_ebay_address" class="form-control">
                <option value="OFF" {{$model->type_ebay_address=='OFF' ? 'selected' : ''}}> OFF </option>
                <option value="ON" {{$model->type_ebay_address=='ON' ? 'selected' : ''}}> ON </option>
            </select>
        </div>
        <div class="col-lg-10">
            <code for="name" class="control-label">Ebay:客人要求更改地址的</code>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-1">
            <select name="type_ebay_color" class="form-control">
                <option value="OFF" {{$model->type_ebay_color=='OFF' ? 'selected' : ''}}> OFF </option>
                <option value="ON" {{$model->type_ebay_color=='ON' ? 'selected' : ''}}> ON </option>
            </select>
        </div>
        <div class="col-lg-10">
            <code for="name" class="control-label">Ebay:客人要求选颜色的</code>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-1">
            <select name="type_ebay_twenty_five_day" class="form-control">
                <option value="OFF" {{$model->type_ebay_twenty_five_day=='OFF' ? 'selected' : ''}}> OFF </option>
                <option value="ON" {{$model->type_ebay_twenty_five_day=='ON' ? 'selected' : ''}}> ON </option>
            </select>
        </div>
        <div class="col-lg-10">
            <code for="name" class="control-label">Ebay距离发货时间距离大于等于25天</code>
        </div>
    </div>--}}
    <!--ebay-->

    <div class="row">
        <div class="col-lg-10">
            <label class="control-label">消息模板</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
        </div>
    </div>
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
        <div class="col-lg-3">
            <div class="form-group">
                <select class="form-control" id="templates" onchange="changeTemplate($(this),'text');">
                    <option>请选择模版</option>
                </select>
            </div>
        </div>
        {{--                   <div class="col-lg-2" class="loadingDiv">
                                <img src="{{ asset('loading.gif') }}" width="30" />
                            </div>--}}
    </div>
    <div class="row">
        <div class="col-lg-12" id="templateContent">
            <div class="form-group">
                <textarea class="form-control"  id="textcontent"
                          rows="16" name="template" style="width:100%;height:400px;">{{ old('template') ? old('template') : $model->template }}</textarea>
            </div>
        </div>
    </div>



@stop

@section('pageJs')
    <script>
        $('.datetime_select').datetimepicker({theme: 'defalut'});

        function changeChildren(parent) {
            $('#children').html('');
            $('#children').append('<option>请选择类型</option>');
            $('#templates').html('');
            $('#templates').append('<option>请选择</option>');
            if (parent.val() != "") {
                $.post(
                    '{{ route('messageTemplateType.ajaxGetChildren') }}',
                    {id: parent.val()},
                    function (response) {
                        if (response != 'error') {
                            $.each(response, function (n, child) {
                                $('#children').append('<option value="' + child.id + '">' + child.name + '</option>');
                            });
                        }
                    }, 'json'
                );
            }
        }

        function changeTemplateType(type) {
            $('#templates').html('');
            $('#templates').append('<option>请选择</option>');
            $.post(
                '{{ route('messageTemplateType.ajaxGetTemplates') }}',
                {id: type.val()},
                function (response) {
                    if (response != 'error') {
                        $.each(response, function (n, template) {
                            $('#templates').append('<option value="' + template.id + '">' + template.name + '</option>');
                        });
                    }
                }, 'json'
            );
        }

        /**
         * type mail邮件 或者 text文本
         */
        function changeTemplate(template,type) {
            $.post(
                '{{ route('messageTemplate.ajaxGetTemplate') }}',
                {id: template.val()},
                function (response) {
                    if (response != 'error') {
                        //替换字符串
                        //response['content']=response['content'].replace("署名", assign_name);

                        if(type == 'email'){
                            editor.setContent(response['content']);
                        }else if(type == 'text'){
                            $('#textcontent').val(response['content']);
                        }
                        //记录回复邮件的类型
                        $('#tem_type').val(response.type_id);
                    }
                }, 'json'
            );
        }
    </script>
@stop