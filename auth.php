<?php
require_once __DIR__ . '/session_bootstrap.php';

function require_login(): void {
  if (empty($_SESSION['uid'])) {
    header('Location: /public/login.html', true, 302);
    exit;
  }
}

function require_role(int $role): void {
  require_login();
  if (($_SESSION['role'] ?? '') !== $role) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Brak uprawnień.";
    exit;
  }
}

function require_user_perms(): void{
    require_login();
    if (empty($_SESSION['lid']) && $_SESSION['role'] === 1) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Brak uprawnień.";
    exit;
  }
  }