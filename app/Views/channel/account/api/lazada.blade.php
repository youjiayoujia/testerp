<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2016-05-30
 * Time: 15:16
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
                            <label for="account" class='control-label'>lazada_api_host</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="lazada_api_host" name='lazada_api_host' value="{{ $account->lazada_api_host }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>lazada_access_key</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="lazada_access_key" name='lazada_access_key' value="{{ $account->lazada_access_key }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>lazada_user_id</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="lazada_user_id" name='lazada_user_id' value="{{ $account->lazada_user_id }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>lazada_site</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="lazada_site" name='lazada_site' value="{{ $account->lazada_site }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>lazada_currency_type</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="lazada_currency_type" name='lazada_currency_type' value="{{ $account->lazada_currency_type }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="account" class='control-label'>lazada_currency_type_cn</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <input type='text' class="form-control" id="lazada_currency_type_cn" name='lazada_currency_type_cn' value="{{ $account->lazada_currency_type_cn }}">
                        </div>
                    </div>





                   {{-- <div class='form-group col-lg-3'>
                        <label for="expected_date">期望上传日期</label>

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