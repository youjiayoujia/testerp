@extends('common.detail')
@section('detailBody')
    <form action="{{route('product.submitItemEdit')}}" METHOD="POST">
        {!! csrf_field() !!}

    <div class="panel panel-default">
        <div class="panel-heading">product信息</div>
        <div class="panel-body">
            @if(!$limits->isEmpty())
                <div class='row'>
                @foreach($limits as $limit)
                        <div class="col-lg-1">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="limits[{{$limit->id}}][status]" checked> {{$limit->name}}
                                    </label>
                                </div>
                            </div>
                        </div>
                @endforeach
                </div>
            @endif
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">注意事项</div>
                            <input type="text" class="form-control" name="notify" value="{{$product->notify}}"/>
                            <input type="hidden" class="form-control" name="product_id" value="{{$product->id}}"/>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">SKU编辑页面</div>
        <div class="panel-body">
                @if(!$items->isEmpty())
                    @foreach($items as $item)
                        <div class='row'>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">SKU</div>
                                        <input type="text" class="form-control" name="items[{{$item->id}}][sku]" value="{{$item->sku}}" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">名称</div>
                                        <input type="text" class="form-control" name="items[{{$item->id}}][c_name]" value="{{ $item->c_name }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">成本价</div>
                                        <input type="text" class="form-control" name="items[{{$item->id}}][purchase_price]"  value="{{ $item->purchase_price }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">供应商链接</div>
                                        <input type="text" class="form-control" name="items[{{$item->id}}][purchase_url]" value="{{ $item->purchase_url }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">参考链接</div>
                                        <input type="text" class="form-control" name="items[{{$item->id}}][competition_url]"  value="{{ $item->competition_url }}" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                @endif

        </div>
    </div>
<div>
    <input class="btn btn-success" type="submit" value="提交" />
</div>
    </form>

@stop