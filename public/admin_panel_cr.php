<?php
require_once __DIR__ . '/../auth.php';
require_role(2);

$view = 'admin_panel_cr.php';
$title = 'Admin - panel sterowania - dodawanie';

require __DIR__ . '/../app/views/layout.php';