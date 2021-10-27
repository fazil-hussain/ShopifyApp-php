<?php
include_once('inc/conn.php');
$token = $_POST['token'];
$token_array = explode('.', $token);
$assoc_token = array_combine(['header', 'payload', 'signature'], $token_array);
$payload = json_decode(base64_decode($assoc_token['payload']), true);
$shop = parse_url($payload['dest']);
$now = new DateTime();
$is_future = $payload['exp'] > $now->getTimestamp() ? true : false;
$is_past = $payload['exp'] > $now->getTimestamp();
$secret_key = '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';
$hash_token = hash_hmac('sha256', $assoc_token['header'] . '.' . $assoc_token['payload'], $secret_key, true);
$hash_token = rtrim(strtr(base64_encode($hash_token), '+/', '-_'), '=');



$query = "INSERT INTO sessions (shop_url, session_token) VALUES ('" . $shop['host'] . "', '" . $token . "') ON DUPLICATE KEY UPDATE session_token='" . $token . "'";
if ($connection->query($query)) {
    // do somehting here
    // array_push($response['response'],array('mysql_result' => true));
} else {
    // do something here
    // array_push($response['response'],array('mysql_result' => false));
}

if (!$is_future || !$is_past) {
    $response = array("error" => "token is expierd");
    echo json_encode($response);
    return;
}

if ($hash_token !== $assoc_token['signature']) {
    $response = array("error" => "token is invalid");
    echo json_encode($response);
    return;
}
$response = array(
    'shop' => $shop,
    'success' => true
);
echo json_encode($response);
