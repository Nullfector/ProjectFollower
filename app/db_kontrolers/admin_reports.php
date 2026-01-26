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
                //echo 'Zapytanie do bazy danych się nie powiodło!';
                if ($_GET['format'] === 'json') {
                    echo json_encode(['ok'=>false, 'error'=>'Zapytanie do bazy danych się nie powiodło!']);
                } else {
                    echo 'Zapytanie do bazy danych się nie powiodło!';
                }
                exit;
            }
            
            $headers = ['nazwa_projektu' => 'Nazwa projektu',
                        'late_tasks' => 'Ilość spóźninych zadań',
                        'prcnt_end' => 'Procent zakończenia projektu',
                        'czas_zakończenia' => 'Planowy deadline projektu'];
            
            include __DIR__ . '/../views/partial/report_table.php';
            break;
        case 'activity':
            $result = $model->user();
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            $headers = ['login' => 'Login',
                        'liczba_ukończonych_zadań' => 'Ile zadań ukończył',
                        'prcnt_zadań' => 'Procent wszystkich zadań',
                        'liczba_ukończonych_projektów' => 'W ilu ukończonych projektach brał udział',
                        'prcnt_projektów' => 'Procent wszystkich projektów'];


            if ($_GET['format'] === 'json') {
                echo json_encode([
                    'ok' => true,
                    'ret_val' => $rows
                ], JSON_UNESCAPED_UNICODE);
            } else {
                include __DIR__ . '/../views/partial/report_table.php';
            }
            break;
        case 'workload':
            $result = $model->team();
            $rows = $result['ret_val'];
            if($result['ok']==false){
                echo 'Zapytanie do bazy danych się nie powiodło!';
            }
            $headers = ['id_zesp' => 'Id zespołu',
                        'nazwa_zespołu' => 'Nazwa zespólu',
                        'all_tasks' => 'Ilość zadań które zespół wykonał',
                        'all_projs' => 'Ilość projektów w kórych zespół uczestniczył',
                        'average' => 'Średni procent skończenia aktywnych projektów'];

            include __DIR__ . '/../views/partial/report_table.php';
            break;
        default:
            echo 'Niepoprawne zapytanie: parametr action';
            exit;
    }
    //echo json_encode($result);

}catch(PDOException $e){

}
?>