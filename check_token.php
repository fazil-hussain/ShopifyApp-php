<?php

/*
============================================
    Geeting Data from store
============================================
*/
$getdata = "SELECT * FROM shops WHERE shop_url='" . $params['shop'] . "'";
$result = mysqli_query($connection, $getdata);

if ($result->num_rows < 1) {
    header("location: install.php?shop=" . $_GET['shop']);
    exit();
} else {
    $dataa = $result->fetch_assoc();

    /*
============================================
    Settig Value to Shooify Class Functions
============================================
*/
    $shopify->set_url($dataa['shop_url']);
    $shopify->set_token($dataa['access_token']);

    /*
============================================
    Api Call to Get SHop Details
============================================
*/
    $shop = $shopify->rest_api('/admin/api/2021-04/shop.json', array(), 'GET');
    // echo print_r($shop);
    $response = json_decode($shop['body'], true);
    
    if (array_key_exists('errors', $response)) {
        header("location: install.php?shop=" . $_GET['shop']);
        exit();
    }
}