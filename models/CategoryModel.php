<?php
require_once __DIR__ . '/../config/database.php';

class CategoryModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getCategoryById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getTotalCategoryCount() {

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getPaginatedCategories($limit, $offset) {

        $stmt = $this->db->prepare("

        SELECT * FROM categories 
        ORDER BY id DESC 
        LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCategory($name, $description, $status, $image = null) {

        $sql = "INSERT INTO categories (name, description, status, image) VALUES (:name, :description, :status, :image)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([

            ':name' => $name,
            ':description' => $description,
            ':status' => $status,
            ':image' => $image
        ]);
    }



    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE categories SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    public function updateCategory($id, $name, $description, $status, $image = null) {

        $sql = "UPDATE categories SET name = :name, description = :description, status = :status, image = :image WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([

            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':status' => $status,
            ':image' => $image
        ]);
    }


    public function deleteCategory($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM categories");
        return $stmt->fetch()['count'];
    }

    
    public function getAllCategoriesWithProductCount() {

        $stmt = $this->db->query("
        SELECT c.*, COUNT(p.id) AS product_count
        FROM categories c
        INNER JOIN products p ON c.id = p.category_id
        WHERE c.status = 1 AND p.status = 1
        GROUP BY c.id
        HAVING COUNT(p.id) > 0
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getProductsByCategory($category_id, $max_price = null) {

        $sql = "SELECT * FROM products 

        WHERE category_id = :category_id 
        AND status = 1";

        $params = [':category_id' => $category_id];


        if ($max_price !== null && is_numeric($max_price) && $max_price > 0) {

            $sql .= " AND price <= :max_price";

            $params[':max_price'] = (float)$max_price;
        }

        $sql .= " ORDER BY created_at DESC";

    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
