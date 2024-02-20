<?php 
namespace App\Controllers;
use App\Models\User;
use App\Models\Database;

class LoginController {
    public function index() {
        require_once __DIR__ . '/../Views/loginpage.html';
    }
}