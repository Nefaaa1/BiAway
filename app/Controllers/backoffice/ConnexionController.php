<?php 
namespace App\Controllers;
use App\Models\User;
use App\Models\Database;

class LoginController {
    public function index() {
        require_once __DIR__ . '/../Views/backoffice/connexion.php';
    }

    public function inscription(){
        //VERIFICATION MAIL A FAIRE
        $u=new User();
        $_POST['password']= password_hash($_POST['password'],PASSWORD_BCRYPT);
        $u->setData($_POST);
        $u->save();
    }

    public function connexion(){
        $u=new User();
        $_POST['password']= password_hash($_POST['password'],PASSWORD_BCRYPT);
        $u->setData($_POST);
        $u->save();
    }
}