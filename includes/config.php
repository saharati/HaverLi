<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'on');
mb_internal_encoding('UTF-8');
$mysqli = new MySQLi('p:127.0.0.1', 'root', 'Pp2p7br4', 'imutz-v2');
$mysqli->set_charset('utf8');
?>