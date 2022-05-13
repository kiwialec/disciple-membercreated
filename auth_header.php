<?php

function isAuthenticated($mc){
    if($mc["status"] === false){
        return ["status" => false, "error" => "It looks like this link is broken! Variables failed"];
    }
    
    $authenticated_user = getEncryptedData(["dataVar" => "_dm", "secret" => $mc["decrypted_data"]["authenticatedLinkSecret"]]);
    if($authenticated_user["status"] === false){
        return ["status" => false, "error" => "You aren't logged in correctly."];
    }
    return $authenticated_user;
}

function isAuthorized($mc, $authenticated_user){
    if(isset($mc["decrypted_data"]["allowed_users"]) AND !in_array($authenticated_user["decrypted_data"]["id"], $mc["decrypted_data"]["allowed_users"])){
        return ["status" => false, "error" => "You are not authorized to do this"];
    }
    return ["status" => true];
}