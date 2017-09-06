@extends('common.form')
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-2">
        <label class='control-label'>追踪号/包裹id</label>
        <input type='text' class="form-control buf" placeholder="追踪号/包裹id">
    </div>
    <div class="form-group col-lg-4">
        <label class='control-label'>包裹类型</label>
        <textarea class="form-control holder"></textarea>
    </div>
</div>
<a href='javascript:' class='btn btn-info btn-lg export'>导出</a>
<table class="table table-bordered table-striped table-hover sortable">
    <thead>
    <tr>
        <th><input type='checkbox' class='all'/></th>
        <th>包裹id</th>
        <th>产品名称</th>
        <th>追踪号</th>
        <th>sku</th>
        <th>库位</th>
        <th>包裹类型</th>
    </tr>
    </thead>
    <tbody class='trIn'>
    </tbody>
</table>
@stop
@section('pageJs')
    <script type="text/javascript">
    $(document).ready(function(){
        $(document).on('keypress', function(event){
            if(event.keyCode == '13') {
                buf = $('.buf').val();
                if(buf) {
                    $.get(
                        "{{ route('package.ajaxReturnInShelf')}}",
                        {buf,buf},
                        function(result) {
                            if(result == 'false') {
                                $('.buf').val('');
                                $('.holder').val('');
                                alert('找不到包裹');
                                return false;
                            }
                            $('.trIn').prepend(result[0]);
                            $('.holder').val(result[1]);
                            $('.buf').val('');
                        }
                    );
                }
                return false;
            }
        })

        $(document).on('click', '.all', function(){
            if($(this).prop('checked') == true) {
                $.each($('.single'), function(){
                    $(this).prop('checked', true);
                })
            } else {
                $.each($('.single'), function(){
                    $(this).prop('checked', false);
                })
            }
        })

        $(document).on('click', '.export', function(){
            arr = new Array();
            i = 0;
            $.each($('.single'), function(){
                if($(this).prop('checked') == true) {
                    arr[i] = $(this).parent().next().text();
                }
                i++;
            })
            location.href="{{ route('package.exportInfo') }}?arr=" + arr;
        })
    });
    </script>
@stop
@section('formButton')@stop