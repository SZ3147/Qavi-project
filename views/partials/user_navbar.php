<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Default language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}
?>
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">

<nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top" style="background-color: #fff;">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-danger" href="/QaviEcommerce/user/home">
            <i class="fas fa-store me-2"></i><?= Lang::get('brand') ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar" aria-controls="userNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="userNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($_SERVER['REQUEST_URI'] === '/QaviEcommerce/user/home') ? 'text-danger fw-bold' : '' ?>" href="/QaviEcommerce/user/home">
                        <i class="fas fa-home me-1"></i><?= Lang::get('home') ?>
                    </a>
                </li>

                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/cart') !== false) ? 'text-danger fw-bold' : '' ?>" href="/QaviEcommerce/user/cart">
                            <i class="fas fa-shopping-cart me-1"></i><?= Lang::get('cart') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/order_history') !== false) ? 'text-danger fw-bold' : '' ?>" href="/QaviEcommerce/user/order_history">
                            <i class="fas fa-box me-1"></i><?= Lang::get('orders') ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav align-items-center">

                <!-- 🌐 Language Switcher -->
                <li class="nav-item">
                    <form method="get" action="" class="d-flex">
                        <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="en" <?= ($_SESSION['lang'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                            <option value="de" <?= ($_SESSION['lang'] ?? '') === 'de' ? 'selected' : '' ?>>Deutsch</option>
                            <option value="fr" <?= ($_SESSION['lang'] ?? '') === 'fr' ? 'selected' : '' ?>>Français</option>
                            <option value="ned" <?= ($_SESSION['lang'] ?? '') === 'ned' ? 'selected' : '' ?>>Dutch</option>
                            <?php
                            

                            foreach ($_GET as $key => $value) {

                                if ($key !== 'lang') {

                                    echo "<input type='hidden' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($value) . "'>";
                                }
                            }
                            ?>
                        </select>
                    </form>
                </li>

                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                    <li class="nav-item d-flex align-items-center ms-3">
                        <!-- Display mode -->
                        <div class="nav-link text-muted d-flex align-items-center" id="usernameDisplay" style="cursor: default;">
                            <i class="fas fa-user-circle me-1"></i>
                            <span id="usernameText"><?= htmlspecialchars($_SESSION['name'] ?? Lang::get('user')) ?></span>
                            <i class="fas fa-edit ms-2 editNameBtn" style="cursor:pointer;" title="<?= Lang::get('edit_name') ?>"></i>
                        </div>

                        <!-- Edit mode -->
                        <div class="nav-link text-muted d-none" id="usernameEdit">
                            <input type="text" id="usernameInput" value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>" style="width: 140px; display:inline-block;">
                            <button id="saveNameBtn" class="btn btn-sm btn-primary ms-1"><?= Lang::get('save') ?></button>
                            <button id="cancelEditBtn" class="btn btn-sm btn-secondary ms-1"><?= Lang::get('cancel') ?></button>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/QaviEcommerce/user/logout">
                            <i class="fas fa-sign-out-alt me-1"></i><?= Lang::get('logout') ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-3">
                        <a class="nav-link" href="/QaviEcommerce/user/login">
                            <i class="fas fa-sign-in-alt me-1"></i><?= Lang::get('login') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/QaviEcommerce/user/signup">
                            <i class="fas fa-user-plus me-1"></i><?= Lang::get('signup') ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- JavaScript for Name Edit -->
<script>
$(document).ready(function() {
    $('.editNameBtn').click(function() {
        $('#usernameDisplay').addClass('d-none');
        $('#usernameEdit').removeClass('d-none');
        $('#usernameInput').focus();
    });

    $('#cancelEditBtn').click(function() {
        $('#usernameEdit').addClass('d-none');
        $('#usernameDisplay').removeClass('d-none');
        $('#usernameInput').val($('#usernameText').text());
    });

    $('#saveNameBtn').click(function() {
        var newName = $('#usernameInput').val().trim();
        if (newName.length === 0) {
            alert('<?= Lang::get('name_cannot_be_empty') ?>');
            return;
        }

        var csrfToken = $('meta[name="csrf-token"]').attr('content') || '';

        $.ajax({
            url: '/QaviEcommerce/user/updateName',
            method: 'POST',
            dataType: 'json',
            data: {
                name: newName,
                csrf_token: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    $('#usernameText').text(newName);
                    $('#usernameEdit').addClass('d-none');
                    $('#usernameDisplay').removeClass('d-none');
                } else {
                    alert(response.message || '<?= Lang::get('update_failed') ?>');
                }
            },
            error: function() {
                alert('<?= Lang::get('error_updating_name') ?>');
            }
        });
    });
});
</script>
