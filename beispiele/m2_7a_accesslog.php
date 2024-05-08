<?php
/**
 * Praktikum DBWT. Autoren:
 * Rabia, Türe, 3674806
 * Sofia, Moll, 3637355
 */

$file = fopen("accesslog.txt", "a");

$date = date("Y-m-d H:i:s");
$browser = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];

$logEntry = "$date - Browser: $browser - IP: $ip\n";

fwrite($file, $logEntry);

fclose($file);


