<?php
    class ToEditModel{
        private PDO $pdo;

        public function __construct(PDO $other_pdo){
            $this->pdo = $other_pdo;
        }

        public function fun_u(int $id):array {
            $stmt = $this->pdo->prepare("SELECT imie,nazwisko,adres_mail,login,haslo,rola FROM Użytkownik WHERE id_u=:id;");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['ok'=>true,'ret_val'=>$data];
        }

        public function fun_ze(int $id):array {
            $stmt = $this->pdo->prepare("SELECT nazwa FROM Zespół WHERE id_ze=:id;");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['ok'=>true,'ret_val'=>$data];
        }

        public function fun_p(int $id):array {
            $stmt = $this->pdo->prepare("SELECT nazwa_projektu, czas_startu, rozpoczęty FROM Projekt WHERE id_p=:id;");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['ok'=>true,'ret_val'=>$data];
        }

        public function fun_zad(int $id):array {
            $stmt = $this->pdo->prepare("SELECT nazwa_zadania,priorytet,czas_staru,czas_zakończenia, rozpoczęty FROM Zadanie WHERE id_za=:id;");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['ok'=>true,'ret_val'=>$data];
        }
    }
?>