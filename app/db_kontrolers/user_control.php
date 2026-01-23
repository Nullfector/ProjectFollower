<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../auth.php';
require_login();

require_once __DIR__ . '/../../app/models/userModel.php';
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
    $model = new userModel($pdo);

    switch($action){
        case 'team':
            $result = $model->getTeams((int)$_SESSION['uid']);
            break;
        case 'zads':
            $result = $model->getProjs((int)$_SESSION['uid']);
            break;
        case 'pass':
            $result = $model->editPass($data['nowe']);
            break;
        case 'table':
            $result = $model->getZadTable((int)$_SESSION['uid']);
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            
            $headers = ['id_za' => 'Id zadania',
                        'nazwa_zadania' => 'Nazwa zadania',
                        'czas_zakończenia' => 'Planowy czas zakończenia',
                        'fakt_czas_startu' => 'Czas rozpoczęcia',
                    'nazwa_projektu' => 'Nazwa nadrzędnego projektu',
                'czas_staru' => 'Planowy czas rozpoczęcia'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
        case 'users':
            $result = $model->getUser((int)$data['id']);
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            
            $headers = ['imie' => 'Imie',
                        'nazwisko' => 'Nazwisko',
                        'adres_mail' => 'Adres e-mail',
                        'login' => 'Login',
                    'is_lider' => 'Czy jest liderem?'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
        case 'unfinished':
            $result = $model->getUnfinishedTasks((int)$data['id'],(int)$_SESSION['uid']);
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            
            $headers = ['nazwa_zadania' => 'Nazwa zadania',
                        'fakt_czas_startu' => 'Faktyczny czas startu',
                        'fakt_czas_zak' => 'Faktyczny czas zakończenia',
                        'czas_staru' => 'Planowy czas startu',
                    'czas_zakończenia' => 'Planowy czas zakończenia'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
        default:
            echo json_encode(['ok'=>false,'error'=>"Niepoprawny parametr 'action'."]);
            exit;
    }
    if(!($action == 'table' || $action == 'users' || $action == 'unfinished')) echo json_encode($result);

} catch (PDOException $e) {
    //$mapped = $model->mapError($e);
    //http_response_code($mapped['http']);
    echo json_encode(/*$mapped['payload']*/$e);
}



?>