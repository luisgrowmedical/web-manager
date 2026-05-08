<?php
$url = 'https://urologoencuernavaca.com.mx/wp-json/web-manager/v1/stats?api_key=c672be5f626658da0ae394b35c8cd0668a5f525ec0446442cd45b846b625f67d';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-API-KEY: c672be5f626658da0ae394b35c8cd0668a5f525ec0446442cd45b846b625f67d']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
echo curl_exec($ch);
