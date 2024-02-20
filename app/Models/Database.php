<?php
namespace App\Models;
use PDO;
use PDOException;


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
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
            exit;
        }
        return self::$instance;
    }

}