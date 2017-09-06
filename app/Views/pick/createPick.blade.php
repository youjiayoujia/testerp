@extends('common.form')
@section('formAction') {{ $url }} @stop
@section('formBody')
    <table class='table table-bordered'>
        <tbody>
        <tr><td>该仓库目前共有可拣货包裹{{$count}}个。请选择需要拣货的包裹，生成拣货单，系统将自动分组成多张。</td></tr>
        </tbody>
    </table>
    <table class='table'>
        <tr>
            <td class='col-lg-1'>销售渠道</td>
            <td class='row'>
            <input type='checkbox' name='channel' class='channel_all' checked='true'>全部
            @foreach($channels as $channel)
                <input type='checkbox' name='channel[]' class='channel' value="{{$channel->id}}" checked='true'>{{$channel->name}}
            @endforeach
            </td>
        </tr>
        <tr>
            <td class='col-lg-1'>包裹类型</td>
            <td class='row'>
            <input type='checkbox' class='package_all' checked='true'>全部
            <input type='checkbox' name='package[]' class='package' value='SINGLE' checked='true'>单单
            <input type='checkbox' name='package[]' class='package' value='SINGLEMULTI' checked='true'>单多
            <input type='checkbox' name='package[]' class='package' value='MULTI' checked='true'>多多
            </td>
        </tr>
    </table>
    <div>
        <table class='table table-bordered table-striped'>
            <tr>
                <td>包裹数</td>
                <td>{{ $count }}</td>
            </tr>
            <tr>
                <td>单单/单多拣货数：</td>
                <td><input type='text' name='singletext' class='form-control' value='25'></td>
            </tr>
            <tr>
                <td>多多拣货数</td>
                <td><input type='text' name='multitext' class='form-control' value='20'></td>
            </tr>
        </table>
    </div>
    <div class='form-group'>
        <label for='logistics'>邮递方式</label>
        <div class='checkbox'>
            <label>
                <input type='checkbox' class='logistics_all' checked='true'>全部
            </label>
            <label>
                <input type='checkbox' class='logistics_opposite'>反选
            </label>
            <label>
                <input type='checkbox' class='mixed' name='mixed'>混合物流
            </label>
        </div>
    </div>
    @foreach($logisticses as $key => $logistics)
    <h4><font color='red'>面单尺寸:{{$logistics->first()->template ? $logistics->first()->template->size : '暂无尺寸'}}</font></h4>
        <div class='form-group'>
            <div class='checkbox'> 
                @foreach($logistics as $single)
                <label class='col-lg-3'>
                    <input type='checkbox' name='logistics[]' class='logistics' value="{{$single->id}}" checked='true'>{{$single->code}}
                </label>
                @endforeach
            </div>
        </div>
    <br/>
    @endforeach
@stop
@section('formButton')
    <input type="button" class="btn btn-success submit" value='生成拣货单'/>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.channel_all').click(function(){
        if($(this).prop('checked') == true)
            $('.channel').prop('checked', true);
        else
            $('.channel').prop('checked', false);
    });

    $('.package_all').click(function(){
        if($(this).prop('checked') == true)
            $('.package').prop('checked', true);
        else
            $('.package').prop('checked', false);
    });

    $('.logistics_all').click(function(){
        if($(this).prop('checked') == true)
            $('.logistics').prop('checked', true);
        else
            $('.logistics').prop('checked', false);
    });

    $('.logistics_opposite').click(function(){
        $('.logistics').each(function(){
            if($(this).prop('checked') == true)
                $(this).prop('checked', false);
            else
                $(this).prop('checked', true);
        })
    });

    $('.submit').click(function(){
        $(this).attr('disabled', true);
        $(this).val('正在生成拣货单');
        $('.thefatherform').submit();
    })
});
</script>
@stop