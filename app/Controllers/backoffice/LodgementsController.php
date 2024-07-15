<?php 
namespace App\Controllers\Backoffice;
use App\Models\Lodgement;
use App\Models\Role;
use App\Models\User;
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
        $data['h2'] = "Ajouter un logement";
        $data['button'] = "Ajouter";       
        $lodgement = new Lodgement();
        $data['users'] = User::getAllUsers(Database::getInstance(), $_POST);
        $data['lodgement'] = $lodgement->getData();  
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/lodgement.php';
    }

    public function voir($id) {
        $data['h2'] = "Modifier un logement";    
        $data['button'] = "Modifier";       
        $lodgement = new Lodgement();
        $lodgement->get($id);
        $data['users'] = User::getAllUsers(Database::getInstance(), $_POST);
        $data['lodgement'] = $lodgement->getData();
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/lodgement.php';
    }

   

   
    
}