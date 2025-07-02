<?php
require_once __DIR__ . '/../config/database.php';

class Lang {
    private static $translations = [];

    public static function load($langCode) {
        global $pdo;  

        $langCode = strtolower($langCode);

        
        $stmt = $pdo->prepare("SELECT name FROM language WHERE code = :code LIMIT 1");
        $stmt->execute(['code' => $langCode]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $filename = strtoupper($row['name']) . '.php';

            $langFile = __DIR__ . "/../language/{$filename}";
            if (file_exists($langFile)) {
                self::$translations = include $langFile;
                return self::$translations;
            } else {
             
                self::$translations = [];
                error_log("Language file not found: {$langFile}");
                return self::$translations;
            }
        } else {
            
            self::$translations = [];
            error_log("Language code not found in database: {$langCode}");
            return self::$translations;
        }
    }

    public static function get($key) {
        return self::$translations[$key] ?? $key;
    }
}
