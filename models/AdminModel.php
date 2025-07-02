<?php
require_once '../config/database.php';

class AdminModel {

    public function getAllAdmins() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM users WHERE role = 'admin'");
        return $stmt->fetchAll();
    }

    public function getAdminById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND role = 'admin'");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
?>
