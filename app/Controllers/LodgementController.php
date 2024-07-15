<?php 
namespace App\Controllers;
use App\Models\Lodgement;
use App\Models\User;
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
}