<?php 

namespace App\Controllers;
use App\Models\User;
use Exception;

class LoginController {
    public function index() {
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/loginpage.php';
    }

    public function inscription(){
        // header('Content-Type: application/json');
        $obligatoire= array('lastname','firstname', 'mail', 'password');
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
        if($u->verif_mail($_POST['mail'])){
            $error['mail'] = 'Le mail est déjà pris !';
            echo json_encode(['status' => 'error', 'key' => $error, 'message' => 'Le mail est déjà pris !']);
            exit();
        }
        $_POST['password']= password_hash($_POST['password'],PASSWORD_BCRYPT);
        $u->setData($_POST);
        try {
            $u->save();
            $u->last_connexion=date('Y-m-d H:i:s');
            $u->save();
            unset($u->password);
            $_SESSION['user']=[
                'id' => $u->id,
                'firstname' => $u->firstname,
                'lastname' => $u->lastname,
                'mail' => $u->mail,
                'id_role' => $u->id_role
            ];
            echo json_encode(['status' => 'success', 'message' => 'Inscription réussie !']);
          } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
          }
    }

    public function connexion(){
        // header('Content-Type: application/json');
        $obligatoire= array('mail','password');
        $error = array();
        foreach ($obligatoire as $o){
            if($_POST[$o] =="")
                $error[$o]= 'Information obligatoire !';
        }
        if(count($error)>0){
            echo json_encode(['status' => 'error', 'key' => $error,  'message' => 'Informations obligatoires manquante !']);
            exit();
        }
        $u=new User();
        $verif_id =$u->getBy('mail', $_POST['mail']);
        if(empty($verif_id['id'])){
            echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail ou mot de passe incorrect !']);
            exit();
        }
        $u->get($verif_id['id']);
        if(password_verify($_POST['password'],$u->password)){
            $u->last_connexion=date('Y-m-d H:i:s');
            $u->save();
            unset($u->password);
            $_SESSION['user']=[
                'id' => $u->id,
                'firstname' => $u->firstname,
                'lastname' => $u->lastname,
                'mail' => $u->mail,
                'id_role' => $u->id_role
            ];
            echo json_encode(['status' => 'success', 'message' => 'Connexion réussie !']);
        }else{
            echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail ou mot de passe incorrect !']);
        }
    }

    public function deconnexion(){
        unset($_SESSION['user']);
        session_destroy();
        header("Location: /");
        exit();
    }
}