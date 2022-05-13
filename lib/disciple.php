<?php

function discipleCreateGroup($params){
    $createdGroup = discipleApi([
        "url" => "{$params["apiHost"]}/v1/groups",
        "body" => [
            "name" => $params["groupName"],
            "description" => $params["groupDescription"],
            "membership_type" => $params["groupVisibility"],
            "posting_permission" => "everyone"
        ],
        "apiKey" => $params["apiKey"]
    ]);
    var_dump($createdGroup);
    $newGroupKey = $createdGroup["group"]["key"];

    $addMember = discipleApi([
        "url" => "{$params["apiHost"]}/v1/groups/$newGroupKey/members",
        "body" => [
            "user_id" => $params["user_id"],
            "member_type" => "admin"
        ],
        "apiKey" => $params["apiKey"]
    ]);

    return $createdGroup;
}

function discipleApi($params){
    $ch = curl_init( $params["url"] );
    $payload = json_encode( $params["body"] );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$params["apiKey"]}",
        'Content-Type: application/json'
    ]);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}