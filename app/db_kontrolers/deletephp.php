<?php

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../auth.php';

require_once __DIR__ . '/../../app/models/deleteModel.php';
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'DELETE') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            throw new Exception('Nieprawidłowy JSON w body (DELETE).');
        }
    } else {
        $data = $_POST;
    }

$action = $data['action'];

try
{
    $model = new deleteModel($pdo);
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'DELETE') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            throw new Exception('Nieprawidłowy JSON w body (DELETE).');
        }
    } else {
        $data = $_POST;
    }

    switch($action){
        case 'del_user':
            $result = $model->fun_u((int)$data['id']);
            break;
        case 'del_typ':
            $result = $model->fun_t((int)$data['id']);
            break;
        case 'del_zesp':
            $result = $model->fun_ze((int)$data['id']);
            break;
        case 'del_proj':
            $result = $model->fun_p((int)$data['id']);
            break;
        default:
            echo jsno_encode(['ok'=>false,'error'=>"Niepoprawny parametr 'action'."]);
            exit;
    }
    echo json_encode($result);

} catch (PDOException $e) {
    $mapped = $model->mapError($e);
    //http_response_code($mapped['http']);
    echo json_encode($mapped['payload']);
}

?>