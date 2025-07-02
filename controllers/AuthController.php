<?php
require_once __DIR__ . '/../models/AuthModel.php';

class AuthController {
    private $authModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . '/../config/database.php';
        $db = Database::getInstance();
        $this->authModel = new AuthModel($db);
    }

    public function showUserLogin() {
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user') {

            header("Location: /QaviEcommerce/user/home");
            exit();
        }

        if (session_status() === PHP_SESSION_NONE) {

            session_start();
        }
        // $error = $_SESSION['error'] ?? 'Nothing found';
        // echo "<pre>DEBUG ERROR: $error</pre>";
        require_once __DIR__ . '/../views/user/login.php';
    }


    public function showUserSignup() {

        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user') {

            header("Location: /QaviEcommerce/user/home");
            exit();
        }
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        require_once __DIR__ . '/../views/user/signup.php';
    }

    public function showAdminLogin() {

        if (session_status() === PHP_SESSION_NONE) {

            session_start();
        }
        // $error = $_SESSION['error'] ?? 'Nothing found';
        // echo "<pre>DEBUG ERROR: $error</pre>";
        require_once __DIR__ . '/../views/admin/login.php';
    }

    public function showAdminSignup() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        require_once __DIR__ . '/../views/admin/signup.php';
    }

    public function processUserSignup() {
        $this->handleSignup('user');
    }

    public function processAdminSignup() {
        $this->handleSignup('admin');
    }

    private function handleSignup($role) {

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $redirect = $role === 'admin' ? '/QaviEcommerce/admin/signup' : '/QaviEcommerce/user/signup';

    // Validate fields
        if (strlen($name) < 3 || !preg_match("/^[a-zA-Z\s]+$/", $name)) {

            $_SESSION['error'] = "Name_must_be_at_least_3_characters_and_contain_only_letters_and_spaces.";
            header("Location: $redirect");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $_SESSION['error'] = "Invalid_email_format.";
            header("Location: $redirect");
            exit();
        }

        if ($password !== $confirmPassword) {

            $_SESSION['error'] = 'Passwords_do_not_match.';
            header("Location: $redirect");
            exit();
        }

        
        if (

             

            strlen($password) < 8 ||

            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/\d/', $password) ||
            !preg_match('/[@$!%*?&]/', $password)
        ) {
        $_SESSION['error'] = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
        header("Location: $redirect");
        exit();
        }

   
        if ($this->authModel->emailExists($email, $role)) {


            $_SESSION['error'] = "Email already registered.";
            header("Location: $redirect");
            exit();
        }

        $success = $this->authModel->register($name, $email, $password, $role);

        if ($success) {

            $_SESSION['success'] = "Signup successful! You can now log in.";
            $successRedirect = $role === 'admin' ? '/QaviEcommerce/admin/login' : '/QaviEcommerce/user/login';
            header("Location: $successRedirect");
            exit();
        }

    // No need for else — just continue
        $_SESSION['error'] = "Registration failed. Try again.";
        header("Location: $redirect");
        exit();

    }



    public function processUserLogin() {
        $this->handleLogin('user', 'user', 'home');
    }

    public function processAdminLogin() {
        $this->handleLogin('admin', 'admin', 'dashboard');
    }

    private function handleLogin($expectedRole, $controller, $redirectAction) {



        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $_SESSION['login_email'] = $email;

    // Basic validation
        if (empty($email) || empty($password)) {

            $_SESSION['error'] = "Please_fill_in_all_fields.";
            header("Location: /QaviEcommerce/{$expectedRole}/login");
            exit();
        }

    // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $_SESSION['error'] = 'Please_enter_a_valid_email_address';
            
            header("Location: /QaviEcommerce/{$expectedRole}/login");
            exit();
        }

    // Fetch user record
        $user = $this->authModel->getUserByEmailAndRole($email, $expectedRole);

        if (!$user) {

            $_SESSION['error'] = "Email_not_found.";
            header("Location: /QaviEcommerce/{$expectedRole}/login");
            exit();
        }

        if (!password_verify($password, $user['password'])) {

            $_SESSION['error'] = "Incorrectpassword.";
            header("Location: /QaviEcommerce/{$expectedRole}/login");
            exit();
        }

        if (isset($user['status']) && (int)$user['status'] === 0) {

            $_SESSION['error'] = "Your_account_has_been_disabled.";
            header("Location: /QaviEcommerce/{$expectedRole}/login");
             exit();
        }

    // Successful login
        unset($_SESSION['error'], $_SESSION['login_email']);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

    // Redirect to originally requested page, if set
        $redirect = $_SESSION['redirect_after_login'] ?? "/QaviEcommerce/$controller/$redirectAction";
        unset($_SESSION['redirect_after_login']); // Clean up

    // Security check to avoid external redirects
        if (strpos($redirect, '/QaviEcommerce/') !== 0) {

            $redirect = "/QaviEcommerce/$controller/$redirectAction";
        }

        header("Location: $redirect");
        exit();
    }




    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /QaviEcommerce');
        exit();
    }
}
