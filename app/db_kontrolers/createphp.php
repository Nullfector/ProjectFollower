<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../auth.php';
//require_role('admin'); // jeśli tylko admin ma mieć dostęp

require_once __DIR__ . '/../../app/models/createModel.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'];

try
{
    $model = new createModel($pdo);
    //$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]);
    /*$handlers = ['add_user' => 'fun_u',
                'add_typ' => 'fun_t',
                'add_zesp' => 'fun_ze',
                'add_proj' => 'fun_p',
                'add_zad' => 'fun_za'];*/

    //$result = $fn($pdo, $_POST);
    switch ($action){
        case 'add_user':
            $result = $model->fun_u($_POST['pole3_1'], $_POST['pole3_2'], $_POST['pole3_3'], $_POST['pole3_4'], $_POST['pole3_5'], (int)$_POST['pole3_6']);
            break;
        case 'add_typ':
            $result = $model->fun_t($_POST['pole2']);
            break;
        case 'add_zesp':
            $result = $model->fun_ze($_POST['pole4_1'], (int)$_POST['pole4_2']);
            break;
        case 'add_proj':
            $result = $model->fun_p($_POST['pole5_1'], (int)$_POST['pole5_2'], $_POST['pole5_3'], (int)$_POST['pole5_4']);
            break;
        case 'add_zad':
            $result = $model->fun_za($_POST['pole6_1'], (int)$_POST['pole6_2'], (int)$_POST['pole6_5'], $_POST['pole3_3'], $_POST['pole3_4']);
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