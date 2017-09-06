<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-19
 * Time: 15:12
 */
?>
@extends('common.form')
@section('formAction') {{ route('ebayStoreCategory.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">Erp分类：</label>
        </div>
        <div class="form-group col-sm-6">
            <select class="select_select0 col-sm-4" name="category">
                @foreach($erpCategory as $key=> $name)
                    <option value="{{$key}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">站点：</label>
        </div>
        <div class="form-group col-sm-6">
            <select class="select_select0 col-sm-4" name="site" id="site">
                @foreach(config('ebaysite.site_name_id') as $name=>$id)
                    <option value="{{$id}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right">仓库：</label>
        </div>
        <div class="form-group col-sm-6">
            <select class="select_select0 col-sm-4" name="warehouse">
                @foreach(config('ebaysite.warehouse') as $key=>$name)
                    <option value="{{$key}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-1">
            <label for="subject" class="right"></label>
        </div>

        <div class="form-group col-sm-6">
            <label class="col-sm-6">Ebay店铺分类</label>
            <label class="col-sm-3">描述模板</label>

        </div>
    </div>
    @foreach($account as $key=> $ac)
        <div class="row">
            <div class="form-group col-sm-1">
                <label for="subject" class="right">{{$ac}}：</label>
            </div>
            <div class="form-group col-sm-6">
                <select class="select_select0 col-sm-6 category" name="category_description[{{$key}}][store_category]">
                    <option value=""></option>
                    @if(isset($account_category[$key]['root']))
                        @foreach($account_category[$key]['root'] as $v )
                            <option value="{{$v['store_category']}}"@if(isset($account_category[$key]['child'][$v['store_category']])){{'disabled'}} @endif>{{$v['store_category_name']}}</option>
                            @if(isset($account_category[$key]['child'][$v['store_category']]))
                                @foreach($account_category[$key]['child'][$v['store_category']] as $v1 )
                                    <option value="{{$v1['store_category']}}" @if(isset($account_category[$key]['child'][$v1['store_category']])) {{'disabled'}}@endif>{{'&nbsp; &nbsp; |-'.$v1['store_category_name']}}</option>
                                    @if(isset($account_category[$key]['child'][$v1['store_category']]))
                                        @foreach($account_category[$key]['child'][$v1['store_category']] as $v2 )
                                            <option value="{{$v2['store_category']}}">{{'&nbsp; &nbsp;&nbsp; &nbsp; |-'.$v2['store_category_name']}}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                    {{-- @foreach($account as $key=> $a)
                         <option value="{{$key}}">{{$a}}</option>
                     @endforeach--}}
                </select>


                <select class="select_select0 col-sm-3 description" name="category_description[{{$key}}][description_id]">
                    <option value=""></option>
                    @foreach($template as $t_k=> $t_name)
                        <option value="{{$t_k}}">{{$t_name}}</option>
                    @endforeach
                </select>
            </div>

        </div>








    @endforeach









@stop

@section('pageJs')
    <script type="text/javascript">
        $('.select_select0').select2();
    </script>

@stop
