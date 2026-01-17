<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../auth.php';
require_role(2);

require_once __DIR__ . '/../../app/models/adminRepsModel.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'];

try{

    $model = new adminRepsModel($pdo);

    switch($action){
        case 'percent':
            $result = $model->percent();
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            $headers = ['nadrzędny_projekt' => 'Id projektu',
                        'id_za' => 'Id zadania',
                        'nazwa_zadania' => 'Nazwa zadania'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
        case 'activity':
            //$result = ;
            break;
        case 'workload':
            //$result = ;
            break;
        default:
            echo 'Niepoprawne zapytanie: parametr action';
            exit;
    }
    //echo json_encode($result);

}catch(PDOException $e){

}
?>