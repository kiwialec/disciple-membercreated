<?php
/* 
    This file is used to create the encrypted link containing api key, authenticated link secret, etc. It's really only used by Disciple team, so doesn't need to be pretty
*/


function encrypt($data, $key) {
    $iv = random_bytes(16);
    $ciphertext = openssl_encrypt($data, "AES-256-CBC", $key, 0, $iv);

    return $ciphertext . "--" . base64_encode($iv);
}

function sign($data, $key) {
    $encoded_data = base64_encode($data);
    return $encoded_data . "--" . hash_hmac('sha1', $encoded_data, $key);
}

function encrypt_and_sign($data, $key) {
    return sign(encrypt($data, $key), $key);
}

?>