<?php
require_once __DIR__ . '/../config/database.php';

class CartModel {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function addProductToCart($user_id, $product_id, $quantity) {

    
        $existing = $this->pdo->prepare("SELECT quantity FROM cart

        WHERE user_id = :user_id AND product_id = :product_id");

        $existing->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $currentQty = $existing->fetchColumn();
    
        if ($currentQty !== false) {

       
            $newQty = $currentQty + $quantity;
            $stmt = $this->pdo->prepare("UPDATE cart SET quantity = :quantity


            WHERE user_id = :user_id AND product_id = :product_id");
        } else {

        
            $newQty = $quantity;
            $stmt = $this->pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) 

            VALUES (:user_id, :product_id, :quantity)");
        }
    
        $stmt->execute([

            'user_id' => $user_id,
            'product_id' => $product_id,
            'quantity' => $newQty
        ]);
    }


    public function updateProductQuantity($user_id, $product_id, $quantity) {

        $sql = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([

            ':quantity' => $quantity,
            ':user_id' => $user_id,
            ':product_id' => $product_id
        ]);
    }


    public function getCartItems($user_id) {
        $stmt = $this->pdo->prepare("SELECT c.*, p.name, p.price, p.image 
                                     FROM cart c
                                     JOIN products p ON c.product_id = p.id
                                     WHERE c.user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function removeFromCart($userId, $productId) {

  
        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([
            'user_id' => $userId,
            'product_id' => $productId

            
        ]);
    
        return true;
    }
    public function clearCart($userId) {

        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
    }
    public function getProductQuantityInCart($user_id, $product_id) {

        $stmt = $this->pdo->prepare("SELECT quantity FROM cart 
        WHERE user_id = :user_id AND product_id = :product_id");

        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $result = $stmt->fetchColumn();
        return $result ? (int)$result : 0;
    }


}
