@extends('common.form')
@section('formAction') /purchaseOrder/updateItemWaitTime/{{$purchase_item_id}}  @stop
@section('formBody')  
 <input type="hidden" name="_method" value="PUT"/>
 <input type="hidden" name="userid" value="2"/>
        <div class="row">
         <div class="form-group col-lg-4">
                <strong>预计到货时间:</strong>
                <input class="form-control" id="waittime" placeholder="渠道创建时间" name='wait_time' value="">
            </div>
            <div class="form-group col-lg-4">
                <strong>报等备注:</strong>
                <textarea class="vLargeTextField" cols="50"   rows="3" name="wait_remark">
                </textarea>
            </div>
           
            </div>                 
      </div>
 <script type="text/javascript">
 $('#waittime').cxCalendar();
 </script>
@stop