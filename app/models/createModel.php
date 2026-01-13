<?php

class createModel {
    private PDO $pdo;

    public function __construct(PDO $our_pdo){
        $this->pdo = $our_pdo;
    }

    public function mapError(PDOException $e): array
    {
        $sqlstate = $e->getCode();
        $msg = 'Błąd bazy danych.';
        $http = 500;

        switch ($sqlstate){
            case 'P1001':
                $msg = "Dane użytkownika 'login' lub 'adres email' nie mogą się powtarzać!";
                break;
            case 'P1002':
                $msg = "Nazwa projektu nie może się powtarzać!";
                break;
            case 'P1003':
                $msg = "Zadanie o takiej nazwie już istnieje w projekcie!";
                break;
            case 'P1004':
                $msg = "Nie da się dodać zadania do projektu zakończonego lub archiwizowanego!";
                break;
            case 'P1005':
                $msg = "Nazwa typu nie może się powtarzać!";
                break;
            case 'P1006':
                $msg = "Nazwy zespołów nie mogą się powtarzać!";
                break;
            case 'P1007':
                $msg = "Nieprawidłowe dane w polu 'lider' - taki użytkownik nie istnieje!";
                break;
            case '23503': //fk
                $msg = "Wystąpił błąd foreign-key, wartość pola jest nieprawidłowa!";
                break;
            case '23514': //check
                $msg = "Wystąpił błąd check, wartość pola jest nieprawidłowa!";
                break;
        }
        return ['http'=>$http, 'payload'=>[
        'ok' => false,
        'error' => $msg,
        'code' => $sqlstate,
        'detail' => $e->getMessage(),
    ]];
    }

    public function fun_u(string $imie, string $nazwisko, string $email, string $login, string $haslo, int $rola): array
    {
        /*$imie = $post['pole3_1'];
        $nazwisko = $post['pole3_2'];
        $email = $post['pole3_3'];
        $login = $post['pole3_4'];
        $haslo = $post['pole3_5'];
        $rola = $post['pole3_6'];*/

        $stmt = $this->pdo->prepare("INSERT INTO Użytkownik (imie, nazwisko, adres_mail, login, haslo, rola) VALUES (:imie, :nazwisko, :email, :login,:haslo, :rola);");
        $stmt->execute([':email' => $email, ':nazwisko' => $nazwisko, ':imie' => $imie, ':login' => $login, ':haslo' => $haslo, ':rola' => $rola]);
        return ['ok'=>true, 'message'=> 'Dodano rekord.'];

    }

    public function fun_t(string $nazwa): array
    {
        //$nazwa = $post['pole2'];

        $stmt = $this->pdo->prepare("INSERT INTO Typ_projektu (nazwa_typu) VALUES (:nazwa);");
        $stmt->execute([':nazwa' => $nazwa]);
        return ['ok'=>true, 'message'=> 'Dodano rekord.'];
    }

    public function fun_p(string $nazwa, int $typ, string $czas, int $admin): array
    {
        /*$nazwa = $post['pole5_1'];
        $typ = $post['pole5_2'];
        $czas = $post['pole5_3'];
        $admin = $post['pole5_4'];*/

        if($admin==0){
            $stmt = $this->pdo->prepare("INSERT INTO Projekt (nazwa_projektu,typ_projektu,czas_startu) VALUES (:nazwa,:typ,:czas);");
            $stmt->execute([':nazwa' => $nazwa, ':typ'=>$typ,':czas'=>$czas]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO Projekt (nazwa_projektu,typ_projektu,admin,czas_startu) VALUES (:nazwa,:typ,:admin,:czas);");
            $stmt->execute([':nazwa' => $nazwa, ':typ'=>$typ,':admin'=>$admin,':czas'=>$czas]);
        }
        return ['ok'=>true, 'message'=> 'Dodano rekord.'];
    }

    public function fun_ze(string $nazwa, int $lider): array
    {
        //$nazwa = $post['pole4_1'];
        //$lider = $post['pole4_2'];

        $stmt = $this->pdo->prepare("INSERT INTO Zespół (nazwa,lider) VALUES (:nazwa,:lider);");
        $stmt->execute([':nazwa' => $nazwa,':lider'=>$lider]);

        //DODAĆ WSTAWIANIE ASOCJACJI BO INACZEJ BĘDZIE LIPA - jusz :3
        $stmt = $this->pdo->prepare("SELECT id_ze FROM Zespół WHERE nazwa=:nazwa AND lider=:lider;");
        $stmt->execute([':nazwa' => $nazwa,':lider'=>$lider]);
        $idZ = $stmt->fetchColumn();

        $stmt = $this->pdo->prepare("INSERT INTO Asocjacja_U_Ze (id_u,id_ze) VALUES (:lider,:idZ);");
        $stmt->execute([':idZ' => $idZ,':lider'=>$lider]);

        return ['ok'=>true, 'message'=> 'Dodano rekord.'];
    }

    public function fun_za(string $nazwa, int $projekt, int $priorytet, string $czas_start, string $czas_end): array
    {
        /*$nazwa = $post['pole6_1'];
        $projekt = $post['pole6_2'];
        $priorytet = $post['pole6_5'];
        $czas_start = $post['pole6_3'];
        $czas_end = $post['pole6_4'];*/

        if($priorytet==""){
            $stmt = $this->pdo->prepare("INSERT INTO Zadanie (nazwa_zadania,nadrzędny_projekt,czas_staru,czas_zakończenia) VALUES (:nazwa,:projekt,:czas_start,:czas_end);");
            $stmt->execute([':nazwa' => $nazwa, ':projekt'=>$projekt,':czas_start'=>$czas_start, ':czas_end'=>$czas_end]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO Zadanie (nazwa_zadania,nadrzędny_projekt,priorytet,czas_staru,czas_zakończenia) VALUES (:nazwa,:projekt,:priorytet,:czas_start,:czas_end);");
            $stmt->execute([':nazwa' => $nazwa, ':projekt'=>$projekt,':priorytet'=>$priorytet,':czas_start'=>$czas_start, ':czas_end'=>$czas_end]);
        }
        return ['ok'=>true, 'message'=> 'Dodano rekord.'];
    }

}

?>