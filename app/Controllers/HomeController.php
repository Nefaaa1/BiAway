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
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}