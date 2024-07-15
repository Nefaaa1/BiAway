<?php
namespace App\Models;
use PDO;
use PDOException;
use Exception;

abstract class Database {

private static $instance;

    private function __construct() {
    }

    public static function getInstance() {
        try {
            if (!isset(self::$instance)) {
                self::$instance = new PDO('mysql:host=localhost;port=3306;dbname=BiAway', 'root', 'root');
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return self::$instance;
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données : " . $e->getMessage());
        }
        
    }

}