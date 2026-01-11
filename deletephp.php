<?php
//DO EDYCJI TAK ABY BYŁO Z DELETE WSZĘDIE!!!! - jusz jest
header('Content-Type: application/json; charset=utf-8');
$dsn = "pgsql:host=localhost;port=5432;dbname=projectfollower";
$user = "postgres";
$pass = "Mialem2koty!";

function mapError(PDOException $e): array
{
    $sqlstate = $e->getCode();
    $msg = 'Błąd bazy danych.';
    $http = 500;

    switch ($sqlstate){
        case 'P1008':
            $msg = "Tak długo jak użytkownik jest liderem zespołu, nie można go usunąć!";
            break;
        case 'P1009':
            $msg = "Nie da się usunąć użytkownika będącego adminem zarchiwizowanego lub archiwalnego projektu";
            break;
        case '23503': //fk
            $msg = "Wystąpił błąd foreign-key, próbujesz usunąć wartość przypisaną do innego istniejącego obiektu - nie wolno!";
            break;
        default:
            $msg = "Coś poszło nie tak :c";
    }
    return ['http'=>$http, 'payload'=>[
    'ok' => false,
    'error' => $msg,
    'code' => $sqlstate,
    'detail' => $e->getMessage(),
  ]];
}

try
{
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]);
    $handlers = ['del_user' => 'fun_u',
                'del_typ' => 'fun_t',
                'del_zesp' => 'fun_ze',
                'del_proj' => 'fun_p'];
//--------------
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'DELETE') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            throw new Exception('Nieprawidłowy JSON w body (DELETE).');
        }
    } else {
        // zostawiamy kompatybilność z POST (jeśli kiedyś użyjesz)
        $data = $_POST;
    }
//------------
    //$action = $_POST['action'];
    $action = $data['action'];
    $fn = $handlers[$action];
    //$result = $fn($pdo, $_POST);
    $result = $fn($pdo, $data);

    echo json_encode(['ok'=>true]+$result);

} catch (PDOException $e) {
    $mapped = mapError($e);
    //http_response_code($mapped['http']);
    echo json_encode($mapped['payload']);
}


function fun_u(PDO $pdo, array $post): array
{
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM Użytkownik WHERE id_u=:id;");
    $stmt->execute([':id' => $id]);
    return ['message'=> 'Usunięto rekord pomyślnie.'];

}

function fun_t(PDO $pdo, array $post): array
{
    $id = $post['id'];

    $stmt = $pdo->prepare("DELETE FROM Typ_projektu WHERE id_t=:id;");
    $stmt->execute([':id' => $id]);
    return ['message'=> 'Usunięto rekord pomyślnie.'];
}

//2 funkcje poniżej raczej nie potrzebują wyłapywania exception, bo i tak można wprowadzić tylko takie indeksy, które są w option
//Może w przyszłości dodam tam ELSE RAISE EXCEPTION, ale to może jak już wszytsko będzie działać co?

function fun_p(PDO $pdo, array $post): array
{
    $id = $_POST['id'];

    $stmt = $pdo->prepare("SELECT usuwanie_projektu_funkcja(:id);");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row == "ok")
    {
        return ['message'=> 'Usunięto rekord pomyślnie.'];
    } else {
        return ['message'=> 'Rekordu nie można usunąć - dodano etykietę arch.'];
    }
}

function fun_ze(PDO $pdo, array $post): array
{
    $id = $_POST['id'];

    $stmt = $pdo->prepare("SELECT usuwanie_zespołu_funkcja(:id);");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == "ok")
    {
        return ['message'=> 'Usunięto rekord pomyślnie.'];
    } else {
        return ['message'=> 'Rekordu nie można usunąć - dodano etykietę arch.'];
    }
}

?>