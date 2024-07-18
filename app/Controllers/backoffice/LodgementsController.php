<?php 
namespace App\Controllers\Backoffice;
use App\Models\Lodgement;
use App\Models\Role;
use App\Models\User;
use App\Models\Database;
use Exception;

if($_SESSION['user']['id_role'] != 1){
    header("Location: /backoffice"); 
    exit();
}


class LodgementsController {
    public function listes() {
        $data['roles'] = Role::getAllRoles(Database::getInstance());    
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/lodgements.php';
    }

    public function recherche() {
        try{
            $data['lodgments'] = Lodgement::getAllLodgements(Database::getInstance(), $_POST, 0);
            echo json_encode($data['lodgments']);
        }catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
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

    public function delete(){
        $u=new Lodgement();
        $u->get($_POST['id']);
       
        if($_SESSION['user']['id_role'] != 1){
            if($_SESSION['user']['id'] != $u->id_user){
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Suppression impossible !']);
                exit();
            }
        }

        if($u->picture != null){
            $imagePath = 'public/assets/img/lodgements/'.$u->picture;
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
            echo json_encode(['status' => 'success', 'message' => 'Suppression réussie !']);
            exit();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit();
        }
    } 
    public function switch() {
        $u=new Lodgement();
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
}