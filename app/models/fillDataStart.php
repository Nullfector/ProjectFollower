<?php

class fillDataStart {
    private PDO $pdo;

    public function __construct(PDO $our_pdo){
        $this->pdo = $our_pdo;
    }

    public function fun_s_proj(){
        $stmt = $this->pdo->query("SELECT id_p, nazwa_projektu FROM Projekt WHERE archiwalne=false AND zakończony=false ORDER BY nazwa_projektu;");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($data==[]){
            return ['ok'=>false,'error'=>'Brak typów'];
        }
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_t(){
        $stmt = $this->pdo->query("SELECT id_t, nazwa_typu FROM Typ_projektu ORDER BY nazwa_typu;");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($data==[]){
            return ['ok'=>true,'ret_val'=>[]];
        }
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_u(int $id, int $param){
        if($param==0){
            $stmt = $this->pdo->prepare("SELECT id_u, login FROM Użytkownik WHERE id_u !=:id ORDER BY login;");
            $stmt->execute([':id' => $id]);
        } else {
            $stmt = $this->pdo->query("SELECT id_u, login FROM Użytkownik ORDER BY login;");
        }
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($data==[]){
            return ['ok'=>true,'ret_val'=>[]];
        }
        return ['ok'=>true,'ret_val'=>$data];
    }

    public function fun_s_ze(){
        $stmt = $this->pdo->query("SELECT id_ze, nazwa FROM Zespół WHERE archiwalne=false ORDER BY nazwa;");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($data==[]){
            return ['ok'=>true,'ret_val'=>[]];
        }
        return ['ok'=>true,'ret_val'=>$data];
    }

}

?>