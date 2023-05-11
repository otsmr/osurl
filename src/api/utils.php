<?php 

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

function get_random_surl($l = 4, $c = "abcdefghijklmnopqrstuvwxyz0123456789") {
    for ($s = '', $i = 0, $z = strlen($c)-1; $i < $l; $x = rand(0,$z), $s .= $c[$x], $i++);
    return $s;
}

function clean_url_id($string) {
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
    global $CONFIG;

    $ch = curl_init("https://ipinfo.io/$ip?token=" . $CONFIG->ipinfoio_token);
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => TRUE
    ));
    $response = curl_exec($ch);

    if($response === FALSE)
        return null;

    try {
        return json_decode($response, TRUE);
    } catch (\Throwable $th) {
        return null;
    }

}