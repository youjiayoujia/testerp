<?php
$tracking_no = $model->tracking_no;
$url = "http://120.55.205.164/api/RmlLabelcn?productBarcode=".$tracking_no."";
$headers = array(                                //Token生成规则  base64_encode('xxx:xxx');  账号：密码0B0437995000008492054
        "Content-type: application/json;charset=utf-8",
        "Authorization:Basic U2VsbG1vcmU6U2VsbG1vcmU4ODg="
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 0);
$data = curl_exec($ch);
curl_close($ch);

header("Content-type: application/pdf");
echo $data;exit;
$filename = ''.$tracking_no.'.pdf';
$fp = fopen($filename,'w');
fwrite($fp,$data);
fclose($fp);
header("Content-type: application/pdf");
readfile("".$tracking_no.".pdf");   //直接读取
unlink("".$tracking_no.".pdf");// 请求循环
exit;

 ?>