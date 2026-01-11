<?php
header('Content-Type: application/json; charset=utf-8');
$dsn = "pgsql:host=localhost;port=5432;dbname=projectfollower";
$user = "postgres";
$pass = "Mialem2koty!";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]);
    $handlers = ['lider'=>'fun_s_lider', 'aso_u_ze_create'=>'fun_s_asoc','aso_u_ze_del'=>'fun_s_asod',
                'aso_za_ze_create' => 'fun_s_asocz', 'aso_za_ze_del'=>'fun_s_asodz','admin'=>'fun_s_a','typ'=>'fun_s_typ',
                'zadanie'=>'fun_s_za', 'zadanie_new'=>'fun_s_znew', 'zadanie_del'=>'fun_s_zdel'];
    $action = $_GET['action'];
    //obrona tutaj (na potem)

    $fn = $handlers[$action];
    $result = $fn($pdo);

    echo json_encode($result);

} catch(PDOException $e)
{
    echo json_encode(['ok'=>false,'error'=>'Błąd bazy danych.','code'=>$e->getCode()]);
}


//ZAPYTANIA SĄ DO POPRAWY! A RACZEJ DO SPRAWDZENIA NA INNYM ZESTAWIE DANYCH CZY DOBRZE DZIAŁAJĄ

function fun_s_lider(PDO $pdo): array
{
    $id = $_GET['id']; //zespołu
    $stmt = $pdo->prepare("SELECT id_u, login FROM Użytkownik WHERE id_u!=(SELECT lider FROM Zespół WHERE id_ze=:id) ORDER BY login;");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}

function fun_s_asoc(PDO $pdo): array
{
    $id = $_GET['id']; //zespołu
    $stmt = $pdo->prepare("SELECT u.id_u, u.login FROM Użytkownik u WHERE NOT EXISTS(SELECT 1 FROM Asocjacja_U_Ze aso WHERE u.id_u = aso.id_u AND aso.id_ze=:id) ORDER BY u.login;");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}

function fun_s_asod(PDO $pdo): array
{
    $id = $_GET['id']; //zespołu
    $stmt = $pdo->prepare("SELECT u.id_u, u.login FROM Użytkownik u JOIN Asocjacja_U_Ze aso ON aso.id_u=u.id_u WHERE aso.id_ze=:id 
    AND u.id_u != (SELECT lider FROM Zespół WHERE id_ze=:id) ORDER BY u.login;");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}

function fun_s_asodz(PDO $pdo): array
{
    $id = $_GET['id']; //projektu
    $idq = $_GET['idq']; //zespołu
    $stmt = $pdo->prepare("SELECT z.id_za, z.nazwa_zadania FROM Asocjacja_Za_Ze aso JOIN Zadanie z ON z.id_za=aso.id_zadania WHERE z.nadrzędny_projekt=:id
    AND aso.id_zespolu=:idq ORDER BY z.nazwa_zadania;");
    $stmt->execute([':id' => $id, ':idq'=>$idq]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}
function fun_s_asocz(PDO $pdo): array
{
    //DZIAŁA W ZAŁOŻENIU ŻE 1 ZESPÓŁ - 1 ZADANIE - CZYLI TAK JAK USTAWA PRZEWIDUJE
    $id = $_GET['id']; //projektu
    $stmt = $pdo->prepare("SELECT z.id_za, z.nazwa_zadania FROM Asocjacja_Za_Ze aso RIGHT JOIN Zadanie z ON z.id_za=aso.id_zadania WHERE z.nadrzędny_projekt=:id
     AND aso.id_zespolu IS NULL ORDER BY z.nazwa_zadania;");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}
function fun_s_typ(PDO $pdo): array
{
    $id = $_GET['id']; //projektu
    $stmt = $pdo->prepare("SELECT t.id_t, t.nazwa_typu FROM Typ_projektu t WHERE t.id_t!=(SELECT p.typ_projektu FROM Projekt p WHERE p.id_p=:id) ORDER BY t.nazwa_typu;");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}
function fun_s_a(PDO $pdo): array
{
    $id = $_GET['id']; //projektu
    $stmt = $pdo->prepare("SELECT u.id_u, u.login FROM Użytkownik u WHERE u.id_u IS DISTINCT FROM (SELECT p.admin FROM Projekt p WHERE p.id_p=:id) ORDER BY u.login;");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}
function fun_s_za(PDO $pdo): array
{
    $id = $_GET['id']; //projektu
    $stmt = $pdo->prepare("SELECT id_za, nazwa_zadania FROM Zadanie WHERE nadrzędny_projekt=:id AND zakończony=false ORDER BY nazwa_zadania;");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}
function fun_s_znew(PDO $pdo): array
{
    $id = $_GET['id']; //zadania
    $idq = $_GET['idq']; //projektu
    $stmt = $pdo->prepare("SELECT z.id_za, z.nazwa_zadania FROM Zadanie z WHERE NOT EXISTS(SELECT 1 FROM Asocjacja_Za_Self aso WHERE aso.id_zad_podległe=:id
    AND aso.id_zad_blokujące=z.id_za) AND z.nadrzędny_projekt=:idq AND (SELECT czas_zakończenia FROM Zadanie WHERE id_za=:id)<z.czas_staru ORDER BY z.nazwa_zadania;");
    $stmt->execute([':id' => $id,'idq'=>$idq]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}

function fun_s_zdel(PDO $pdo): array
{
    $id = $_GET['id']; //zadania
    $stmt = $pdo->prepare("SELECT z.id_za, z.nazwa_zadania FROM Asocjacja_Za_Self aso JOIN Zadanie z ON aso.id_zad_blokujące=z.id_za
     WHERE aso.id_zad_podległe=:id ORDER BY z.nazwa_zadania;");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['ok'=>true,'ret_val'=>$data];
}

?>