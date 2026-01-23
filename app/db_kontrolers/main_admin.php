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
            $result = $model->edit_state_zad((int)$data['id'], (int)$data['val']);
            break;
        case 'projekt_fin':
            $result = $model->get_project();
            break;
        case 'zespoly':
            $result = $model->get_zesps();
            break;
        case 'tasks':
            $result = $model->write_tasks((int)$data['id']);
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            
            $headers = ['nazwa_zadania' => 'Nazwa zadania',
                        'czas_staru' => 'Planowy czas startu',
                        'czas_zakończenia' => 'Planowy czas zakończenia',
                        'fakt_czas_startu' => 'Faktyczny czas rozpoczęcia',
                    'fakt_czas_zak' => 'Faktyczny czas zakończenia'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
        case 'tasks_conn':
            $result = $model->get_task_connections((int)$data['id']);
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            
            $headers = ['nazwa_p' => 'Nazwa zadania podległego',
                        'nazwa_b' => 'Nazwa zadania blokującego'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
            //-----------------------------------------------------------------
        case 'team':
            $result = $model->get_team((int)$data['id']);
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            
            $headers = ['nazwa' => 'Nazwa zespołu',
                        'archiwalne' => 'Czy zespół jest archiwalny',
                        'login' => 'Login lidera zespołu',
                    'pelne_imie' => 'Pełne imie lidera',
                    'adres_mail' => 'Adres e-mail lidera'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
        case 'team_conn':
            $result = $model->get_team_connections_u((int)$data['id']);
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            
            $headers = ['login' => 'Login członka',
                        'pelne_imie' => 'Imie członka',
                        'adres_mail' => 'Adres e-mail'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
        case 'team_conn2':
            $result = $model->get_team_connections_zad((int)$data['id']);
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            
            $headers = ['nazwa_zadania' => 'Nazwa zadania',
                        'fakt_czas_startu' => 'Czas rozpoczęcia',
                        'fakt_czas_zak' => 'Czas zakończenia',
                    'nazwa_projektu' => 'Nazwa nadrzędnego projektu'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
            //-------------------------------------------
        case 'pr':
            $result = $model->get_detail_p((int)$data['id']);
            $row = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }

            include __DIR__ . '/../views/partial/raport_list.php';
            break;
            //--------------------------------------------------------------------
        default:
            echo json_encode(['ok'=>false,'error'=>"Niepoprawny parametr 'action'."]);
            exit;
    }
    if(!($action == 'tasks_conn' || $action == 'tasks' || $action == 'team' || $action == 'team_conn' || $action == 'team_conn2'
    || $action == 'pr')) echo json_encode($result);

} catch (PDOException $e) {
    //$mapped = $model->mapError($e);
    //http_response_code($mapped['http']);
    echo json_encode(/*$mapped['payload']*/$e);
}



?>