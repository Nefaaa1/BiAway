<?php 
namespace App\Controllers;
use App\Models\User;
use App\Models\Database;

class HomeController {
    public function index() {
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/home.php';
    }
}