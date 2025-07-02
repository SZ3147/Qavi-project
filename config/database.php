<?php
class Database {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            $host = 'localhost';
            $db = 'clothing_store'; // ✅ Your new database
            $user = 'root';
            $pass = '';

            try {
                self::$instance = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
