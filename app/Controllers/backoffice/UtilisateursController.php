<?php 
namespace App\Controllers\Backoffice;
use App\Models\User;
use App\Models\Role;
use App\Models\Database;
use Exception;

if($_SESSION['user']['id_role'] != 1){
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
        if($_POST['id'] == 0){
            $obligatoire= array('lastname','firstname','phone', 'mail', 'password');
        }else{
            $obligatoire= array('lastname','firstname', 'mail');
        }
        $error = array();
        foreach ($obligatoire as $o){
            if($_POST[$o] =="")
                $error[$o]= 'Information obligatoire !';
        }
        if(count($error)>0){
            http_response_code(500);
            echo json_encode(['status' => 'error', 'key' => $error,  'message' => 'Informations obligatoires manquante !']);
            exit();
        }

        //VERIFICATION MAIL A FAIRE
        $u=new User();
        if($_POST['id'] !=0){
            $u->get($_POST['id']);
        }
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST['mail'])) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Adresse mail non valide !']);
            exit();
        }
        if($u->verif_mail($_POST['mail'])){
            $error['mail'] = 'Le mail est déjà pris !';
            http_response_code(500);
            echo json_encode(['status' => 'error', 'key' => $error, 'message' => 'Le mail est déjà pris !']);
            exit();
        }
        //VERIFICATION TELEPGONE 
         if (!preg_match('/^0[1-9]\d{8}$/', $_POST['phone'])) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Le numéro de téléphone n\'est pas valide !']);
            exit();
        }

        //VERIFICATION PICTURE
        if (isset($_FILES['picture']) AND $_FILES['picture']['error'] == 0){
            if ($_FILES['picture']['size'] <= 1000000) {
                $infosfichier = pathinfo($_FILES['picture']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                $name_picture = 'user_'.uniqid().'.'.$extension_upload;
                if (in_array($extension_upload, $extensions_autorisees)){
                    move_uploaded_file($_FILES['picture']['tmp_name'], 'public/assets/img/users/'.$name_picture );
                    $_POST['picture']= $name_picture;
                }else{
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' =>'L\'image n\'est pas dans le bon format !']);
                    exit();
                }               
            }else{
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' =>'Image trop lourde !']);
                exit();
            }
            if($u->picture != null){
                $imagePath = 'public/assets/img/users/'.$u->picture;
                if (file_exists($imagePath)) {
                    if (unlink($imagePath)) {} 
                    else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenu pendant la suppression']);
                        exit();
                    }
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenu pendant la suppression']);
                    exit();
                }
            }
        }

        //PASSWORD
        if(isset($_POST['password']) && $_POST['password'] != ""){
            if (strlen($_POST['password']) < 12 || !preg_match('/[A-Za-z]/', $_POST['password']) || !preg_match('/[0-9]/', $_POST['password'])) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Votre mot de passe doit contenir au moins 12 caractère avec des chiffres et lettres !']);
                exit();
            }
            $_POST['password']= password_hash($_POST['password'],PASSWORD_BCRYPT);
        }else{
            unset($_POST['password']);
        }
        // $_POST['password']= password_hash(,PASSWORD_BCRYPT);
        $u->setData($_POST);
        try {
            $u->save();
            echo json_encode(['status' => 'success', 'message' => 'Enregistrement réussie !']);
          } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
          }
    }

    public function delete() {
        $u=new User();
        if($_POST['id'] !=0){
            $u->get($_POST['id']);
        }
        if($u->picture != null){
            $imagePath = 'public/assets/img/users/'.$u->picture;
            if (file_exists($imagePath)) {
                if (unlink($imagePath)) {} 
                else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenu pendant la suppression']);
                    exit();
                }
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenu pendant la suppression']);
                exit();
            }
        }
        try {
            $u->delete();
            echo json_encode(['status' => 'success', 'message' => 'Suppresion réussie !']);
          } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function switch() {
        $u=new User();
        if($_POST['id'] !=0){
            $u->get($_POST['id']);
        }
        try {
            if($_POST['switch'] == 1){
                $u->activate();
            }else{
                $u->desactivate();
            }
            echo json_encode(['status' => 'success', 'message' => 'Changement de statut réussie !']);
          } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function recherche() {
        $data['users'] = User::getAllUsers(Database::getInstance(), $_POST);
        echo json_encode($data['users']);
    }
    
}