
@extends('layouts.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"> Paypal固定税 </div>
        <div class="panel-body">
              <form action="{{route('paypal.update_rates')}}" method="POST">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="group_id" class="control-label">小Paypal成交费</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <div class="input-group">
                            <input type="text" class="form-control" id="group_id"  name="transactions_fee_small" value="{{$rates->transactions_fee_small}}">
                            <div class="input-group-addon">%</div>
                            </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="group_id" class="control-label">Paypal固定费用</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                            <input type="text" class="form-control" id="group_id"  name="fixed_fee_big" value="{{$rates->fixed_fee_big}}">
                        </div>
                            </div>
                    </div>

                    <button type="submit" class="btn btn-success">提交</button>
              </form>
        </div>
    </div>
@stop