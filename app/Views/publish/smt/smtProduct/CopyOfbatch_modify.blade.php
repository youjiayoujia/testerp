<?php
/**
 * 批量修改在线产品模板
 */
?>
<style>
    .dia input {
        height: 25px;
        width: 257px;
        margin: 1px;
    }
    input.text-90 {
        width: 90px;
    }
    input.text-60 {
        width: 60px;
    }
    .dia .rad {
        margin: 0px;
        width: 15px;
    }
    .dia select.sel-qu {
        margin-left: 0;
        width: 70px;
    }
    .dia td.td-left{text-align:right;padding-right:5px}
    .dia td.td-right {
        text-align: left;
        padding-left: 30px;
    }
    input,label { vertical-align:middle;}
    .layer_pageContent{padding-top: 10px;padding-left: 10px;}
    .proCon, #msgList {
        width: 690px;
        height: 320px;
        overflow-y: auto;
    }
    .proCon .proList{
        width: 100%;
    }
    .proList thead td, .proList tbody td {
        background-color: #EBF3FF;
        border-top: 1px solid #DBE9FF;
        color: #677DA1;
        font-weight: 100;
        height: 30px;
        padding: 10px 10px;
    }
    .proList .td-right {
        text-align: right;
    }
    .pagination {
        margin-top: 10px;
        padding: 0px 18px;
        height: 21px;
        border-bottom: 1px solid #CCC;
        text-align: center;
        font: 400 11px tahoma;
        background: #F0F0F4;
        width: 690px;
    }
    .pagination .page-number {
        font-weight: 700;
        line-height: 22px;
        float: left;
    }
    .pagination .page-skip {
        float: right;
        line-height: 22px;
    }
    .pagination .page-skip .page-skip-text{
        font-size: 11px;
    }
    .pagination .page-skip-button{
        line-height: 14px;
        font-size: 12px;
    }
    .pagination .page-links {
        display: inline-block;
        zoom: 1;
    }
    .page-links {
        overflow: hidden;
    }
    .pagination .page-skip-text {
        padding: 2px 0;
        height: 14px;
        line-height: 14px;
        width: 35px;
    }
    .pagination .page-prev, .pagination .page-next {
        height: 14px;
        border: 1px solid;
        line-height: 22px;
    }
    .pagination .page-prev a, .pagination .page-next a{
        text-underline: none;
    }
</style>
<div class="container-fluid">

    <div class="row" style="padding: 15px 30px 0px;">
        <!--添加产品按钮下边一个是一样的-->
        <div class="form-group">
            <div class="col-sm-2">
                <a class="btn btn-primary btn-sm addProduct">添加更多产品</a>
            </div>

            <div class="col-sm-offset-5">
                <a class="btn btn-primary btn-sm disabled submitModify" href="#">提交</a>
            </div>

        </div>

        <div class="alert-warning" style="margin:10px auto;">
            发布涉嫌侵犯知识产权产品将会受到处罚，请在发布前仔细阅读<a href="http://seller.aliexpress.com/rule/rulecate/intellectual01.html" target="_blank">知识产权规则</a>。品牌点击查看
            <a href="http://seller.aliexpress.com/education/rule/product/brand.html" target="_blank">品牌列表参考。</a>
            <?php
            if ($from != 'draft'){
                echo '<p class="red">修改在线产品时，点击提交后将会实时变更线上产品数据</p>';
            }else {
                echo '<p class="red">修改草稿和待发布数据，提交后只会变更本地数据</p>';
            }
            ?>
        </div>

        <!--产品列表-->
        <table class="table table-bordered table-condensed" id="up-proList">
            <colgroup>
                <col width="6%"/>
                <col/>
                <col width="8%"/>
                <col width="11%"/>
                <col width="8%"/>
                <col width="10%"/>
                <col width="9%"/>
                <col width="8%"/>
                <col width="8%"/>
                <col width="10%"/>
                <col width="3%"/>
            </colgroup>
            <thead>
            <tr>
                <td>&nbsp;</td>
                <td>
                    产品标题<a class="p-info" href="#">修改</a>
                </td>
                <td>
                    关键词<a class="p-keyword" href="#">修改</a>
                </td>
                <td>
                    销售单位/方式<a class="p-sell" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="销售的单位和方式，可选择单价零售或打包销售"></a>
                </td>
                <td>
                    包装重量<a class="p-quality" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="包装重量=产品净重+外包装重量"></a>
                </td>
                <td>
                    包装尺寸<a class="p-size" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="产品包装以后的长、宽、高"></a>
                </td>
                <td>
                    产品信息模块<a class="p-detail" href="#">修改</a>
                </td>
                <td>
                    服务模板<a class="p-serve" href="#">修改</a>
                </td>
                <td>
                    运费模板<a class="p-module" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="国际物流的运费设置，新手推荐“新手运费模板”"></a>
                </td>
                <td>
                    零售价(US $)<a class="p-price" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="“零售价”为淘宝原价折算成美金价格。需要设置利润后方可销售"></a>
                </td>
                <td></td>
            </tr>
            </thead>

            <!--先输出错误信息-->
            <?php if (!empty($error)):?>
            <tbody>
            <tr>
                <td colspan="11">
                    <div class="alert alert-danger"><?php echo $error;?></div>
                </td>
            </tr>
            </tbody>

            <?php else:?>

            <!--产品列表，一个产品一个tbody-->
            <?php
            if (!empty($productList)):
                $unitId = '100000015';
                //$token_id = 0;
                foreach ($productList as $key => $product):
                    $detail = $product->details;
                    $unitId = $detail->productUnit;
                    //$token_id = $token_id ? $token_id : $product['token_id'];
            ?>
            <tbody>
            <tr>
                <td>
                    <?php
                    //产品图片
                    $imageUrl = '';
                    if (!empty($detail['imageURLs'])){
                        $imageUrls = explode(';', $detail['imageURLs']);
                        $imageUrl = array_shift($imageUrls);
                    }
                    ?>
                    <img src="<?php echo $imageUrl;?>" alt="产品图片" width="80" height="80"/>
                </td>
                <td>
                    <span class="s-ti"><?php echo $product['subject'];?></span>
                    <input type="hidden" name="subject" value="<?php echo $product['subject'];?>">
                </td>
                <td>
                    <span class="s-k1"><?php echo $detail['keyword'];?></span>
                    <input type="hidden" name="keywords" value="<?php echo $detail['keyword'];?>"/>
                    <br>
                    <span class="s-k2"><?php echo $detail['productMoreKeywords1']?></span>
                    <input type="hidden" name="productMoreKeywords1" value="<?php echo $detail['productMoreKeywords1']?>"/>
                    <br>
                    <span class="s-k3"><?php echo $detail['productMoreKeywords2']?></span>
                    <input type="hidden" name="productMoreKeywords2" value="<?php echo $detail['productMoreKeywords2']?>"/>
                </td>
                <td>
                    <span class="s-se">按<?php echo $unitList[$detail['productUnit']]['name'].' ('.$unitList[$detail['productUnit']]['name_en'].')';?>出售</span>
                    <input type="hidden" name="packageWay" value="<?php echo ($detail['packageType'] == 1 ? 'true' : 'false').'-'.$detail['productUnit'].'-'.$detail['lotNum'];?>">
                </td>
                <td>
                    <div>
                        <span class="s-qu"><?php echo $product['grossWeight'];?></span>公斤
                        <input type="hidden" name="grossWeight" value="<?php echo $product['grossWeight'];?>">
                    </div>
                </td>
                <td>
                    <div class="td-size">
                        <div>
                            <span class="s1">长：</span>
                            <span class="s-l"><?php echo (int)$product['packageLength'];?></span>
                            <input type="hidden" name="packageLength" value="<?php echo (int)$product['packageLength'];?>">
                            厘米
                        </div>
                        <div>
                            <span class="s1">宽：</span>
                            <span class="s-w"><?php echo (int)$product['packageWidth'];?></span>
                            <input type="hidden" name="packageWidth" value="<?php echo (int)$product['packageWidth'];?>">
                            厘米
                        </div>
                        <div>
                            <span class="s1">高：</span>
                            <span class="s-h"><?php echo (int)$product['packageHeight'];?></span>
                            <input type="hidden" name="packageHeight" value="<?php echo (int)$product['packageHeight'];?>">
                            厘米
                        </div>
                    </div>
                </td>
                <td>
                    <div class="td-size">
                        <div>
                            <span class="sl-t"></span>
                            <input type="hidden" name="tModuleId">
                            <input type="hidden" name="tModuleName">
                            <input type="hidden" name="tModuleType">
                        </div>
                        <div>
                            <span class="sl-b"></span>
                            <input type="hidden" name="bModuleId">
                            <input type="hidden" name="bModuleName">
                            <input type="hidden" name="bModuleType">
                        </div>
                    </div>
                </td>
          
                <td>
                    <span class="sku-price" style="background: lightskyblue; color: white;">零</span>
                    <span class="s-pr"><?php echo ($product['productMinPrice'] == $product['productMaxPrice'] ? $product['productMinPrice'] : $product['productMinPrice'].' - '.$product['productMaxPrice']);?></span>
                    <input type="hidden" name="priceCreaseNum" value="">
                    <input type="hidden" name="priceCreaseType" value="">
                    <input type="hidden" name="isSKU" value="true">
                </td>
                <td>
                    <a class="icon-trash red bigger-130 pro-remove" href="#"></a>
                    <input type="hidden" name="productId" value="<?php echo $product['productId'];?>"/>
                    <input type="hidden" name="categoryId" value="<?php echo $product['categoryId'];?>"/>
                    <input type="hidden" name="changed" value="false"/>
                </td>
            </tr>
            </tbody>
            <?php
                endforeach;
            endif;
            endif;
            ?>
        </table>

        <!--添加产品按钮最上边一个是一样的-->
        <div class="form-group">
            <div class="col-sm-2">
                <a class="btn btn-primary btn-sm addProduct">添加更多产品</a>
            </div>

            <div class="col-sm-offset-5">
                <a class="btn btn-primary btn-sm disabled submitModify" href="#">提交</a>
            </div>
        </div>
    </div>

    <!--标题-->
    <div id="dia-info" class="hide">
        <table class="dia dia-info">
            <tbody>
            <tr>
                <td width="105" class="td-left">标题开头添加</td>
                <td width="270"><input type="text" name="startTitle" maxlength="128"></td>
            </tr>
            <tr>
                <td width="105" class="td-left">标题结尾添加</td>
                <td width="270"><input type="text" name="endTitle" maxlength="128"></td>
            </tr>
            <tr>
                <td width="105" class="td-left">标题中的</td>
                <td width="270">
                    <input type="text" class="text-90" name="oldTitle" maxlength="128">
                    替换为
                    <input type="text" value="" class="text-90" name="newTitle" maxlength="128">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:35px;"><span class="dia-tip">小提示：对标题进行修改将导致产品重新审核</span></td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--关键词-->
    <div id="dia-keyword" class="hide">
        <table class="dia dia-keyword">
            <tbody>
            <tr>
                <td width="130" class="td-left">替换产品关键词</td>
                <td width="245"><input type="text" value="" name="key1" maxlength="128"></td>
            </tr>
            <tr>
                <td width="130" class="td-left">替换更多关键词</td>
                <td width="245"><input type="text" value="" name="key2" maxlength="50"></td>
            </tr>
            <tr>
                <td width="130" class="td-left"></td>
                <td width="245"><input type="text" value="" name="key3" maxlength="50"></td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:66px;"><span class="dia-tip">小提示：对关键词进行修改将导致产品重新审核</span></td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--销售方式-->
    <div id="dia-sell" class="hide">
        <table class="dia">
            <tbody>
            <tr>
                <td width="95" class="td-left">最小计量单位</td>
                <td width="280">
                    <select name="unit-sel">
                        <?php
                        $unitId = !empty($unitId) ? $unitId : '100000015';
                        $unit_ch = '';
                        $unit_en = '';
                        foreach ($unitList as $unit){
                            if ($unitId == $unit['id']){
                                $unit_ch = $unit['name'];
                                $unit_en = $unit['name_en'];
                            }
                            echo '<option value="'.$unit['id'].'" '.($unitId == $unit['id'] ? 'selected="selected"' : '').'>'.$unit['name'].'('.$unit['name_en'].')'.'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="80" class="td-left">销售方式</td>
                <td width="290">
                    <input style="width:14px;" type="radio" value="0" name="sell-by" checked="checked"><span><?php echo $unit_ch.'('.$unit_en.')';?></span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input style="width:14px;" type="radio" value="1" name="sell-by">打包出售 每包
                    <input type="text" name="sell-num" disabled="disabled" class="text-60" maxlength="6"><span><?php echo $unit_ch.'('.$unit_en.')';?></span>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--重量-->
    <div id="dia-quality" class="hide">
        <table class="dia">
            <tbody>
            <tr>
                <td width="178" class="td-right">
                    <input class="rad" type="radio" name="quality" value="0" checked="checked">
                    <label>批量修改为</label>
                </td>
                <td width="197">
                    <input type="text" class="text-90" name="at-quality" maxlength="10">公斤
                </td>
            </tr>
            <tr>
                <td width="178" class="td-right">
                    <input class="rad" type="radio" name="quality" value="1">
                    <label>按</label>
                    <select class="sel-qu">
                        <option value="0">重量</option>
                        <option value="1">百分比</option>
                    </select>
                    <label>增加</label>
                </td>
                <td width="197">
                    <input type="text" class="text-90" disabled="disabled" name="add-quality" maxlength="10">
                    <span class="s-unit">公斤</span>
                </td>
            </tr>
            <tr>

                <td colspan="2" style="padding-left:71px;"><span class="dia-tip">小提示：如果减少，可以输入负数</span></td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--尺寸-->
    <div id="dia-size" class="hide">
        <table class="dia">
            <tbody>
            <tr>
                <td width="125" class="td-left">长</td>
                <td width="250"><input class="text-90" type="text" name="len" maxlength="3">厘米</td>
            </tr>
            <tr>
                <td width="125" class="td-left">宽</td>
                <td width="250"><input class="text-90" type="text" name="wid" maxlength="3">厘米</td>
            </tr>
            <tr>
                <td width="125" class="td-left">高</td>
                <td width="250"><input class="text-90" type="text" name="hei" maxlength="3">厘米</td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--产品信息模板-->
    <div id="dia-detail" class="hide">
        <table class="dia">
            <tbody>
            <tr height="30">
                <td width="125" class="td-left">详细描述顶部：</td>
                <td width="250"><a href="#" class="yellowish-btn addModule btn btn-sm" extattr="top">选择产品信息模版</a></td>
            </tr>
            <tr height="30">
                <td class="td-left">已选择：</td>
                <td>
                    <span class="sel-t">--</span>
                    <input type="hidden" name="t-temp-id">
                    <input type="hidden" name="t-temp-name">
                    <input type="hidden" name="t-temp-type">
                </td>
            </tr>
            <tr height="30">
                <td class="td-left">详细描述底部：</td>
                <td><a href="#" class="yellowish-btn addModule btn btn-sm" extattr="bottom">选择产品信息模版</a></td>
            </tr>
            <tr height="30">
                <td class="td-left">已选择：</td>
                <td>
                    <span class="sel-b">--</span>
                    <input type="hidden" name="b-temp-id">
                    <input type="hidden" name="b-temp-name">
                    <input type="hidden" name="b-temp-type">
                </td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--服务模板-->
    <div class="hide" id="dia-serve">
        <table class="dia dia-module">
            <tbody>
            <tr>
                <td width="375">
                    <div class="mo-contain">
                        <img alt="loading" src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--运费模板-->
    <div id="dia-module" class="hide">
        <table class="dia dia-module">
            <tbody>
            <tr>
                <td width="375">
                    <div class="mo-contain">
                        <img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--零售价-->
    <div id="dia-price" class="hide">
        <table class="dia">
            <tbody>
            <tr class="dia-price-tdx" style="display:none;">
                <td colspan="2" style="padding-left:56px;color:red;">已售出代销产品平均加价幅度为20%-50%</td>
            </tr>
            <tr>
                <td class="td-left" width="175">
                    按
                    <select class="sel-qu">
                        <option value="0">金额</option>
                        <option value="1">百分比</option>
                    </select>
                    增加
                </td>
                <td width="200">
                    <input type="text" class="text-90" value="" name="price" maxlength="10" autocomplete="false">
                    <span class="span-unit">美元</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:56px;"><span class="dia-tip">小提示：如果减少，可输入负数。</span></td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--选择更多产品-->
    <div id="addProSup" class="hide">
        <div class="addProList">
            <div class="search-bar">
                <input type="text" name="subject" class="txt-tip" maxlength="128">
                <select name="productGroup">
                    <option value="0" selected="selected">产品分组</option>
                    <option value="-2">All</option>
                    <?php
                    if (!empty($groupList)):
                        foreach ($groupList as $group){
                            if (array_key_exists('child', $group) && !empty($group['child'])){
                                echo '<optgroup label="'.$group['group_name'].'">';
                                foreach ($group['child'] as $row){
                                    echo '<option value="'.$row['group_id'].'">'.$row['group_name'].'</option>';
                                }
                                echo '</optgroup>';
                            }else {
                                echo '<option value="'.$group['group_id'].'">'.$group['group_name'].'</option>';
                            }
                        }
                    endif;
                    ?>
                </select>
                <!--<select name="memberId">
                    <option value="" selected="selected">产品负责人</option>
                    <option value="All">All</option>
                    <option value="cn1512099214">wei su</option>
                </select>-->
                <?php if ($from != 'draft'):?>
                <select name="offLineTime">
                    <option value="0" selected="selected">到期时间</option>
                    <option value="-1">All</option>
                    <option value="3">剩余3天内</option>
                    <option value="7">剩余7天内</option>
                    <option value="30">剩余30天内</option>
                </select>
                <?php endif;?>
                <input type="hidden" name="from" id="from" value="<?php echo $from;?>" />
                <input type="button" value="搜索" class="btn-submit-m btn btn-sm" name="btn-submit">

                <div style="display: none;" class="product_nil_tip"><span class="me_tip">请输入或选择查询条件</span></div>
            </div>
        </div>
        <div class="proCon">
            <table class="proList">
                <thead>
                <tr>
                    <td width="100">图片</td>
                    <td width="220">产品名称 :</td>
                    <td width="90">负责人</td>
                    <td width="120">售价(US$)</td>
                    <td width="70" class="td-right">
                        全选
                        <input type="checkbox" name="p-selAll">
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="5" style="text-align:center">
                        <img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading" style="width:32px; height:32px">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="pagination clearfix hide">
            <div class="page-number">Page <span class="currentpage">1</span> of <span class="totalPage">1</span></div>
            <div class="page-skip">
                Go to Page
                <input type="text" class="page-skip-text" value="">
                <input type="button" class="page-skip-button" value="GO">
            </div>
            <div class="page-links clearfix">
                <a class="page-prev" href="#">Previous</a>
                <a class="page-next" href="#">Next</a>
            </div>
        </div>
    </div>

    <div id="msgList" class="hide">
        <div class="loading center">修改中，请不要操作...</div>
        <div class="center completed hide">操作已完成，如有错误信息，请注意查看</div>
        <div class="alert alert-warning">
        </div>
    </div>

</div>
@section('pageJs')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/layer/layer.min.js') }}"></script>
<script type="text/javascript">
    /**没有封装成函数，暂时先集成在系统中吧**/
    var moreProducts = {}; //全局变量，存储获取更多产品时的产品信息
    var from = '<?php echo $from;?>';
    $(function(){
        //修改按钮的点击事件
        $(document).on('click', '#up-proList thead a', function(event){
            var myClass = $(this).attr('class');
            var temp = myClass.split('-');
            var targetId = 'dia-'+temp[1];
            var obj = $('#'+targetId);

            if (temp[0] != 'p'){
                return false;
            }

            if ($('tbody input[name=productId]').length == 0){ //判断是否已经选择产品
               showtips('请先选择产品', 'alert-warning');
               return false;
            }

            $.layer({
                type: 1,   //0-4的选择,
                title: '详情预览',
                border: [0],
                closeBtn: [0],
                shadeClose: false,
                offset: ['50px', ''],//50px
                area: ['415px', '240px'],
                closeBtn: [0, true],
                zIndex: 20,
                btns: 2,
                btn: ['确定', '取消'],
                page: {
                    //html: html
                    dom: '#'+targetId
                },
                success: function(othis){
                    obj.removeClass('hide');
                    if (myClass == 'p-module'){ //异步获取运费模板
                        getTemplateList(<?php echo $token_id;?>, 'module');
                    }else if(myClass == 'p-serve'){ //异步获取服务模板
                        getTemplateList(<?php echo $token_id;?>, 'serve');
                    }else if(myClass == 'p-sell'){ //把单位显示的单位统一下
                        var optionStr = obj.find('.dia [name=unit-sel]').find('option:selected').text();
                        obj.find('table').find('td span').html(optionStr);
                    }else if(myClass == 'p-detail'){ //产品模板

                        var moduleBtn = othis.find('a.addModule');

                        moduleBtn.on('click', function(){
                            var direct = $(this).attr('extattr'); //方向:控制是在顶部还是底部添加
                            $.layer({
                                type: 2,   //0-4的选择,
                                title: '选择模块',
                                border: [0],
                                closeBtn: [0],
                                zIndex: 400,
                                shadeClose: false,
                                offset: ['50px', ''],//50px
                                area: ['600px', '300px'],//480
                                closeBtn: [0, true],
                                btns: 2,
                                btn: ['确定', '取消'],
                                iframe: {
                                    src : '<?php echo admin_base_url("smt/smt_product/moduleSelect?single=1&token_id=".$token_id);?>'
                                },
                                yes: function(index2){
                                    //选择并关闭
                                    var moduleObj = layer.getChildFrame('.checkbox:checked', index2);
                                    if (moduleObj.length == 1){
                                        var moduleId = moduleObj.val();
                                        var moduleTitle = moduleObj.attr('title');
                                        var moduleType = moduleObj.attr('lang');

                                        var parentOjb = parent.$('#dia-detail');
                                        var tModuleId = parentOjb.find('input[name=t-temp-id]').val(); //顶部的产品信息模板
                                        var bModuleId = parentOjb.find('input[name=b-temp-id]').val(); //底部的产品信息模板

                                        if (direct == 'top'){ //定位
                                            if (moduleId == bModuleId){
                                                showtips('不能在顶部和底部选择同样的模板', 'alert-warning');
                                                return false;
                                            }
                                            parentOjb.find('span.sel-t').text(moduleTitle);
                                            parentOjb.find('input[name=t-temp-id]').val(moduleId);
                                            parentOjb.find('input[name=t-temp-name]').val(moduleTitle);
                                            parentOjb.find('input[name=t-temp-type]').val(moduleType);
                                        }else if (direct == 'bottom'){
                                            if (moduleId == tModuleId){
                                                showtips('不能在顶部和底部选择同样的模板', 'alert-warning');
                                                return false;
                                            }
                                            parentOjb.find('span.sel-b').text(moduleTitle);
                                            parentOjb.find('input[name=b-temp-id]').val(moduleId);
                                            parentOjb.find('input[name=b-temp-name]').val(moduleTitle);
                                            parentOjb.find('input[name=b-temp-type]').val(moduleType);
                                        }
                                        layer.close(index2);
                                    }
                                }
                            });
                        })
                    }
                },
                yes: function(index){ //确认按钮的时候执行的判断

                    //添加各对象的判断条件
                    if (targetId == 'dia-info'){ //标题
                        //开头添加
                        var startTitle = obj.find('input[name=startTitle]').val().trim();

                        //结尾添加
                        var endTitle = obj.find('input[name=endTitle]').val().trim();

                        //替换
                        var oldTitle = obj.find('input[name=oldTitle]').val().trim();
                        var newTitle = obj.find('input[name=newTitle]').val();
                        if (oldTitle == '' && newTitle != ''){
                            obj.find('.error-box').empty().html('<div class="board-error"><i class="icon-remove-sign red"></i>请输入要替换的词</div>');
                            return false;
                        }

                        if (startTitle != '' || endTitle != '' || oldTitle != ''){
                            $('.s-ti').each(function(){
                                var subject = $(this).html();
                                var newSubject;
                                    newSubject = startTitle + ' ' + subject;
                                    newSubject += ' ' + endTitle;
                                newSubject = $.trim(newSubject);
                                if (oldTitle != '') {
                                    newSubject = newSubject.replaceAll(oldTitle, newTitle, true);
                                }
                                $(this).html(newSubject);
                                $(this).closest('td').find('input[name=subject]').val(newSubject);
                            });
                        }

                    }else if (targetId == 'dia-keyword') { //修改关键词，空格的话对应的不进行修改

                        var key1 = obj.find('input[name=key1]').val().trim();
                        var key2 = obj.find('input[name=key2]').val().trim();
                        var key3 = obj.find('input[name=key3]').val().trim();

                        if (key1 != ''){
                            $('.s-k1').html(key1).closest('td').find('input[name=keywords]').val(key1);
                        }
                        if (key2 != ''){
                            $('.s-k2').html(key2).closest('td').find('input[name=productMoreKeywords1]').val(key2);
                        }
                        if (key3 != ''){
                            $('.s-k3').html(key3).closest('td').find('input[name=productMoreKeywords2]').val(key3);
                        }

                    }else if(targetId == 'dia-sell'){ //销售方式(打包)和单位

                        var sells_by = obj.find('input[name="sell-by"]:checked').val();
                        var unit_obj = obj.find('.dia [name=unit-sel]');
                        var unit_checked = unit_obj.val(); //选中的单位ID
                        var unit_name = unit_obj.find('option:selected').text(); //选中的单位名称

                        var input_str = '';
                        if (sells_by == 0){
                            input_str = 'false-'+unit_checked+'-1';
                        }else if(sells_by == 1){
                            var num = obj.find('input[name=sell-num]').val();
                            var reg = /^[1-9][0-9]*$/;
                            if (reg.test(num) && num <= 100000 && num >= 2){
                                input_str = 'true-'+unit_checked+'-'+num;
                            }else {
                                obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请填写2~100000之间的整数</div>');
                                return false;
                            }
                        }

                        $('.s-se').html(unit_name);
                        $('.s-se').closest('td').find('input[name=packageWay]').val(input_str);
                        unit_obj.val('100000015'); //默认让它选择刚开始第一个产品的

                    }else if(targetId == 'dia-quality'){ //重量

                        var flag = obj.find('.rad[name=quality]:checked').val();
                        if (flag == 1){
                            var operate = obj.find('.sel-qu').val();
                            var addQuality = obj.find('input[name=add-quality]').val().trim();

                            if (operate == 1){
                                if (addQuality == '' || isNaN(addQuality) || addQuality < -100 || addQuality > 100){
                                    obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请填写-100.00到100.00之间的数字（最多两位小数）</div>');
                                    return false;
                                }
                            }else {
                                if (addQuality == '' || isNaN(addQuality) || addQuality < -70 || addQuality > 70){
                                    obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请填写-70.000~70.000之间的数字（最多三位小数）</div>');
                                    return false;
                                }
                            }
                            $('.s-qu').each(function(){
                                var weight_target = $(this).closest('tr').find('input[name=grossWeight]');
                                var weight = parseFloat(weight_target.val().trim());
                                addQuality = parseFloat(addQuality);

                                if (operate == 1){ //按百分比处理
                                    var new_weight = weight * (1 + addQuality/100);
                                }else {//必须转换下类型，不然可能会计算成合并字符串了
                                    var new_weight = weight + addQuality;
                                }

                                new_weight = new_weight.toFixed(3);
                                $(this).text(new_weight);
                                weight_target.val(new_weight);
                            });
                        }else { //0
                            var atQuality = obj.find('input[name=at-quality]').val().trim();
                            if (atQuality != '' && !isNaN(atQuality) && atQuality >= 0.001 && atQuality <= 500.000){
                                var weight = new Number(atQuality);
                                weight = weight.toFixed(3); //格式化，会四舍五入
                                $('.s-qu').text(weight);
                                $('.s-qu').closest('tr').find('input[name=grossWeight]').val(weight);
                            }else {
                                obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请填写0.001~500.000之间的数字</div>');
                                return false;
                            }
                        }

                    }else if(targetId == 'dia-size'){ //尺寸

                        var len = obj.find('input[name=len]').val().trim();
                        var wid = obj.find('input[name=wid]').val().trim();
                        var hei = obj.find('input[name=hei]').val().trim();
                        if (len == '' || wid == '' || hei == '' || isNaN(len) || isNaN(wid) || isNaN(hei)){ //不是数字就不替换
                            obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请将长宽高填写完整</div>');
                            return false;
                        }
                        len = parseInt(len);hei = parseInt(hei); wid = parseInt(wid);
                        if (len < 1 || len > 700 || hei < 1 || hei > 700 || wid < 1 || wid > 700){
                            obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请填写1~700之间的数字</div>');
                            return false;
                        }

                        //开始替换
                        $('.td-size .s-l').text(len).closest('div').find('input[name=packageLength]').val(len);
                        $('.td-size .s-w').text(wid).closest('div').find('input[name=packageWidth]').val(wid);
                        $('.td-size .s-h').text(hei).closest('div').find('input[name=packageHeight]').val(hei);

                    } else if(targetId == 'dia-serve'){ //服务模板

                        var serveId = obj.find('select[name="module"]').val();
                        if (serveId == null || serveId == 'undefined' || isNaN(serveId) || serveId < 0){
                            obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请选择运费模版</div>');
                            return false;
                        }

                        var serveName = obj.find('select[name="module"]').find('option:selected').text();
                        //console.log(serveName);
                        //开始替换下服务模板
                        $('.s-serve-name').html(serveName);
                        $('.s-serve-name').closest('td').find('input[name=promiseTemplateId]').val(serveId);
                        $('#dia-serve div.mo-contain').html('<img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading">');

                    }else if(targetId == 'dia-module'){ //运费模板

                        var freightId = obj.find('select[name=module]').val();
                        if (freightId == null || freightId == 'undefined' || isNaN(freightId) || freightId < 0){
                            obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请选择运费模版</div>');
                            return false;
                        }

                        var freightName = obj.find('select[name=module]').find('option:selected').text();

                        //开始替换运费模板
                        $('.s-mo').html(freightName);
                        $('.s-mo').closest('td').find('input[name=freightTemplateId]').val(freightId);
                        $('#dia-module div.mo-contain').html('<img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading">');

                    }else if (targetId == 'dia-price'){ //售价

                        var operateType = obj.find('select.sel-qu').val();
                        var num = obj.find('input[name=price]').val(); //增加或减少的值

                        if (operateType == 0){
                            if (num == '' || isNaN(num) || num < -100000 || num > 100000 || !checkNum(num, 2)){
                                obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请输入-100000.00~100000.00之间的数字（最多两位小数）</div>');
                                return false;
                            }
                        }else if(operateType == 1){
                            if (num == '' || isNaN(num) || num < -100 || num > 9999 || !checkNum(num, 2)){
                                obj.find('.error-box').html('<div class="board-error"><i class="icon-remove-sign red"></i>请输入-100.00~9999.00之间的数字（最多两位小数）</div>');
                                return false;
                            }
                            num = parseFloat(num);
                        }

                        $('.s-pr').each(function() { //所有的肯定是不一样的
                            var price = $(this).text(); //现有价格 --可能会有最大值和最小值
                            var minPrice = parseFloat(price);
                            var hasMaxPrice = false; //有最大值的标识
                            if (price.indexOf('-') > 0) {
                                var tmp = price.split('-');
                                var minPrice = parseFloat(tmp[0].trim());
                                var maxPrice = parseFloat(tmp[1].trim());
                                hasMaxPrice = true;
                            }

                            var priceCreaseNumObj = $(this).closest('td').find('input[name=priceCreaseNum]');
                            var priceCreaseTypeObj = $(this).closest('td').find('input[name=priceCreaseType]');
                            var priceCreaseNum = priceCreaseNumObj.val();
                            var priceCreaseType = priceCreaseTypeObj.val();

                            priceCreaseNum = priceCreaseNum == '' ? num : priceCreaseNum + ',' + num;
                            priceCreaseType = priceCreaseType == '' ? operateType : priceCreaseType + ',' + operateType;

                            //增加值
                            if (operateType == 0){
                                minPrice = minPrice + parseFloat(num);
                                if (hasMaxPrice){maxPrice = maxPrice + parseFloat(num);}
                            }else if (operateType == 1){
                                minPrice = minPrice * (1 + num/100);
                                if (hasMaxPrice){maxPrice = maxPrice * (1 + num/100);}
                            }

                            minPrice = minPrice.toFixed(2);
                            if (hasMaxPrice) {
                                maxPrice = maxPrice.toFixed(2);
                            }
                            var priceStr = minPrice + (hasMaxPrice ? ' - ' + maxPrice : '');
                            $(this).text(priceStr);
                            priceCreaseNumObj.val(priceCreaseNum);
                            priceCreaseTypeObj.val(priceCreaseType);
                        })
                    }else if (targetId == 'dia-detail'){
                        //选择的模板信息
                        var tTmpId = obj.find('input[name=t-temp-id]').val();
                        if (tTmpId != ''){
                            var tTmpName = obj.find('input[name=t-temp-name]').val();
                            var tTmpType = obj.find('input[name=t-temp-type]').val();
                            $('span.sl-t').text(tTmpName);
                            var divObj = $('span.sl-t').closest('div');
                            divObj.find('input[name=tModuleId]').val(tTmpId);
                            divObj.find('input[name=tModuleName]').val(tTmpName);
                            divObj.find('input[name=tModuleType]').val(tTmpType);
                        }

                        var bTmpId = obj.find('input[name=b-temp-id]').val();
                        if (bTmpId != ''){
                            var bTmpName = obj.find('input[name=b-temp-name]').val();
                            var bTmpType = obj.find('input[name=b-temp-type]').val();
                            $('span.sl-b').text(bTmpName);
                            var divObj = $('span.sl-b').closest('div');
                            divObj.find('input[name=bModuleId]').val(bTmpId);
                            divObj.find('input[name=bModuleName]').val(bTmpName);
                            divObj.find('input[name=bModuleType]').val(bTmpType);
                        }
                        obj.find('.sel-t,.sel-b').text('--');
                        obj.find('input:hidden').val('');
                    }
                    obj.find('input:text').val('');
                    obj.find('.error-box').empty();
                    layer.close(index);
                    $('[name=changed]').val('true');
                    $('.submitModify').removeClass('disabled');
                },
                no: function(index){
                    obj.find('input:text').val('');
                    obj.find('.error-box').empty();
                    if (targetId == 'dia-serve' || targetId == 'dia-module'){
                        $('#'+targetId+' div.mo-contain').html('<img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading">');
                    }else if (targetId == 'dia-detail'){
                        obj.find('.sel-t,.sel-b').text('--');
                        obj.find('input:hidden').val('');
                    }
                    layer.close(index);
                },
                close: function(index){
                    obj.find('input:text').val('');
                    obj.find('.error-box').empty();
                    if (targetId == 'dia-serve' || targetId == 'dia-module'){
                        $('#'+targetId+' div.mo-contain').html('<img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading">');
                    }else if (targetId == 'dia-detail'){
                        obj.find('.sel-t,.sel-b').text('--');
                        obj.find('input:hidden').val('');
                    }
                    layer.close(index);
                }
            });
        });

        //单位选择同时变更所显示的单位
        $(document).on('change', 'select[name=unit-sel]', function(){
            var optionStr = $(this).find('option:selected').text();
            $(this).closest('table').find('td span').html(optionStr);
        });

        //销售方式选择
        $(document).on('click', '#dia-sell input[name="sell-by"]', function(){
            if ($(this).val() == 1){
                $('input[name="sell-num"]').attr('disabled', false);
            }else {
                $('input[name="sell-num"]').attr('disabled', true);
            }
        });
        //重量选择
        $(document).on('click', '#dia-quality .rad[name=quality]', function(){
            if ($(this).val() == 0){
                $(this).closest('tr').find('input[name="at-quality"]').attr('disabled', false);
                $(this).closest('tr').siblings('tr').find('input[name="add-quality"]').attr('disabled', true);
            }else if ($(this).val() == 1){
                $(this).closest('tr').find('input[name="add-quality"]').attr('disabled', false);
                $(this).closest('tr').siblings('tr').find('input[name="at-quality"]').attr('disabled', true);
            }
        });
        //尺寸输入限制，只让输入数字或者删除
        $(document).on('keydown', '#dia-size .dia :text', function(e){
            if ((e.keyCode < 48 && e.keyCode != 8) || e.keyCode > 51){
                return false;
            }
        })

        //删除行记录
        $(document).on('click', '.pro-remove', function(){
            if ($(this).closest('td').find('input[name=changed]').val() == 'true'){ //已经有变更了
                if (!confirm('该产品已被修改，删除将放弃修改的内容，确定吗?')){
                    return false;
                }
            }
            $(this).closest('tbody').remove();
        });


        //添加更多产品方法
        $(document).on('click', 'a.addProduct', function(){
            var paginationObj = $('#addProSup .pagination');
            var currentObj = paginationObj.find('.page-number .currentpage');
            var totalOjb = paginationObj.find('.totalPage');
            $.layer({
                type: 1,   //0-4的选择,
                title: '选择产品',
                border: [0],
                closeBtn: [0],
                shadeClose: false,
                offset: ['50px', ''],//50px
                area: ['700px', '480px'],//480
                closeBtn: [0, true],
                btns: 2,
                btn: ['确定', '取消'],
                page: {
                    dom: '#addProSup'
                },
                success: function(){
                    $('#addProSup').removeClass('hide');
                    var page = 1; //刚开始选择，肯定是让看第一页
                    ajaxGetProductListData(page);
                },
                yes: function(index){
                    //把选择的产品组装下，添加到显示页面中

                    $(':checkbox[name=p-sel]:checked').each(function(){
                        var productId = $(this).val();
                        var str = createSelectedProductHtml(productId);
                        $('#up-proList').append(str);
                    });
                    layer.close(index);
                    $('#addProSup table.proList tbody').empty().html('<td colspan="5" style="text-align:center"><img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading" style="width:32px; height:32px"></td>');
                    $('#addProSup').find('input:text,select').val('');
                    currentObj.text(1);
                    totalOjb.text(1);
                },
                no: function(index){
                    layer.close(index);
                    $('#addProSup table.proList tbody').empty().html('<td colspan="5" style="text-align:center"><img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading" style="width:32px; height:32px"></td>');
                    $('#addProSup').find('input:text,select').val('');
                    currentObj.text(1);
                    totalOjb.text(1);
                },
                close: function(index){
                    layer.close(index);
                    $('#addProSup table.proList tbody').empty().html('<td colspan="5" style="text-align:center"><img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading" style="width:32px; height:32px"></td>');
                    $('#addProSup').find('input:text,select').val('');
                    currentObj.text(1);
                    totalOjb.text(1);
                }
            });
        });


        //添加更多产品时的上一页下一页
        $(document).on('click', '.page-links a, .page-skip-button', function(){
            var obj = $(this);
            var direct = obj.attr('class');
            var paginationObj = $('#addProSup .pagination');
            var currentPage = paginationObj.find('.page-number .currentpage').text();
            var totalPage = paginationObj.find('.totalPage').text();
            var page = currentPage;
            page = parseInt(page);
            if (direct == 'page-prev'){
                if (currentPage == 1){ //本来就是第一页了，不管它
                    return false;
                }
                page -= 1;
            }else if(direct == 'page-next'){
                if (currentPage == totalPage){ //当前页已经是最后一页了，也不管他
                    return false;
                }
                page += 1;
            }else if (direct == 'page-skip-button'){
                var goPage = $('.page-skip-text').val();
                //小于第一页或者大于最后一页或者等于当前页都不查询
                if (goPage < 1 || goPage > totalPage || goPage == currentPage){
                    return false;
                }
                page = parseInt(goPage);
            }
            ajaxGetProductListData(page);
        });


        //添加更多产品时的全选
        $(document).on('click', ':checkbox[name=p-selAll]', function(e){
            if (this.checked){
                $(':checkbox[name=p-sel]').prop('checked', true);
            }else {
                $(':checkbox[name=p-sel]').prop('checked', false);
            }
        });

        //添加更多产品时的筛选
        $(document).on('click', '.btn-submit-m', function(){
            ajaxGetProductListData(1);
        });

        //异步提交批量修改的数据信息
        $(document).on('click', '.submitModify', function(){

            var pListObj = $('#up-proList').find('tbody'); //产品列表
            if (pListObj.length == 0){
                showtips('请选择需要操作的产品信息。', 'alert-warning');
                return false;
            }

            $.layer({
                type: 1,   //0-4的选择,
                title: false,
                border: [0],
                closeBtn: [0],
                shadeClose: false,
                offset: ['50px', ''],//50px
                area: ['700px', '300px'],
                closeBtn: [1, true],
                btns: 0,
                page: {
                    dom: '#msgList'
                },
                success: function(){
                    $('#msgList, #msgList div.loading').removeClass('hide');
                    $('#msgList div.completed').addClass('hide');
                },
                close: function(index){
                    layer.close(index);
                    $('#msgList .alert-warning').empty();
                }
            });

            //一个个异步上传，成功的保存并删除本页显示的数据,失败的全部提交后显示总的信息
            var submitUrl = from == 'draft' ? '<?php echo admin_base_url("smt/smt_product/ajaxEditdraftData");?>' : '<?php echo admin_base_url("smt/smt_product/ajaxEditPostProduct");?>';

            var i= 0;
            var flag = false;
            pListObj.each(function(){
                i++;
                var postData = {}; //传过去的数据信息
                postData.subject = $(this).find('input[name=subject]').val().trim();
                postData.keywords = $(this).find('input[name=keywords]').val().trim();
                postData.productMoreKeywords1 = $(this).find('input[name=productMoreKeywords1]').val().trim();
                postData.productMoreKeywords2 = $(this).find('input[name=productMoreKeywords2]').val().trim();
                postData.packageWay = $(this).find('input[name=packageWay]').val().trim();
                postData.grossWeight = $(this).find('input[name=grossWeight]').val().trim();
                postData.packageLength = $(this).find('input[name=packageLength]').val().trim();
                postData.packageWidth = $(this).find('input[name=packageWidth]').val().trim();
                postData.packageHeight = $(this).find('input[name=packageHeight]').val().trim();

                //产品信息模块
                //顶部的
                postData.tModuleId = $(this).find('input[name=tModuleId]').val().trim();
                postData.tModuleName = $(this).find('input[name=tModuleName]').val().trim();
                postData.tModuleType = $(this).find('input[name=tModuleType]').val().trim();
                //底部的
                postData.bModuleId = $(this).find('input[name=bModuleId]').val().trim();
                postData.bModuleName = $(this).find('input[name=bModuleName]').val().trim();
                postData.bModuleType = $(this).find('input[name=bModuleType]').val().trim();

                postData.promiseTemplateId = $(this).find('input[name=promiseTemplateId]').val().trim();
                postData.freightTemplateId = $(this).find('input[name=freightTemplateId]').val().trim();
                postData.priceCreaseNum = $(this).find('input[name=priceCreaseNum]').val().trim();
                postData.priceCreaseType = $(this).find('input[name=priceCreaseType]').val().trim();
                postData.isSKU = $(this).find('input[name=isSKU]').val();
                postData.productId = $(this).find('input[name=productId]').val().trim();
                postData.categoryId = $(this).find('input[name=categoryId]').val().trim();
                postData.token_id = '<?php echo $token_id;?>';
                postData.changed = $(this).find('input[name=changed]').val();

                var obj = $(this);
                //提交
                if (i == pListObj.length) flag = true;
                ajaxSleep.post(submitUrl, postData, 'ajaxQueue', true, obj, flag);
            });
        });
    })

    String.prototype.replaceAll = function(reallyDo, replaceWith, ignoreCase) {
        if (!RegExp.prototype.isPrototypeOf(reallyDo)) {
            return this.replace(new RegExp(reallyDo, (ignoreCase ? "gi": "g")), replaceWith);
        } else {
            return this.replace(reallyDo, replaceWith);
        }
    }
    //异步获取账号运费或产品服务模板
    function getTemplateList(token_id, opt){
        var url = '';
        var target = 'dia-'+opt;
        switch(opt){
            case 'serve': //服务模板
                url = '{{route('smtProduct.getServiceTemplateList')}}'+token_id;
                break;
            case 'module'://运费模板
                url = '{{route('smtProduct.getFreightTemplateList')}}'+token_id;
                break;
        }

        if (url == ''){
            return false;
        }

        $.ajax({
            url: url,
            data: '',
            type: 'GET',
            dataType: 'JSON',
            success: function(data){
                if (data.status){
                    var newStr = '<select name="module" size="5" style="width: 370px;">';
                    $.each(data.data, function(index, el){
                        if (opt == 'module') {
                            newStr += '<option value="' + el.templateId + '">' + el.templateName + '</option>';
                        }else if (opt == 'serve'){
                            newStr += '<option value="' + el.serviceID + '">' + el.serviceName + '</option>';
                        }
                    });
                    newStr += '</select>';
                    $('#'+target+' div.mo-contain').html(newStr);
                }
            }
        });
    }

    /**
     * 添加更多产品时，异步获取更多产品(需要排除已选择的)
     */
    function ajaxGetProductListData(page){
        //产品ID
        var productIds = $('#up-proList tbody input[name=productId]').map(function(){
            return $(this).val();
        }).get().join(',');

        //下边是搜索条件部分
        var searchBar = $('#addProSup .search-bar');
        //搜索标题
        var subject = searchBar.find('input[name=subject]').val().trim();
        //产品分组
        var productGroup = searchBar.find('[name=productGroup]').val();
        //到期时间
        var offLineTime = searchBar.find('[name=offLineTime]').val();
        //来源
        var from = searchBar.find('[name=from]').val();

        //分页信息
        var paginationObj = $('#addProSup .pagination');
        var currentPage = paginationObj.find('.page-number .currentpage').text();

        var postData = {};
        postData.productIds = productIds;
        postData.page = page == currentPage ? currentPage : page; //传了哪页还是哪页为主
        postData.token_id = '<?php echo $token_id;?>';
        postData.subject = subject;
        postData.productGroup = productGroup;
        postData.offLineTime = offLineTime;
        postData.from = from;

        //异步获取到产品的分页信息
        $.ajax({
            url: '<?php echo admin_base_url("smt/smt_product/ajaxGetProductListExceptProducts");?>',
            data: postData,
            type: 'POST',
            dataType: 'JSON',
            success: function(data){
                if (data.status){ //获取数据成功了
                    var str = '';
                    moreProducts = data.data.products;
                    if (moreProducts) {
                        $.each(moreProducts, function (index, el) {
                            str += '<tr>' +
                            '<td class="product-pic-item">' +
                            '<div id="ph-handle-1" class="pic" style="width:52px; height:auto; *font-size:30px;">' +
                            '<a class="picRind" style="display:block;">' +
                            '<img class="order-list-product-img picCore" src="' + el.img + '" width="100" height="100">' +
                            '</a>' +
                            '</div>' +
                            '</td>' +
                            '<td><span>' + el.subject + '</span></td>' +
                            '<td><span>xiaoxiao</span></td>' +
                            '<td><span class="sku-price">零</span><span class="s-pr">' + (el.productMinPrice == el.productMaxPrice ? el.productMinPrice : el.productMinPrice+' - '+el.productMaxPrice) + '</span></td>' +
                            '<td class="td-right"><span><input type="checkbox" value="' + el.productId + '" name="p-sel"></span>' +
                            '</td>' +
                            '</tr>';
                        });
                        if (str == ''){
                            str += '<tr><td colspan="5">没有找到符合条件的信息。</td></tr>';
                        }
                    }
                    $('#addProSup table.proList tbody').empty().html(str);

                    //分页处理
                    if (data.data.pages > 1){
                        paginationObj.removeClass('hide').find('.totalPage').text(data.data.pages);
                        paginationObj.find('.page-number .currentpage').text(page);
                    }else {
                        paginationObj.addClass('hide').find('.totalPage').text(1);
                        paginationObj.find('.page-number .currentpage').text(1);
                    }
                }
            }
        });
    }

    //判断数字是否符合位置要求
    function checkNum(num, decimal){
        var pos = num.indexOf('.');
        if (pos != -1){ //检索到了小数点
            var newNum = num.substr(pos+1);
            if (newNum.length > decimal){
                return false;
            }
        }
        return true;
    }

    //创建批量修改产品时的html页面信息
    function createSelectedProductHtml(productId){
        var str = '';
        if (moreProducts){
            $.each(moreProducts, function(index, el){
                if (productId == el.productId){
                    str += '<tbody>' +
                    '<tr>' +
                    '<td>' +
                    '<img src="'+el.img+'" alt="产品图片" width="80" height="80">' +
                    '</td>' +
                    '<td>' +
                    '<span class="s-ti">'+el.subject+'</span>' +
                    '<input type="hidden" name="subject" value="'+el.subject+'">' +
                    '</td>' +
                    '<td>' +
                    '<span class="s-k1">'+el.keyword+'</span>' +
                    '<input type="hidden" name="keywords" value="'+el.keyword+'">' +
                    '<br>' +
                    '<span class="s-k2">'+el.productMoreKeywords1+'</span>' +
                    '<input type="hidden" name="productMoreKeywords1" value="'+el.productMoreKeywords1+'">' +
                    '<br>' +
                    '<span class="s-k3">'+el.productMoreKeywords2+'</span>' +
                    '<input type="hidden" name="productMoreKeywords2" value="'+el.productMoreKeywords2+'">' +
                    '</td>' +
                    '<td>' +
                    '<span class="s-se">按'+el.unitName+'出售</span>' +
                    '<input type="hidden" name="packageWay" value="'+(el.packageType == 1 ? 'true' : 'false')+'-'+el.productUnit+'-'+el.lotNum+'">' +
                    '</td>' +
                    '<td>' +
                    '<div>' +
                    '<span class="s-qu">'+el.grossWeight+'</span>公斤' +
                    '<input type="hidden" name="grossWeight" value="'+el.grossWeight+'">' +
                    '</div>' +
                    '</td>' +
                    '<td>' +
                    '<div class="td-size">' +
                    '<div>' +
                    '<span class="s1">长：</span>' +
                    '<span class="s-l">'+parseInt(el.packageLength)+'</span>' +
                    '<input type="hidden" name="packageLength" value="'+parseInt(el.packageLength)+'">厘米' +
                    '</div>' +
                    '<div>' +
                    '<span class="s1">宽：</span>' +
                    '<span class="s-w">'+parseInt(el.packageWidth)+'</span>' +
                    '<input type="hidden" name="packageWidth" value="'+parseInt(el.packageWidth)+'">厘米' +
                    '</div>' +
                    '<div>' +
                    '<span class="s1">高：</span>' +
                    '<span class="s-h">'+parseInt(el.packageHeight)+'</span>' +
                    '<input type="hidden" name="packageHeight" value="'+parseInt(el.packageHeight)+'">厘米' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '<td>' +
                    '<div class="td-size">' +
                    '<div>' +
                    '<span class="sl-t"></span>' +
                    '<input type="hidden" name="tModuleId">' +
                    '<input type="hidden" name="tModuleName">' +
                    '<input type="hidden" name="tModuleType">' +
                    '</div>' +
                    '<div>' +
                    '<span class="sl-b"></span>' +
                    '<input type="hidden" name="bModuleId">' +
                    '<input type="hidden" name="bModuleName">' +
                    '<input type="hidden" name="bModuleType">' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '<td>' +
                    '<span class="s-serve-name">'+el.promiseTemplateName+'</span>' +
                    '<input type="hidden" name="promiseTemplateId" value="'+el.promiseTemplateId+'">' +
                    '</td>' +
                    '<td>' +
                    '<span class="s-mo">'+el.templateName+'</span>' +
                    '<input type="hidden" name="freightTemplateId" value="'+el.freightTemplateId+'">' +
                    '</td>' +
                    '<td>' +
                    '<span class="sku-price" style="background: lightskyblue; color: white;">零</span>' +
                    '<span class="s-pr">'+(el.productMinPrice == el.productMaxPrice ? el.productMinPrice : el.productMinPrice+' - '+el.productMaxPrice)+'</span>' +
                    '<input type="hidden" name="priceCreaseNum" value="">' +
                    '<input type="hidden" name="priceCreaseType" value="">' +
                    '<input type="hidden" name="isSKU" value="true">' +
                    '</td>' +
                    '<td>' +
                    '<a class="icon-trash red bigger-130 pro-remove" href="#"></a>' +
                    '<input type="hidden" name="productId" value="'+el.productId+'">' +
                    '<input type="hidden" name="categoryId" value="'+el.categoryId+'">' +
                    '<input type="hidden" name="changed" value="false"/>' +
                    '</td>' +
                    '</tr>' +
                    '</tbody>';
                    return false;
                }
            });
        }
        return str;
    }

    //jquery队列扩展
    (function ($) {
        var jqXhr = {},
            ajaxRequest = {};

        $.ajaxQueue = function (settings) {
            var options = $.extend({ className: 'DEFEARTNAME' }, $.ajaxSettings, settings);
            var _complete = options.complete;
            $.extend(options, {
                complete: function () {
                    if (_complete)
                        _complete.apply(this, arguments);

                    if ($(document).queue(options.className).length > 0) {
                        $(document).dequeue(options.className);
                    } else {
                        ajaxRequest[options.className] = false;
                    }
                }
            });

            $(document).queue(options.className, function () {
                $.ajax(options);
            });

            if ($(document).queue(options.className).length == 1 && !ajaxRequest[options.className]) {
                ajaxRequest[options.className] = true;
                $(document).dequeue(options.className);
            }
        };

        $.ajaxSingle = function (settings) {
            var options = $.extend({ className: 'DEFEARTNAME' }, $.ajaxSettings, settings);

            if (jqXhr[options.className]) {
                jqXhr[options.className].abort();
            }

            jqXhr[options.className] = $.ajax(options);
        };

    })(jQuery);

    //调用jquery队列的方法
    var ajaxSleep = (function () {
        var _settings = {
            cache: false
        };
        return {
            post: function (url, params, mode, isAsync, obj, flag) {
                var mode = mode || 'ajax',
                    isAsync = isAsync || false;

                $[mode]($.extend(_settings, {
                    type: 'POST',
                    url: url,
                    data: params,
                    async: isAsync,
                    className: 'POST',
                    success: function(data){
                        var data = eval('(' + data + ')'); //返回的居然还是json字符串
                        //对象操作
                        if (flag){
                            $('#msgList div.loading').addClass('hide');
                            $('#msgList div.completed').removeClass('hide');
                        }
                        if (data.status){
                            if (data.info == ''){
                                obj.remove();
                                return false;
                            }
                        }
                        $('#msgList .alert-warning').append('<div>'+data.info+'</div>');
                    }
                }));
            }
        };
    } ());
</script>
@stop