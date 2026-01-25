<?php
  $map = [1 => 'Użytkownik', 2 => 'Administrator'];
?>

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'App') ?></title>
  <link rel="stylesheet" href="/../app/views/assets/basic_display.css">
</head>
<body>
  <nav>
    Zalogowany: <?= htmlspecialchars($_SESSION['login'] ?? 'Nieznany użytkownik (jak)') ?>
    | rola: <?= htmlspecialchars($map[$_SESSION['role']] ?? 'Nieznana rola (jak)') ?>
    | <a href="logout.php">Wyloguj</a>
  </nav>

  <main>
    <?php require __DIR__ . '/' . $view; ?>
  </main>
</body>
</html>