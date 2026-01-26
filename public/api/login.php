<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../session_bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$login = $_POST['login'] ?? '';
$pass  = $_POST['haslo'] ?? '';

if ($login === '' || $pass === '') {
  echo json_encode(['ok' => false, 'error' => 'Podaj login i hasło.']);
  exit;
}

$stmt = $pdo->prepare("SELECT id_u, login, haslo, rola FROM Użytkownik WHERE login = :l;");
$stmt->execute([':l' => $login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  echo json_encode(['ok' => false, 'error' => 'Nieprawidłowy login lub hasło.']);
  exit;
}


$hash = $user['haslo'];

//$ok = password_verify($pass, $hash);
// TYMCZASOWO \/ (bo w bazie haszy nie ma)
$ok = ($pass === $hash);

if (!$ok) {
  echo json_encode(['ok' => false, 'error' => 'Nieprawidłowy login lub hasło.']);
  exit;
}

session_regenerate_id(true);

$_SESSION['uid']   = (int)$user['id_u'];
$_SESSION['login'] = $user['login'];
$_SESSION['role']  = $user['rola'];

$redirect = 'me.php';
if ($user['rola'] === 2) $redirect = 'admin.php';

echo json_encode(['ok' => true, 'redirect' => $redirect]);