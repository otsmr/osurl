<?php

require_once __DIR__ . "/../config.php";

try {

    $dbh = new PDO("mysql:host=" . $CONFIG->dbhost . ";dbname=" . $CONFIG->dbname, $CONFIG->dbuser, $CONFIG->dbpassword);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    print "Error!: " . $e->getMessage() . "<br/>";
    die();

}

$dbh->exec("
    CREATE TABLE IF NOT EXISTS `shorturls` (
        `urlID` varchar(150) PRIMARY KEY NOT NULL,
        `userID` int(11) NOT NULL,
        `stats` int(11) NOT NULL,
        `pass` text NOT NULL,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `link` text NOT NULL
    );");

$dbh->exec("
    CREATE TABLE IF NOT EXISTS `stats` (
        `ID` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `urlID` varchar(150) NOT NULL,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `city` varchar(150) NOT NULL DEFAULT '-',
        `region` varchar(150) NOT NULL DEFAULT '-',
        `country` varchar(150) NOT NULL DEFAULT '-',
        `loc` varchar(150) NOT NULL DEFAULT '-',
        `ip` varchar(150) NOT NULL DEFAULT '-',
        FOREIGN KEY (`urlID`) REFERENCES `shorturls` (`urlID`) ON DELETE CASCADE ON UPDATE CASCADE
      );");