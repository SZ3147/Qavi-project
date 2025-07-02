<?php include __DIR__ . '/../partials/user_header.php'; ?>
<?php include __DIR__ . '/../partials/user_navbar.php'; ?>

<?php
// Values set from controller logic
$csrf_token = $_SESSION['csrf_token'] ?? '';
$email = $_SESSION['login_email'] ?? '';
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

// Clear flash messages so they don't persist on refresh
unset($_SESSION['error']);
unset($_SESSION['login_email']);
unset($_SESSION['success']);
?>

<style>
    /* Main layout styles */
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Navbar fixes */
    .navbar {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
    }

    /* Login container */
    .login-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        margin-top: 1rem;
    }

    /* Login card */
    .login-card {
        width: 100%;
        max-width: 450px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    /* Card header */
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        text-align: center;
        border-bottom: none;
    }

    .card-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.8rem;
    }

    /* Card body */
    .card-body {
        padding: 2rem;
    }

    /* Form elements */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 8px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Button styles */
    .btn-login {
        display: block;
        width: 100%;
        padding: 0.75rem;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #5a6fd1 0%, #6a4199 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Message styles */
    .alert {
        padding: 0.75rem 1.25rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .alert-error {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    /* Footer link */
    .login-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: #6c757d;
    }

    .login-footer a {
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .login-footer a:hover {
        color: #5a6fd1;
        text-decoration: underline;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .login-card {
            border-radius: 0;
            box-shadow: none;
        }
        
        .card-header {
            padding: 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="card-header">

            <h2><?= Lang::get("welcome_back"); ?></h2>
        </div>
        <div class="card-body">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars(Lang::get($success)) ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars(Lang::get($error)) ?></div>
            <?php endif; ?>

            <form method="POST" action="/QaviEcommerce/user/login" autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                <div class="form-group">
                    <label for="email"><?=Lang::get('EmailAddress')?></label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           value="<?= htmlspecialchars($email) ?>" 
                           autocomplete="username" 
                           required 
                           autofocus
                           placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="password"><?=Lang::get('Password')?></label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           autocomplete="current-password" 
                           
                           placeholder="Enter your password">
                </div>

                <button type="submit" class="btn-login"><?=Lang::get("login")?></button>
            </form>

            <div class="login-footer">
                <?=Lang::get('Donthaveanaccount')?>' <a href="/QaviEcommerce/user/signup"><?=Lang::get('Createone')?></a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>