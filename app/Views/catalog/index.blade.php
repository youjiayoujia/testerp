@extends('common.table')
@section('tableToolButtons')


        <div class="btn-group">
            <form method="POST" action="{{ route('addLotsOfCatalogs') }}" enctype="multipart/form-data" id="add-lots-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" class="file" id="qualifications" placeholder="上传审核资料" name="excel" value="">

            </form>

        </div>
    <div class="btn-group">
        <a href="javascript:" class="btn btn-warning download-csv">Excel格式
            <i class="glyphicon glyphicon-arrow-down"></i>

        </a>
        <a class="btn btn-success add-lots-of-catagory" href="javascript:void(0);">
            <i class="glyphicon glyphicon-plus"></i> 批量导入品类
        </a>
    </div>
    <div class="btn-group" role="group">
        <div class="form-group" style="margin-bottom:0px">
            <select id="ms" class="js-example-basic-multiple" multiple="multiple" name="select_channel" style="width:200px">
                @foreach($channels as $channel)
                    <option value="{{$channel->id}}" class='aa'>{{$channel->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="export" data-status="pass" data-name="审核通过">导出Excel</a></li>
            <li><a href="javascript:" id="do_edit" data-status="notpass" data-name="审核不通过">编辑税率</a></li>
        </ul>
    </div>

    <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'> 全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>分类</th>
    <th>名称</th>
    @foreach($channels as $channel)
        <th>{{$channel->name}}</th>
    @endforeach
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $catalog)
        <tr>

            <td>
                <input type="checkbox" name="item_catalog[]" value="{{$catalog->id}}" class="item_catalog">
            </td>

            <td>{{ $catalog->id }}</td>
            <td>{{ $catalog->CatalogCategoryName }}</td>
            <td>{{ $catalog->all_name }}</td>
            @foreach($channels as $channel)
                <?php $rate =''; ?>
                    @foreach($catalog->channels as $itemchannel)
                    @if($itemchannel->id == $channel->id)
                        <?php $rate =  Tool::getPercent($itemchannel->pivot->rate); ?>
                    @endif
                @endforeach
                    <td>{{$rate}}</td>
            @endforeach
            <td>
                <a href="{{ route('catalog.show', ['id'=>$catalog->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看
                </a>
                <a href="{{ route('catalog.edit', ['id'=>$catalog->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $catalog->id }}"
                   data-url="{{ route('catalog.destroy', ['id' => $catalog->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach

    <link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
    <script src="{{ asset('js/multiple-select.js') }}"></script>
    <script>
        $(document).ready(function () {
            //批量导出
            $('.export').click(function () {
                var filter = false;
                filter = getFilterInfo();
                if(filter != false){
                    location.href = "{{ route('catalog.exportCatalogRates') }}?filter=" + filter;
                }
            });
            $("#do_edit").click(function () {
                var filter = false;
                filter = getFilterInfo();
                if(filter != false){
                    location.href = "{{ route('catalog.editCatalogRates') }}?filter=" + filter;
                }
            });

            $('.add-lots-of-catagory').click(function () {
                addlotsOfCatagory();
            });

            $('.download-csv').click(function(){
                location.href="{{ route('catalogCsvFormat')}}";
            });
        });

        function addlotsOfCatagory() {
            var csv = $('input[name="excel"]').val();
            if(csv == ''){
                alert('请先上传excel文件！');
                return false;
            }
            $('#add-lots-form').submit();
        }

        function getFilterInfo(){
            catalog_ids = new Array();
            var channel_ids = new Array();
            var i = 0;
            var j = 0;
            $.each($('.item_catalog:checked'), function () {
                catalog_ids[i] = $(this).val();
                i++;
            })
            $("#ms option:selected").each(function () {
                channel_ids[j] = $(this).val();
                j++;
            });

            if(channel_ids.length){
                channel_ids = channel_ids.join(',');
            }else{
                alert('请选择平台');
                return false;
            }

            if (catalog_ids.length) {
                catalog_ids = catalog_ids.join(',');
            } else {
                alert('请选择分类');
                return false;
            }
            return catalog_ids+'|'+channel_ids;
        }
        $('.select_all').click(function () {
            if ($(this).prop('checked') == true) {
                $('.item_catalog').prop('checked', true);
            } else {
                $('.item_catalog').prop('checked', false);
            }
        });

        //批量select插件
        $(".js-example-basic-multiple").select2();
    </script>
@stop
