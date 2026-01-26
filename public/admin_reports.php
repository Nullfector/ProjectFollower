<?php
require_once __DIR__ . '/../auth.php';
require_role(2);

$view = 'admin_reps.php';
$title = 'Admin - dostępne raporty';


require __DIR__ . '/../app/views/layout.php';