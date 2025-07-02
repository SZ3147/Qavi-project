<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {


    

    

   
    private $db;

    public function __construct() {

        $this->db = Database::getInstance();
    }
    

    public function createUser($email, $password, $role = 'user') {
        global $db;
        $stmt = $this->db->query("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
        $stmt->execute(['email' => $email, 'password' => $password, 'role' => $role]);
    }

    public function getUserByEmail($email) {
        global $db;
        $stmt = $this->db->query("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function getUserById($id) {
        global $db;
        $stmt = $this->db->query("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updateUserName($id, $name) {
        global $db;

        $stmt =  $this->db->query("UPDATE users SET name = :name WHERE id = :id");
        return $stmt->execute(['name' => $name, 'id' => $id]);
    }

    public function getCount() {
        global $db;
        
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
        return $stmt->fetch()['count'];
    }

    public function getTotalUsers() {
         global $db;

        $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }


}
?>
