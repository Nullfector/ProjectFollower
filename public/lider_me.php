<?php
require_once __DIR__ . '/../auth.php';
require_user_perms(); 

$title = 'Użytkownik - panel lidera';

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../app/models/userModel.php';


$view = 'lider_panel.php';

require_once __DIR__ . '/../app/views/layout.php';