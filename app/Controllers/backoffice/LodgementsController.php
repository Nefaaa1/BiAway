<?php 
namespace App\Controllers\Backoffice;
use App\Models\Lodgement;
use App\Models\Role;
use App\Models\Database;
use Exception;

if($_SESSION['admin']['id_role'] != 1){
    header("Location: /backoffice"); 
    exit();
}


class LodgementsController {
    public function listes() {
        $data['roles'] = Role::getAllRoles(Database::getInstance());    
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/lodgements.php';
    }

    public function ajouter() {
        $data['h2'] = "Ajouter un utilisateur";
        $data['button'] = "Ajouter";       
        $data['roles'] = Role::getAllRoles(Database::getInstance());  
        $user = new Lodgement();
        $data['user'] = $user->getData();  
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/lodgement.php';
    }

    public function voir($id) {
        $data['h2'] = "Modifier un utilisateur";    
        $data['button'] = "Modifier";       
        $user = new Lodgement();
        $user->get($id);
        $data['roles'] = Role::getAllRoles(Database::getInstance());
        $data['user'] = $user->getData();
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/lodgement.php';
    }

    public function save(){
        $obligatoire= array('lastname','firstname', 'mail');
        $error = array();
        foreach ($obligatoire as $o){
            if($_POST[$o] =="")
                $error[$o]= 'Information obligatoire !';
        }
        if(count($error)>0){
            echo json_encode(['status' => 'error', 'key' => $error,  'message' => 'Informations obligatoires manquante !']);
            exit();
        }

        //VERIFICATION MAIL A FAIRE
        $u=new Lodgement();
        if($_POST['id'] !=''){
            $u->get($_POST['id']);
        }
       
        // $_POST['password']= password_hash($_POST['password'],PASSWORD_BCRYPT);
        $u->setData($_POST);
        try {
            $u->save();
            echo json_encode(['status' => 'success', 'message' => 'Enregistrement réussie !']);
          } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
          }
    }

    public function recherche() {
        $data['lodgements'] = Lodgement::getAllLodgements(Database::getInstance(), $_POST);
        echo json_encode($data['lodgements']);
    }
    
}