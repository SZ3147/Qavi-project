<?php
require_once __DIR__ . '/../config/database.php';

class AuthModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    
    public function emailExists($email, $role) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email AND role = :role");
        $stmt->execute(['email' => $email, 'role' => $role]);
        return $stmt->rowCount() > 0;
    }

    
    public function register($name, $email, $password, $role) {
        try {
            
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
            return $stmt->execute([

                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => $role
            ]);

        } catch (PDOException $e) {


            if ($e->getCode() == 23000) {
                 
                return false;
            }
            throw $e; 
        }

    }

   
    public function login($email, $password, $role) {

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND role = :role LIMIT 1");
        $stmt->execute(['email' => $email, 'role' => $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            return $user;
        }
        return false;
    }


    public function getUserByEmailAndRole($email, $role) {

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND role = :role LIMIT 1");
        $stmt->execute(['email' => $email, 'role' => $role]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



}