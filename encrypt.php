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

if(isset($_POST["submittedForm"])){
    $display_link = "https://disciple-membercreated.herokuapp.com/?_mc=" . encrypt_and_sign(json_encode([
        "apiHost" => $_POST["apiHost"],
        "apiKey" => $_POST["apiKey"],
        "authenticatedLinkSecret" => $_POST["authenticatedLinkSecret"],
    ]), hex2bin(getenv("secretEncryptKey")));
}

if(isset($display_link)){
    echo "Your group create link is $display_link<BR><BR>";
}
?>
<form method="POST">
    Community URL: <input type=text name="apiHost" placeholder="https://my-community.disciplemedia.com" ><br>
    API Key: <input type=text name="apiKey" placeholder="abcdefg123" ><br>
    Authenticated Links Shared Secret: <input type=text name="authenticatedLinkSecret" placeholder="abcdefg123" ><br>
    <input type="submit" name="submittedForm" value="Create Link">
</form>