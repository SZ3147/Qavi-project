<?php
require_once __DIR__ . '/../config/database.php';

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function placeOrder($user_id, $total_amount, $address = '', $city = '', $state = '', $zip = '', $payment_method = 'Cash on Delivery', $phone = '') {
          
        $stmt = $this->db->prepare("INSERT INTO orders 
        (user_id, total_amount, status, address, city, state, zip, payment_method, phone) 
        VALUES (:user_id, :total_amount, 'Pending', :address, :city, :state, :zip, :payment_method, :phone)");
    
        $stmt->execute([

            'user_id' => $user_id,
            'total_amount' => $total_amount,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'payment_method' => $payment_method,
            'phone' => $phone
        ]);
    
        return $this->db->lastInsertId();
    }

    public function addOrderItem($order_id, $product_id, $quantity, $price) {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                   VALUES (:order_id, :product_id, :quantity, :price)");
        $stmt->execute([
            'order_id' => $order_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }
    public function getOrdersPaginated($offset, $limit){

        $stmt = $this->db->prepare("SELECT * FROM orders ORDER BY created_at DESC LIMIT :offset, :limit");
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {

            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $orders;
    }
    public function updateOrder($orderId, $status, $address, $city, $state, $zip, $userId = null, $name = '') {

   
        $currentOrder = $this->getOrderById($orderId);

    
        $changes = [];
        if ($currentOrder['status'] !== $status) $changes['status'] = $status;
        if ($currentOrder['address'] !== $address) $changes['address'] = $address;
        if ($currentOrder['city'] !== $city) $changes['city'] = $city;
        if ($currentOrder['state'] !== $state) $changes['state'] = $state;
        if ($currentOrder['zip'] !== $zip) $changes['zip'] = $zip;
        if ($userId !== null && $currentOrder['user_id'] != $userId) $changes['user_id'] = $userId;

   
        $nameUpdated = false;

   
        if ($currentOrder['name'] !== $name && !empty($name)) {

            $nameSql = "UPDATE users

            JOIN orders ON users.id = orders.user_id 
            SET users.name = :name 
            WHERE orders.id = :order_id";
            $nameStmt = $this->db->prepare($nameSql);
            $nameStmt->execute([

                'name' => $name,
                'order_id' => $orderId
            ]);
            $nameUpdated = $nameStmt->rowCount() > 0;
        }

    
        if (empty($changes) && !$nameUpdated) {

            return false;
        }

    
        if (!empty($changes)) {

            $sql = "UPDATE orders SET ";
            $setParts = [];
            $params = ['id' => $orderId];

            foreach ($changes as $field => $value) {


                $setParts[] = "$field = :$field";
                $params[$field] = $value;
            }

            $sql .= implode(', ', $setParts) . " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->rowCount() > 0 || $nameUpdated;
        }

        return $nameUpdated;
    }


    public function countOrders(){

        $stmt = $this->db->query("SELECT COUNT(*) FROM orders");
        return $stmt->fetchColumn();
    }


    public function getTotalOrders() {

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalRevenue() {

        $stmt = $this->db->prepare("SELECT SUM(total_amount) FROM orders");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalProductsSold() {

        $stmt = $this->db->prepare("SELECT SUM(quantity) FROM order_items");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getRevenueStats(){

        $stmt = $this->db->prepare("

        SELECT 
        SUM(order_items.quantity * order_items.price) AS total_revenue,
        SUM(order_items.quantity) AS total_quantity
        FROM orders
        JOIN order_items ON orders.id = order_items.order_id
        WHERE orders.status != 'cancelled'
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getOrdersByUser($userId) {

        try {

            $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
        
        
            error_log("Found " . $stmt->rowCount() . " orders for user $userId");
        
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($orders as &$order) {

                $orderId = $order['id'];
                $order['items'] = $this->getOrderItems($orderId);
            
            
                error_log("Order {$orderId} has " . count($order['items']) . " items");
            }

            return $orders;
        } catch (PDOException $e) {


            error_log("Database error in getOrdersByUser: " . $e->getMessage());
            return [];
        }
    }


    public function getAllOrders() {
        $stmt = $this->db->query("SELECT * FROM orders");
        return $stmt->fetchAll();
    }

    public function updateUserStatus($userId, $status) {

        $query = "UPDATE users SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$status, $userId]);
    }

    public function getAllOrdersWithUserDetails() {

    
        $stmt = $this->db->query("

        SELECT o.*, u.name 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    
        foreach ($orders as &$order) {

            $order['items'] = $this->getOrderItems($order['id']);
        }
    
        return $orders;
    }
    public function getOrderItems($order_id) {

        $stmt = $this->db->prepare("

        SELECT oi.*, p.name as product_name, p.image 
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = :order_id
        ");
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOrdersWithUserDetailsPaginated($limit, $offset, $status = null, $userId = null) {

        $sql = "SELECT

        o.id, 
        o.status, 
        o.created_at,
        o.total_amount,
        o.address,
        o.city,
        o.state,
        o.zip,
        u.name,
        u.email,
        (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) AS items_count
        FROM orders o
        JOIN users u ON o.user_id = u.id";

        $where = [];
        $params = [];

        if ($status) {

            $where[] = "o.status = :status";
            $params[':status'] = $status;
        }

        if ($userId) {

            $where[] = "o.user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        if (!empty($where)) {

            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";


        $stmt = $this->db->prepare($sql);

    
        foreach ($params as $key => $value) {

            $stmt->bindValue($key, $value);
        }

    
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {

            if (isset($order['total_amount'])) {

                $order['total_amount'] = str_replace(',', '', $order['total_amount']);
            }
        }

        return $orders;
    }




    public function updateUserRole($userId, $role) {

        $query = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$role, $userId]);
    }

    public function getUserById($userId) {

        $query = "SELECT id, name, email, role, status FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countOrdersByUser($userId) {

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function getTotalOrderCount($status = null, $userId = null) {

    
        $sql = "SELECT COUNT(*) FROM orders";
        $where = [];
        $params = [];
    
        if ($status) {

            $where[] = "status = :status";
            $params['status'] = $status;
        }
    
        if ($userId) {

            $where[] = "user_id = :user_id";
            $params['user_id'] = $userId;
        }
    
        if (!empty($where)) {

            $sql .= " WHERE " . implode(' AND ', $where);
        }
    
        $stmt = $this->db->prepare($sql);
    
        foreach ($params as $key => $value) {

            $stmt->bindValue(':' . $key, $value);
        }
    
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
    
        


    public function getOrderById($id) {

    
        $stmt = $this->db->prepare("

        SELECT o.*, u.name, u.email 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.id = :id
        ");

        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {

            return false;
        }

    
        $order['items'] = $this->getOrderItems($order['id']);
    
        return $order;
    }

    public function getOrdersByUserPaginated($userId, $limit, $offset) {

        $stmt = $this->db->prepare("

        SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {

            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $orders;
    }


    public function updateOrderStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function getCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM orders");
        return $stmt->fetch()['count'];
    }
    public function deleteOrder($orderId) {

        $stmtOrder = $this->db->prepare("DELETE FROM orders WHERE id = ?");
        $stmtOrder->execute([$orderId]);
        return $stmtOrder->rowCount() > 0; 
    }


    public function getTopSellingProducts($limit = 5){

        $stmt = $this->db->prepare("

        SELECT 
        p.id,
        p.name,
        p.price,
        
        SUM(oi.quantity) as total_quantity,
        SUM(oi.quantity * oi.price) as total_amount
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        GROUP BY oi.product_id
        ORDER BY total_quantity DESC
        LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountByStatus($status) {

        $sql = "SELECT COUNT(*) FROM orders WHERE status = :status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetchColumn();
    }


    public function getAllUsers() {

        $stmt = $this->db->query("SELECT id, name, email, status, created_at FROM users WHERE role = 'user' ORDER BY name");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedUsers($limit, $offset) {

        $stmt = $this->db->prepare("SELECT id, name, email, status, role, created_at FROM users WHERE role = 'user' ORDER BY name LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalUserCount() {

        $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
        return (int)$stmt->fetchColumn();
    }


}
?>
