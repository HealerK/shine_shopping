<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
define('MYSQL_USER','root');
define('MYSQL_PASSWORD','');
define('MYSQL_HOST','localhost');
define('MYSQL_NAME','ap_shopping');

$options = array(
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
);

$pdo = new PDO(
    'mysql:dbhost='.MYSQL_HOST.';dbname=' .MYSQL_NAME,MYSQL_USER,MYSQL_PASSWORD,$options
);



