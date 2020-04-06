<?php

/**
 * Session wird initialisiert
 */

$logged = false;
$userID = null;

 
ini_set('session.cookie_domain', '.' . $_SERVER["SERVER_NAME"] );
session_name('sid');
session_start();


/**
 * Startup
 */

require_once(__DIR__ . "/../config.php");

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
    for ($s = '', $i = 0, $z = strlen($c)-1; $i < $l; $x = rand(0,$z), $s .= $c{$x}, $i++);
    return $s;
}

function clean($string) {
    $string = str_replace(' ', '-', $string);
    $string = strtolower($string);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

function getIpAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[count($ips) - 1]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function getLocationFromIP () {

    $postData = array(
        'ip' => getIpAddress()
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

function isInDB() {
    global $logged, $userID, $pages;

    if(!isset($_GET["url"])) return false;

    if (in_array($_GET["url"], $pages)) return $_GET["url"];
    
    $db = new \DB();
    $urlID = clean($db->check($_GET["url"]));

    $url = $db->get("SELECT * FROM `shorturls` WHERE urlID = '$urlID' ");

    if(!$url["urlID"]) {
        header("Location: /");
        die();
    }

    if($url["pass"] !== "" && (!isset($_POST["passProt"]) || !password_verify($_POST["passProt"], $url["pass"]))) {
        return "needpass";
    }

    if (!startsWith($url["link"], "http://") && !startsWith($url["link"], "https://") ){
        $url["link"] = "http://" . $url["link"];
    }
    
    $ip_hashed = md5(getIpAddress() . "dwß9g3jkbdjasbd938eueiqhdkjebf910302389");
    
    $location = getLocationFromIP();

    if (!is_null($location)) {

        $city = $db->check($location["zipcode"] . " " .$location["city"]);
        $region = $db->check($location["region"]);
        $country = $db->check($location["country_short"] . " - " . $location["country_long"]);
        $latitude = (float) $location["latitude"];
        $longitude = (float) $location["longitude"];

        $db->set("INSERT INTO `stats` (urlID, city, region, country, latitude, longitude, ip_hashed) VALUES ('$urlID', '$city', '$region', '$country', '$latitude', '$longitude', '$ip_hashed')");

    }
    
    header("Location: " . $url["link"]);
    die();
    
}

class Output{
    
    public function error($code = false){
        $r = json_encode([
            "error" => $code
        ]);
        die($r);
    }

    public function success($code = true){
        $r = json_encode([
            "ok" => $code
        ]);     
        die($r);
    }

}

class DB{

    private $conn;
    private $o;

    public function __construct(){

        $this->o = new Output();
        $this->connect();

    }

    private function connect(){

        global $CONFIG;
        $this->conn = @mysqli_connect($CONFIG["dbhost"], $CONFIG["dbuser"], $CONFIG["dbpassword"], $CONFIG["dbname"]);

        if(!$this->conn){ 
            $this->o->error("Es konnte keine Verbindung zur Datenbank hergestellt werden.");
        }

    }

    public function get($sql){
        try{
            $res = $this->query($sql);
            if($res) return @mysqli_fetch_array($res);
            else return false;
        }catch(Exception $e){
            return false;
        }
    }

    public function set($sql){
        return @mysqli_query($this->conn, $sql);
    }
    public function query($sql){
        return mysqli_query($this->conn, $sql);
    }

    public function check($check){
        return mysqli_real_escape_string($this->conn, $check);
    }

}