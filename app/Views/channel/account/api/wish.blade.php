<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-05-26
 * Time: 13:43
 */
?>
<div class="modal fade" id="myModal{{ $account->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('channelAccount.updateApi', ['id' => $account->id]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ $account->alias }} API设置</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>Wish_publish_code</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="wish_publish_code" name='wish_publish_code' value="{{ $account->wish_publish_code }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>Wish_client_id</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="wish_client_id" name='wish_client_id' value="{{ $account->wish_client_id }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>Wish_client_secret</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="wish_client_secret" name='wish_client_secret' value="{{ $account->wish_client_secret }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>Wish_redirect_uri</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="wish_redirect_uri" name='wish_redirect_uri' value="{{ $account->wish_redirect_uri }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>Wish_refresh_token</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="wish_refresh_token" name='wish_refresh_token' value="{{ $account->wish_refresh_token }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>Wish_access_token</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="wish_access_token" name='wish_access_token' value="{{ $account->wish_access_token }}">
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>Wish_expiry_time</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input id="aliexpress_access_token_date" class='form-control' name='wish_expiry_time' type="text" placeholder='token过期日期' value="{{ $account->wish_expiry_time }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>Wish_proxy_address</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="wish_proxy_address" name='wish_proxy_address' value="{{ $account->wish_proxy_address }}">
                        </div>
                    </div>



                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>Wish_sku_resolve(SKU解析方式)</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="wish_sku_resolve" value="1" {{ Tool::isChecked('wish_sku_resolve', '1',$account) }}>方式1（001*SKU）
                                </label>
                                <label>
                                    <input type="radio" name="wish_sku_resolve" value="2" {{ Tool::isChecked('wish_sku_resolve', '2',$account) }}>方式2（S*001KU）
                                </label>
                            </div>
                        </div>
                    </div>



                   {{-- <div class="form-group col-lg-2">
                        <label for="is_merge_package" class="control-label">是否相同地址合并包裹</label>

                        <div class="radio">
                            <label>
                                <input type="radio" name="is_merge_package" value="1" {{ Tool::isChecked('is_merge_package', '1', null, true) }}>是
                            </label>
                            <label>
                                <input type="radio" name="is_merge_package" value="0" {{ Tool::isChecked('is_merge_package', '0') }}>否
                            </label>
                        </div>
                    </div>--}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script type='text/javascript'>
    $(document).ready(function(){
        $('#aliexpress_access_token_date').cxCalendar("YY-MM-DD hh:ss:ii");
    });
</script>