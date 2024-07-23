<?php

namespace App\Models;
use PDO;
use PDOException;
use Exception;

class User extends Database {

    private $pdo;
    public int $id = 0;
    public int $actif =1;
    public int $id_role =2;
    public string $firstname ="";
    public string $lastname ="";
    public string $mail ="";
    public string $phone ="";
    public string $password ="";
    public ?string $picture ="";
    public ?string $creation ="";
    public ?string $modification ="";
    public ?string $last_connexion ="";
    

    public function __construct() {
        $this->pdo = parent::getInstance();
    }

    public function _set($k, $v){
        if (property_exists($this, $k)) {
            switch($k){
                case 'id' :  $this->$k = intval($v); break;
                case 'id_role' :  $this->$k = ($v == "" ? 2 : intval($v)); break;
                case 'firstname' :  $this->$k = ucfirst($v); break;
                case 'lastname' : $this->$k = strtoupper($v); break;
                case 'mail' : $this->$k = strtolower($v); break;
                case 'phone' : $this->$k = strtolower($v); break;
                case 'password' : $this->$k = $v; break;
                case 'picture' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'creation' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'modification' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'last_connexion' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'actif' : $this->$k = ($v == "" ? 0 : $v); break;
                default : $this->$k = $v;
            }
        }else{
            echo 'La propriété n\'existe pas !';
        }
    }

    public function _get(string $k, $v){
        if (property_exists($this, $k)) {
            return $this->$k = $v;
        }else{
            return 'La propriété n\'existe pas !';
        }
    }

    public function get(int $id){
        if ($id != 0) {
            try{
                $sql ='SELECT * FROM users WHERE id=?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array($id));
                $data =$stmt->fetch(PDO::FETCH_ASSOC);
                if($data)
                    $this->setData($data);
            }catch(PDOException $e){
                throw new Exception("Erreur lors de la recupération de l'user ".$id." : " . $e->getMessage());
            }
        }else{
            throw new Exception("L'id est incorrect !");
        }
    }

    public function getBy(string $k, $v): array | bool{
            try{
                $sql ="SELECT id FROM users WHERE $k=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array($v));
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                throw new Exception("Aucun utilisateur trouvé :" . $e->getMessage());
            }
    }

    public function getByAdmin(string $k, $v): array| bool{
        try{
            $sql ="SELECT id FROM users WHERE id_role=1 AND $k=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array($v));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            throw new Exception("Aucun utilisateur trouvé :" . $e->getMessage());
        }
}

    public function save() :void{
        if($this->id == 0){
            $this->_insert();
        } else {
            $this->modification= date('Y-m-d H:i:s');
            $req= array();
            foreach(array_keys(get_object_vars($this)) as $k){
                if ($k != "pdo" && $k != "id")
                    $req[]= $k.'= :'.$k;
            }
            $sql ='UPDATE users SET '. implode(", ", $req) .' WHERE id=:id';
            
            try{
                $stmt = $this->pdo->prepare($sql);
                foreach(array_keys(get_object_vars($this)) as $k){
                    if ($k != "pdo")
                     $stmt->bindValue(':'. $k, $this->$k);
                }
                $stmt->execute();
            }catch(PDOException $e){
                throw new Exception("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
            }
        }
    }

    private function _insert():void{
        $req= array();
        $val= array();
        $this->creation= date('Y-m-d H:i:s');
        $this->modification= date('Y-m-d H:i:s');
        foreach(array_keys(get_object_vars($this)) as $k){
            if ($k != "id" && $k != "pdo"){
                $req[]= $k;
                $val[]=':'.$k;
            }       
        }
        $sql ='INSERT INTO users ( '. implode(",", $req) .' ) VALUES ( '. implode(",", $val) .' )';
        try{
            $stmt = $this->pdo->prepare($sql);
            foreach(array_keys(get_object_vars($this)) as $k){
                if ($k != "id" && $k != "pdo")
                    $stmt->bindValue(':' . $k, $this->$k);
            }
            $stmt->execute();
        }catch(PDOException $e){
            throw new Exception("Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage());
        }
    }

    public function desactivate():void{
        $this->actif = 0;
        $this->save();
    }

    public function activate():void{
        $this->actif = 1;
        $this->save();
    }


    public function delete():void{
        if ($this->id != 0 && $this->id != "") {
            try{
                $sql ='DELETE FROM users WHERE id=?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array($this->id));
            }catch(PDOException $e){
                throw new Exception("Erreur lors de la suppression de l'utilisateur ".$this->id." : " . $e->getMessage());
            }
        }else{
            throw new Exception("Erreur lors de la suppression de l'utilisateur, l'id n'est pas valide");
        }
    }

    public function verif_mail($mail) :bool{
        
        $sql ='SELECT id FROM users WHERE mail=:mail AND id !="'. $this->id.'"';
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':' .'mail', $mail);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            throw new Exception("Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage());
        }
        if (count($data) == 0)
            return false;
        else    
            return true;
    }

    public function setData($data = array()):void{
        foreach(array_keys(get_object_vars($this)) as $a){
            if ($a != "pdo"){
                if(isset($data[$a])){
                    $this->_set($a,$data[$a]);
                }else{
                    $this->_set($a,$this->$a);
                }
            } 
        }
    }
    public function getData():array{
        $data = array();
        foreach(array_keys(get_object_vars($this)) as $a){
            if ($a != "pdo"){
                $data[$a] = $this->$a;
            }
        }
        return $data;
    }

    public static function getAllUsers(PDO $pdo, array $req = array()):array{
        $where = array();
        $val = array();

        //id_role
        if(isset($req['id_role']) && $req['id_role'] !=''){
            $where[]= 'AND users.id_role=?';
            $val[]=$req['id_role'];
        }

         //recherche
         if(isset($req['recherche']) && $req['recherche'] !=''){
            $where[]= 'AND (users.firstname LIKE "%'.$req["recherche"].'%" OR users.lastname LIKE "%'.$req["recherche"].'%" OR users.mail LIKE "%'.$req["recherche"].'%")';
        }

        $sql ='SELECT users.*, roles.name AS role_name
                FROM users
                INNER JOIN roles ON users.id_role = roles.id
                WHERE 1
                '. implode(' ',  $where).'
                ORDER BY users.id_role,users.lastname,users.firstname';

        try {
            $query = $pdo->prepare($sql);
            $query->execute($val);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
            return [];
        }
    }
    public static function getCount(PDO $pdo) :int{
        $sql ='SELECT COUNT(*)
                FROM users';

        try {
            $query = $pdo->prepare($sql);
            $query->execute();
            return $query->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
        }
    }
}