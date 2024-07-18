<?php 
namespace App\Controllers\Backoffice;
use App\Models\Lodgement;
use App\Models\Role;
use App\Models\User;
use App\Models\Database;
use App\Models\Reservation;
use Exception;

if($_SESSION['user']['id_role'] != 1){
    header("Location: /backoffice"); 
    exit();
}


class ReservationsController {
    public function listes() {
        $data['roles'] = Role::getAllRoles(Database::getInstance());    
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/reservations.php';
    }

    public function recherche() {
        try{
            $data['reservations'] = Reservation::getAllreservation(Database::getInstance(), $_POST);
            echo json_encode($data['reservations']);
        }catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function ajouter() {
        $data['h2'] = "Ajouter une réservation";
        $data['button'] = "Ajouter";       
        $reservation = new Reservation();
        $data['reservation'] = $reservation->getData();  
        $data['users'] = User::getAllUsers(Database::getInstance());
        $data['lodgements'] = Lodgement::getAllLodgements(Database::getInstance());
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/reservation.php';
    }

    public function voir($id) {
        $data['h2'] = "Modifier une réservation";    
        $data['button'] = "Modifier";       
        $reservation = new Reservation();
        $reservation->get($id);
        $data['reservation'] = $reservation->getData();
        $data['users'] = User::getAllUsers(Database::getInstance());
        $data['lodgements'] = Lodgement::getAllLodgements(Database::getInstance());
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/backoffice/reservation.php';
    }
    public function save(){
        $obligatoire= array('id_user','id_lodgement', 'start', 'end');
        $error = array();
        foreach ($obligatoire as $o){
            if(!isset($_POST[$o]) || $_POST[$o] =="")
                $error[$o]= 'Information obligatoire !';
        }
        if(count($error)>0){
            http_response_code(500);
            echo json_encode(['status' => 'error', 'key' => $error,  'message' => 'Informations obligatoires manquante !']);
            exit();
        }     
        $u=new Reservation();
        if($_POST['id'] !=0){
            $u->get($_POST['id']);
        }
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
        $u=new Reservation();
        if($_POST['id'] !=0){
            $u->get($_POST['id']);
        }
        try {
            $u->delete();
            echo json_encode(['status' => 'success', 'message' => 'Suppresion réussie !']);
          } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}