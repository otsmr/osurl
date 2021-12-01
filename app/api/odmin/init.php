<?php 

require_once __DIR__ . "/../../config.php";
require_once "odmin.php";

$odmin = new \ODMIN\OAuth((object) [
    "secret" => $CONFIG->odmin_secret,
    "service_id" => $CONFIG->odmin_service_id,
    "api_base" => $CONFIG->odmin_base_url
]);