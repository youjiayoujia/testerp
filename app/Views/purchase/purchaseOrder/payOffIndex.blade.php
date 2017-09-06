@extends('common.detail')
@section('detailBody')
    <form action="{{ route('purchaseOrder.excelPayOffExecute') }}" method="post" enctype="multipart/form-data">
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
         <input type="file" name="upload" >
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary" style="float:left;margin-left:-15px">
               批量付款
            </button>
         </div>
     </form>

    <a href="javascript:" class="btn btn-warning download-csv">CSV格式
        <i class="glyphicon glyphicon-arrow-down"></i>
    </a>
    <div class="panel panel-default">
        <div class="panel-heading">操作结果:</div>
        <div class="panel-body"> 
                <table class="gridtable" align="center" valign="center" id="fenye">
                    <tr>
                        <th width=600>采购单号</th>
                        <th width=600>付款状态</th>
                    </tr>
                    @if(count($data))
                            
                        @foreach($data as $_data)
                            <tr class="test" style="display: none;">
                                <td width=600>{{$_data['id']}}</td>
                                <td width=600>@if($_data['status']==1)<span style="color:green">成功</span>
                                    @elseif($_data['status']==0)<span style="color:red">失败,采购单号不存在</span>
                                    @else<span style="color:red">失败,采购单已经付款</span>
                                    @endif</td>
                            </tr>   
                        @endforeach
                    @endif    
                </table>
            
        </div> 
    </div>

    <div id="changpage" style="text-align:center">

    </div>

<style type="text/css">
    table.gridtable {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }
    table.gridtable th {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }
    table.gridtable td {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
    }
</style>
@stop

@section('pageJs')
    <script>
        $(document).ready(function () {
            //批量导出模板
            $('.download-csv').click(function(){
                location.href="{{ route('purchaseOrderPayOffCsvFormat')}}";
            });
        })

        var obj,j;
        var page=0;
        var nowPage=0;//当前页
        var listNum=20;//每页显示<ul>数
        var PagesLen;//总页数
        var PageNum=7;//分页链接接数(5个)
        onload=function(){
        obj=document.getElementById("fenye").getElementsByClassName("test");
        j=obj.length
        
        PagesLen=Math.ceil(j/listNum);
        upPage(0)
        }
        function upPage(p){
        nowPage=p
        //内容变换
        for (var i=0;i<j;i++){
        obj[i].style.display="none"
        }
        for (var i=p*listNum;i<(p+1)*listNum;i++){
        if(obj[i])obj[i].style.display="table-row"
        }
        //分页链接变换
        strS='<a href="###" onclick="upPage(0)">首页</a>  '
        var PageNum_2=PageNum%2==0?Math.ceil(PageNum/2)+1:Math.ceil(PageNum/2)
        var PageNum_3=PageNum%2==0?Math.ceil(PageNum/2):Math.ceil(PageNum/2)+1
        var strC="",startPage,endPage;
        if (PageNum>=PagesLen) {startPage=0;endPage=PagesLen-1}
        else if (nowPage<PageNum_2){startPage=0;endPage=PagesLen-1>PageNum?PageNum:PagesLen-1}//首页
        else {startPage=nowPage+PageNum_3>=PagesLen?PagesLen-PageNum-1: nowPage-PageNum_2+1;var t=startPage+PageNum;endPage=t>PagesLen?PagesLen-1:t}
        for (var i=startPage;i<=endPage;i++){
         if (i==nowPage)strC+='<a href="###" style="color:red;font-weight:700;" onclick="upPage('+i+')">'+(i+1)+'</a> '
         else strC+='<a href="###" onclick="upPage('+i+')">'+(i+1)+'</a> '
        }
        strE=' <a href="###" onclick="upPage('+(PagesLen-1)+')">尾页</a>  '
        strE2=nowPage+1+"/"+PagesLen+"页"+"  共"+j+"条"
        strE3 = "，成功"+{{$success}}+"条，失败<span style='color:red;font-weight:700;'>"+{{$fail}}+"</span>条"
            document.getElementById("changpage").innerHTML=strS+strC+strE+strE2+strE3
        }
    </script>
@stop