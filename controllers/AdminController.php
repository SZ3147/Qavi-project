<?php
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class AdminController {
    
    private $categoryModel;
    private $productModel;
    private $orderModel;
    private $userModel; 

    public function __construct() {
        
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->userModel = new UserModel();
    }

    public function dashboard() {


        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {

            header("Location: /QaviEcommerce/admin/");
            exit();
        }

   
        $data['categoryCount'] = $this->categoryModel->getCount();
        $data['productCount'] = $this->productModel->getCount();
        $data['orderCount'] = $this->orderModel->getCount();
        $data['topSelling'] = $this->orderModel->getTopSellingProducts(5);

   
        $data['pendingOrders'] = $this->orderModel->getCountByStatus('pending');
        $data['successOrders'] = $this->orderModel->getCountByStatus('delivered');
        $data['cancelledOrders'] = $this->orderModel->getCountByStatus('cancelled');  

    
        $data['totalOrders'] = $data['orderCount'];

   
        $revenueData = $this->orderModel->getRevenueStats();
        $data['totalRevenue'] = $revenueData['total_revenue'] ?? 0;
        $data['totalProductsSold'] = $revenueData['total_quantity'] ?? 0;

    
        $data['totalUsers'] = $this->userModel->getCount();

  
        extract($data);
        include __DIR__ . '/../views/admin/dashboard.php';
    }






    public function viewProduct($id) {
        $productId = intval($id);
        $product = $this->productModel->getProductById($productId);

        if (!$product) {
            $_SESSION['error_message'] = "Product not found.";
            header("Location: /admin/products");
            exit;
        }

        include __DIR__ . '/../views/admin/product_view.php';
    }

    public function manageCategories() {

        $categoriesPerPage = 10;
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    
    
        $totalCategories = $this->categoryModel->getTotalCategoryCount();
    
    
        $totalPages = ceil($totalCategories / $categoriesPerPage);
    
    
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages && $totalPages > 0) $currentPage = $totalPages;

    
   
        $offset = ($currentPage - 1) * $categoriesPerPage;
    
   
        $categories = $this->categoryModel->getPaginatedCategories($categoriesPerPage, $offset);
    
        $data = [

            'categories' => $categories,
            'total_categories' => $totalCategories,
            'current_page' => $currentPage,
            'total_pages' => $totalPages
        ];
    
        include __DIR__ . '/../views/admin/categories.php';
    }

    public function addCategoryForm() {
        include __DIR__ . '/../views/admin/add_category.php';
    }

    public function addCategory() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = $_POST['status'];

            $imageFileName = null;

            if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] === UPLOAD_ERR_OK) {

                try {

                    $imageFileName = $this->handleCategoryImageUpload($_FILES['category_image']);
                } catch (Exception $e) {

                    $_SESSION['error_message'] = $e->getMessage();
                    header('Location: /QaviEcommerce/admin/addCategoryForm');
                    exit;
                }
            }

        
            $this->categoryModel->addCategory($name, $description, $status, $imageFileName);

            header('Location: /QaviEcommerce/admin/categories');
            exit;
        }
    }

    private function handleCategoryImageUpload($file) {

        $uploadDir = __DIR__ . '/../../public/uploads/';  
    
    
        if (!file_exists($uploadDir)) {

            mkdir($uploadDir, 0777, true);
        }

    
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {

            throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
        }

    
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'cat_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

    
        if (!move_uploaded_file($file['tmp_name'], $destination)) {

            throw new Exception('Failed to upload image.');
        }

        return $filename; 
    }

    public function editProductPage($id) {
        $productId = (int)$id;
        $product = $this->productModel->getProductById($productId);

        if (!$product) {
            $_SESSION['error_message'] = "Product not found";
            header('Location: /admin/products');
            exit();
        }

        
        $categories = $this->categoryModel->getAllCategoriesWithProductCount();

        include __DIR__ . '/../views/admin/product_edit.php';
    }

    public function editCategory($id) {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            header('HTTP/1.1 404 Not Found');
            die("Category with ID $id not found.");
        }

        $data['category'] = $category;

        include __DIR__ . '/../views/admin/edit_category.php';
    }

    public function updateCategory() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $status = $_POST['status'];
            $existingImage = $_POST['old_image'] ?? null;
            $imageFileName = $existingImage;
            $removeImage = isset($_POST['remove_image']) && $_POST['remove_image'] == 1;

        
            $currentCategory = $this->categoryModel->getCategoryById($id);
            if (!$currentCategory) {

                $_SESSION['error_message'] = "Category not found.";
                header("Location: /QaviEcommerce/admin/manageCategories");
                exit;
            }

        
            $isChanged = false;

       
            if (

                $name !== $currentCategory['name'] ||
                $description !== $currentCategory['description'] ||
                $status != $currentCategory['status']
            ) {

                $isChanged = true;
            }

        
            if ($removeImage && $existingImage) {

                $isChanged = true;
                $uploadDir = __DIR__ . '/../../public/uploads/';
                $oldImagePath = $uploadDir . $existingImage;
                if (file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $imageFileName = null; 
            }

        
            if (!$removeImage && isset($_FILES['category_image']) && $_FILES['category_image']['error'] === UPLOAD_ERR_OK) {

                try {

                    $uploadedImage = $this->handleCategoryImageUpload($_FILES['category_image']);
                    if ($uploadedImage !== $existingImage) {

                        $isChanged = true;
                        $imageFileName = $uploadedImage;
                    
                        if ($existingImage) {

                            $uploadDir = __DIR__ . '/../../public/uploads/';
                            $oldImagePath = $uploadDir . $existingImage;
                            if (file_exists($oldImagePath)) {

                                unlink($oldImagePath);
                            }
                        }
                    }
                } catch (Exception $e) {

                    $_SESSION['error_message'] = $e->getMessage();
                    header("Location: /QaviEcommerce/admin/editCategory/$id");
                    exit;
                }
            }

            if ($isChanged) {

            
                $this->categoryModel->updateCategory($id, $name, $description, $status, $imageFileName);
                $_SESSION['success_message'] = "Category updated successfully.";
            } else {

            
                $_SESSION['info_message'] = "No changes made to the category.";
            }

            header('Location: /QaviEcommerce/admin/manageCategories');
            exit;
        }
    }





    public function deleteCategory($id) {
        $this->categoryModel->deleteCategory($id);
        header('Location: /QaviEcommerce/admin/manageCategories');
        exit;
    }

    public function toggleCategoryStatus($id, $status) {
        if ($id !== null && $status !== null) {
            $this->categoryModel->updateStatus($id, $status);
        }

        header("Location: /QaviEcommerce/admin/manageCategories");

        exit;
    }

    public function manageProducts() {

        $productsPerPage = 10;

   
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

    
        $totalProducts = $this->productModel->getTotalProductCount();

    
        $totalPages = ceil($totalProducts / $productsPerPage);

    
        if ($currentPage < 1) {

            $currentPage = 1;
        } elseif ($currentPage > $totalPages) {

            $currentPage = $totalPages;
        }

    
        $offset = ($currentPage - 1) * $productsPerPage;

    
        $products = $this->productModel->getPaginatedProducts($productsPerPage, $offset);

    
        $categories = $this->categoryModel->getAllCategoriesWithProductCount();

    
        $data = [

            'products' => $products,
            'categories' => $categories,
            'total_products' => $totalProducts,
            'current_page' => $currentPage,
            'total_pages' => $totalPages
        ];

        include __DIR__ . '/../views/admin/product.php';
    }


    public function addProductPage() {
        $categories = $this->categoryModel->getAllCategoriesWithProductCount();
        $selectedCategory = $_GET['category_id'] ?? null;
        $data['categories'] = $categories;
        $data['selectedCategory'] = $selectedCategory;
        include __DIR__ . '/../views/admin/product_add.php';
    }

    public function addProduct() {
        $categories = $this->categoryModel->getAllCategoriesWithProductCount();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = trim($_POST['price']);
            $category_id = $_POST['category_id'];
            $imageFile = $_FILES['image'];
            $quantity = trim($_POST['quantity']);

            try {
                
                $imagePath = $this->productModel->handleImageUpload($imageFile);
                $this->productModel->addProduct($name, $description, $price, $category_id, $imagePath, $quantity);

                header('Location: /QaviEcommerce/admin/products');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $data['categories'] = $categories;
        if (isset($error)) {
            $data['error'] = $error;
        }

        include __DIR__ . '/../views/admin/product_add.php';
    }

    public function viewCategory($id) {

        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {

            header('HTTP/1.1 404 Not Found');
            die("Category with ID $id not found.");
        }

    
        $productsPerPage = 8;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        $offset = ($currentPage - 1) * $productsPerPage;

    
        $totalProducts = $this->productModel->countProductsByCategoryId($id);
        $totalPages = ceil($totalProducts / $productsPerPage);

    
        $products = $this->productModel->getProductsByCategoryIdPaginated($id, $productsPerPage, $offset);

    
        $data = [

            'category' => $category,
            'products' => $products,
            'current_page' => $currentPage,
            'total_pages' => $totalPages
        ];

        include __DIR__ . '/../views/admin/view_category.php';
    }


    public function toggleStatus($id, $status) {

        if ($id !== null && in_array((int)$status, [0, 1], true)) {

            $this->productModel->updateStatus((int)$id, (int)$status);
        }

        header("Location: /QaviEcommerce/admin/products");
        exit;
    }

    public function updateProduct($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_message'] = "Invalid request";
            header('Location: /admin/products');
            exit();
        }

        $productId = (int)$id;

       
        $required = ['name', 'category_id', 'price'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error_message'] = "Please fill in all required fields";
                header("Location: /QaviEcommerce/admin/product_edit/$productId");
                exit();
            }
        }

       
        $data = [
            'id' => $productId,
            'name' => trim($_POST['name']),
            'category_id' => (int)$_POST['category_id'],
            'price' => (float)$_POST['price'],
            'description' => trim($_POST['description'] ?? ''),
            'quantity' => (int)$_POST['quantity'] 
        ];

        
        try {
            if (!empty($_FILES['image']['name'])) {
                
                $data['image'] = $this->productModel->handleImageUpload($_FILES['image']);
            } elseif (isset($_POST['remove_image']) && $_POST['remove_image'] == 'on') {
                
                $data['image'] = null;
            }

            
            if ($this->productModel->updateProduct($data)) {
                $_SESSION['success_message'] = "Product updated successfully";
            } else {
                $_SESSION['error_message'] = "Failed to update product";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: /admin/product_edit/$productId");
            exit();
        }

        header('Location: /QaviEcommerce/admin/products');
        exit();
    }

    public function products($page = 1) {

        $productsPerPage = 10;

   
        $currentPage = is_numeric($page) && $page > 0 ? (int)$page : 1;

        $totalProducts = $this->productModel->getTotalProductCount();
        $totalPages = ceil($totalProducts / $productsPerPage);

        if ($currentPage > $totalPages && $totalPages > 0) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $productsPerPage;
        $products = $this->productModel->getPaginatedProducts($productsPerPage, $offset);
        $categories = $this->categoryModel->getAllCategoriesWithProductCount();

        $data = [

            'products' => $products,
            'categories' => $categories,
            'total_products' => $totalProducts,
            'current_page' => $currentPage,
            'total_pages' => $totalPages
        ];

        include __DIR__ . '/../views/admin/product.php';
    }


    public function deleteProduct($id) {
        if (empty($id)) {
            die("Product ID is required");
        }

        $success = $this->productModel->deleteProduct($id);

        if (!$success) {
            $_SESSION['error_message'] = 'Failed to delete product';
        }

        header('Location: /QaviEcommerce/admin/products');
        exit();
    }

    public function manageOrders($param1 = null, $param2 = null){


   
        if (is_int($param1)) {

        
            $page = $param1;
            $status = $param2; 
        } else {

        
            $status = $param1;
            $page = is_int($param2) ? $param2 : 1;
        }

        $limit = 10;
        $offset = ($page - 1) * $limit;

    
        $data['orders'] = $this->orderModel->getOrdersWithUserDetailsPaginated($limit, $offset, $status);

    
        foreach ($data['orders'] as &$order) {

            $order['items'] = $this->orderModel->getOrderItems($order['id']);
        }

    
        $totalOrders = $this->orderModel->getTotalOrderCount($status);
        $data['total_pages'] = ceil($totalOrders / $limit);
        $data['current_page'] = $page;
        $data['total_orders'] = $totalOrders;
        $data['status_filter'] = $status;

        include __DIR__ . '/../views/admin/order.php';
    }





    public function updateUserRole($userId) {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userId = (int)$userId;
            $newRole = $_POST['role'];
        
        // Prevent modifying your own admin role
            if ($userId == $_SESSION['user_id']) {

                $_SESSION['error'] = 'You cannot modify your own role.';
                header("Location: /QaviEcommerce/admin/manageUsers");
                exit;
            }
        
            $success = $this->orderModel->updateUserRole($userId, $newRole);
        
            if ($success) {

                $_SESSION['message'] = 'User role updated successfully';
            } else {

                $_SESSION['error'] = 'Failed to update user role';
            }
        
            header("Location: /QaviEcommerce/admin/manageUsers");
            exit;
        }
    }
    public function manageUsers() {

        $usersPerPage = 10;
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

        $totalUsers = $this->orderModel->getTotalUserCount();
        $totalPages = ceil($totalUsers / $usersPerPage);

        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages && $totalPages > 0) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $usersPerPage;

        $data = [

            'users' => $this->orderModel->getPaginatedUsers($usersPerPage, $offset),
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
        ];

        include __DIR__ . '/../views/admin/manage_users.php';
    }


    public function toggleUserStatus($userId, $status) {



        $userId = (int)$userId;
        $status = (int)$status;
    
    // Update user status in database
        $success = $this->orderModel->updateUserStatus($userId, $status);
    
        if ($success) {
            

            $_SESSION['message'] = 'User status updated successfully';
        } else {

            $_SESSION['error'] = 'Failed to update user status';
        }
    
        header("Location: /QaviEcommerce/admin/manageUsers");
        exit;
    }

    public function editOrder($id) {
        $orderId = $id;

        if (!$orderId) {
            $_SESSION['error'] = "No order ID provided.";
            header("Location: /admin/orders");
            exit;
        }

        // Get order details with user information
        $order = $this->orderModel->getOrderById($orderId);

        if (!$order) {
            $_SESSION['error'] = "Order not found.";
            header("Location: /admin/orders");
            exit;
        }

        // Get all users for dropdown (if you want to allow changing order ownership)
        $users = $this->orderModel->getAllUsers();

        $data = [
            'order' => $order,
            'users' => $users
        ];
    
        include __DIR__ . '/../views/admin/edit_order.php';
    }

    public function updateOrder() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $orderId = $_POST['order_id'];
            $status = $_POST['status'];
            $address = $_POST['address'] ?? '';
            $city = $_POST['city'] ?? '';
            $state = $_POST['state'] ?? '';
            $zip = $_POST['zip'] ?? '';
            $userId = $_POST['user_id'] ?? null;
            $name = $_POST['name'] ?? '';

            try {

            // Update order in database
                $success = $this->orderModel->updateOrder(

                    $orderId,
                    $status,
                    $address,
                    $city,
                    $state,
                    $zip,
                    $userId,
                    $name
                );

                if ($success) {

                    $_SESSION['message'] = 'Order updated successfully';
                } else {

                    $_SESSION['message'] = 'Order details were unchanged';
                }
            
                header("Location: /QaviEcommerce/admin/manageOrders");

                exit;
            } catch (Exception $e) {

                $_SESSION['error'] = 'Error updating order: ' . $e->getMessage();
                header("Location: /QaviEcommerce/admin/editOrder/$orderId");
                exit;
            }
        }
    }


    public function deleteOrder($id) {

        $orderId = (int)$id;
        $success = $this->orderModel->deleteOrder($orderId);

        if ($success) {

            $_SESSION['message'] = "Order #$orderId deleted successfully";
        } else {

            $_SESSION['error'] = "Failed to delete order #$orderId";
        }

        header("Location: /QaviEcommerce/admin/manageOrders/");
        exit;
    }


    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /QaviEcommerce/admin/login');
        exit();
    }
}