<?php
require_once("telesign.php");

$customer_id = '6470CD34-7794-4543-A70F-AFBD04DF53C5';
$secret_key = 'yvPPGgvPbYfBI65juyXVkNoI0lSFa82K4YxzgbTOoYl4UXPn/WFY6KZzPBTjAtGk7TwEfPUgoXGlHv7xVUh31Q==';

$verify = new Verify($customer_id, $secret_key);

$digits = 7;
$code = rand(pow(10, $digits-1), pow(10, $digits)-1);

$phone_number = "13103409700";
$result = $verify->sms($phone_number, $code);

echo '<pre>';
print_r($result);
die;