<?php 
namespace App\Controllers;
use App\Models\Lodgement;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Database;
use Exception;

class LodgementController {
    public function index($id) {
        $lodgement = new Lodgement();
        $lodgement->get($id);
        $user = new User();
        $user->get($lodgement->id_user);
        $lodgements= Lodgement::getAllLodgements(Database::getInstance(), array('id_user' => $lodgement->id_user));
        $data['user']=array(
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'count_lodgement'=> count($lodgements)
         );
        $data['lodgement'] = $lodgement->getData();

        //recupération de la longitude et latitude pour l'affichage de la map 
        $url = "https://api-adresse.data.gouv.fr/search/?q=".urlencode($data['lodgement']['city'])."&type=municipality&limit=1";
        $response = file_get_contents($url);
        $response = json_decode($response, true);
        $data['latitude'] = $response['features'][0]['geometry']['coordinates'][1];
        $data['longitude'] = $response['features'][0]['geometry']['coordinates'][0];    
        
        //vérification si reserver
        $data['is_reserved'] =false;
        if (isset($_SESSION['user'])){
            $verif = Reservation::IsReserved(Database::getInstance(), $_SESSION['user']['id'], $data['lodgement']['id'] );
            if($verif){ 
                $data['is_reserved'] =true;
            }
        }
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/lodgement.php';
    }

    public function save(){
        $obligatoire= array('title','id_user', 'city','peoples', 'price');
        $error = array();
        foreach ($obligatoire as $o){
            if($_POST[$o] =="")
                $error[$o]= 'Information obligatoire !';
        }
        if(count($error)>0){
            echo json_encode(['status' => 'error', 'key' => $error,  'message' => 'Informations obligatoires manquante !']);
            exit();
        }

        if (isset($_FILES['picture']) AND $_FILES['picture']['error'] == 0){
            if ($_FILES['picture']['size'] <= 1000000) {
                $infosfichier = pathinfo($_FILES['picture']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                $name_picture = 'lodgement_'.uniqid().'.'.$extension_upload;
                if (in_array($extension_upload, $extensions_autorisees)){
                    move_uploaded_file($_FILES['picture']['tmp_name'], 'public/assets/img/lodgements/'.$name_picture );
                }
                $_POST['picture']= $name_picture;
            }
        }

        $u=new Lodgement();
        if($_POST['id'] !=''){
            $u->get($_POST['id']);
        }
        $u->setData($_POST);
        try {
            $u->save();
            echo json_encode(['status' => 'success', 'message' => 'Enregistrement réussie !']);
            exit();
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit();
        }
    }

    public function delete(){
        $u=new Lodgement();
        $u->get($_POST['id']);
       
        if($_SESSION['user']['id_role'] != 1){
            if($_SESSION['user']['id'] != $u->id_user){
                echo json_encode(['status' => 'error', 'message' => 'Suppression impossible !']);
                exit();
            }
        }

        if($u->picture != null){
            $imagePath = 'public/assets/img/lodgements/'.$u->picture;
            if (file_exists($imagePath)) {
                if (unlink($imagePath)) {} 
                else {
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenu pendant la suppression']);
                    exit();
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenu pendant la suppression']);
                exit();
            }
        }

        try {
            $u->delete();
            echo json_encode(['status' => 'success', 'message' => 'Suppression réussie !']);
            exit();
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit();
        }
    }

    public function reservation(){
        $obligatoire= array('start','end', 'id_lodgement','id_user');
        $error = array();
        $_POST['id_user']=$_SESSION['user']['id'];
        foreach ($obligatoire as $o){
            if($_POST[$o] =="")
                $error[$o]= 'Information obligatoire !';
        }
        if(count($error)>0){
            echo json_encode(['status' => 'error', 'key' => $error,  'message' => 'Informations obligatoires manquante !']);
            exit();
        }

        $r=new Reservation();
        $r->setData($_POST);
        try {
            $r->save();
            echo json_encode(['status' => 'success', 'message' => 'Réservation envoyée !']);
            exit();
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit();
        }
    }
}