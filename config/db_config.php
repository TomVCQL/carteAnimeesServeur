<?php

$whitelist = array('127.0.0.1','::1');

if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
        //Mode test localhost
        $servername = 'localhost';
        $dbname = 'io9qg_pfe';
        $username = 'root';
        $password = '';
} else {
        $servername = 'io9qg.myd.infomaniak.com';
        $dbname = 'io9qg_pfe';
        $username = 'io9qg_adminPFE';
        $password = 'Pfe12345';
}
try {
        $db = new PDO("mysql:host=$servername; dbname=$dbname;", $username, $password);
} catch (PDOException $e) {
        echo "Erreur!: " . $e->getMessage() . "<br/>";
        die();
}