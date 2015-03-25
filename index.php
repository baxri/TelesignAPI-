<?php

require 'telesign.php';

$telesign = new TeleSign();

$digits = 7;
$code = rand(pow(10, $digits-1), pow(10, $digits)-1);

$telesign->sendSMS($code);

echo '<pre>';
print_r($telesign);
die;

