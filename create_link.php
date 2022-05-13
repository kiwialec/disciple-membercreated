<?php
require_once("lib/encrypt.php");
require_once("template/header.html");
if(isset($_POST["submittedForm"])){
    $allowedVisibilityTypes = [];
    if($_POST["visibilitySecret"] == "on") $allowedVisibilityTypes[] = "secret";
    if($_POST["visibilityPrivate"] == "on") $allowedVisibilityTypes[] = "private";
    if($_POST["visibilityPublic"] == "on") $allowedVisibilityTypes[] = "public";
    if($_POST["visibilityMandatory"] == "on") $allowedVisibilityTypes[] = "mandatory";

    $display_link = "https://disciple-membercreated.herokuapp.com/?_mc=" . encrypt_and_sign(json_encode([
        "apiHost" => $_POST["apiHost"],
        "apiKey" => $_POST["apiKey"],
        "authenticatedLinkSecret" => $_POST["authenticatedLinkSecret"],
        "createdAt" => time(),
        "allowed_visibility" => $allowedVisibilityTypes
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
    <strong>Allowed Group Types</strong><br>
    <input type=checkbox name="visibilitySecret" checked> Secret<br>
    <input type=checkbox name="visibilityPrivate" checked> Private<br>
    <input type=checkbox name="visibilityPublic"> Public<br>
    <input type=checkbox name="visibilityMandatory"> Mandatory<br>
    <input type="submit" name="submittedForm" value="Create Link">
</form>

<?php
require_once("template/footer.html");