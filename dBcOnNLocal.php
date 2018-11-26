<?php

$un = '';
$pw = '';
$dsn = "mysql:host=localhost;port=3306;dbname=;charset=utf8";
$pdo_options = array(PDO::ATTR_EMULATE_PREPARES => false, 
					 PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);


$db = new PDO($dsn, $un, $pw, $pdo_options);


