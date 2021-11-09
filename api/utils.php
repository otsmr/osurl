<?php 

function is_url_in_db(string $url) {
    global $odmin, $pages;

    if (in_array($url, $pages)) {
        return $url;
    }
    
    $db = new \DB();
    $url_id = clean($db->check($url));

    $db_url = $db->get("SELECT * FROM `shorturls` WHERE urlID = '$url_id' ");

    if(!$db_url["urlID"]) {
        header("Location: /");
        die();
    }

    if($db_url["pass"] !== "" && (!isset($_POST["passProt"]) || !password_verify((string) $_POST["passProt"], $db_url["pass"]))) {
        return "needpass";
    }

    if (!startsWith($db_url["link"], "http://") && !startsWith($db_url["link"], "https://") ){
        $db_url["link"] = "http://" . $db_url["link"];
    }
    
    $ip = get_ip_address();
    $ip_hashed = md5($ip . "dwß9g3jkbdjasbd938eueiqhdkjebf910302389");
    $location = get_location_from_ip($ip);

    if (!is_null($location)) {

        $city = $db->check($location["zipcode"] . " " . $location["city"]);
        $region = $db->check($location["region"]);
        $country = $db->check($location["country_short"] . " - " . $location["country_long"]);
        $latitude = (float) $db->check($location["latitude"]);
        $longitude = (float) $db->check($location["longitude"]);

        $db->set("INSERT INTO `stats` (urlID, city, region, country, latitude, longitude, ip_hashed) VALUES ('$url_id', '$city', '$region', '$country', '$latitude', '$longitude', '$ip_hashed')");

    }
    
    http_response_code(307);
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta http-equiv = "refresh" content = "0; url = <?php echo $db_url['link']; ?>" />
            <title>Weiterleiten</title>
        </head>
        <body> </body> </html>
        <?php
    } else {
        header("Location: " . $db_url["link"]);
    }
    die();
    
}

function startsWith($haystack, $needle) {
    return (substr($haystack, 0, strlen($needle)) === $needle);
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

function random($l = 4, $c = "abcdefghijklmnopqrstuvwxyz0123456789") {
    for ($s = '', $i = 0, $z = strlen($c)-1; $i < $l; $x = rand(0,$z), $s .= $c[$x], $i++);
    return $s;
}

function clean($string) {
    $string = str_replace(' ', '-', $string);
    $string = strtolower($string);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

function get_ip_address() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[count($ips) - 1]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function get_location_from_ip (string $ip) {

    $postData = array(
        'ip' => $ip
    );

    $ch = curl_init('https://ipinfo.oproj.de/api/ip');
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
    ));
    $response = curl_exec($ch);

    if($response === FALSE){
        return null;
    }

    try {
        return json_decode($response, TRUE);
    } catch (\Throwable $th) {
        return null;
    }

}