@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('countriesChange.store') }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-2">
        <label for="country_from" class='control-label'>来源国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" placeholder="来源国家" name='country_from' value="{{ old('country_from') }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="country_to" class='control-label'>目标国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select class="form-control country_to" name='country_to' id="country_to"></select>
    </div>
</div>
@stop
@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function () {
            $('.country_to').select2({
                ajax: {
                    url: "{{ route('ajaxCountryTo') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            country_to: params.term,
                            page: params.page
                        };
                    },
                    results: function(data, page) {
                        if((data.results).length > 0) {
                            var more = (page * 20)<data.total;
                            return {results:data.results,more:more};
                        } else {
                            return {results:data.results};
                        }
                    }
                }
            });

        });
    </script>
@stop