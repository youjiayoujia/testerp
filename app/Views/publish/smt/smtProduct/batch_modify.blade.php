@extends('common.form')
@section('formAction')  {{ route('smt.batchModify') }} @stop
@section('formBody')
@if(!empty($productList))
    <input type='hidden' value='POST' name="_method">
    <input type='hidden' value='{{$productIds}}' name="productIds">
    @foreach($productList as $product)
    <div class="panel panel-default">  
        <div class="panel-body">
           <div class="row">
                <div class="form-group col-md-1">
                    <label class="right">产品图片:</label>
                    <?php
                    $imageURLs = $product->details->imageURLs;
                    $imagesUrlArr = explode(';', $imageURLs);
                    $firstImageURL = array_shift($imagesUrlArr);
                    ?>
                    @if(!empty($firstImageURL))
                    <a target="_blank" href="{{ $firstImageURL}}"><img style="width:50px;height:50px;" src="{{ $firstImageURL}}"></a>
                    @endif	    
                </div>     		               			
                <div class="form-group col-md-10">
                     <div class="row">
                	    <div class="col-md-3">
                        <label>关键词</label>      
                        <input type="text" class="form-control" name="products[{{$product->productId}}][keyword]"  value="{{$product->details->keyword}}" >  
                        </div>
                        
                         <div class="col-md-3">
                        <label>关键词1</label>      
                        <input type="text" class="form-control" name="products[{{$product->productId}}][productMoreKeywords1]"  value="{{$product->details->productMoreKeywords1}}" >  
                        </div>
                        
                        <div class="col-md-3">
                        <label>关键词2</label>      
                        <input type="text" class="form-control" name="products[{{$product->productId}}][productMoreKeywords2]"  value="{{$product->details->productMoreKeywords2}}" >  
                        </div>
                        
                        <div class="form-group col-md-2"> 
                            <label>销售单位/方式</label>   
                            <select class="form-control" name="products[{{$product->productId}}][productUnit]">
                                @if(!empty($unitList))
                                    @foreach($unitList as $key => $unit)
                                        <option value="{{$key}}"  <?php echo $product->details->productUnit == $key ? 'selected="selected"' : ''; ?>><?php echo $unit['name'] . '(' . $unit['name_en'] . ')'; ?></option>
                                    @endforeach
                                @endif
                            </select>
                         </div>
                         <div class="form-group col-md-1"> 
                             <label>包装重量</label>
                             <input type="text" class="form-control" name="products[{{$product->productId}}][grossWeight]"  value="{{$product->grossWeight}}"> 
                         </div>                         
                     </div>                                 
                </div> 
                <div class="form-group col-md-5">                                                     
                    <label>包装尺寸(cm)</label> 
                    <div class="row">
                        <div class="col-md-8">                            
                            <label class="col-md-1">长:</label>                             
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="products[{{$product->productId}}][packageLength]"  value="{{$product->packageLength}}">  
                            </div>                                               
                                                 
                            <label class="col-md-1">宽:</label>                             
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="products[{{$product->productId}}][packageWidth]"  value="{{$product->packageWidth}}">  
                            </div>                                               
                                                    
                            <label class="col-md-1">高:</label>                             
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="products[{{$product->productId}}][packageHeight]"  value="{{$product->packageHeight}}">  
                            </div>                                               
                        </div>                          
                     </div>                          
                </div> 
                <div class="form-group col-md-2"> 
                    <label>服务模版</label> 
                    <select class="form-control" name="products[{{$product->productId}}][promiseTemplateId]">
                        @if(!empty($serveList))
                           @foreach($serveList as $key => $serve) 
                              <option value="{{$key}}"  <?php echo $product->details->promiseTemplateId == $key ? 'selected="selected"' : ''; ?>><?php echo $serve['serviceName'] ; ?></option>   
                           @endforeach
                        @endif
                    </select>
                </div>   
                <div class="form-group col-md-2"> 
                    <label>运费模版</label> 
                    <select class="form-control" name="products[{{$product->productId}}][freightTemplateId]">
                        @if(!empty($freightList))
                           @foreach($freightList as $key => $freight) 
                              <option value="{{$key}}"  <?php echo $product->details->freightTemplateId == $key ? 'selected="selected"' : ''; ?>><?php echo $freight['templateName'] ; ?></option>   
                           @endforeach
                        @endif
                    </select>
                </div> 
                
                <div class="form-group col-md-1"> 
                    <label>零售价</label> 
                    <input type="text" class="form-control" name="products[{{$product->productId}}][productPrice]"  value="{{$product->productPrice}}"> 
                </div>
            </div>                                        
        </div>
    </div>  
 
    @endforeach
@endif
@stop
@section('formButton')
    <div class="text-center">     
        <button  type="submit" class="btn btn-success submit_btn ">提交</button>
        <button  type="button" name="back" id="back" class="btn btn-success" onclick="window.history.back()">返回</button>
    </div>
@show

<script type="text/javascript">
       
</script>
