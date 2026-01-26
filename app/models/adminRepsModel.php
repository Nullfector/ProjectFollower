<?php

class adminRepsModel{
    private PDO $pdo;

    public function __construct($other_pdo){
        $this->pdo = $other_pdo;
    }

    public function percent(): array{ 
        $stmt = $this->pdo->query('SELECT * FROM raport_spóźnień;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function user(): array{
        $stmt = $this->pdo->query('SELECT * FROM raport_aktywności;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function team(): array{
        $stmt = $this->pdo->query('SELECT * FROM raport_nakładu_pracy;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function edit_state_proj(int $id, int $state): array
    {
        $date = date('Y-m-d');
        if($state==0){
            $stmt = $this->pdo->prepare('UPDATE Projekt SET rozpoczęty = true, fakt_czas_startu = :d WHERE id_p=:id;');
            $stmt->execute([':id'=>$id, ':d'=>$date]);
        } else {
            $stmt = $this->pdo->prepare('UPDATE Projekt SET zakończony = true, fakt_czas_zak = :d WHERE id_p=:id;');
            $stmt->execute([':id'=>$id, ':d'=>$date]);
        }
        return ['ok'=> true, 'message'=> 'Poprawnie nadpisano'];
    }

    public function edit_state_zad(int $id, int $state): array
    {
        $date = date('Y-m-d');
        if($state==0){
            $stmt = $this->pdo->prepare('UPDATE Zadanie SET rozpoczęty = true, fakt_czas_startu = :d WHERE id_za=:id;');
            $stmt->execute([':id'=>$id, ':d'=>$date]);
        } else {
            $stmt = $this->pdo->prepare('UPDATE Zadanie SET zakończony = true, fakt_czas_zak = :d WHERE id_za=:id;');
            $stmt->execute([':id'=>$id, ':d'=>$date]);
        }
        return ['ok'=> true, 'message'=> 'Poprawnie nadpisano'];
    }

    public function get_proj(): array
    {
        $stmt = $this->pdo->query('SELECT id_p, nazwa_projektu FROM Projekt WHERE zakończony = false AND archiwalne=false AND rozpoczęty = false ORDER BY nazwa_projektu;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    //to poniżej wrócić do starej wersji jak coś się rozsypie
    /*public function get_zad(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT id_za, nazwa_zadania FROM Zadanie WHERE nadrzędny_projekt=:id AND zakończony=false AND rozpoczęty = false ORDER BY nazwa_zadania;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }*/
    public function get_zad(int $id): array
    {
        $stmt = $this->pdo->prepare("WITH not_to_show AS (SELECT DISTINCT aso.id_zad_blokujące FROM Zadanie z JOIN Asocjacja_Za_Self aso 
        ON aso.id_zad_podległe = z.id_za WHERE z.nadrzędny_projekt=:id AND z.zakończony=false)  
        SELECT zad.id_za, zad.nazwa_zadania FROM Zadanie zad WHERE zad.nadrzędny_projekt = :id
        AND NOT EXISTS (
            SELECT 1
            FROM not_to_show nts
            WHERE nts.id_zad_blokujące = zad.id_za
        ) AND zad.rozpoczęty = false 
        ORDER BY zad.nazwa_zadania");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_proj2(): array
    {
        $stmt = $this->pdo->query('SELECT id_p, nazwa_projektu FROM Projekt WHERE zakończony = false AND archiwalne=false AND rozpoczęty = true ORDER BY nazwa_projektu;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_zad2(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT id_za, nazwa_zadania FROM Zadanie WHERE nadrzędny_projekt=:id AND zakończony=false AND rozpoczęty = true ORDER BY nazwa_zadania;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

//--------------------------------------------------------------------------------------------------------------------

    public function get_project(int $opt): array
    {
        if($opt==0){
            $stmt = $this->pdo->query('SELECT id_p, nazwa_projektu FROM Projekt ORDER BY nazwa_projektu;');
        } else if($opt==1){
            $stmt = $this->pdo->query('SELECT id_p, nazwa_projektu FROM Projekt WHERE rozpoczęty=zakończony OR archiwalne=true ORDER BY nazwa_projektu;');
        }else {
            $stmt = $this->pdo->query('SELECT id_p, nazwa_projektu FROM Projekt WHERE archiwalne=false AND rozpoczęty=true AND zakończony=false ORDER BY nazwa_projektu;');
        }
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function write_tasks(int $id): array
    {
        $stmt = $this->pdo->prepare('SELECT nazwa_zadania, czas_staru, czas_zakończenia, fakt_czas_startu, fakt_czas_zak FROM Zadanie WHERE nadrzędny_projekt=:id ORDER BY nazwa_zadania;'); //, zakończony, rozpoczęty
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_task_connections(int $id): array
    {
        $stmt = $this->pdo->prepare('SELECT z1.nazwa_zadania AS nazwa_p, z2.nazwa_zadania AS nazwa_b FROM Asocjacja_Za_self aso JOIN Zadanie z1 ON z1.id_za = aso.id_zad_podległe JOIN Zadanie z2 ON z2.id_za = aso.id_zad_blokujące
         WHERE z1.nadrzędny_projekt=:id ORDER BY z1.nazwa_zadania;');
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_team(int $id): array {
        //uwaga tutaj
         $stmt = $this->pdo->prepare("SELECT z.nazwa, u.login, u.imie || ' ' || u.nazwisko AS pelne_imie, u.adres_mail, z.archiwalne FROM Zespół z JOIN Użytkownik u ON z.lider=u.id_u WHERE z.id_ze=:id;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_team_connections_u(int $id): array{
        $stmt = $this->pdo->prepare("SELECT u.login, u.imie || ' ' || u.nazwisko AS pelne_imie, u.adres_mail FROM Asocjacja_U_Ze aso JOIN Użytkownik u USING(id_u) WHERE aso.id_ze=:id 
        AND aso.id_u!=(SELECT lider FROM Zespół WHERE id_ze=:id);");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_team_connections_zad(int $id): array{
        $stmt = $this->pdo->prepare("SELECT z.nazwa_zadania, z.fakt_czas_startu, z.fakt_czas_zak, p.nazwa_projektu FROM Asocjacja_Za_Ze aso JOIN Zadanie z ON z.id_za = aso.id_zadania 
        JOIN Projekt p ON p.id_p=z.nadrzędny_projekt WHERE aso.id_zespolu=:id;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_zesps(int $opt): array{
        if($opt==2){
            $stmt = $this->pdo->query("SELECT id_ze, nazwa FROM Zespół ORDER BY nazwa;");
        } else if($opt==1){
            $stmt = $this->pdo->query("SELECT id_ze, nazwa FROM Zespół WHERE archiwalne=true  ORDER BY nazwa;");
        } else {
            $stmt = $this->pdo->query("SELECT id_ze, nazwa FROM Zespół WHERE archiwalne=false ORDER BY nazwa;");
        }
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_detail_p(int $id): array{
        $stmt = $this->pdo->prepare("SELECT p.id_p, p.archiwalne, p.czas_startu, z.czas_zakończenia, 
        p.fakt_czas_startu, p.fakt_czas_zak FROM Projekt p JOIN Zadanie z ON 
        p.id_zadania_końcowego = z.id_za WHERE p.id_p=:id;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

}

?>