<?php
require_once __DIR__ . '/../auth.php';
require_role(2);

$view = 'admin_over.php'; //admin_home
$title = 'Admin - strona domowa';

require_once __DIR__ . '/../app/views/layout.php';