<?php

$rand = false;
$url = false;

$baseUrl = "https://" . $_SERVER["HTTP_HOST"];

require_once "db.php";
require_once "utils.php";

$odmin->init_session_from_cookie();

if(isset($_GET["url"]))
    $url = is_url_in_db((string) $_GET["url"]);

if(
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["link"]) &&
    $_POST["link"] !== "" &&
    $odmin->is_logged_in()
) {

    $db = new DB();
    $time = 0;
    $rand = true;
    $pass = "";

    $link = $db->check((string) $_POST["link"]);
    $custom = clean($db->check((string) $_POST["custom"]));

    if (filter_var($link, FILTER_VALIDATE_URL) === FALSE) {
        $rand = "error";
    } else {
        if($_POST["pass"] !== "")
            $pass = password_hash((string) $_POST["pass"], PASSWORD_BCRYPT);
    
        if ($custom !== "") {
            if ($db->get("SELECT * FROM shorturls WHERE urlID = '$custom'")) 
                $rand = "error";
            else 
                $rand = false;
        }
    }


    if ($rand !== "error") {

        if ($rand) {
            while(1){
                $rand = random();
                if(!$db->get("SELECT * FROM shorturls WHERE urlID = '$rand'")) {
                    break;
                }
                $time++;
                if($time >= 150) { 
                    $rand = "error"; break;
                } 
            }
        } else {
            $rand = $custom;
        }

        $user_id = (int) $db->check($odmin->session->user_id);
    
        if(!$db->set("INSERT INTO shorturls (urlID, userID, pass, link) VALUES ('$rand', '$user_id', '$pass', '$link')")) {
            $rand = "error";
        }
    }
}