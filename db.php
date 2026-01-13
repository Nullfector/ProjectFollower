<?php

$dsn = "pgsql:host=localhost;port=5432;dbname=projectfollower";
$user = "postgres";
$pass = "Mialem2koty!";

$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,]);

?>