<?php
header('Content-Type: application/json; charset=utf-8');
$dsn = "pgsql:host=localhost;port=5432;dbname=projectfollower";
$user = "postgres";
$pass = "Mialem2koty!";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]);
    $handlers = ['projekt'=>'fun_s_proj', 'typ'=>'fun_s_t','user'=>'fun_s_u', 'zesp'=>'fun_s_ze', 'zad'=>'fun_s_za'];
    $action = $_GET['action'];
    //obrona tutaj (na potem)

    $fn = $handlers[$action];
    $result = $fn($pdo);


    echo json_encode($result);

} catch(PDOException $e)
{
    echo jsno_encode(['ok'=>false,'error'=>'Błąd bazy danych.','code'=>$e->getCode()]);
}

function fun_s_proj(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT id_p, nazwa_projektu FROM Projekt WHERE archiwalne=false AND zakończony=false ORDER BY nazwa_projektu;");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($data==[]){
        return ['ok'=>false,'error'=>'Brak typów'];
    }
    return ['ok'=>true,'ret_val'=>$data];
}

function fun_s_t(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT id_t, nazwa_typu FROM Typ_projektu ORDER BY nazwa_typu;");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($data==[]){
        return [];
    }
    return ['ok'=>true,'ret_val'=>$data];
}

function fun_s_u(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT id_u, login FROM Użytkownik ORDER BY login;");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($data==[]){
        return [];
    }
    return ['ok'=>true,'ret_val'=>$data];
}

function fun_s_ze(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT id_Ze, nazwa FROM Zespół WHERE archiwalne=false ORDER BY nazwa;");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($data==[]){
        return [];
    }
    return ['ok'=>true,'ret_val'=>$data];
}

/*function fun_s_za(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT id_Ze, nazwa FROM Zespół ORDER BY nazwa;");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($data==[]){
        return [];
    }
    return ['ok'=>true,'ret_val'=>$data];
}*/


?>