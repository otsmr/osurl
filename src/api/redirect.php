<?php

require_once __DIR__ . "/utils.php";
require_once __DIR__ . "/db.php";

$url_id = clean_url_id($url);

$s = $dbh->prepare("SELECT * FROM `shorturls` WHERE urlID = ?");
$s->execute(array($url_id));
$row = $s->fetchAll();

if (count($row) == 0) {
    header("Location: /");
    die();
}

$dburl = $row[0];

if ($dburl["pass"] !== "" && (!isset($_POST["passProt"]) || !password_verify((string) $_POST["passProt"], $dburl["pass"]))) {

    $url = "password-request";

} else {

    if ($dburl["stats"] == 1) {

        $ip = get_ip_address();
        $l = get_location_from_ip($ip);
    
        if (!is_null($l)) {
    
            try {

                $city = $l["postal"] . " " . $l["city"];
    
                $s = $dbh->prepare("INSERT INTO `stats` (urlID, city, region, country, loc, ip) VALUES (?, ?, ?, ?, ?, ?)");
                $s->execute(array($url_id, $city, $l["region"], $l["country"], $l["loc"], $ip));

            } catch (Exception $e) {}
    
        }
    
    }
    
    http_response_code(307);
    if ($_SERVER['REQUEST_METHOD'] === "POST") { ?>
    
    <!DOCTYPE html><html lang="en"><head>
        <meta http-equiv = "refresh" content = "0; url = <?php echo $dburl['link']; ?>" />
        <title>Weiterleiten</title>
    </head><body></body></html> <?php
    
    } else {
        header("Location: " . $dburl["link"]);
    }
    
    die();

}