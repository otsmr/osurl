<?php

$shorten_url = "";
$shorten_error_message = "";

require_once __DIR__ . "/db.php";
require_once __DIR__ . "/utils.php";

function create_shorten_link (string $link, string $custom, $userID) {
    global $dbh;

    if (filter_var($link, FILTER_VALIDATE_URL) === FALSE) {
        return [
            "error_message" => "Keine gültige URL.",
            "shorten_url" => ""
        ];
    }

    if ($custom === "") {

        for ($i=0; $i < 150; $i++) { 

            $custom = get_random_surl();

            $s = $dbh->prepare("SELECT * FROM `shorturls` WHERE urlID = ?");
            $s->execute(array($custom));
        
            if (count($s->fetchAll()) === 0)
                break;

            if($i === 149) return [
                "error_message" => "Random-Url nicht verfügbar.",
                "shorten_url" => ""
            ];
        }
    
    } else {

        $s = $dbh->prepare("SELECT * FROM `shorturls` WHERE urlID = ?");
        $s->execute(array($custom));
    
        if (count($s->fetchAll()) > 0) return [
            "error_message" => "Custom-Url nicht verfügbar.",
            "shorten_url" => ""
        ];

    }

    $password = "";
    if (isset($_POST["pass"]) && $_POST["pass"] !== "") {
        $password = password_hash((string) $_POST["pass"], PASSWORD_BCRYPT);
    }

    $stats = 0;
    if ((string) $_POST["enable-statistics"] === "on") {
        $stats = 1;
    }

    if (!startsWith($link, "http://") && !startsWith($link, "https://") ){
        $link = "http://" . $link;
    }

    $s = $dbh->prepare("INSERT INTO shorturls (urlID, userID, pass, link, stats) VALUES (?, ?, ?, ?, ?)");
    if(!$s->execute(array($custom, $userID, $password, $link, $stats))) {
        return [
            "error_message" => "Custom-Url konnte nicht erstellt werden.",
            "shorten_url" => ""
        ];
    }

    return [
        "error_message" => "",
        "shorten_url" => $custom
    ];

}

$link = (string) $_POST["link"];
$custom = clean_url_id((string) $_POST["custom"]);
$userID = (int) $odmin->session->user_id;

$status = create_shorten_link((string) $_POST["link"], $custom, $userID);