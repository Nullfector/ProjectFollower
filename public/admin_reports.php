<?php
require_once __DIR__ . '/../auth.php';
require_role(2);

$view = 'admin_reps.php';
$title = 'Admin - dostępne raporty';

//tutaj zrobimy jeszcze wysyłanie danych do modelu - aka zbieranie danych bo trzeba bedzie

require __DIR__ . '/../app/views/layout.php';