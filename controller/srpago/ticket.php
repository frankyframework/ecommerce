<?php
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$MyRequest->getRequest('url'));
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0");
echo curl_exec($ch);
curl_close($ch);
?>