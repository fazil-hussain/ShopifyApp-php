<?php
/*
============================================
    Installing App Into Store
============================================
*/
$_API_KEY = 'c086c1ff8ecbd235dd6aa7cc54b77f65';
$_NGROK_URL = 'https://eeed-39-52-114-187.ngrok.io';
$shop = $_GET['shop'];
// echo $shop;
$scope = 'read_products,write_products,read_orders,write_orders,read_script_tags, write_script_tags';
$redirect_uri = $_NGROK_URL.'/shopiapp/token.php';
$nonce = bin2hex(random_bytes( 12 ));
$access_mode = 'per-user';
/*
============================================
    Oauth URL
============================================
*/
$oauth_url = 'https://' . $shop . '/admin/oauth/authorize?client_id=' . $_API_KEY . '&scope=' . $scope . '&redirect_uri=' . $redirect_uri . '&state=' . $nonce . '&grant_options[]=' . $access_mode;

header("location: " .$oauth_url);
exit();