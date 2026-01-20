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

    public function get_zad(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT id_za, nazwa_zadania FROM Zadanie WHERE nadrzędny_projekt=:id AND zakończony=false AND rozpoczęty = false ORDER BY nazwa_zadania;");
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

    public function get_project(): array
    {
        $stmt = $this->pdo->query('SELECT id_p, nazwa_projektu FROM Projekt ORDER BY nazwa_projektu;');
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

}

?>