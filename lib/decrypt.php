<?php

function getEncryptedData($params){
    $secret = $params["secret"];
    $haveEncryptedData = isset($_GET[$params["dataVar"]]);
    if(!$haveEncryptedData) {
        return ["status" => false];
    }

    $encrypted_data = $_GET[$params["dataVar"]];
    $key = hex2bin($secret);
    $decryped_data = decrypt_and_verify($encrypted_data, $key);
    return ["status" => true, "encrypted_data" => $encrypted_data, "decrypted_data" => $decryped_data];

}

function verify($signed_data, $key) {
    $pieces = explode('--', $signed_data);
    $data = $pieces[0];
    $digest = $pieces[1];

    if (hash_equals(hash_hmac('sha1', $data, $key), $digest)) {
        return $data;
    } else {
        return false;
    }
}

function decrypt($encrypted_data, $key) {
    $pieces = explode("--", base64_decode($encrypted_data));
    $data = $pieces[0];
    $iv = base64_decode($pieces[1], true);

    return openssl_decrypt($data, "AES-256-CBC", $key, 0, $iv);
}

function decrypt_and_verify($encrypted_data, $key) {
    $encrypted_data = verify($encrypted_data, $key);

    if ($encrypted_data) {
        return json_decode(decrypt($encrypted_data, $key),true);
    } else {
        return false;
    }
}
?>