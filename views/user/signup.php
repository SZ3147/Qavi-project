<?php include __DIR__ . '/../partials/user_header.php'; ?>
<?php include __DIR__ . '/../partials/user_navbar.php'; ?>

<?php
$csrf_token = $_SESSION['csrf_token'] ?? '';
$name = $_SESSION['signup_name'] ?? '';
$email = $_SESSION['signup_email'] ?? '';
$error = $_SESSION['error'] ?? '';

// Clear session error and fields after use
unset($_SESSION['error'], $_SESSION['signup_name'], $_SESSION['signup_email']);
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

    /* Signup container */
    .signup-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        margin-top: 1rem;
    }

    /* Signup card */
    .signup-card {
        width: 100%;
        max-width: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .signup-card:hover {
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
        position: relative;
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

    .password-strength {
        height: 4px;
        background: #e9ecef;
        margin-top: 8px;
        border-radius: 2px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        width: 0%;
        background: #dc3545;
        transition: width 0.3s ease, background 0.3s ease;
    }

    /* Button styles */
    .btn-signup {
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

    .btn-signup:hover {
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

    /* Footer link */
    .signup-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: #6c757d;
    }

    .signup-footer a {
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .signup-footer a:hover {
        color: #5a6fd1;
        text-decoration: underline;
    }

    /* Password requirements */
    .password-requirements {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .signup-card {
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

<div class="signup-container">
    <div class="signup-card">
        <div class="card-header">
            <h2><?=Lang::get('Create_Your_Account')?></h2>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars(Lang::get($error)) ?></div>
            <?php endif; ?>

            <form method="POST" action="/QaviEcommerce/user/signup" autocomplete="off" id="signupForm">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                <div class="form-group">
                    <label for="name"><?=Lang::get("FullName")?></label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="name" 
                        name="name" 
                        required 
                        minlength="3" 
                        pattern="[a-zA-Z\s]+" 
                        title="Only letters and spaces"
                        value="<?= htmlspecialchars($name) ?>"
                        autofocus
                        placeholder="<?=Lang::get("Enter_your_full_name")?>"
                    >
                </div>

                <div class="form-group">
                    <label for="email"><?=Lang::get('EmailAddress')?></label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        required 
                        autocomplete="username"
                        value="<?= htmlspecialchars($email) ?>"
                        placeholder="<?=Lang::get("Enter_your_email")?>"
                    >
                </div>

                <div class="form-group">
                    <label for="password"><?=Lang::get('Password')?></label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}"
                        title="Must contain at least 8 characters, including uppercase, lowercase, number, and special character."
                        placeholder="<?=Lang::get("Create_a_password")?>"
                    >
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <div class="password-requirements">
                        <?=Lang::get("Must_contain:8+_characters,_uppercase,_lowercase,_number,_and_special_character")?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password"><?=Lang::get("Confirm_Password")?></label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="confirm_password" 
                        name="confirm_password" 
                        required
                        placeholder="<?=Lang::get("Confirm_your_password")?>"
                    >
                    <small id="passwordMatch" class="text-danger"></small>
                </div>

                <button type="submit" class="btn-signup"><?=Lang::get("Create_Account")?></button>
            </form>

            <div class="signup-footer">
                <?=Lang::get("Already_have_an_account?")?> <a href="/QaviEcommerce/user/login"><?=Lang::get("login")?></a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordStrengthBar = document.getElementById('passwordStrengthBar');
    const passwordMatch = document.getElementById('passwordMatch');
    const signupForm = document.getElementById('signupForm');

    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Length check
        if (password.length >= 8) strength += 25;
        if (password.length >= 12) strength += 15;
        
        // Character type checks
        if (/[A-Z]/.test(password)) strength += 20;
        if (/[a-z]/.test(password)) strength += 20;
        if (/[0-9]/.test(password)) strength += 10;
        if (/[^A-Za-z0-9]/.test(password)) strength += 10;
        
        // Update strength bar
        strength = Math.min(100, strength);
        passwordStrengthBar.style.width = strength + '%';
        
        // Update color based on strength
        if (strength < 40) {
            passwordStrengthBar.style.backgroundColor = '#dc3545';
        } else if (strength < 70) {
            passwordStrengthBar.style.backgroundColor = '#ffc107';
        } else {
            passwordStrengthBar.style.backgroundColor = '#28a745';
        }
    });

    // Password confirmation check
    confirmPasswordInput.addEventListener('input', function() {
        if (passwordInput.value !== this.value) {
            passwordMatch.textContent = 'Passwords do not match';
        } else {
            passwordMatch.textContent = '';
        }
    });

    // Form validation
    signupForm.addEventListener('submit', function(e) {
        if (passwordInput.value !== confirmPasswordInput.value) {
            e.preventDefault();
            passwordMatch.textContent = 'Passwords must match';
            confirmPasswordInput.focus();
        }
    });
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>