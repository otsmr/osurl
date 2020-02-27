<?php

$rand = false;

$baseUrl = "https://".$_SERVER["HTTP_HOST"];

require_once __DIR__ . "/load.php";

if(isset($_COOKIE['token'])){

    $url = $CONFIG["odmin_base_url"] . "/api/istokenvalid/" . $_COOKIE['token'];

    try {

        $res = json_decode(file_get_contents($url));
    
        if(isset($res->valid) && $res->valid) {
            $logged = true;
            $userID = $res->user->id;
        } else {
            setcookie("token", "", time()-3600);
        }

    } catch (\Throwable $th) {}

}

$url = isInDB();

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["link"]) && $_POST["link"] !== "" && $logged) {

    $db = new DB();
    $time = 0;
    $rand = true;

    $link = $db->check($_POST["link"]);
    $custom = clean($db->check($_POST["custom"]));

    if($_POST["pass"] !== "") $pass = password_hash($_POST["pass"], PASSWORD_BCRYPT);
    else $pass = "";

    if ($custom !== "") {
        if ($db->get("SELECT * FROM urls WHERE urlID = '$custom'")) {
            $rand = "error";
        } else {
            $rand = false;
        }
    }

    if ($rand !== "error") {
        if ($rand) {
            while(1){
                $rand = random();
                if(!$db->get("SELECT * FROM urls WHERE urlID = '$rand'")) {
                    break;
                }
                $time++;
                if($time >= 150) { $rand = "error"; break; } 
            }
        } else {
            $rand = $custom;
        }
    
        if(!$db->set("INSERT INTO urls (urlID, userID, pass, link) VALUES ('$rand', '$userID', '$pass', '$link')")) {
            $rand = "error";
        }
    }
}