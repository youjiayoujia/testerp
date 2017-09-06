@extends('common.form')
@section('formAction')@stop
@section('formBody')
    <div class="col-lg-12">
        <table class="table table-bordered table-striped table-hover sortable">
            <thead>
            <tr>
               <th>Package ID</th>
               <th>订单号</th>
               <th>状态</th>
               <th>物流方式</th>
            </tr>
            </thead>
            <tbody>
            @foreach($packages as $model)
            <tr>
              <td>{{$model->id}}</td>
              <td>{{$model->order ? $model->order->id : ''}}</td>
              <td>{{$model->status_name}}</td>
              <td class='col-lg-2'>
                <select name='logistics' class='form-control logistics'>
                  <option value=''></option>
                  @foreach($logisticses as $logistics)
                  <option value="{{ $logistics->id }}">{{$logistics->code}}</option>
                  @endforeach
                </select>
              </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
<?php echo $packages->render(); ?>
@stop
@section('formButton')@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.logistics').select2();

    $(document).on('change', '.logistics', function(){
      id = $(this).parent().parent().find('td:eq(0)').text();
      logistics = $(this).val();
      $.get("{{route('package.setManualLogistics')}}",
          {id:id,logistics:logistics},
          function(result){
            if(!result) {
              alert('物流有误');
            }
          });
      });
});
</script>
@stop
