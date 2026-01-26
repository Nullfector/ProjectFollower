<?php

class fillDataEdit{
    private PDO $pdo;

    public function __construct(PDO $other_pdo){
        $this->pdo = $other_pdo;
    }

    public function fun_s_lider(int $id): array
    {
        //id zespołu
        $stmt = $this->pdo->prepare("SELECT id_u, login FROM Użytkownik WHERE id_u!=(SELECT lider FROM Zespół WHERE id_ze=:id) ORDER BY login;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_asoc(int $id): array
    {
        //id zespołu
        $stmt = $this->pdo->prepare("SELECT u.id_u, u.login FROM Użytkownik u WHERE NOT EXISTS(SELECT 1 FROM Asocjacja_U_Ze aso WHERE u.id_u = aso.id_u AND aso.id_ze=:id) ORDER BY u.login;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_asod(int $id): array
    {
        //id zespołu
        $stmt = $this->pdo->prepare("SELECT u.id_u, u.login FROM Użytkownik u JOIN Asocjacja_U_Ze aso ON aso.id_u=u.id_u WHERE aso.id_ze=:id 
        AND u.id_u != (SELECT lider FROM Zespół WHERE id_ze=:id) ORDER BY u.login;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_asodz(int $id, int $idq): array
    {
        //id projektu
        //idq zespołu
        $stmt = $this->pdo->prepare("SELECT z.id_za, z.nazwa_zadania FROM Asocjacja_Za_Ze aso JOIN Zadanie z ON z.id_za=aso.id_zadania WHERE z.nadrzędny_projekt=:id
        AND aso.id_zespolu=:idq ORDER BY z.nazwa_zadania;");
        $stmt->execute([':id' => $id, ':idq'=>$idq]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }
    public function fun_s_asocz(int $id): array
    {
        //DZIAŁA W ZAŁOŻENIU ŻE 1 ZESPÓŁ - 1 ZADANIE - CZYLI TAK JAK USTAWA PRZEWIDUJE
        //idprojektu
        $stmt = $this->pdo->prepare("SELECT z.id_za, z.nazwa_zadania FROM Asocjacja_Za_Ze aso RIGHT JOIN Zadanie z ON z.id_za=aso.id_zadania WHERE z.nadrzędny_projekt=:id
        AND aso.id_zespolu IS NULL ORDER BY z.nazwa_zadania;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_typ(int $id): array
    {
        //id projektu
        $stmt = $this->pdo->prepare("SELECT t.id_t, t.nazwa_typu FROM Typ_projektu t WHERE t.id_t!=(SELECT p.typ_projektu FROM Projekt p WHERE p.id_p=:id) ORDER BY t.nazwa_typu;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_a(int $id): array
    {
        //id projektu
        $stmt = $this->pdo->prepare("SELECT u.id_u, u.login FROM Użytkownik u WHERE u.id_u IS DISTINCT FROM (SELECT p.admin FROM Projekt p WHERE p.id_p=:id) ORDER BY u.login;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_za(int $id): array
    {
        //id projektu
        $stmt = $this->pdo->prepare("SELECT id_za, nazwa_zadania FROM Zadanie WHERE nadrzędny_projekt=:id AND zakończony=false ORDER BY nazwa_zadania;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_znew(int $id, int $idq): array
    {
        //id zadania
        //idq projektu
        $stmt = $this->pdo->prepare("SELECT z.id_za, z.nazwa_zadania FROM Zadanie z WHERE NOT EXISTS(SELECT 1 FROM Asocjacja_Za_Self aso WHERE aso.id_zad_podległe=:id
        AND aso.id_zad_blokujące=z.id_za) AND z.nadrzędny_projekt=:idq AND (SELECT czas_zakończenia FROM Zadanie WHERE id_za=:id)<z.czas_staru
        AND z.rozpoczęty=false ORDER BY z.nazwa_zadania;");
        $stmt->execute([':id' => $id,'idq'=>$idq]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_zdel(int $id): array
    {
        //id zadania
        $stmt = $this->pdo->prepare("SELECT z.id_za, z.nazwa_zadania FROM Asocjacja_Za_Self aso JOIN Zadanie z ON aso.id_zad_blokujące=z.id_za
        WHERE aso.id_zad_podległe=:id AND z.rozpoczęty=false ORDER BY z.nazwa_zadania;");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok'=>true,'ret_val'=>$data];
    }
}
?>