<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../auth.php';
//require_role(2); // jeśli tylko admin ma mieć dostęp

require_once __DIR__ . '/../../app/models/adminRepsModel.php';
header('Content-Type: application/json; charset=utf-8');


$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'PUT') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            throw new Exception('Nieprawidłowy JSON w body (UPDATE).');
        }
    } else if($method === 'POST'){
        // zostawiamy kompatybilność z POST (jeśli kiedyś użyjesz)
        $data = $_POST;
    } else if($method === 'GET'){
        $data = $_GET;
    }

$action = $data['action'];

try
{
    $model = new adminRepsModel($pdo);
    //$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]);
    /*$handlers = ['edit_user' => 'fun_u',
                'edit_zesp' => 'fun_ze',
                'edit_u_ze' => 'fun_u_ze',
                'edit_za_ze'=>'fun_za_ze',
                'edit_proj' => 'fun_p',
                'del_za' => 'fun_d_za',
                'edit_zad' => 'fun_za',
                'aso_self' => 'fun_self'];*/

    
    //$fn = $handlers[$action];
    //$result = $fn($pdo, $data);
    switch($action){
        case 'zadanie_1':
            $result = $model->get_zad((int)$data['id']);
            break;
        case 'projekt_1':
            $result = $model->get_proj();
            break;
        case 'zadanie_2':
            $result = $model->get_zad2((int)$data['id']);
            break;
        case 'projekt_2':
            $result = $model->get_proj2();
            break;
        case 'edit_proj':
            $result = $model->edit_state_proj((int)$data['id'], (int)$data['val']);
            break;
        case 'edit_zad':
            $result = $model->edit_sate_zad((int)$data['id'], (int)$data['val']);
            break;
        default:
            echo json_encode(['ok'=>false,'error'=>"Niepoprawny parametr 'action'."]);
            exit;
    }

    echo json_encode($result);

} catch (PDOException $e) {
    $mapped = $model->mapError($e);
    //http_response_code($mapped['http']);
    echo json_encode($mapped['payload']);
}



?>