<?php
include_once("inc/conn.php");
include_once("inc/shopify.php");

/*
============================================
    Checking Shoop Details
============================================
*/


$shopify = new Shopify();
$params = $_GET;

include_once("check_token.php");

/*
============================================
    Credit Array to make Credit
============================================
*/
$array = ([
    "application_credit" => [
        "description" => "application credit for refund",
        "amount" => 5.0,
        "test" => true
    ]
]);

/*
============================================
    Api Call To create Credit
============================================
*/
$credit = $shopify->rest_api('/admin/api/2021-10/application_credits.json', $array, 'POST');
$credit = json_decode($credit['body'], true);
print_r($credit);