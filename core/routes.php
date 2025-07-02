<?php
require_once __DIR__ . '/Lang.php';
require_once __DIR__ . '/../config/database.php';

// Start session at the beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$pdo = Database::getInstance();


$allowedLangs = [];
try {
    $stmt = $pdo->query("SELECT code FROM language");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $allowedLangs[] = strtolower($row['code']);
    }
} catch (PDOException $e) {
    error_log("Failed to fetch allowed languages: " . $e->getMessage());
   
    $allowedLangs = ['en', 'de', 'fr', 'ned'];
}

if (isset($_GET['lang'])) {
    $langParam = strtolower($_GET['lang']);
    if (in_array($langParam, $allowedLangs)) {
        $_SESSION['lang'] = $langParam;
    }
}


$lang = $_SESSION['lang'] ?? 'en';


Lang::load($lang);

$requestUrl = $_GET['url'] ?? '';
$segments = explode('/', trim($requestUrl, '/'));


if (empty($requestUrl)) {
    header("Location: /QaviEcommerce/user/home");
    exit();
}


$controllerName = '';
$action = '';


if ($segments[0] === 'user') {
    if (isset($segments[1]) && $segments[1] === 'update_cart_quantity') {
        $controllerName = 'user';
        $action = 'update_cart_quantity';
    }

  
    if (isset($segments[1]) && $segments[1] === 'checkStock') {
        require_once __DIR__ . '/../controllers/UserController.php';
        $controller = new UserController();
        if (method_exists($controller, 'checkStock')) {
            $controller->checkStock();
            exit();
        }
    }

    
    if (isset($segments[1], $segments[2]) && $segments[1] === 'product_detail' && is_numeric($segments[2])) {
        $controllerName = 'user';
        $action = 'product_detail';
        $segments = ['user', 'product_detail', (int)$segments[2]];
    }

    
    if ($controllerName == 'user' && $action == 'logout') {
        require_once __DIR__ . '/../controllers/UserController.php';
        $controller = new UserController();
        $controller->logout();
    }

    
    if (!isset($segments[1]) || $segments[1] === 'login' || $segments[1] === 'signup') {
        $controllerName = 'auth';
        if (!isset($segments[1]) || $segments[1] === 'login') {
            $action = ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'processUserLogin' : 'showUserLogin';
        } elseif ($segments[1] === 'signup') {
            $action = ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'processUserSignup' : 'showUserSignup';
        }
    } else {
        $controllerName = 'user';
        $action = $segments[1] ?? 'home';
    }
}

elseif ($segments[0] === 'admin') {
    if (!isset($segments[1]) || $segments[1] === 'login' || $segments[1] === 'signup') {
        $controllerName = 'auth';
        if (!isset($segments[1]) || $segments[1] === 'login') {
            $action = ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'processAdminLogin' : 'showAdminLogin';
        } elseif ($segments[1] === 'signup') {
            $action = ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'processAdminSignup' : 'showAdminSignup';
        }
    } elseif (isset($segments[1], $segments[2], $segments[3]) && $segments[1] === 'orders' && $segments[2] === 'page' && is_numeric($segments[3])) {
        $controllerName = 'admin';
        $action = 'manageOrders';
        $segments = ['admin', 'manageOrders', (int)$segments[3]];
    } elseif (isset($segments[1], $segments[2]) && $segments[1] === 'editCategoryPage' && is_numeric($segments[2])) {
        $controllerName = 'admin';
        $action = 'editCategoryPage';
        $segments = ['admin', 'editCategoryPage', (int)$segments[2]];
    } elseif ($segments[1] === 'updateCategory') {
        $controllerName = 'admin';
        $action = 'updateCategory';
    } elseif ($segments[1] === 'categories') {
        $controllerName = 'admin';
        $action = 'manageCategories';
    } elseif (isset($segments[1], $segments[2]) && $segments[1] === 'orders' && in_array($segments[2], ['Pending', 'Shipped', 'Delivered', 'Cancelled'])) {
        $controllerName = 'admin';
        $action = 'manageOrders';
        $segments = ['admin', 'manageOrders', $segments[2]];
    } elseif (isset($segments[1], $segments[2], $segments[3], $segments[4]) &&
          $segments[1] === 'orders' &&
          in_array($segments[2], ['Pending', 'Shipped', 'Delivered', 'Cancelled']) &&
          $segments[3] === 'page' &&
          is_numeric($segments[4])) {
        $controllerName = 'admin';
        $action = 'manageOrders';
        $segments = ['admin', 'manageOrders', $segments[2], (int)$segments[4]];
    } elseif (isset($segments[1], $segments[2], $segments[3], $segments[4]) &&
              $segments[1] === 'product' &&
              $segments[2] === 'toggleStatus' &&
              is_numeric($segments[3]) &&
              ($segments[4] === '0' || $segments[4] === '1')) {
        $controllerName = 'admin';
        $action = 'toggleStatus';
        $segments = ['admin', 'toggleStatus', (int)$segments[3], (int)$segments[4]];
    } elseif (isset($segments[1], $segments[2], $segments[3]) &&
              $segments[1] === 'toggleUserStatus' &&
              is_numeric($segments[2]) &&
              ($segments[3] === '0' || $segments[3] === '1')) {
        $controllerName = 'admin';
        $action = 'toggleUserStatus';
        $segments = ['admin', 'toggleUserStatus', (int)$segments[2], (int)$segments[3]];
    } elseif ($segments[1] === 'manageUsers') {
        $controllerName = 'admin';
        $action = 'manageUsers';
    } elseif (isset($segments[1], $segments[2]) && $segments[1] === 'editProductPage' && is_numeric($segments[2])) {
        $controllerName = 'admin';
        $action = 'editProductPage';
        $segments = ['admin', 'editProductPage', (int)$segments[2]];
    } elseif ($segments[1] === 'updateProduct') {
        $controllerName = 'admin';
        $action = 'updateProduct';
    } elseif (isset($segments[1], $segments[2]) && $segments[1] === 'deleteOrder' && is_numeric($segments[2])) {
        $controllerName = 'admin';
        $action = 'deleteOrder';
        $segments = ['admin', 'deleteOrder', (int)$segments[2]];
    } elseif ($segments[1] === 'products') {
        $controllerName = 'admin';
        $action = 'products';
       
        if (isset($segments[2]) && is_numeric($segments[2])) {
            $segments = ['admin', 'products', (int)$segments[2]];
        } else {
            $segments = ['admin', 'products'];
        }
    } else {
        $controllerName = 'admin';
        $action = $segments[1] ?? 'dashboard';
    }
}


$controllerMap = [
    'auth' => 'AuthController',
    'user' => 'UserController',
    'admin' => 'AdminController'
];


if (isset($controllerMap[$controllerName])) {
    $controllerFile = __DIR__ . "/../controllers/{$controllerMap[$controllerName]}.php";

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerMap[$controllerName]();

        if (method_exists($controller, $action)) {
            $params = array_slice($segments, 2);
            $params = array_map(fn($p) => is_numeric($p) ? (int)$p : $p, $params);
            call_user_func_array([$controller, $action], $params);
            exit();
        }
    }
}


http_response_code(404);
header('Content-Type: application/json');
echo json_encode([
    'status' => 'error',
    'message' => 'Endpoint not found',
    'requested_url' => $requestUrl
]);
exit();
