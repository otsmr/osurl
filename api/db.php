<?php 

class DB {

    private $conn;

    public function __construct(){
        $this->connect();
    }

    private function connect(){

        global $CONFIG;
        $this->conn = @mysqli_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpassword, $CONFIG->dbname);

        if(!$this->conn){ 
            die("Es konnte keine Verbindung zur Datenbank hergestellt werden.");
        }

    }

    public function get($sql){
        try {

            $res = $this->query($sql);
            if ($res)   
                return @mysqli_fetch_array($res);
            
        } catch(Exception $e){ }
        return false;
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