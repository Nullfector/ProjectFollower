<?php
require_once __DIR__ . '/../auth.php';
require_login();
?>
<!doctype html>
<html lang="pl">
<head><meta charset="utf-8"><title>Moje konto</title></head>
<body>
  <h1>Witaj, <?= htmlspecialchars($_SESSION['login']) ?></h1>
  <p>Rola: <b><?= htmlspecialchars($_SESSION['role']) ?></b></p>
  <p><a href="logout.php">Wyloguj</a></p>
</body>
</html>