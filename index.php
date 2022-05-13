<?php
require_once("lib/decrypt.php");
require_once("lib/disciple.php");
require_once("auth_header.php");

require_once("template/header.html");

$mc = getEncryptedData(["dataVar" => "_mc", "secret"=> getenv("secretEncryptKey")]);

$authenticated_user = isAuthenticated($mc);
if($authenticated_user["status"] === false){
    echo $authenticated_user["error"];
    exit;
}

$authentication_check = isAuthorized($mc, $authenticated_user);
if($authentication_check["status"] === false){
    echo $authentication_check["error"];
    exit;
}

if(isset($_POST["submittedForm"]) AND $authenticated_user["status"] === true){
    discipleCreateGroup([
        "user_id" => $authenticated_user["decrypted_data"]["id"],
        "apiHost" => $mc["decrypted_data"]["apiHost"],
        "apiKey" => $mc["decrypted_data"]["apiKey"],
        "groupName" => $_POST["groupName"],
        "groupDescription" => $_POST["groupDescription"]
    ]);
    echo "Your group has been created!";
    exit;
}
?>
<form method="POST">
    <h3 style="margin-left: 3rem!important;margin-top:100px">Create a New Group</h3>
    <div class="form-outline m-5">
        <input type="text" id="groupName" name="groupName" class="form-control" />
        <label class="form-label" for="groupName">Group Name</label>
        <div class="form-helper">Give your group a name to make it stand out</div>
    </div>

    <div class="form-outline m-5">
        <textarea class="form-control" name="groupDescription" id="groupDescription" rows="4"></textarea>
        <label class="form-label" for="groupDescription">Group Description</label>
        <div class="form-helper">A sentence or two about what members can expect when they join.</div>
    </div>


        <select class="select" name="groupVisibility" id="groupVisibility" >
        <?php
            $visibilityTypes = [
                "mandatory" => [
                    "label" => "Mandatory",
                    "description" => "test"
                ],
                "public" => [
                    "label" => "Public",
                    "description" => "test"
                ],
                "private" => [
                    "label" => "Private",
                    "description" => "test"
                ],
                "secret" => [
                    "label" => "Secret",
                    "description" => "test"
                ]
                ];
            foreach($visibilityTypes as $key => $val){
                if(!isset($mc["decrypted_data"]["allowed_visibility"]) OR in_array($key,$mc["decrypted_data"]["allowed_visibility"])){
                    echo "<option value='$key'>{$val["label"]}</option>";
                }
            }
        ?>
            
        </select>

    <input type="submit" class="btn btn-primary" style="margin-left: 3rem!important;" name="submittedForm" value="Create Group">
</form>


    
<?php
require_once("template/footer.html");