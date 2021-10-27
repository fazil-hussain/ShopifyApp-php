<?php
include_once("inc/conn.php");
$api_key = 'c086c1ff8ecbd235dd6aa7cc54b77f65';

// Set variables for our request
$shared_secret = "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";
$params = $_GET; // Retrieve all request parameters
$shop_url = $params['shop'];
$hmac = $_GET['hmac']; // Retrieve HMAC request parameter
$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
ksort($params); // Sort params lexographically

// Compute SHA256 digest
$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

// Use hmac data to check that the response is from Shopify or not
if (hash_equals($hmac, $computed_hmac)) {
    // Set variables for our request
    $query = array(
        "client_id" => $api_key, // Your API key
        "client_secret" => $shared_secret, // Your app credentials (secret key)
        "code" => $params['code'] // Grab the access key from the URL
    );

    // Generate access token URL
    $access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";

    // Configure curl client and execute request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $access_token_url);
    curl_setopt($ch, CURLOPT_POST, count($query));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
    $result = curl_exec($ch);
    curl_close($ch);

    // Store the access token
    $result = json_decode($result, true);
    $access_token = $result['access_token'];

    $qry = "INSERT INTO shops(shop_url,access_token,hmac,install_date) VALUES('" . $shop_url . "','" . $access_token . "','" . $hmac . "', '" . date("Y-m-d H:i:s") . "') ON DUPLICATE KEY UPDATE access_token= '" . $access_token . "'";

    if (mysqli_query($connection, $qry)) {

        echo "<script>top.window.location= 'https://" . $shop_url . "/admin/apps'</script>";
        die;
        // header("location: https://" . $shop_url . "/admin/apps");
        // exit();
    } else {
        echo "Error: " . $qry . "<br>" . mysqli_error($connection);
    }
} else {
    echo 'not validated';
}
