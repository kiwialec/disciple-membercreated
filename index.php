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
        "groupDescription" => $_POST["groupDescription"],
        "groupVisibility" => $_POST["groupVisibility"]
    ]);
    echo "Your group has been created!";
    exit;
}
?>
<form method="POST" class="needs-validation">
    <h3 style="margin-left: 3rem!important;margin-top:100px">Create a New Group</h3>
    <div class="form-outline m-5">
        <input type="text" id="groupName" name="groupName" class="form-control" required maxlength="32" />
        <label class="form-label" for="groupName">Group Name</label>
        <div class="form-helper">Give your group a name to make it stand out</div>
    </div>

    <div class="form-outline m-5">
        <textarea class="form-control" name="groupDescription" id="groupDescription" rows="4" required></textarea>
        <label class="form-label" for="groupDescription">Group Description</label>
        <div class="form-helper">A sentence or two about what members can expect when they join.</div>
    </div>

    <div class="form-outline m-5">
        <strong>Group Visibility</strong><BR>

        <?php
            $visibilityTypes = [
                "secret" => [
                    "label" => "Secret",
                    "description" => "Only group members can see this group"
                ],
                "private" => [
                    "label" => "Private",
                    "description" => "All community members can find this group and request to join. Only members can see posts and engage."
                ],
                "public" => [
                    "label" => "Public",
                    "description" => "All community members can find this group and see content. Anyone can join and engage."
                ],
                "mandatory" => [
                    "label" => "Mandatory",
                    "description" => "All community members are a part of this group and they cannot leave."
                ],
                
            ];
            $isCheckedSet = false;
            foreach($visibilityTypes as $key => $val){
                if(!isset($mc["decrypted_data"]["allowed_visibility"]) OR in_array($key,$mc["decrypted_data"]["allowed_visibility"])){
                    if(!$isCheckedSet){
                        $isCheckedSet = true;
                        $thisChecked = true;
                    }else{
                        $thisChecked = false;
                    }
                    echo '<div class="form-check">
                        <input class="form-check-input" type="radio" name="groupVisibility" value="'.$key.'" id="visibilitySetting'.$key.'" '.(($thisChecked) ? "checked" : "").' required />
                        <label class="form-check-label" for="visibilitySetting'.$key.'"> <strong>'.$val["label"].'</strong>: '.$val["description"].'</label>
                    </div>';
                }
            }
        ?>
            
            
    </div>
    <input type="submit" class="btn btn-primary" style="margin-left: 3rem!important;" name="submittedForm" value="Create Group">
</form>

    
<?php
require_once("template/footer.html");