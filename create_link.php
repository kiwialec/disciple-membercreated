<?php
require_once("lib/encrypt.php");

if(isset($_POST["submittedForm"])){
    $display_link = "https://disciple-membercreated.herokuapp.com/?_mc=" . encrypt_and_sign(json_encode([
        "apiHost" => $_POST["apiHost"],
        "apiKey" => $_POST["apiKey"],
        "authenticatedLinkSecret" => $_POST["authenticatedLinkSecret"],
        "createdAt" => time()
        
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