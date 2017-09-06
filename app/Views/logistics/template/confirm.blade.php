@extends('common.detail')
@section('detailBody')
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                <label for="package_id" class="control-label">包裹ID</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control package_id" id="package_id" placeholder="包裹ID" name='package_id' value="">
            </div>
            <div class="form-group">
                <label for="logistics_id" class='control-label'>物流方式</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <select class="form-control logistics_id" id="logistics_id" name="logistics_id"></select>
            </div>
            <div class='form-group'>
                <a class='btn btn-info preview form-control'>面单预览</a>
            </div>
            <div class='form-group' style="display: none;" id="confirmBtn">
                <button type='button' class='btn btn-danger queren form-control'>确认</button>
            </div>
        </div>
        <div class="col-lg-9">
            <iframe id="templateDiv" width="800px" height="800px" src=""></iframe>
        </div>
    </div>
@stop
@section('pageJs')
    <script type='text/javascript'>
        $(document).ready(function () {
            $('.preview').on('click', function () {
                var package_id = $('#package_id').val();
                var logistics_id = $('#logistics_id').val();
                var url = '{{ route('template.preview') }}' + '?package_id=' + package_id + '&logistics_id=' + logistics_id;
                $('#templateDiv').attr("src", url);
                $('#confirmBtn').show();
            });

            //确认
            $('.queren').click(function () {
                if (confirm("是否确认?")) {
                    var logistics_id = $('#logistics_id').val();
                    $.ajax({
                        url: "{{ route('queren') }}",
                        data: {logistics_id: logistics_id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            window.location.reload();
                        }
                    });
                }
            });

            $('.logistics_id').select2({
                ajax: {
                    url: "{{ route('logistics.ajaxLogistics') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            logistics_id: params.term,
                            page: params.page
                        };
                    },
                    results: function (data, page) {
                        if ((data.results).length > 0) {
                            var more = (page * 20) < data.total;
                            return {results: data.results, more: more};
                        } else {
                            return {results: data.results};
                        }
                    }
                }
            });

        });
    </script>
@stop