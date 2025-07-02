<?php


// Values set from controller logic
$csrf_token = $_SESSION['csrf_token'] ?? '';
$email = $_SESSION['login_email'] ?? '';
$error = $_SESSION['error'] ?? '';

// Clear error so it doesn't persist on refresh
unset($_SESSION['error']);
unset($_SESSION['login_email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <style>
        .error { 
            color: red;
            padding: 10px;
            margin: 10px 0;
            background: #ffeeee;
            border: 1px solid #ffcccc;
            border-radius: 4px;
        }
        .login-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Admin Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/QaviEcommerce/admin/login" autocomplete="on">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?= htmlspecialchars($email) ?>" 
                       autocomplete="username" 
                       required 
                       autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       autocomplete="current-password" 
                       required>
            </div>

            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>

      
    </div>
</body>
</html>
