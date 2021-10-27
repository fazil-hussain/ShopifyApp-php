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
include_once('inc/header.php');

/*
============================================
    Creating WebHook
============================================
*/
$webhook_data = ([
  "webhook" => [
    "topic" => "products/create",
    "address" => "https://4728-2400-adc7-3101-5c00-60f0-16e3-483e-71ff.ngrok.io/shopiapp/webhook_example.php",
    "format" => "json",
  ]
  ]);

  /*
============================================
    Api Call To Create WebHook
============================================
*/
$webhook = $shopify->rest_api('/admin/api/2021-10/webhooks.json',$webhook_data, 'POST');
$response = json_decode($webhook['body'], true);
echo print_r($response);