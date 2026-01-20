<?php
require_once __DIR__ . '/../auth.php';
require_role(1); //require_role sam w sobie ma już require_login()

$title = 'Użytkownik - strona domowa';

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../app/models/userModel.php';

$model = new userModel($pdo);
if($model->setupUser((int)$_SESSION['uid']) != []){
  $isLeader = true;
  $_SESSION['lid'] = true;
} else {
  $isLeader = false;
  $_SESSION['lid'] = false;
}

$view = $isLeader ? 'user_extra.php' : 'user_home.php';

require_once __DIR__ . '/../app/views/layout.php';
