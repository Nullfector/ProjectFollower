<?php

class adminRepsModel{
    private PDO $pdo;

    public function __construct($other_pdo){
        $this->pdo = $other_pdo;
    }

    public function percent(){ 
        $stmt = $this->pdo->query('SELECT * FROM raport_spóźnień;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function user(){
        $stmt = $this->pdo->query('SELECT * FROM raport_aktywności;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function team(){
        $stmt = $this->pdo->query('SELECT * FROM raport_nakładu_pacy;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function edit_state_proj(int $id, int $state)
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

    public function edit_state_zad(int $id, int $state)
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

    public function get_proj()
    {
        $stmt = $this->pdo->query('SELECT id_p, nazwa_projektu FROM Projekt WHERE zakończony = false AND archiwalne=false AND rozpoczęty = false ORDER BY nazwa_projektu;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_zad(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT id_za, nazwa_zadania FROM Zadanie WHERE nadrzędny_projekt=:id AND zakończony=false AND rozpoczęty = false ORDER BY nazwa_zadania;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_proj2()
    {
        $stmt = $this->pdo->query('SELECT id_p, nazwa_projektu FROM Projekt WHERE zakończony = false AND archiwalne=false AND rozpoczęty = true ORDER BY nazwa_projektu;');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function get_zad2(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT id_za, nazwa_zadania FROM Zadanie WHERE nadrzędny_projekt=:id AND zakończony=false AND rozpoczęty = true ORDER BY nazwa_zadania;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }
}

?>