<?php 

namespace App\Controllers;
use App\Models\User;
use Exception;

class LoginController {
    public function index() {
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/loginpage.php';
    }

    public function inscription(){
        $obligatoire= array('lastname','firstname', 'phone', 'mail', 'password');
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
        
        //VERIFICATION MAIL 
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST['mail'])) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Adresse mail non valide !']);
            exit();
        }
        $u=new User();
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
        //VERIFICATION PASSWORD
        if (strlen($_POST['password']) < 12 || !preg_match('/[A-Za-z]/', $_POST['password']) || !preg_match('/[0-9]/', $_POST['password'])) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Votre mot de passe doit contenir au moins 12 caractère avec des chiffres et lettres !']);
            exit();
        }
        $_POST['password']= password_hash($_POST['password'],PASSWORD_BCRYPT);
        
        $u->setData($_POST);
        try {
            // $u->save();
            $u->last_connexion=date('Y-m-d H:i:s');
            $u->save();
            unset($u->password);
            $result = $u->getBy('mail',$u->mail);
            $_SESSION['user']=[
                'id' =>  $result['id'],
                'firstname' => $u->firstname,
                'lastname' => $u->lastname,
                'mail' => $u->mail,
                'id_role' => $u->id_role
            ];
            echo json_encode(['status' => 'success', 'message' => 'Inscription réussie !']);
          } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
          }
    }

    public function connexion(){
        $obligatoire= array('mail','password');
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
        $u=new User();
        $verif_id =$u->getBy('mail', $_POST['mail']);
        if(empty($verif_id['id'])){
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail ou mot de passe incorrect !']);
            exit();
        }
        $u->get($verif_id['id']);
        if($u->actif == 0){
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail ou mot de passe incorrect !']);
            exit();
        }
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
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail ou mot de passe incorrect !']);
        }
    }

    public function deconnexion(){
        unset($_SESSION['user']);
        session_regenerate_id(true);
        session_destroy();
        header("Location: /");
        exit();
    }
}