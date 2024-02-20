<?php

namespace App\Models;
use PDO;

class User extends Database {

    private $pdo;
    public $id ="";
    public $firstname ="";
    public $lastname ="";
    public $password ="";

    public function __construct() {
        $this->pdo = parent::getInstance();
    }

    public function _set($k, $v){
        if (property_exists($this, $k)) {
            switch($k){
                case 'id' :  $this->$k = intval($v); break;
                case 'firstname' :  $this->$k = ucfirst($v); break;
                case 'lastname' : $this->$k = strtoupper($v); break;
                case 'password' : $this->$k = $v; break;
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
        if ($id != "") {
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

    public function save(){
        if($this->id == ""){
            $this->insert();
        } else {
            $req= array();
            foreach(array_keys(get_object_vars($this)) as $k){
                if ($k != "id")
                    $req[]= $k.'= :'.$k;
            }
            $sql ='UPDATE users SET '. implode(" ", $req) .' WHERE id=:id';
            try{
                $stmt = $this->pdo->prepare($sql);
                foreach(array_keys(get_object_vars($this)) as $k){
                    $stmt->bindValue(':' . $k, $this->$k);
                }
            }catch(PDOException $e){
                echo "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
            }
        }
    }

    private function _insert(){
        
    }

    private function setData($data = array()){
        foreach($data as $k => $v){
            $this->$k = $v;
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