<?php

namespace App\Models;
use PDO;
use PDOException;

class Document extends Database {

    private $pdo;
    public $id ="";
    public $creation ="";
    public $modification ="";
    public $actif ="1";
    public $id_liaison ="";
    public $type_liaison ="";
    public $name ="";
    public $path ="";
    public $extension ="";    

    public function __construct() {
        $this->pdo = parent::getInstance();
    }

    public function _set($k, $v){
        if (property_exists($this, $k)) {
            switch($k){
                case 'id' :  $this->$k = intval($v); break;
                case 'creation' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'modification' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'actif' : $this->$k = ($v == "" ? 0 : $v); break;
                case 'id_liaison' :  $this->$k = ($v == "" ? NULL : intval($v)); break;
                case 'type_liaison' :  $this->$k = strtolower($v); break;
                case 'name' : $this->$k = strtoupper($v); break;
                case 'path' : $this->$k = $v; break;
                case 'extension' : $this->$k = strtoupper($v); break;             
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
                $sql ='SELECT * FROM documents WHERE id=?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $id, PDO::PARAM_INT);
                $stmt->execute();
                $this->setData($stmt->fetch(PDO::FETCH_ASSOC));
            }catch(PDOException $e){
                echo "Erreur lors de la recupération du document ".$id." : " . $e->getMessage();
            }
        }else{
            return 'L\'id est incorrect !';
        }
    }

    public function getBy($k, $v){
            try{
                $sql ="SELECT id FROM documents WHERE $k=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $v, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                echo "Aucun document trouvé :" . $e->getMessage();
            }
    }

    public function save(){
        if($this->id == 0){
            $this->_insert();
        } else {
            $this->modification= date('Y-m-d H:i:s');
            $req= array();
            foreach(array_keys(get_object_vars($this)) as $k){
                if ($k != "pdo" && $k != "id")
                    $req[]= $k.'= :'.$k;
            }
            $sql ='UPDATE documents SET '. implode(", ", $req) .' WHERE id=:id';
            
            try{
                $stmt = $this->pdo->prepare($sql);
                foreach(array_keys(get_object_vars($this)) as $k){
                    if ($k != "pdo")
                     $stmt->bindValue(':'. $k, $this->$k);
                }
                $stmt->execute();
            }catch(PDOException $e){
                echo "Erreur lors de la mise à jour du document : " . $e->getMessage();
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
        $sql ='INSERT INTO documents ( '. implode(",", $req) .' ) VALUES ( '. implode(",", $val) .' )';
        try{
            $stmt = $this->pdo->prepare($sql);
            foreach(array_keys(get_object_vars($this)) as $k){
                if ($k != "id" && $k != "pdo")
                    $stmt->bindValue(':' . $k, $this->$k);
            }
            $stmt->execute();
        }catch(PDOException $e){
            echo "Erreur lors de l'ajout du document : " . $e->getMessage();
            echo $sql;
        }
    }

    public function delete(){
        $this->actif = 0;
        $this->save();
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

    public static function search($pdo, $req = array()){
        $where = array();
        $val = array();

        //id_lodgement
        if(isset($req['id_lodgement']) && $req['id_lodgement'] !=''){
            $where[]= 'AND documents.id_liaison=?';
            $val[]=$req['id_lodgement'];
        }

        $sql ='SELECT documents.*
                FROM documents
                WHERE 1
                '. implode(' ',  $where);

        try {
            $query = $pdo->prepare($sql);
            $query->execute($val);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des documents : " . $e->getMessage();
            return [];
        }
    }
}