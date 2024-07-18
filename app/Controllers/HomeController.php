<?php 
namespace App\Controllers;
use App\Models\User;
use App\Models\Database;
use App\Models\Lodgement;
use Exception;

class HomeController {
    public function index() {
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/home.php';
    }
    public function recherche() {
        try{
            $data['lodgements'] = Lodgement::getAllLodgements(Database::getInstance(), $_POST);
            echo json_encode($data['lodgements']);
        }catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function send_contact(){
        $obligatoire= array('nom','prenom','telephone','mail', 'message');
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
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $email = htmlspecialchars($_POST['mail']);
        $message = htmlspecialchars($_POST['message']);

        $message_confirmation = '<body>
                                <p>Bonjour '.$prenom.',</p>
                                <p>Nous vous confirmons que notre équipe a bien reçu votre message et vous répondra dans les meilleurs délais.</p>
                                <p>L\'équipe de BiAway</p>
                            </body>';

        $message_demande = '<body>
                            <p>Nouvelle demande : '.$nom.'  '.$prenom.' - '.$email .'- '.$telephone.'</p>
                            <p>Message :</p>
                            <p style="font-style:italic;">'.$message.'</p>
                        </body>';

        $headers = "From: no-reply@antoinefagnere.fr\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        try{
            envoyerEmail($email, "Confirmation de demande de contact", $message_confirmation, $headers);
            envoyerEmail("contact@antoinefagnere.fr", "Demande de contact reçu", $message_demande, $headers);
            echo json_encode(['status' => 'success', 'message' => 'Message envoyé !']);
        }catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        
    }
}