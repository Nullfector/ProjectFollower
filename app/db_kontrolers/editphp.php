<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../auth.php';
//require_role(2); // jeśli tylko admin ma mieć dostęp

require_once __DIR__ . '/../../app/models/editModel.php';
header('Content-Type: application/json; charset=utf-8');


$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'PUT') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            throw new Exception('Nieprawidłowy JSON w body (UPDATE).');
        }
    } else if($method === 'DELETE') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            throw new Exception('Nieprawidłowy JSON w body (UPDATE).');
        }
    } else if($method === 'POST'){
        // zostawiamy kompatybilność z POST (jeśli kiedyś użyjesz)
        $data = $_POST;
    }

$action = $data['action'];

try
{
    $model = new editModel($pdo);
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
        case 'edit_user':
            $result = $model->fun_u($data['pole8_1'],$data['pole8_2'],$data['pole8_3'],$data['pole8_4'],$data['pole8_5'], (int)$data['pole8_6'], (int)$data['pole8_0']);
            break;
        case 'edit_zesp':
            $result = $model->fun_ze($data['pole9_1'],(int)$data['pole9_2'],(int)$data['pole9_8'],(int)$data['pole9_0']);
            break;
        case 'edit_u_ze':
            $result = $model->fun_u_ze((int)$data['pole9_0'], (int)$data['pole9_3'], (int)$data['pole9_4'], $method);
            break;
        case 'edit_za_ze':
            $result = $model->fun_za_ze((int)$data['pole9_0'], (int)$data['pole9_5'], (int)$data['pole9_6'], $method);
            break;
        case 'edit_proj':
            $result = $model->fun_p((int)$data['pole10_0'],$data['pole10_1'],(int)$data['pole10_2'], (int)$data['pole10_3']/*, (int)$data['pole10_6']*/, (int)$data['pole10_5'],$data['pole10_10']);
            break;
        case 'del_za':
            $result = $model->fun_d_za((int)$data['pole10_4']);
            break;
        case 'edit_zad':
            $result = $model->fun_za((int)$data['pole11_8'], (int)$data['pole11_2'], $data['pole11_1'], $data['pole11_4'], $data['pole11_5']/*, (int)$data['pole11_9']*/);
            break;
        case 'aso_self':
            $result = $model->fun_self((int)$data['pole11_8'], (int)$data['pole11_6'], (int)$data['pole11_7'], $method);
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