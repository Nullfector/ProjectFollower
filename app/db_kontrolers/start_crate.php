<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../auth.php';
//require_role(2); // jeśli tylko admin ma mieć dostęp

require_once __DIR__ . '/../../app/models/fillDataStart.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';

try {
    $model = new fillDataStart($pdo);

    switch($action){
        case 'projekt':
            $result = $model->fun_s_proj();
            break;
        case 'typ':
            $result = $model->fun_s_t();
            break;
        case 'user':
            $result = $model->fun_s_u();
            break;
        case 'zesp':
            $result = $model->fun_s_ze();
            break;
        default:
            echo jsno_encode(['ok'=>false,'error'=>"Niepoprawny parametr 'action'."]);
            exit;
    }

    echo json_encode($result);

} catch(PDOException $e)
{
    echo jsno_encode(['ok'=>false,'error'=>'Błąd bazy danych.','code'=>$e->getCode()]);
}