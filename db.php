<?php

<<<<<<< HEAD
$dsn = "pgsql:host=localhost;port=5432;dbname=projectfollower";
$user = "postgres";
$pass = "Mialem2koty!";
=======
$dsn = "pgsql:host=db;port=5432;dbname=projectfollower";
$user = "postgres";
$pass = "postgres";
>>>>>>> 007b3a4 (Transfer)

$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,]);

?>