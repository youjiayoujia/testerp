@extends('common.detail')
@section('detailBody')
    <form action="{{ route('spu.uploadSku') }}" method="post" enctype="multipart/form-data">
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                 <input type="file" name="upload" >
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                       data-dismiss="modal">关闭
                    </button>
                    <button type="submit" class="btn btn-primary" >
                       提交
                    </button>
                 </div>
             </form>
@stop