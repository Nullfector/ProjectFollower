<?php
require_once __DIR__ . '/../auth.php';
require_user_perms(); //require_role sam w sobie ma już require_login()

$title = 'Użytkownik - panel lidera';

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../app/models/userModel.php';

//tu jeszcze dla admina trzeba dorobić

$view = 'lider_panel.php';

require_once __DIR__ . '/../app/views/layout.php';