<?php
require_once __DIR__ . '/../config/database.php';

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllProducts() {

        $stmt = $this->db->query("
        SELECT products.*, categories.name AS category_name, categories.status AS category_status 
        FROM products 
        JOIN categories ON products.category_id = categories.id
        ");
        return $stmt->fetchAll();
    }


    public function getProductById($id) {

        $stmt = $this->db->prepare("

        SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Get total number of products
    public function getTotalProductCount() {

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

// Get products for current page
    public function getPaginatedProducts($limit, $offset) {

        $stmt = $this->db->prepare("
        SELECT p.*, c.name AS category_name, c.status AS category_status
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.id DESC
        LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public function countProductsByCategory($categoryId, $maxPrice = null, $searchTerm = null) {

        $sql = "SELECT COUNT(*) FROM products WHERE category_id = :category_id AND status = 1";
        $params = ['category_id' => $categoryId];

        if ($maxPrice !== null) {

            $sql .= " AND price <= :max_price";
            $params['max_price'] = $maxPrice;
        }

        if ($searchTerm) {

            $sql .= " AND name LIKE :search_term";
            $params['search_term'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getProductsByCategory($categoryId, $maxPrice = null, $searchTerm = null, $limit = 12, $offset = 0) {


        $sql = "SELECT * FROM products WHERE category_id = :category_id AND status = 1";
        $params = ['category_id' => $categoryId];

        if ($maxPrice !== null) {

            $sql .= " AND price <= :max_price";
            $params['max_price'] = $maxPrice;
        }

        if ($searchTerm) {

            $sql .= " AND name LIKE :search_term";
            $params['search_term'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY name ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

    // Bind normal params
        foreach ($params as $key => $val) {

            $stmt->bindValue(':' . $key, $val);
        }
    // Bind limit and offset as integers
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    


    public function countProductsByCategoryId($categoryId) {

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $categoryId]);
        return (int)$stmt->fetchColumn();
    }

    public function getProductsByCategoryIdPaginated($categoryId, $limit, $offset) {

        $stmt = $this->db->prepare("SELECT * FROM products WHERE category_id = :category_id LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public function decreaseStock($productId, $quantity) {

        $sql = "UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$quantity, $productId, $quantity]);

        return $stmt->rowCount() > 0;  // returns true if update happened, false if not enough stock
    }



   
    public function addProduct($name, $description, $price, $category_id, $imagePath,$quantity) {

        $stmt = $this->db->prepare("INSERT INTO products (name, description, price, category_id, image,quantity) VALUES (?, ?, ?, ?, ?,?)");
        $stmt->execute([$name, $description, $price, $category_id, $imagePath,$quantity]);
    }


    public function updateProduct($data) {

        $required = ['id', 'name', 'description', 'price', 'category_id', 'quantity'];
        foreach ($required as $field) {

            if (!isset($data[$field])) {

                throw new InvalidArgumentException("Missing required field: $field");
            }
        }

        $params = [

            'id' => $data['id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'category_id' => $data['category_id'],
            'quantity' => $data['quantity']
    ];

    $sql = "UPDATE products SET
        name = :name, 
        description = :description, 
        price = :price, 
        category_id = :category_id,
        quantity = :quantity";

    if (isset($data['image'])) {
        $product = $this->getProductById($data['id']);
        if ($product && $product['image']) {
            $this->deleteImage($product['image']);
        }
        $sql .= ", image = :image";
        $params['image'] = $data['image'];
    }

    if (isset($data['stock'])) {
        $sql .= ", stock = :stock";
        $params['stock'] = $data['stock'];
    }

    $sql .= " WHERE id = :id";

    $stmt = $this->db->prepare($sql);
    return $stmt->execute($params);
}



    public function deleteProduct($id) {

        try {


        
            $product = $this->getProductById($id);
            if (!$product) {

                throw new Exception('Product not found');
            }

        
            if (!empty($product['image'])) {

                  
            $this->deleteImage($product['image']);
            }

        
            $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
            $success = $stmt->execute(['id' => $id]);

            if (!$success || $stmt->rowCount() === 0) {

                throw new Exception('Failed to delete product or product not found');
            }

            return true;
            } catch (Exception $e) {


        
                error_log("Error deleting product: " . $e->getMessage());
                return false;
            }
    }   

    public function getCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM products");
        return $stmt->fetch()['count'];
    }

    public function updateStatus($id, $status) {

        $stmt = $this->db->prepare("UPDATE products SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function toggleStatus($id, $status) {

        if ($id !== null && in_array((int)$status, [0, 1], true)) {

            $this->productModel->updateStatus((int)$id, (int)$status);
        }

        header("Location: /QaviEcommerce/admin/products");
        exit;
    }

    public function handleImageUpload($file) {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!file_exists($uploadDir)) {






            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif',"image/jpg"];
        if (!in_array($file['type'], $allowedTypes)) {

            throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
        }

        if (!move_uploaded_file($file['tmp_name'], $destination)) {

            throw new Exception('Failed to upload image.');
        }


        return $filename; 
    }



    private function deleteImage($imagePath) {

    
        $safeImageName = ltrim(basename($imagePath), '/');
    
    
    
        $fullPath = __DIR__ . '/../../public/' . $imagePath;
    
        if (file_exists($fullPath)) {


            unlink($fullPath);
        }
    }

}
    
