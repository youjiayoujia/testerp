<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>打印wish邮面单</title>
</head>
<body>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }

    #main_frame_box {
        width: 382px;
        margin: 0 auto;
        height: 378px;
        overflow: hidden;
        margin-bottom: 2px;
    }

    td {
        border: 1px solid #000;
        border-bottom: none;
    }
</style>
<div id="main_frame_box">
    <div style="width:379px;height:150px;border:1px solid #000;border-bottom:none;">
        <p style="float:left;width:140px;height:30px;">
            <img src="{{ asset('picture/EUB01.jpg') }}"/>
        </p>
        <p style="float:left;width:120px;height:30px;text-align:center;font-size:12px;font-weight:bold;line-height:30px;border-right:1px solid #000;">
            Small Packet By Air
        </p>
        <p style="float:left;width:50px;line-height:30px;text-align:center;font-weight:bold;height:30px;border-right:1px solid #000;">
            {{ $model->country ? $model->country->code : '' }}
            @if($model->country)
                @foreach(['RU' => 21,'US' => 22,'GB' => 23,'BR' => 24,
                          'AU' => 25,'FR' => 26,'ES' => 27,'CA' => 28,
                          'IL' => 29,'IT' => 30,'DE' => 31,'CL' => 32,
                          'SE' => 33,'BY' => 34,'NO' => 35,'NL' => 36,
                          'UA' => 37,'CH' => 38,'MX' => 39,'PL' => 40,] as $key => $value )
                    @if($key == $model->country->code)
                        {{ $value }}
                    @endif
                @endforeach
            @endif
        </p>
        <p style="float:left;width:64px;height:30px;line-height:30px;font-size:14px;font-weight:bold;">
            wish郵
        </p>
        <p style="float:left;width:140px;">
            <span style="word-wrap: break-word;width:130px;height:84px;font-family:STHeiti;display:inline-block;border-bottom:1px solid #000;font-size:8px;padding-left:4px;">
            From:<br/>
            SLME<br/>
                {{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->address) : '') : '' }}<br/>
            <b style="font-weight:bold;">Phone:{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->phone) : '') : '' }}</b>
            </span>
            <span style="width:140px;font-size:10px;background:#fff;display:inline-block;border-bottom:1px solid #000;">
            自编号:{{ $model->id }}
            </span>
        </p>
        <p style="float:left;width:235px;height:99px;border:1px solid #000;border-right:none;font-family:STHeiti;font-size:12px;">
            <span style="font-size:12px;font-family:STHeiti;">Ship To:</span>
            {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}&nbsp;&nbsp;&nbsp;&nbsp;<br/>
            {{ $model->shipping_address . ' ' . $model->shipping_address1 }}
            {{ $model->shipping_city }}<br/>
            {{ $model->shipping_state }}
            {{ $model->shipping_zipcode }}<br/>
            {{ $model->country ? $model->country->name : '' }}&nbsp;
            @if($model->country)
                @foreach(['1' => "日本,",
                          '2' => "奥地利,保加利亚,韩国,马来西亚,斯洛伐克,泰国,新加坡,印度,印度尼西亚",
                          '3' => "爱尔兰,比利时,波兰,丹麦,芬兰,捷克,葡萄牙,瑞士,希腊,意大利",
                          '4' => "阿曼,阿塞拜疆,塔吉克斯坦,土库曼斯坦,爱沙尼亚,巴基斯坦,白俄罗斯,波黑,朝鲜,法国,菲律宾,哈萨克斯坦,吉尔吉斯斯坦,加拿大,卡塔尔,拉脱维亚,立陶宛,卢森堡,罗马尼亚,马耳他,美国,蒙古,塞浦路斯,斯里兰卡,斯洛文尼亚,土耳其,乌克兰,乌兹别克斯坦,西班牙,新西兰,叙利亚,亚美尼亚,越南",
                          '5' => "阿尔巴尼亚,阿尔及利亚,阿富汗,阿根廷,阿联酋,黑山,埃及,美属萨摩亚,埃塞俄比亚,安道尔,安哥拉,巴布亚新几内亚,巴林,巴拿马,巴西,贝宁,冰岛,博茨瓦纳,不丹,布隆迪,赤道几内亚,多哥,厄瓜多尔,法罗群岛,法属波利尼西亚,梵蒂冈,斐济,冈比亚,哥伦比亚,格鲁吉亚,古巴,关岛,基里巴斯,吉布提,几内亚,几内亚比绍,加纳,加蓬,柬埔寨,津巴布韦,喀麦隆,科特迪瓦,科威特,肯尼亚,库克群岛,老挝,黎巴嫩,利比里亚,利比亚,列支敦士登,卢旺达,马达加斯加,马德拉群岛,马尔代夫,马拉维,马里,马其顿,马绍尔群岛,毛里求斯,毛里塔尼亚,秘鲁,密克罗尼西亚,缅甸,摩尔多瓦,摩洛哥,摩纳哥,莫桑比克,墨西哥,纳米比亚,南非,尼泊尔,尼日尔,尼日利亚,塞尔维亚,塞拉利昂,塞内加尔,塞舌尔,圣马力诺,斯威士兰,苏丹,苏里南,索马里,坦桑尼亚,汤加,突尼斯,瓦努阿图,委内瑞拉,文莱,乌干达,新喀里多尼亚,亚速尔群岛,也门,伊拉克,伊朗,约旦,赞比亚,直布罗陀",
                          '6' => "阿鲁巴,安圭拉,刚果,巴巴多斯,巴哈马,巴拉圭,百慕大,波多黎各,玻利维亚,伯利兹,多米尼加,法属圭亚那,哥斯达黎加,格林纳达,格陵兰岛,瓜德罗普,圭亚那,海地,荷属安的列斯群岛,洪都拉斯,开曼群岛,马提尼克,尼加拉瓜,萨尔瓦多,圣皮埃尔和密克隆,特立尼达和多巴哥,危地马拉,乌拉圭,牙买加,智利",
                          '7' => "俄罗斯,",
                          '8' => "澳大利亚,英国,瑞典,以色列,德国,挪威,荷兰,克罗地亚,匈牙利"] as $key => $value)
                    @if(in_array($model->country->cn_name, explode(',',$value)))
                        {{ $key }}
                    @endif
                @endforeach
            @endif
            {{ $model->country ? $model->country->cn_name : '' }}&nbsp;
            @if(!empty($model->country ? $model->country->cn_name : ''))
                @if($model->country->cn_name == '俄罗斯')
                    @if(substr($model->shipping_zipcode, 0, 1) < 5)
                        {{ 'EKA小' }}
                    @else
                        @foreach(['EKA大' => ['60','61','62'],
                                  'OVB' => ['63','64','65','66','67'],
                                  'VVO' => ['68','69']] as $key => $value)
                            @if(in_array(substr($model->shipping_zipcode, 0, 2), $value[$key]))
                                @if($key == 'VVO')
                                    {{ 'MOW' }}
                                @else
                                    {{ $key }}
                                @endif
                            @endif
                        @endforeach
                    @endif
                @elseif($model->country->cn_name == '美国')
                    @foreach(['0' => '① (USJFKA)',
                              '1' => '① (USJFKA)',
                              '2' => '① (USJFKA)',
                              '3' => '① (USJFKA)',
                              '4' => '① (USJFKA)',
                              '5' => '① (USJFKA)',
                              '6' => '① (USJFKA)',
                              '7' => '② (USSFOA)',
                              '8' => '② (USSFOA)',
                              '9' => '③ (USLAXA)'] as $key => $value)
                        @if(substr($model->shipping_zipcode, 0, 1) == $key)
                            {{ $value }}
                        @endif
                    @endforeach
                @else
                    {{ '' }}
                @endif
            @endif
            <br/>
            Phone：{{ $model->shipping_phone }}
        </p>
    </div>
    <table border="0" style="width:382px;height:155px;" cellspacing="0" cellpadding="0">
        <tr height="45">
            <td colspan="3" style="border-top:none;">
                <p style="width:86px;text-align:center;font-weight:bold;line-height:50px;height:50px;float:left;">
                    Untracked
                </p>
                <p style="width:270px;float:left;text-align:center;">
                    <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}">
                    <br/>{{ $model->tracking_no }}
                </p>
            </td>
        </tr>
        <tr height="15">
            <td colspan="3" style="font-size:10px;font-weight:bold;">
                退件单位:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $model->logistics ? ($model->logistics->emailTemplate ? ($model->logistics->emailTemplate->unit) : '') : '' }}
            </td>
        </tr>
        <tr style="height:15px;font-weight:bold;font-size:10px;text-align:center;">
            <td width="70%" style="border-right:none;">
                Description of Contents
            </td>
            <td width="15%" style="border-right:none;">
                Kg
            </td>
            <td width="15%">
                Val(US $)
            </td>
        </tr>
        <tr style="font-size:12px;">
            <td width="70%" style="border-right:none;">
                {{ $model->getDeclaredInfo()['declared_en'] }}
            </td>
            <td width="15%" style="border-right:none;">
                {{ $model->getDeclaredInfo()['weight'] }}
            </td>
            <td width="15%">
                {{ $model->getDeclaredInfo()['declared_value'] }}
            </td>
        </tr>
        <tr height="15" style="font-size:12px;">
            <td width="70%" style="border-right:none;font-size:12px;">
                Totalg Gross Weight(kg)
            </td>
            <td width="15%" style="border-right:none;">
                {{ $model->total_weight }}
            </td>
            <td width="15%">
                {{ $model->total_price }}
            </td>
        </tr>
        <tr height="55">
            <td colspan="3" style="border-bottom:1px solid #000;font-size:9px;">
                I certify that the particulars given in this declaration are correct and this item does not contain any
                dangerous
                articles prohibited by legislation or by postal or customers regulations.<br/>
                <span style="font-weight:bold;font-size:11px;">Sender's signiture& Data Signed :SLME {{ date('Y-m-d') }}</span>
                &nbsp;&nbsp;&nbsp;
                <span style="font-weight:bold;display:inline-block;border:2px solid #000;width:83px;line-height:15px;height:15px;font-size:14px;">
                已验视CN22
                </span>
            </td>
        </tr>
    </table>
    <div style="width:382px;height:40px;margin:0 auto;font-size:10px;white-space:normal;overflow:hidden;">
        {{ $model->sku_info }}
        <b style="float:right;font-size:11px;">
            【{{ $model->logistics ? $model->logistics->logistics_code : '' }}】
        </b>
    </div>
</div>
</body>
</html>