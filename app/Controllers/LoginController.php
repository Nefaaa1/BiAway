<?php 
namespace App\Controllers;
use App\Models\User;
use App\Models\Database;


class LoginController {
    public function index() {
        require_once __DIR__ . '/../Views/loginpage.php';
    }

    public function inscription(){
        //VERIFICATION MAIL A FAIRE
        $u=new User();
        $_POST['password']= password_hash($_POST['password'],PASSWORD_BCRYPT);
        $u->setData($_POST);
        if($u->save()){
            echo json_encode(['status' => 'success', 'message' => 'Inscription réussie !']);
        }else{
            echo json_encode(['status' => 'success', 'message' => 'Erreur lors de l\'inscription.']);
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
            unset($u->password);
            $_SESSION['user']=$u;
            echo json_encode(['status' => 'success', 'message' => 'Connexion réussie !', 'userId' => $u->id]);
        }else{
            echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail ou mot de passe incorrect !']);
        }
    }
}