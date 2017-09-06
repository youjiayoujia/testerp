<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }

    td {
        white-space: nowrap;
    }
</style>
<div id="main_frame_main">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" style="font-size:10px;">&nbsp;</td>
                        <td align="center" style="font-size:10px;">Verkaufsmanager Pro: Rechnung mit ausgewiesener Mehrwertsteuer drucken für top-de</td>
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="right">
                <table border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td>&nbsp;</td>
                        <td><span><b>Maitoo GmbH</b></span>
                            <br/>
                            Gerokstraße 6<br/>
                            01307,Dresden<br/>
                            Deutschland<br/>
                            gw13028899202@163.com
                        </td>
                    </tr>
                </table>
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tr>
                        <td>
                            <span><b>{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}</b></span><br/>
                            {{ $model->shipping_address ? $model->shipping_address : '' }}&nbsp;{{ $model->shipping_address1 ? $model->shipping_address1 : '' }}<br/>
                            {{ $model->shipping_city ? $model->shipping_city : '' }}<br/>
                            {{ $model->shipping_state ? $model->shipping_state : '' }}&nbsp;{{ $model->shipping_zipcode ? $model->shipping_zipcode : '' }}<br/>
                            {{ $model->country ? $model->country->name : '' }}
                        </td>
                        <td width="200" align="right" valign="top" style="font-size:16px;"><b>Rechnungsnr.: 62</b></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center">&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2" align="right">
                            <table border="2" cellspacing="0" cellpadding="3">
                                <tr style="color:#666666;">
                                    <th>Datum</th>
                                    <th>Verkaufsprotokollnr.</th>
                                </tr>
                                <tr>
                                    <td align="center">{{ date('d. M. Y', strtotime($model->payment_date ? $model->payment_date : '')) }}</td>
                                    <td align="center">{{ $model->ordernum ? $model->ordernum : '' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td width="300">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" border="2" cellpadding="3" cellspacing="0">
                    <tr style="color:#666666;">
                        <th>Stückzahl</th>
                        <th>Artikelnummer</th>
                        <th align="left">Artikelbezeichnung</th>
                        <th>Netto</th>
                        <th>MwSt.</th>
                        <th>Preis</th>
                        <th>Zwischensumme</th>
                    </tr>
                    <tr>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="left" style=" white-space:normal;"></td>
                        <td align="center"></td>
                        <td align="center">19%</td>
                        <td align="center"></td>
                        <td align="right"></td>
                    </tr>
                    <tr>
                        <td align="center">1</td>
                        <td align="center">&nbsp;</td>
                        <td align="left" style=" white-space:normal;">Verpackung und Versand</td>
                        <td align="center"></td>
                        <td align="center">19%</td>
                        <td align="center"></td>
                        <td align="right"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="right">
                <table border="0" cellspacing="6" cellpadding="0">
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="right">Gesamtnettobetrag</td>
                        <td align="right"></td>
                    </tr>
                    <tr>
                        <td align="right">Mehrwertsteuerbetrag</td>
                        <td align="right"></td>
                    </tr>
                    <tr>
                        <td align="right">Rabatte (-) oder weitere Kosten (+):</td>
                        <td align="right"></td>
                    </tr>
                    <tr>
                        <td align="right"><b>Gesamtbetrag inkl. MwSt. </b></td>
                        <td align="right"></td>
                    </tr>
                    <tr>
                        <td align="right">WEEE-Reg.-Nr.:</td>
                        <td align="right">DE 92563990</td>
                    </tr>
                    <tr>
                        <td align="right"><div>Ust-ID:</div></td>
                        <td align="right">DE 2245963375</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center">PS：Dabei bitte wir den After-Sales-Service von 2 Jahren.</td>
        </tr>
    </table>
</div>
