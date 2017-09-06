<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-19
 * Time: 14:00
 */
?>

@extends('common.table')

@section('tableHeader')

    <div class="modal fade " id="withdraw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">店铺分类更新</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($account as $key=>$ac)
                            <div class="form-group col-sm-6">
                                <div class="form-group col-sm-4">
                                    <label> {{$ac}}:</label>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label> <a class="btn btn-primary"
                                               onclick="updateStoreCategory({{$key}})">更新</a></label>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <th class="sort" data-field="id">ID</th>
    <th>站点</th>
    <th>仓库</th>
    <th>Erp分类</th>
    <th>对应信息</th>
    <th>操作</th>
@stop

@section('tableBody')
    @foreach($data as $v)
        <tr class="">
            <td>{{$v->id}}</td>
            <td>{{$v->ebaySite->site}}</td>
            <td>{{config('ebaysite.warehouse')[$v->warehouse]}}</td>
            <td>{{$v->erpCategory->name}}</td>
            <td></td>
            <td>
                <a href="{{ route('ebayStoreCategory.edit', ['id'=>$v->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $v->id }}"
                   data-url="{{ route('ebayStoreCategory.destroy', ['id' => $v->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>


            </td>
        </tr>
    @endforeach
@stop





@section('tableToolButtons')
    <div class="btn-group">
        <a class="btn btn-primary category-update " data-target="#withdraw"
           data-content="primary_category">更新店铺分类</a>
    </div>

    <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div>

@stop

@section('childJs')
    <script type="text/javascript">
        $('.category-update').click(function () {
            $('#withdraw').modal('show');
        });
        function updateStoreCategory(account_id) {
            $.ajax({
                url: "{{ route('ebayStoreCategory.ajaxUpdateStoreCategory') }}",
                data: {
                    account_id: account_id
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if(result==1){
                        alert('更新成功');
                    }else{
                        alert('更新失败');

                    }


                }
            });

        }

    </script>

@stop


