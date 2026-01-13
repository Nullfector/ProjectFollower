<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'App') ?></title>
</head>
<body>
  <nav>
    Zalogowany: <?= htmlspecialchars($_SESSION['login'] ?? '') ?>
    | rola: <?= htmlspecialchars($_SESSION['role'] ?? '') ?>
    | <a href="logout.php">Wyloguj</a>
  </nav>

  <main>
    <?php require __DIR__ . '/' . $view; ?>
  </main>
</body>
</html>