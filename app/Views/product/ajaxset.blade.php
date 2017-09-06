<div class="panel panel-info adjustmargin">
    <div class="panel-heading">勾选model及对应variation属性:</div>
    @foreach($data['models'] as $model)
        <div class="checkbox panel-body {{$model}}">
            <div class="checkbox col-md-2">
                <label>
                    <input type='checkbox' id="{{$model}}" onclick="quanxuan('{{$model}}')" name='modelSet[{{$model}}][model]' value='{{$model}}'>{{$model}}
                </label>
            </div>

            @foreach($data['variations'] as $key=>$getattr)        
                <div class="checkbox col-md-2 innercheckboxs">{{$getattr['name']}}:
                    @foreach($getattr['value'] as $varaiton_key=>$innervalue)
                        <label>
                            <input type='checkbox' class="{{$model}}quanxuan" name='modelSet[{{$model}}][variations][{{$getattr['name']}}][{{$varaiton_key}}]' value='{{$innervalue}}'>{{$innervalue}}
                        </label>
                    @endforeach
                </div>
            @endforeach
        </div>  
    <hr width="98%" style="border:0.5px solid #d9edf7">
    @endforeach
</div>
<div class="form-group third">
    <label for='set'>feature属性:</label>
    <div class="panel panel-info">
        <div class="checkbox panel-body ">
            <?php if(array_key_exists('features',$data)){ ?>
            @foreach($data['features'] as $key=>$getfeature)
                @if($getfeature['type']==1)
                    <div>                            
                        <div class="featurestyle" style="padding-bottom:10px">
                            {{$getfeature['name']}} : <input type="text" style="margin-left:15px" id="featuretext{{$getfeature['feature_id']}}" value="" name='featureinput[{{$getfeature['feature_id']}}]' />
                        </div>
                    </div>
                @elseif($getfeature['type']==2)
                    <div class="radio">{{$getfeature['name']}}
                        @foreach($getfeature['value'] as $value)
                            <label>
                                <input type='radio' name='featureradio[{{$getfeature['feature_id']}}][]' value='{{$value}}'>{{$value}}
                            </label>
                        @endforeach
                    </div>
                @else($getfeature['type']==3)
                    <div class="checkbox">{{$getfeature['name']}}
                        @foreach($getfeature['value'] as $value)
                            <label>
                                <input type='checkbox' name='featurecheckbox[{{$getfeature['feature_id']}}][]' value='{{$value}}'>{{$value}}
                            </label>
                        @endforeach
                    </div>
                @endif
            @endforeach
            <?php }else{ ?>
                <span>无feature属性</span>
            <?php } ?>
        </div>
    </div>
</div>

