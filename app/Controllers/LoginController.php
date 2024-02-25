<?php 

namespace App\Controllers;
use App\Models\User;

class LoginController {
    public function index() {
        require_once __DIR__ . '/../Views/loginpage.php';
    }

    public function inscription(){
        header('Content-Type: application/json');
        //VERIFICATION MAIL A FAIRE
        $u=new User();
        if($u->verif_mail($_POST['mail'])){
            echo json_encode(['status' => 'error', 'key' => 'mail', 'message' => 'Le mail est déjà pris !']);
            exit();
        }
        $_POST['password']= password_hash($_POST['password'],PASSWORD_BCRYPT);
        $u->setData($_POST);
        if($u->save()){
            echo json_encode(['status' => 'success', 'message' => 'Inscription réussie !']);
        }else{
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'inscription.']);
        }
    }

    public function connexion(){
        header('Content-Type: application/json');
        $u=new User();
        $verif_id =$u->getBy('mail', $_POST['mail']);
        if($verif_id['id'] == ""){
            echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail ou mot de passe incorrect !']);
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