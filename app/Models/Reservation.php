<?php

namespace App\Models;

use Exception;
use PDO;
use PDOException;

class Reservation extends Database {

    private $pdo;
    public $id ="";
    public $id_user ="";
    public $id_lodgement ="";
    public $creation ="";
    public $start ="";
    public $end ="";
    

    public function __construct() {
        $this->pdo = parent::getInstance();
    }

    public function _set($k, $v){
        if (property_exists($this, $k)) {
            switch($k){
                case 'id' :  $this->$k = intval($v); break;
                case 'id_user' :  $this->$k =intval($v); break;
                case 'id_lodgement' :  $this->$k =intval($v); break;
                case 'creation' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'start' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'end' : $this->$k = ($v == "" ? NULL : $v); break;                         
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
                $sql ='SELECT * FROM reservation WHERE id=?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $id, PDO::PARAM_INT);
                $stmt->execute();
                $this->setData($stmt->fetch(PDO::FETCH_ASSOC));
            }catch(PDOException $e){
                throw new Exception("Erreur lors de la recupération de la reservation ".$id." : " . $e->getMessage());
            }
        }else{
            return 'L\'id est incorrect !';
        }
    }

    public function getBy($k, $v){
            try{
                $sql ="SELECT id FROM reservation WHERE $k=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $v, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                throw new Exception("Aucune reservation trouvé :" . $e->getMessage());
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
            $sql ='UPDATE reservation SET '. implode(", ", $req) .' WHERE id=:id';
            
            try{
                $stmt = $this->pdo->prepare($sql);
                foreach(array_keys(get_object_vars($this)) as $k){
                    if ($k != "pdo")
                     $stmt->bindValue(':'. $k, $this->$k);
                }
                $stmt->execute();
            }catch(PDOException $e){
                throw new Exception("Erreur lors de la mise à jour de la reservation : " . $e->getMessage());
            }
        }
    }

    private function _insert(){
        $req= array();
        $val= array();
        $this->creation= date('Y-m-d H:i:s');
        foreach(array_keys(get_object_vars($this)) as $k){
            if ($k != "id" && $k != "pdo"){
                $req[]= $k;
                $val[]=':'.$k;
            }       
        }
        $sql ='INSERT INTO reservation ( '. implode(",", $req) .' ) VALUES ( '. implode(",", $val) .' )';
        try{
            $stmt = $this->pdo->prepare($sql);
            foreach(array_keys(get_object_vars($this)) as $k){
                if ($k != "id" && $k != "pdo")
                    $stmt->bindValue(':' . $k, $this->$k);
            }
            $stmt->execute();
        }catch(PDOException $e){
            throw new Exception("Erreur lors de l'ajout de la reservation : " . $e->getMessage());
        }
    }

    public function setData($data = array()){
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
    public function getData(){
        $data = array();
        foreach(array_keys(get_object_vars($this)) as $a){
            if ($a != "pdo"){
                $data[$a] = $this->$a;
            }
        }
        return $data;
    }

    public static function getAllreservation($pdo, $req = array()){
        $where = array();
        $val = array();

        //id_user
        if(isset($req['id_user']) && $req['id_user'] !=''){
            $where[]= 'AND users.id=?';
            $val[]=$req['id_user'];
        } 

        //id_lodgement
        if(isset($req['id_lodgement']) && $req['id_lodgement'] !=''){
            $where[]= 'AND id_lodgement=?';
            $val[]=$req['id_lodgement'];
        } 


        $sql ='SELECT reservation.*, users.lastname,users.firstname, users.phone, users.mail
                FROM reservation
                INNER JOIN users ON reservation.id_user = users.id
                WHERE 1 
                '. implode(' ',  $where);

        try {
            $query = $pdo->prepare($sql);
            $query->execute($val);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des reservations : " . $e->getMessage());
            return [];
        }
    }

    public static function IsReserved($pdo, $user_id, $lodgement_id){
        $sql ='SELECT *
                FROM reservation
                WHERE id_user=? AND id_lodgement=?';

        try {
            $query = $pdo->prepare($sql);
            $query->execute(array($user_id, $lodgement_id));
            $reservations = $query->fetchAll(PDO::FETCH_ASSOC);
            return count($reservations) > 0 ? true : false;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des reservations : " . $e->getMessage());
            return false;
        }
    }
}