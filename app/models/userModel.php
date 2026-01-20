<?php

class userModel{
    private PDO $pdo;

    public function __construct(PDO $other){
        $this->pdo = $other;
    }

    public function setupUser(int $id): array{
        $stmt = $this->pdo->prepare('SELECT id_ze, nazwa FROM Zespół WHERE lider=:id AND archiwalne=false ORDER BY nazwa;');
        $stmt->execute([':id' => $id]);
        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $teams;
    }

    public function getTeams(int $id): array{
        $stmt = $this->pdo->prepare('SELECT z.id_ze, z.nazwa FROM Asocjacja_U_Ze aso JOIN Zespół z USING(id_ze) WHERE 
        aso.id_u = :id AND z.archiwalne=false ORDER BY nazwa;');
        $stmt->execute([':id' => $id]);
        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=> true, 'ret_val' => $teams];
    }

    public function getProjs(int $id): array{
        $stmt = $this->pdo->prepare('SELECT DISTINCT p.id_p, p.nazwa_projektu FROM Asocjacja_U_Ze aso1 JOIN Asocjacja_Za_Ze aso2 
        ON aso1.id_ze = aso2.id_zespolu JOIN Zadanie z ON aso2.id_zadania = z.id_za JOIN Projekt p ON p.id_p = z.nadrzędny_projekt 
        WHERE aso1.id_u = :id AND p.rozpoczęty= true AND p.zakończony = false AND p.archiwalne = false ORDER BY p.nazwa_projektu;');
        $stmt->execute([':id' => $id]);
        $projs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=> true, 'ret_val' => $projs];
    }

    public function getZadTable(int $id): array{
        $stmt = $this->pdo->prepare('SELECT z.id_za, z.nazwa_zadania, z.fakt_czas_startu, z.czas_zakończenia, p.nazwa_projektu, z.czas_staru FROM 
        Asocjacja_U_Ze aso1 JOIN Asocjacja_Za_Ze aso2 ON aso1.id_ze = aso2.id_zespolu 
        JOIN Zadanie z ON aso2.id_zadania = z.id_za JOIN Projekt p ON p.id_p = z.nadrzędny_projekt WHERE 
        z.rozpoczęty = true AND z.zakończony = false AND aso1.id_u = :id ORDER BY p.nazwa_projektu;');

        $stmt->execute([':id' => $id]);
        $zads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=> true, 'ret_val' => $zads];
    }

    public function getUser(int $id): array{
        $stmt = $this->pdo->prepare('SELECT u.imie, u.nazwisko, u.adres_mail, u.login, CASE WHEN u.id_u = z.lider THEN 1 
        ELSE 0 END AS is_lider FROM Asocjacja_U_Ze aso1 JOIN Użytkownik u USING(id_u) JOIN Zespół z USING(id_ze) WHERE aso1.id_ze=:id;');
        $stmt->execute([':id' => $id]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true, 'ret_val'=>$users];
    }

    public function getUnfinishedTasks(int $idp, int $uid): array{
        $stmt = $this->pdo->prepare('SELECT z.nazwa_zadania, z.fakt_czas_startu, z.fakt_czas_zak, z.czas_staru, z.czas_zakończenia 
        FROM Asocjacja_U_Ze aso1 JOIN Asocjacja_Za_Ze aso2 ON aso1.id_ze=aso2.id_zespolu 
        JOIN Zadanie z ON aso2.id_zadania = z.id_za WHERE aso1.id_u=:id AND z.nadrzędny_projekt=:idq;');
        $stmt->execute([':id' => $uid, ':idq'=>$idp]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['ok'=>true, 'ret_val'=>$users];
    }
}

?>