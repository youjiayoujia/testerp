<?php
/**
 * Created by PhpStorm.
 * User: hejiancheng
 * Date: 2016-10-07
 * Time: 16:52
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
                            <label for="account" class='control-label'>joom_publish_code</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="joom_publish_code" name='joom_publish_code' value="{{ $account->joom_publish_code }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>joom_client_id</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="joom_client_id" name='joom_client_id' value="{{ $account->joom_client_id }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>joom_client_secret</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="joom_client_secret" name='joom_client_secret' value="{{ $account->joom_client_secret }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>joom_redirect_uri</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="joom_redirect_uri" name='joom_redirect_uri' value="{{ $account->joom_redirect_uri }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>joom_refresh_token</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="joom_refresh_token" name='joom_refresh_token' value="{{ $account->joom_refresh_token }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>joom_access_token</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="joom_access_token" name='joom_access_token' value="{{ $account->joom_access_token }}">
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>joom_expiry_time</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input id="aliexpress_access_token_date" class='form-control' name='joom_expiry_time' type="text" placeholder='token过期日期' value="{{ $account->joom_expiry_time }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>joom_proxy_address</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="joom_proxy_address" name='joom_proxy_address' value="{{ $account->joom_proxy_address }}">
                        </div>
                    </div>



                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>joom_sku_resolve(SKU解析方式)</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="joom_sku_resolve" value="1" {{ Tool::isChecked('joom_sku_resolve', '1',$account) }}>方式1（001*SKU）
                                </label>
                                <label>
                                    <input type="radio" name="joom_sku_resolve" value="2" {{ Tool::isChecked('joom_sku_resolve', '2',$account) }}>方式2（S*001KU）
                                </label>
                            </div>
                        </div>
                    </div>

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