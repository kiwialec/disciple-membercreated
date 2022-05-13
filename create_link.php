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
    echo "Your group create link is:<BR><textarea class='form-control'>$display_link</textarea><BR><BR>";
}
?>
<form method="POST">

    <div class="form-outline m-5">
        <input type="text" id="groupName" name="apiHost" class="form-control"  />
        <label class="form-label" for="apiHost">Community URL</label>
        <div class="form-helper">https://my-community.disciplemedia.com</div>
    </div>
    API Key: <input type=text name="apiKey" placeholder="abcdefg123" ><br>
    <div class="form-outline m-5">
        <input type="text" id="groupName" name="apiKey" class="form-control"  />
        <label class="form-label" for="apiKey">API Key</label>
    </div>
    <div class="form-outline m-5">
        <input type="text" id="groupName" name="authenticatedLinkSecret" class="form-control"  />
        <label class="form-label" for="authenticatedLinkSecret">Authenticated Links Shared Secret</label>
    </div>
    <strong>Allowed Group Types</strong><br>
    <input class="form-check-input" type=checkbox name="visibilitySecret" checked> Secret<br>
    <input class="form-check-input" type=checkbox name="visibilityPrivate" checked> Private<br>
    <input class="form-check-input" type=checkbox name="visibilityPublic"> Public<br>
    <input class="form-check-input" type=checkbox name="visibilityMandatory"> Mandatory<br>
    
    <input class="btn btn-primary" type="submit" name="submittedForm" value="Create Link">
</form>

<?php
require_once("template/footer.html");