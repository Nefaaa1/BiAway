<?php

namespace App\Models;
use PDO;
use PDOException;
use Exception;

class Lodgement extends Database {

    private $pdo;
    public int $id =0;
    public int $id_user =0;
    public string $title ="";
    public ?string $creation ="";
    public ?string $modification ="";
    public int $actif =1;
    public int $peoples =0;
    public string$city ="";
    public float $price =0.0;
    public ?string $picture ="";
    public ?string $description ="";
    

    public function __construct() {
        $this->pdo = parent::getInstance();
    }

    public function _set($k, $v){
        if (property_exists($this, $k)) {
            switch($k){
                case 'id' :  $this->$k = intval($v); break;
                case 'id_user' :  $this->$k =intval($v); break;
                case 'title' :  $this->$k = ucfirst($v); break;
                case 'actif' : $this->$k = ($v == "" ? 0 : $v); break;
                case 'peoples' : $this->$k = ($v == "" ? 0 : $v); break;
                case 'city' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'price' : $this->$k = ($v == "" ? 0.00 : $v); break;
                case 'creation' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'modification' : $this->$k = ($v == "" ? NULL : $v); break;
                case 'picture' : $this->$k = ($v == "" ? NULL : $v); break;    
                case 'description' : $this->$k = ($v == "" ? NULL : $v); break;                            
                default : $this->$k = $v;
            }
        }else{
            echo 'La propriété $k n\'existe pas !';
        }
    }

    public function _get(string $k, $v){
        if (property_exists($this, $k)) {
            return $this->$k = $v;
        }else{
            return 'La propriété $k n\'existe pas !';
        }
    }

    public function get(int $id): void{
        if ($id != 0) {
            try{
                $sql ='SELECT * FROM lodgements WHERE id=?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array($id));
                $data =$stmt->fetch(PDO::FETCH_ASSOC);
                if($data)
                    $this->setData($data);
            }catch(PDOException $e){
                throw new Exception("Erreur lors de la recupération du logement ".$id." : " . $e->getMessage());
            }
        }else{
            throw new Exception("L'id est incorrect !");
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
            $sql ='UPDATE lodgements SET '. implode(", ", $req) .' WHERE id=:id';
            
            try{
                $stmt = $this->pdo->prepare($sql);
                foreach(array_keys(get_object_vars($this)) as $k){
                    if ($k != "pdo")
                     $stmt->bindValue(':'. $k, $this->$k);
                }
                $stmt->execute();
            }catch(PDOException $e){
                throw new Exception("Erreur lors de la mise à jour du logement : " . $e->getMessage());
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
        $sql ='INSERT INTO lodgements ( '. implode(",", $req) .' ) VALUES ( '. implode(",", $val) .' )';
        try{
            $stmt = $this->pdo->prepare($sql);
            foreach(array_keys(get_object_vars($this)) as $k){
                if ($k != "id" && $k != "pdo")
                    $stmt->bindValue(':' . $k, $this->$k);
            }
            $stmt->execute();
        }catch(PDOException $e){
            throw new Exception("Erreur lors de l'ajout du logement : " . $e->getMessage());
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
        if ($this->id != 0) {
            try{
                $sql ='DELETE FROM lodgements WHERE id=?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array($this->id));
            }catch(PDOException $e){
                throw new Exception("Erreur lors de la suppression du logement ".$this->id." : " . $e->getMessage());
            }
        }else{
            throw new Exception("Erreur lors de la suppression du logement, l'id n'est pas valide");
        }
    }

    public function setData(array $data = array()):void{
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

    public static function getAllLodgements(PDO $pdo, array $req = array(), int $actif = 1) :array{
        $where = array();
        $val = array();

        //actif
        if(isset($actif) && $actif == 1){
            $where[]= 'AND lodgements.actif=?';
            $val[]=$actif;
        }

        //id_user
        if(isset($req['id_user']) && $req['id_user'] !=''){
            $where[]= 'AND users.id=?';
            $val[]=$req['id_user'];
        } 
        //peoples
        if(isset($req['peoples']) && $req['peoples'] !=''){
        $where[]= 'AND lodgements.peoples <= ?';
        $val[]=$req['peoples'];
        } 

         //recherche
         if(isset($req['recherche']) && $req['recherche'] !=''){
            $where[]= 'AND (lodgements.title LIKE "%'.$req["recherche"].'%" OR lodgements.city LIKE "%'.$req["recherche"].'%" )';
        }

        $sql ='SELECT lodgements.*, users.lastname,users.firstname
                FROM lodgements
                INNER JOIN users ON lodgements.id_user = users.id
                WHERE 1 
                '. implode(' ',  $where);

        try {
            $query = $pdo->prepare($sql);
            $query->execute($val);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des logements : " . $e->getMessage());
            return [];
        }
    }
}