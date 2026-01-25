<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../auth.php';
//require_role(2); // jeśli tylko admin ma mieć dostęp

require_once __DIR__ . '/../../app/models/dataToEditModel.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';


try {
    $model = new ToEditModel($pdo);

    switch($action){
        case 'user':
            $result = $model->fun_u((int)$_GET['id']);
            break;
        case 'team':
            $result = $model->fun_ze((int)$_GET['id']);
            break;
        case 'proj':
            $result = $model->fun_p((int)$_GET['id']);
            break;
        case 'zad':
            $result = $model->fun_zad((int)$_GET['id']);
            break;
        default:
            echo json_encode(['ok'=>false,'error'=>"Niepoprawny parametr 'action'."]);
            exit;
    }

    echo json_encode($result);

} catch(PDOException $e)
{
    echo json_encode(['ok'=>false,'error'=>'Błąd bazy danych.','code'=>$e->getCode()]);
}
?>