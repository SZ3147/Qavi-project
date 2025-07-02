<?php
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/CartModel.php';

class UserController {


    public function home() {

        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();

        $data['categories'] = $categoryModel->getAllCategoriesWithProductCount();
        $data['products'] = $productModel->getAllProducts();

    
        $data['is_logged_in'] = isset($_SESSION['user_id']) && $_SESSION['role'] === 'user';
    
        include __DIR__ . '/../views/user/home.php';
    }


    public function updateName() {

    // Verify this is an AJAX POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {

            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

    // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {

            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

    // Verify CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {

            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
            exit;
        }

    // Validate input
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {


            echo json_encode(['success' => false, 'message' => 'Name cannot be empty']);
            exit;
        }

    // Update in database (you'll need to implement this method in your UserModel)
        $userId = $_SESSION['user_id'];
        if ($this->userModel->updateUserName($userId, $name)) {


        // Update session
            $_SESSION['name'] = $name;
            echo json_encode(['success' => true]);
        } else {

            echo json_encode(['success' => false, 'message' => 'Failed to update name']);
        }
        exit;
    }



    public function category() {


        if (!isset($_GET['id'])) {

            echo "Category ID is missing.";
            return;
        }

        $category_id = $_GET['id'];
        $max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : null;
        $search_term = isset($_GET['search']) ? trim($_GET['search']) : null;

    // Pagination params
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $pageSize = 12; // Number of products per page

        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();

    // Get current category info
        $category = $categoryModel->getCategoryById($category_id);
        $data['category_name'] = $category['name'] ?? 'Category';
        $data['category_description'] = $category['description'] ?? '';

    // Get total product count (for pagination)
        $totalProducts = $productModel->countProductsByCategory($category_id, $max_price, $search_term);

    // Calculate offset
        $offset = ($page - 1) * $pageSize;

    // Get products with filters and pagination
        $data['products'] = $productModel->getProductsByCategory($category_id, $max_price, $search_term, $pageSize, $offset);

        $data['max_price'] = $max_price;
        $data['search_term'] = $search_term;
        
        $data['current_page'] = $page;
        $data['pageSize'] = $pageSize;
        $data['totalProducts'] = $totalProducts;
        $data['total_pages'] = ceil($totalProducts / $pageSize);

    // Get all categories for sidebar and this is testing commit
        $data['categories'] = $categoryModel->getAllCategoriesWithProductCount();

        include __DIR__ . '/../views/user/category.php';
    }





    public function product_detail() {

        if (!isset($_GET['id'])) {

            echo "Product ID is missing.";
            return;
        }

        $product_id = $_GET['id'];
        $_SESSION['last_viewed_product_id'] = $product_id;

        $productModel = new ProductModel();
        $product = $productModel->getProductById($product_id);

    // New: get current cart quantity for this user and product
        $cartModel = new CartModel();
        $currentCartQty = isset($_SESSION['user_id']) ? $cartModel->getProductQuantityInCart($_SESSION['user_id'], $product_id) : 0;

    // Calculate available quantity considering cart
        $product['available_quantity'] = max($product['quantity'] - $currentCartQty, 0);

        $data['product'] = $product;

        include __DIR__ . '/../views/user/product_detail.php';
    }


    public function update_cart_quantity(){

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {

            header('Location: /QaviEcommerce/user/cart');
            exit();
        }

    // CSRF check
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {

            $_SESSION['error'] = "Invalid CSRF token.";
            header('Location: /QaviEcommerce/user/cart');
            exit();
        }

        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $change = $_POST['change'] ?? null;
        $inputQty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

        if (!$product_id) {

            $_SESSION['error'] = "Invalid request.";
            header('Location: /QaviEcommerce/user/cart');
            exit();
        }

        $cartModel = new CartModel();
        $productModel = new ProductModel();

        $currentQty = $cartModel->getProductQuantityInCart($_SESSION['user_id'], $product_id);
        $product = $productModel->getProductById($product_id);

        if (!$product) {

            $_SESSION['error'] = "Product not found.";
            header('Location: /QaviEcommerce/user/cart');
            exit();
        }

        $availableQty = $product['quantity'];

    // Determine new quantity based on 'change' or manual input
        if ($change === 'increase') {

            $newQty = $currentQty + 1;

        } elseif ($change === 'decrease') {

            $newQty = $currentQty - 1; // allow going to zero here
        } else {

        
            $newQty = $inputQty;
        }

    
        if ($newQty <= 0) {

            $cartModel->removeFromCart($_SESSION['user_id'], $product_id);

        // Check if cart is now empty
            $cartItems = $cartModel->getCartItems($_SESSION['user_id']);
        if (empty($cartItems)) {

            $_SESSION['success'] = "Cart is now empty.";
        } else {


            $_SESSION['success'] = "Item removed from cart.";
        }

        header('Location: /QaviEcommerce/user/cart');
        exit();
        }

        if ($newQty > $availableQty) {

            $_SESSION['error'] = "Only $availableQty items available.";
            header('Location: /QaviEcommerce/user/cart');
            exit();
        }

    // Update cart quantity
        $cartModel->updateProductQuantity($_SESSION['user_id'], $product_id, $newQty);

        $_SESSION['success'] = "Cart updated.";
        header('Location: /QaviEcommerce/user/cart');
        exit();
    }




    public function add_to_cart() {

        if (!isset($_SESSION['user_id'])) {

        // Store the product ID in session for redirect after login
            if (isset($_GET['id'])) {

                $_SESSION['redirect_after_login'] = "/QaviEcommerce/user/product_detail?id=" . $_GET['id'];
            }
        
            $_SESSION['error_message'] = "Please login first to add items to cart";
            header('Location: /QaviEcommerce/user/login');
            exit();
        }

        if (!isset($_GET['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {

            $_SESSION['error_message'] = "Invalid cart request.";
            header("Location: /QaviEcommerce/user/product_detail?id=" . $_GET['id']);
            exit();
        }

        $product_id = $_GET['id'];
        $quantity = (int)($_POST['quantity'] ?? 0);

        if ($quantity < 1) {

            $_SESSION['error_message'] = "Quantity must be at least 1.";
            header("Location: /QaviEcommerce/user/product_detail?id=$product_id");
            exit();
        }

        $productModel = new ProductModel();
        $product = $productModel->getProductById($product_id);

        if (!$product) {

            $_SESSION['error_message'] = "Product not found.";
            header("Location: /QaviEcommerce/user/product_detail?id=$product_id");
            exit();
        }

        $cartModel = new CartModel();
        $currentCartQty = $cartModel->getProductQuantityInCart($_SESSION['user_id'], $product_id);
        $totalRequestedQty = $currentCartQty + $quantity;

        if ($totalRequestedQty > $product['quantity']) {

            $available = max($product['quantity'] - $currentCartQty, 0);
            $_SESSION['error_message'] = "Only $available more items available (already have $currentCartQty in cart).";
            header("Location: /QaviEcommerce/user/product_detail?id=$product_id");
            exit();
        }

        $cartModel->addProductToCart($_SESSION['user_id'], $product_id, $quantity);

        header('Location: /QaviEcommerce/user/cart');
        exit();
    }

    public function cart() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /QaviEcommerce/user/login');
            exit();
        }

        // Prevent browser caching (server-side)
        header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");


        $cartModel = new CartModel();
        $data['cart'] = $cartModel->getCartItems($_SESSION['user_id']);

        include __DIR__ . '/../views/user/cart.php';
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /QaviEcommerce/user/login');
            exit();
        }

        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartItems($_SESSION['user_id']);

        if (empty($cartItems)) {
            header('Location: /QaviEcommerce/user/cart?message=Your cart is empty.');
            exit();
        }

        $total = array_reduce($cartItems, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);
        $data = ['cart' => $cartItems, 'total' => $total];

        include __DIR__ . '/../views/user/checkout.php';
    }

    public function order_history() {

        if (!isset($_SESSION['user_id'])) {

            header('Location: /QaviEcommerce/user/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $orderModel = new OrderModel();

    // Pagination settings
        $ordersPerPage = 5;
        $totalOrders = $orderModel->countOrdersByUser($userId);
        $totalPages = ceil($totalOrders / $ordersPerPage);

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        if ($currentPage > $totalPages && $totalPages > 0) {

            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $ordersPerPage;

    // Get paginated orders
        $orders = $orderModel->getOrdersByUserPaginated($userId, $ordersPerPage, $offset) ?? [];

    // Pass pagination info to view
        $pagination = [

            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_orders' => $totalOrders
        ];

        include __DIR__ . '/../views/user/order_history.php';
    }


    public function remove_from_cart($product_id = null) {

        if (!isset($_SESSION['user_id']) || !isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {

            $_SESSION['error'] = "Unauthorized or invalid token.";
            header('Location: /QaviEcommerce/user/cart');
            exit();
        }

        if ($product_id) {

            $cartModel = new CartModel();
            $cartModel->removeFromCart($_SESSION['user_id'], $product_id);
            $_SESSION['success'] = "Item removed.";
        } else {

            $_SESSION['error'] = "Product ID missing.";
        }

        header('Location: /QaviEcommerce/user/cart');
        exit();
    }


    public function placeOrder() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /QaviEcommerce/user/login');
            exit();
        }

        $required = ['address', 'city', 'state', 'zip', 'phone'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = "Please fill in all required fields.";
                header('Location: /QaviEcommerce/user/checkout');
                exit();
            }
        }

        $orderModel = new OrderModel();
        $cartModel = new CartModel();
        $productModel = new ProductModel();

        $cartItems = $cartModel->getCartItems($_SESSION['user_id']);
        if (empty($cartItems)) {
            $_SESSION['error'] = "Cart is empty.";
            header('Location: /QaviEcommerce/user/cart');
            exit();
        }

        $total = array_reduce($cartItems, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);
        $orderId = $orderModel->placeOrder(
            $_SESSION['user_id'],
            $total,
            $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'],
            $_POST['payment_method'] ?? 'Cash on Delivery',
            $_POST['phone']
        );

        foreach ($cartItems as $item) {
            $orderModel->addOrderItem($orderId, $item['product_id'], $item['quantity'], $item['price']);
            $productModel->decreaseStock($item['product_id'], $item['quantity']);
            
        }
        $cartModel->clearCart($_SESSION['user_id']);

      
        $_SESSION['message'] = "Order placed successfully!";
        header('Location: /QaviEcommerce/user/order_history');
        exit();
    }

    public function checkStock() {


    // Set JSON header first
        header('Content-Type: application/json');
    
        try {


        // Validate required parameters
            if (!isset($_GET['product_id'])) {

                throw new Exception('product_id parameter is required');
            }
            if (!isset($_GET['requested_qty'])) {

                throw new Exception('requested_qty parameter is required');
            }

            $product_id = (int)$_GET['product_id'];
            $requested_qty = (int)$_GET['requested_qty'];

        // Check session
            if (!isset($_SESSION['user_id'])) {

                throw new Exception('User not logged in');
            }

            $productModel = new ProductModel();
            $product = $productModel->getProductById($product_id);

            if (!$product) {

                throw new Exception('Product not found');
            }

            $cartModel = new CartModel();
            $currentCartQty = $cartModel->getProductQuantityInCart($_SESSION['user_id'], $product_id);
            $availableQty = $product['quantity'] - $currentCartQty;

            if ($requested_qty > $availableQty) {


                echo json_encode([

                    'status' => 'error', 
                    'message' => "Only $availableQty available (you have $currentCartQty in cart)"
                ]);
            } else {

                echo json_encode(['status' => 'success']);
            }
        } catch (Exception $e) {

            http_response_code(400);
            echo json_encode([

                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit(); // Important to prevent any other output
    }

    public function getCartQuantity() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['product_id'])) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $cartModel = new CartModel();
        $quantity = $cartModel->getProductQuantityInCart($_SESSION['user_id'], $_GET['product_id']);
        echo json_encode(['quantity' => $quantity]);
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /QaviEcommerce/user/login');
        exit();
    }
}
