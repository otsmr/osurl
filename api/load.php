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

function isInDB() {
    global $logged, $userID;

    if(!isset($_GET["url"])) return false;

    $displayLogs = false;

    if ($logged && endsWith($_GET["url"], "*")) {
        $displayLogs = true;
    }

    $db = new \DB();
    $urlID = clean($db->check($_GET["url"]));

    $url = $db->get("SELECT * FROM `urls` WHERE urlID = '$urlID' ");

    if ($displayLogs && $url["userID"] != $userID) {
        $displayLogs = false;
    }

    if(!$url["ID"]) {
        header("Location: /");
        die();
    }

    if($url["pass"] !== "" && (!isset($_POST["passProt"]) || !password_verify($_POST["passProt"], $url["pass"]))) {
        return "needPass";
    }

    if (!startsWith($url["link"], "http://") && !startsWith($url["link"], "https://") ){
        $url["link"] = "http://" . $url["link"];
    }

    $logPath = __DIR__ . '/../log/'.$urlID.'.count';

    if ($displayLogs) {

        $content = "Noch keine Daten vorhanden.";
        if (file_exists($logPath)) {
            try {
                $content = @file_get_contents($logPath);
            } catch (Exception $e) { }
        }

        header('Content-Type: text/plain');

        die($content);

    } else {

        $fp = @fopen($logPath, 'a');
        @fwrite($fp, date('d.m.Y H:i:s') . "\t" . $_SERVER['REMOTE_ADDR'] . "\r\n" );
        @fclose($fp);
    
        header("Location: " . $url["link"]);

    }
    
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