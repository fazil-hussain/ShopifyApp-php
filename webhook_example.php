<?php
define('SHOPIFY_APP_SECRET', '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');

/*
============================================
    Verifying Webhook 
============================================
*/
function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$shop_domain = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
$data = file_get_contents('php://input');

$verified = verify_webhook($data, $hmac_header);
// error_log('Webhook verified: '.var_export($verified, true)); //check error.log to see the result
$store_directory = 'data/stores/' . $shop_domain;
if(!file_exists($store_directory)){
    mkdir($store_directory, 0777, true);
    $file = fopen($store_directory.'/store.txt','w');
    fwrite($file,'is the store is verifiied: '.$verified);
}
?>