<?php 
namespace App\Controllers;
use App\Models\User;


class BackOfficeController {
    public function index() {   
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/connexion.php';
    }

    public function dashboard() {
        if($_SESSION['user']['id_role'] != 1){
            header("Location: /backoffice"); 
            exit();
        }
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/dashboard.php';
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
        $verif_id =$u->getByAdmin('mail', $_POST['mail']);
        if(empty($verif_id['id'])){
            http_response_code(500);
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
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail ou mot de passe incorrect !']);
        }
    }
}