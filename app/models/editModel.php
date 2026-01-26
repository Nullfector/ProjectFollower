<?php
    class editModel{
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
                case 'P1001':
                    $msg = "Login lub mail edytowanego rekordu się powtarza!";
                    break;
                case 'P1010':
                    $msg = "Nie zedytujesz archiwalnego zespołu (to nie powinno się nawet pokazać)!";
                    break;
                case 'P1011':
                    $msg = "Taka asocjacj już istnieje (to nie powinno się nawet pokazać)!";
                    break;
                case '42703':
                    $msg = "Kolumny źle nazwałeś!";
                    break;
                case 'P1012':
                    $msg = "Nie zedytujesz archiwalnego lub zakończonego projektu (to nie powinno się nawet pokazać)!";
                    break;
                case 'P1013':
                    $msg = "Nie da się zmienić czasu startowego zadania gdy owe zadanie już się rozpoczęło!";
                    break;
                case 'P1014':
                    $msg = "Nie można zakończyć projektu - nie wszystkie zadania zostały zakończone!";
                    break;
                case 'P1015':
                    $msg = "Nie usuniesz zakończonego zadania (to nie powinno się nawet pokazać)!";
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

        public function fun_u(string $imie, string $nazwisko, string $mail, string $login, string $haslo, int $rola, int $id): array
        {
            //To jest stary szablon funkcji - działa
            //mógłby być lepszy, ale już trochę mam dosyć zabawy z tym, a funkcja ta działa
            $q = "UPDATE Użytkownik SET ";
            $arr = [];
            if($imie!=""){
                $q .= "imie=:i, ";
                $arr[':i'] = $imie;
            }
            if($nazwisko!=""){
                $q .= "nazwisko=:n, ";
                $arr[':n'] = $nazwisko;
            }
            if($login!=""){
                $q .= "login=:l, ";
                $arr[':l'] = $login;
            }
            if($haslo!=""){
                $q .= "haslo=:h, ";
                $arr[':h'] = $haslo;
            }
            if($mail!=""){
                $q .= "adres_mail=:m, ";
                $arr[':m'] = $mail;
            }
            if($rola!="0"){
                $q .= "rola=:r, ";
                $arr[':r'] = $rola;
            }
            $q = substr($q, 0, -2);
            $q .= " WHERE id_u=:id;";
            $arr[':id'] = $id;

            $stmt = $this->pdo->prepare($q);
            $stmt->execute($arr);
            return ['ok'=>true, 'message'=> 'Zedytowano rekord pomyślnie.'];
        }

        public function fun_ze(string $nazwa, int $lider, int $arch, int $id): array
        {
            //To jest stary szablon funkcji - działa
            //mógłby być lepszy, ale już trochę mam dosyć zabawy z tym, a funkcja ta działa
            $q = "UPDATE Zespół SET ";
            $arr = [];

            if($nazwa!=""){
                $q .= "nazwa=:i, ";
                $arr[':i'] = $nazwa;
            }
            if($lider!="0"){
                $q .= "lider=:n, ";
                $arr[':n'] = $lider;
            }
            if($arch!="0"){
                $q .=  "archiwalne=true, ";
            }
            $q = substr($q, 0, -2);
            $q .= " WHERE id_ze=:id;";
            $arr[':id'] = $id;
            $stmt = $this->pdo->prepare($q);
            $stmt->execute($arr);
            return ['ok'=>true, 'message'=> 'Zedytowano rekord pomyślnie.'];
        }

        public function fun_u_ze(int $id, int $n, int $d, string $req): array
        {
            $arr = [];
            $q="";

            if($req=='POST')
            {
                if($n!="0"){
                $stmt = $this->pdo->prepare("INSERT INTO Asocjacja_U_Ze (id_u,id_ze) VALUES (:n,:id);");
                $stmt->execute([':id'=>$id,':n'=>$n]);
                $q .= 'Utworzono Asocjacje użytkownika pomyślnie! ';
            }
            }
            else if($req=='DELETE')
            {
                if($d!="0"){
                $stmt = $this->pdo->prepare("DELETE FROM Asocjacja_U_Ze WHERE id_u=:d AND id_ze=:id;");
                $stmt->execute([':id'=>$id,':d'=>$d]);
                $q .= 'Usunięto asocjacje użytkownika pomyślnie!';
            }
            }

            return ['ok'=>true, 'message'=> $q];
        }

        public function fun_za_ze(int $id, int $n, int $d, string $req): array
        {
            $arr = [];
            $q="";

            if($req=='POST')
            {
                if($n!="0"){
                $stmt = $this->pdo->prepare("INSERT INTO Asocjacja_Za_Ze (id_zespolu,id_zadania) VALUES (:id,:n);");
                $stmt->execute([':id'=>$id,':n'=>$n]);
                $q .= 'Utworzono Asocjacje z zadaniem pomyślnie! ';
            }
            }
            else if($req=='DELETE')
            {
                if($d!="0"){
                $stmt = $this->pdo->prepare("DELETE FROM Asocjacja_Za_Ze WHERE id_zadania=:d AND id_zespolu=:id;");
                $stmt->execute([':id'=>$id,':d'=>$d]);
                $q .= 'Usunięto asocjacje z zadaniem pomyślnie!';
            }
            }

            return ['ok'=>true, 'message'=> $q];
        }

        public function fun_p(int $id, string $nazwa, int $admin, int $typ, int $arch, string $date): array
        {
            //To jest stary szablon funkcji - działa
            //mógłby być lepszy, ale już trochę mam dosyć zabawy z tym, a funkcja ta działa
            $arr = [];
            $q = "UPDATE Projekt SET ";

            if($nazwa !="")
            {
                $q .= "nazwa_projektu=:nazwa, ";
                $arr[':nazwa'] = $nazwa;
            }
            if($date!=""){
                $q .= "czas_startu=:d, ";
                $arr[':d'] = $date;
            }
            if($admin !="0")
            {
                $q .= "admin=:admin, ";
                $arr[':admin'] = $admin;
            }
            if($typ !="0")
            {
                $q .= "typ_projektu=:typ, ";
                $arr[':typ'] = $typ;
            }
            if($arch !="0")
            {
                $q .= "archiwalne=:arch, ";
                $arr[':arch'] = $arch;
            }
            $q = substr($q, 0, -2);
            $q .= " WHERE id_p=:id;";
            $arr[':id'] = $id;
            $stmt = $this->pdo->prepare($q);
            $stmt->execute($arr);

            return ['ok'=>true, 'message'=> 'Pomyślnie zedytowano rekord!'];
        }

        public function fun_d_za(int $id): array
        {
            $stmt = $this->pdo->prepare("DELETE FROM Zadanie WHERE id_za=:id;");
            $stmt->execute([':id'=>$id]);

            return ['ok'=>true, 'message'=> 'Pomyślnie usunięto zadanie'];
        }

        public function fun_za(int $id, int $prio, string $nazwa, string $czas_s, string $czas_e): array
        {
            $array = [':id'=>$id, ':z'=>$nazwa, ':p'=>$prio, ':c'=>$czas_s, ':cz'=>$czas_e];

            $stmt = $this->pdo->prepare("SELECT edycja_zadania(:id, :z, :p, :c, :cz, false);");

            $stmt->execute($array);

            return ['ok'=>true, 'message'=> "Jakimś cudem się udało :3"];
        }

        public function fun_self(int $id, int $n, int $d, string $req): array
        {
            $q = "";

            if($req=='POST')
            {
                if($n!="0"){
                $stmt = $this->pdo->prepare("INSERT INTO Asocjacja_Za_Self (id_zad_podległe, id_zad_blokujące) VALUES (:id,:n);");
                $stmt->execute([':n'=>$n, ':id'=>$id]);
                $q .= "Dodano nową asocjacje!";
            }
            }
            else if($req=='DELETE')
            {
                if($d!="0"){
                $stmt = $this->pdo->prepare("DELETE FROM Asocjacja_Za_Self WHERE id_zad_podległe=:id AND id_zad_blokujące=:del;");
                $stmt->execute([':del'=>$d,':id'=>$id]);   
                $q .= "Usunięto asocjacje pomyślnie!";
            }
            }
            return ['ok'=>true, 'message'=> $q];
        }
    }
?>