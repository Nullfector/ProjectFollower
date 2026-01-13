<?php
require_once __DIR__ . '/../auth.php';
require_role(2);

$view = 'admin_panel_ed.php';
$title = 'Admin - panel sterowania - edycja';

require __DIR__ . '/../app/views/layout.php';