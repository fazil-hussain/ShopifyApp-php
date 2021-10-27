<?php
$data = $_GET;
$shared_secret = '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';

/*
============================================
    Verifying Singnature
============================================
*/
function validateSignature($query, $shared_secret)
{

    if (!is_array($query) || empty($query['signature']) || !is_string($query['signature']))
        return false;

    ksort($query);

    $dataString = array();
    foreach ($query as $key => $value) {
        if ($key != 'signature')
            $dataString[] = "{$key}={$value}";
    }

    $string = implode("", $dataString);

    if (version_compare(PHP_VERSION, '5.3.0', '>='))
        $signature = hash_hmac('sha256', $string, $shared_secret);
    else
        $signature = bin2hex(mhash(MHASH_SHA256, $string, $shared_secret));

    // secure compare        
    return hash_equals($query['signature'], $signature);
}
$verified = validateSignature($data, $shared_secret);

/*
============================================
    Verified Singnature Request 
============================================
*/
if ($verified) {
    echo 'verified';
} else {
    echo 'unverified';
}
