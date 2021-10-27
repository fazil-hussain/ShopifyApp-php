<?php
include_once('inc/shopify.php');
include_once('inc/conn.php');
$shopify = new Shopify();


$data = json_decode(file_get_contents("php://input"), true);
$shopify->set_url($data['shop']);
$getdata = "SELECT * FROM shops WHERE shop_url='" . $data['shop'] . "'";
$result = mysqli_query($connection, $getdata);
if ($result->num_rows < 1) {
   echo 'Ther is no shop' . $data['shop'] . 'of this name';
   return;
}
$store_data = $result->fetch_assoc();
$shopify->set_token($store_data['access_token']);

$gql = $shopify->graphql(array('query' => $data['query']));
$gql = json_decode($gql['body'], true);
echo json_encode($gql);
