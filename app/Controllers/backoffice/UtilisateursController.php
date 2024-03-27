<?php 
namespace App\Controllers\Backoffice;
use App\Models\User;
use App\Models\Role;
use App\Models\Database;
use Exception;

if($_SESSION['admin']['id_role'] != 1){
    header("Location: /backoffice"); 
    exit();
}


class UtilisateursController {
    public function listes() {
        $data['roles'] = Role::getAllRoles(Database::getInstance());    
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/utilisateurs.php';
    }

    public function ajouter() {
        $data['h2'] = "Ajouter un utilisateur";
        $data['button'] = "Ajouter";       
        $data['roles'] = Role::getAllRoles(Database::getInstance());  
        $user = new User();
        $data['user'] = $user->getData();  
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/utilisateur.php';
    }

    public function voir($id) {
        $data['h2'] = "Modifier un utilisateur";    
        $data['button'] = "Modifier";       
        $user = new User();
        $user->get($id);
        $data['roles'] = Role::getAllRoles(Database::getInstance());
        $data['user'] = $user->getData();
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/utilisateur.php';
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
        $u=new User();
        if($_POST['id'] !=''){
            $u->get($_POST['id']);
        }
        if($u->verif_mail($_POST['mail'])){
            $error['mail'] = 'Le mail est déjà pris !';
            echo json_encode(['status' => 'error', 'key' => $error, 'message' => 'Le mail est déjà pris !']);
            exit();
        }
        // $_POST['password']= password_hash(,PASSWORD_BCRYPT);
        $u->setData($_POST);
        try {
            $u->save();
            echo json_encode(['status' => 'success', 'message' => 'Enregistrement réussie !']);
          } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
          }
    }

    public function delete() {
        $u=new User();
        if($_POST['id'] !=''){
            $u->get($_POST['id']);
        }
        $u->delete();
        http_response_code(200);
    }

    public function recherche() {
        $data['users'] = User::getAllUsers(Database::getInstance(), $_POST);
        echo json_encode($data['users']);
    }
    
}