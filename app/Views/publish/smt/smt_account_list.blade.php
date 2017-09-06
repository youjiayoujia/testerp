@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th>帐号</th>
    <th>access_token</th>   
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $account_info)
        <tr>
            <td><input type='checkbox' name='single[]' class='single' value="{{$account_info->id}}"></td>
            <td>{{$account_info->alias}}</td>
            <td>{{$account_info->aliexpress_access_token}}</td>
            <td>
                <button type="button" class="dosome check  btn btn-success btn-sm  " data-id="{{$account_info->id}}">
                   <span class="glyphicon glyphicon-bell" aria-hidden="true"></span>token检测
               </button>

               <button type="button" class="dosome refresh  btn btn-primary btn-sm  " data-id="{{$account_info->id}}">
                   <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>刷新token
               </button>

               <a type="button" class=" reget btn btn-danger btn-sm" href="javascript:void(0)"  target="_blank" data-id="{{$account_info->id}}">
                   <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>重新授权
               </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <button type="button" class="btn btn-sm btn-danger" data-toggle="popover" data-placement="bottom" title="操作流程/注意事项" 
            data-content="先检测token,若提示信息为 Beyond the app call frequency limit,说明该账号当日API次数已经用完 需等明天回复后在使用.若提示信息为 Request need user authorized  点击刷新token.  提示信息为wrong refreshToken  点击重新授权. 以上操作后,仍无效,请联系IT  ">
                    注意事项
    </button>
@stop
@section('pageJs')
<link href="{{ asset('plugins/layer/skin/layer.css') }}" rel="stylesheet">
<script src="{{ asset('plugins/layer/layer.js') }}"></script>
<script type="text/javascript">
    $(function (){
   	 	layer.use('extend/layer.ext.js');
        $("[data-toggle='popover']").popover();
    });
    
    $(".dosome").click(function(){
        //var token_id = $(this).parent().parent().children().eq(0).children().eq(0).children().eq(0).val();
        var token_id = $(this).data('id');
        var type = '';
        var string ='';
        if($(this).hasClass("check"))
        {
            type='check';
            string="确认要检测token ?";
        }else if($(this).hasClass("refresh")){
            type='refresh';
            string="确认要刷新token ?";
        }else{
            alert("出错了！");
            return false;
        }
        if (confirm(string)) {
            $.ajax({
                url: "{{route('smtAccountManage.doAction')}}",
                data: 'token_id=' + token_id+'&type='+type,
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    layer.alert(data.info,2);
                    window.location.reload();
                }
            });
        }
    });

    $(".reget").click(function(){
   	 	var token_id = $(this).data('id');
        layer.prompt({title: '请输入code，并确认',type: 0}, function(pass, index, el) {
         if (pass.trim() == '') {
             layer.close(index);
             return false;
         }
         layer.close(index);
        
         $.ajax({
             url: "{{route('smtAccountManage.resetAuthorization')}}",
             data: 'code='+pass+'&token_id='+token_id,
             type: 'POST',
             dataType: 'JSON',
             success: function (data) {
                 layer.alert(data.info,2)
             }
         });
   		})
    });
</script>
@stop
