@extends('common.form')
@section('formAction')  {{ route('batchUpdate') }} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$item_ids}}' name="item_ids">
    <div class="form-group">
        <label for="sku">待编辑的sku：</label>
    </div>
    <div class="row">
        
        @foreach($skus as $sku)
            <div class="form-group col-md-1">
                <label for="sku">{{$sku->sku}}</label>
            </div>
        @endforeach
    </div>

    <div class="row">
        <?php 
            switch ($param) {
                case 'status':
                     ?>
                     <div class="form-group col-md-3">
                        <label for="size">状态</label>
                        <select id="status" class="form-control" name="status">
                            @foreach(config('item.status') as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                <?php 
                    break;
                
                case 'weight':
                ?>
                <!-- <div class="form-group col-md-3">
                    <label for="color">重量</label>
                    <input class="form-control" id="weight" placeholder="重量" name='weight' value="{{old('weight')}}">
                </div> -->
                <div class="box_content_style">
                    
                        <input name="action" value="modify" type="hidden">
                        <table width="100%" border="0" cellpadding="2" cellspacing="1">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="color:red;">
                                        如不修改<span style="font-size:18px">请勿提交</span>以免覆盖原有资料
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80">
                                        产品规格：
                                    </td>
                                    <td id="ckallinput">
                                        净规格(cm):长
                                        <input name="productsVolume[bp][length]" value="0" type="text" size="8"
                                        title="物品长度为数字且不能小于0">
                                        宽
                                        <input name="productsVolume[bp][width]" value="0" type="text" size="8"
                                        title="物品宽度为数字且不能小于0">
                                        高
                                        <input name="productsVolume[bp][height]" value="0" type="text" size="8"
                                        title="物品高度为数字且不能小于0">
                                        (单位:厘米)
                                        <font color="#FF0000">
                                            【此处为
                                            <strong style="color:#3300FF">
                                                包装前
                                            </strong>
                                            数据】
                                        </font>
                                        <hr style="border:1px solid #F1FDFE;width:99%;">
                                        <script>
                                            $(function() {
                                                confirmRepeat();
                                                confirmRepeatV();
                                                $('#ckallinput > input').keyup(function() {
                                                    if (!confirmRepeat() || !confirmRepeatV()) {
                                                        $('#lock').val('1');
                                                    };
                                                });
                                                function confirmRepeat() {
                                                    var First = $('#products_weight_first').val();
                                                    var Second = $('#products_weight_second').val();
                                                    if (First.length > 0 && Second.length > 0 && First == Second) {
                                                        $('#weight_msg').html('&nbsp;<font color="blue">重量：' + First + 'Kg <font>');
                                                        $('#weight_msg').show();
                                                        $('#lock').val('');
                                                        return true;
                                                    } else {
                                                        $('#weight_msg').html(' &nbsp;<font color="red">两次输入不一致！</font>');
                                                        $('#weight_msg').show();
                                                        return false;
                                                    }
                                                }
                                                function confirmRepeatV() {
                                                    var V1 = $("input[name='productsVolume[ap][length]']").val() + '*' + $("input[name='productsVolume[ap][width]']").val() + '*' + $("input[name='productsVolume[ap][height]']").val();
                                                    var V2 = $("input[name='productsVolume[ap][length]2']").val() + '*' + $("input[name='productsVolume[ap][width]2']").val() + '*' + $("input[name='productsVolume[ap][height]2']").val();
                                                    if (V1 == V2) {
                                                        $('#volume_msg').html('&nbsp;<font color="blue">体积：' + V2 + ' <font>');
                                                        $('#volume_msg').show();
                                                        $('#lock').val('');
                                                        return true;
                                                    } else {
                                                        $('#volume_msg').html(' &nbsp;<font color="red">两次输入不一致！</font>');
                                                        $('#volume_msg').show();
                                                        return false;
                                                    }
                                                }

                                            })
                                        </script>
                                        <input type="hidden" id="lock" value="">
                                        包装后(含耗材）重量:
                                        <input type="password" id="products_weight_first" name="products_weight"
                                        size="8" lang="mustint_0.001" title="物品重量为数字且不能小于0.001" value="0.248">
                                        Kg 【
                                        <font color="red">
                                            重量包括 配件 包材重量
                                        </font>
                                        】
                                        <br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;确认重量输入：
                                        <input type="password" id="products_weight_second" name="products_weight2"
                                        size="8" lang="mustint_0.001" title="物品重量为数字且不能小于0.001" value="0.248">
                                        Kg
                                        <span id="weight_msg" style="none">
                                            &nbsp;
                                            <font color="blue">
                                                重量：0.248Kg
                                                <font>
                                                </font>
                                            </font>
                                        </span>
                                        <hr style="border:1px solid #F1FDFE;width:99%;">
                                        包装后体积(cm):长
                                        <input name="productsVolume[ap][length]" value="23" type="password" size="8"
                                        lang="mustint_0.01" title="物品长度为数字且不能小于0">
                                        宽
                                        <input name="productsVolume[ap][width]" value="18" type="password" size="8"
                                        lang="mustint_0.01" title="物品宽度为数字且不能小于0">
                                        高
                                        <input name="productsVolume[ap][height]" value="5" type="password" size="8"
                                        lang="mustint_0.01" title="物品高度为数字且不能小于0">
                                        (单位:厘米)
                                        <font color="#FF0000">
                                            【此处为
                                            <strong style="color:#3300FF">
                                                包装后
                                            </strong>
                                            数据】
                                        </font>
                                        <br>
                                        &nbsp;&nbsp; &nbsp;&nbsp;确认体积输入：
                                        <input name="productsVolume[ap][length]2" value="23" type="password" size="8"
                                        lang="mustint_0.01" title="物品长度为数字且不能小于0">
                                        宽
                                        <input name="productsVolume[ap][width]2" value="18" type="password" size="8"
                                        lang="mustint_0.01" title="物品宽度为数字且不能小于0">
                                        高
                                        <input name="productsVolume[ap][height]2" value="5" type="password" size="8"
                                        lang="mustint_0.01" title="物品高度为数字且不能小于0">
                                        (单位:厘米)
                                        <span id="volume_msg" style="">
                                            &nbsp;
                                            <font color="blue">
                                                体积：23*18*5
                                                <font>
                                                </font>
                                            </font>
                                        </span>
                                    </td>
                                </tr>
                                <tr bgcolor="#FEDEE1">
                                    <td>
                                        产品配件:
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80">
                                        质检标准:
                                    </td>
                                    <td>
                                        <textarea name="products_check_standard" style=" width:100%;" rows="10"
                                        lang="require" title="质检标准不可为空" id="products_check_standard">
                                            1.检查标签sku是否正确; 2.检查跟上一批次是否一致; 3.外观污染破损检查;
                                        </textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <script>
                        $(function() {
                            $('#form1').submit(function() {
                                if ($('#lock').val()) {
                                    return false;
                                }
                                if (confirm('确认修改吗？')) {
                                    return form_chk(this);
                                } else {
                                    return false;
                                }
                            });
                        })
                    </script>
                </div>
                <script>
                    $(function() {
                        $('#form1').submit(function() {
                            /*var hs_len = $("#product_hscode").val().length;
                            if(hs_len<8 || hs_len>11){
                              alert('hscode字符必须大于等于8个小于等于11个');
                              return false;
                            }*/
                            if ($('#lock').val()) {
                                return false;
                            }
                            if (confirm('确认修改吗？')) {
                                return form_chk(this);
                            } else {
                                return false;
                            }
                        });
                    })
                </script>
                </div>
                <?php 
                    break;

                case 'purchase_price':
                ?>
                
                    <div class="form-group col-md-3">
                        <label for="color">参考成本</label>
                        <input class="form-control" id="cost" placeholder="参考成本" name='cost' value="{{old('cost')}}">
                    </div>
                
                <?php
                    break;

                case 'package_size':
                ?>
                
                    <div class="form-group col-md-3">
                        <label for="color">包装后体积(长*宽*高)</label>
                        <input class="form-control" id="package_size" placeholder="" name='package_size' value="{{old('package_size')}}">
                    </div>
                
                <?php
                    break;

                case 'name':
                ?>
                
                    <div class="form-group col-md-3">
                        <label for="color">中文资料</label>
                        <input class="form-control" id="c_name" placeholder="" name='c_name' value="{{old('c_name')}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="color">英文资料</label>
                        <input class="form-control" id="name" placeholder="" name='name' value="{{old('name')}}">
                    </div>
                
                <?php
                    break;

                case 'declared_value':
                ?>
                
                    <div class="form-group col-md-3">
                        <label for="color">申报价值</label>
                        <input class="form-control" id="declared_value" placeholder="" name='declared_value' value="{{old('declared_value')}}">
                    </div>
                    
                
                <?php
                    break;

                case 'catalog':
                ?>
                    <div class="form-group col-md-3">
                        <label for="color">分类</label>
                        <select id="status" class="form-control" name="status">
                            @foreach($catalogs as $key=>$catalog)
                                <option value="{{$key}}">{{$catalog->name}}</option>
                            @endforeach
                        </select>                    
                    </div>
                <?php
                    break;
            } 
        ?>
    </div>

    
@stop