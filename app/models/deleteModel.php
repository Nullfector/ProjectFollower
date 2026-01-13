<?php

class deleteModel{
    private PDO $pdo;

    public function __construct(PDO $other_pdo){
        $this->pdo = $other_pdo;
    }

    public function mapError(PDOException $e): array
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

    public function fun_u(int $id): array
    {
        $stmt = $this->pdo->prepare("DELETE FROM Użytkownik WHERE id_u=:id;");
        $stmt->execute([':id' => $id]);
        return ['ok'=>true, 'message'=> 'Usunięto rekord pomyślnie.'];

    }

    public function fun_t(int $id): array
    {
        $stmt = $this->pdo->prepare("DELETE FROM Typ_projektu WHERE id_t=:id;");
        $stmt->execute([':id' => $id]);
        return ['ok'=>true, 'message'=> 'Usunięto rekord pomyślnie.'];
    }

    //2 funkcje poniżej raczej nie potrzebują wyłapywania exception, bo i tak można wprowadzić tylko takie indeksy, które są w option
    //Może w przyszłości dodam tam ELSE RAISE EXCEPTION, ale to może jak już wszytsko będzie działać co?

    public function fun_p(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT usuwanie_projektu_funkcja(:id);");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row == "ok")
        {
            return ['ok'=>true, 'message'=> 'Usunięto rekord pomyślnie.'];
        } else {
            return ['ok'=>true, 'message'=> 'Rekordu nie można usunąć - dodano etykietę arch.'];
        }
    }

    public function fun_ze(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT usuwanie_zespołu_funkcja(:id);");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row == "ok")
        {
            return ['ok'=>true, 'message'=> 'Usunięto rekord pomyślnie.'];
        } else {
            return ['ok'=>true, 'message'=> 'Rekordu nie można usunąć - dodano etykietę arch.'];
        }
    }
}

?>