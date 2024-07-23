<?php 

namespace App\Controllers;

use App\Models\Lodgement;
use App\Models\Reservation;
use App\Models\Database;
use App\Models\User;
use Exception;

if(!isset($_SESSION['user'])){
    header("Location: /"); 
    exit();
}

class AccountController {
    public function index() {
        $data['logements'] = Lodgement::getAllLodgements(Database::getInstance(), array('id_user'=>$_SESSION['user']['id']));
        $data['reservations'] =Reservation::getAllreservation(Database::getInstance(), array('id_user' => $_SESSION['user']['id']));;
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/account.php';
    }

    public function ajouter_logement() {
        $lodgement = new Lodgement();
        $data['lodgement']=$lodgement->getData();
        $data['button']='Ajouter le logement'; 
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/account/lodgement.php';
    }

    public function modifier_logement($id) {
        $lodgement = new Lodgement();
        $lodgement->get($id);
        if($_SESSION['user']['id'] != $lodgement->id_user){
            header("Location: /404"); 
            exit();
        }
        $data['lodgement']=$lodgement->getData();
        $data['button']='Modifier le logement'; 
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/account/lodgement.php';
    }

    public function reservation($id) {
        $lodgement = new Lodgement();
        $lodgement->get($id);
        $data['lodgement']=$lodgement->getData();
        $data['reservation'] = Reservation::getAllreservation(Database::getInstance(), array('id_lodgement' =>$id));
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/account/reservation.php';
    }
    

    public function change_password() {
        $obligatoire= array('old_password','password');
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

        //VERIFICATION DE L'ANCIEN MOT DE PASSE
        $u=new User();
        $u->get($_SESSION['user']['id']);
        if(password_verify($_POST['old_password'],$u->password)){
            if (strlen($_POST['password']) < 8 || !preg_match('/[A-Za-z]/', $_POST['password']) || !preg_match('/[0-9]/', $_POST['password'])) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Votre mot de passe doit contenir au moins 8 caractère avec des chiffres et lettres !']);
                exit();
            }
            $u->password = password_hash($_POST['password'],PASSWORD_BCRYPT);
            $u->save();
            echo json_encode(['status' => 'success', 'message' => 'Changement de mot de passe réussi !']);
        }else{
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Ancien mot de passe incorrect !']);
        }

    }
    public function change_picture() {
        $u=new User();
        $u->get($_SESSION['user']['id']);
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
                    echo json_encode(['status' => 'error', 'message' =>'L\'image n\'est pas dans le bon format !']);
                    exit();
                }               
            }else{
                echo json_encode(['status' => 'error', 'message' =>'Image trop lourde !']);
                exit();
            }
        }else{
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Veuillez ajouter une photo !']);
            exit();
        }
        $u->setData($_POST);
        try {
            $u->save();
            echo json_encode(['status' => 'success', 'message' => 'Modification de photo réussie !']);
          } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
          }
    }
}