<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../auth.php';
//require_role(2); // jeśli tylko admin ma mieć dostęp

require_once __DIR__ . '/../../app/models/fillDataEdit.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';


try {
    $model = new fillDataEdit($pdo);

    switch($action){
        case 'lider':
            $result = $model->fun_s_lider((int)$_GET['id']);
            break;
        case 'aso_u_ze_create':
            $result = $model->fun_s_asoc((int)$_GET['id']);
            break;
        case 'aso_u_ze_del':
            $result = $model->fun_s_asod((int)$_GET['id']);
            break;
        case 'aso_u_ze_create':
            $result = $model->fun_s_asocz((int)$_GET['id']);
            break;
        case 'aso_u_ze_del':
            $result = $model->fun_s_asodz((int)$_GET['id'], (int)$_GET['idq']);
            break;
        case 'admin':
            $result = $model->fun_s_a((int)$_GET['id']);
            break;
        case 'typ':
            $result = $model->fun_s_typ((int)$_GET['id']);
            break;
        case 'zadanie':
            $result = $model->fun_s_za((int)$_GET['id']);
            break;
        case 'zadanie_new':
            $result = $model->fun_s_znew((int)$_GET['id'], (int)$_GET['idq']);
            break;
        case 'zadanie_del':
            $result = $model->fun_s_zdel((int)$_GET['id']);
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