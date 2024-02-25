<?php

namespace App\Models;
use PDO;
use PDOException;

class User extends Database {

    private $pdo;
    public $id ="";
    public $id_role ="2";
    public $firstname ="";
    public $lastname ="";
    public $mail ="";
    public $age ="";
    public $password ="";
    public $picture ="";
    public $creation ="";
    public $modification ="";
    public $last_connexion ="";
    

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
                case 'password' : $this->$k = $v; break;
                case 'age' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'picture' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'creation' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'modification' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'last_connexion' : $this->$k = ($v == "" ? NULL : $v); break;
                default : $this->$k = $v;
            }
        }else{
            echo 'La propriété $k n\'existe pas !';
        }
    }

    public function _get($k, $v){
        if (property_exists($this, $k)) {
            return $this->$k = $v;
        }else{
            return 'La propriété $k n\'existe pas !';
        }
    }

    public function get($id){
        if ($id != 0) {
            try{
                $sql ='SELECT * FROM users WHERE id=?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $id, PDO::PARAM_INT);
                $stmt->execute();
                $this->setData($stmt->fetch(PDO::FETCH_ASSOC));
            }catch(PDOException $e){
                echo "Erreur lors de la recupération de l'user ".$id." : " . $e->getMessage();
            }
        }else{
            return 'L\'id est incorrect !';
        }
    }

    public function getBy($k, $v){
            try{
                $sql ='SELECT id FROM users WHERE '.$k.'=?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $v, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                echo "Aucun utilisateur trouvé :" . $e->getMessage();
            }
    }

    public function save(){
        if($this->id == 0){
            $this->_insert();
        } else {
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
                echo "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
            }
        }
    }

    private function _insert(){
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
            echo "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
            echo $sql;
        }
    }

    public function verif_mail($mail){
        
        $sql ='SELECT id FROM users WHERE mail=:mail';
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':' .'mail', $mail);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
            echo $sql;
        }
        if (count($data) == 0)
            return false;
        else    
            return true;
    }

    public function setData($data = array()){
        foreach(array_keys(get_object_vars($this)) as $a){
            if ($a != "pdo"){
                if(isset($data[$a])){
                    $this->_set($a,$data[$a]);
                }else{
                    $this->_set($a,"");
                }
            } 
        }
        
    }

    public static function getAllUsers($pdo){
        $sql ='SELECT * FROM users';

        try {
            $query = $pdo->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
            return [];
        }
    }
}