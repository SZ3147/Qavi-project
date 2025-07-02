<?php include __DIR__ . '/../partials/user_header.php'; ?>
<?php include __DIR__ . '/../partials/user_navbar.php'; ?>


<style>
.back-button {
    display: inline-block;
    background: #e74c3c;
    color: white;
    padding: 8px 15px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.back-button:hover {
    background: #c0392b;
    transform: translateY(-2px);
}

a.product-link {

    color: #3498db;
    text-decoration: none;
}

a.product-link:hover {

    text-decoration: underline;
    color: #2c80b4;
}
</style>

<!-- Prevent browser from caching this page -->
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<?php
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>


<div class="container mt-5">
     
    
    

    

  

    <h2><?=Lang::get('Your_Cart')?></h2>
    <?php if (!empty($data['cart']) && is_array($data['cart'])): ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?=Lang::get('product')?></th>
                    <th><?=Lang::get('Quantity')?></th>
                    <th><?=Lang::get('price')?></th>
                    <th><?=Lang::get('sub_total')?></th>
                    <th><?=Lang::get('action')?></th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($data['cart'] as $item): ?>
                    <tr>
                        <td>


                            <a href="/QaviEcommerce/user/product_detail?id=<?= (int)$item['product_id'] ?>" 
                            style="text-decoration: none; color: #3498db;">
                            <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </td>

                        
                        <td>


                            <form method="POST" action="/QaviEcommerce/user/update_cart_quantity" style="display:inline-flex; align-items:center;" class="quantity-form">

                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                                <input type="hidden" name="product_id" value="<?= (int)$item['product_id'] ?>">

                                <button type="button" 

                                class="btn btn-sm btn-outline-secondary quantity-decrease" 
                                style="padding: 2px 8px;"
                                <?= ($item['quantity'] <= 0) ? 'disabled' : '' ?>>-</button>


                                <input type="number" 
                                name="quantity" 
                                value="<?= (int)$item['quantity'] ?>" 
                                min="0" 
                                max="<?= (int)($item['available_quantity'] ?? 1000) ?>" 
                                style="width: 50px; text-align:center; margin: 0 5px;"
                                class="quantity-input">

                                <button type="button" 

                                class="btn btn-sm btn-outline-secondary quantity-increase" 
                                style="padding: 2px 8px;"
                                <?= ($item['quantity'] >= ($item['available_quantity'] ?? 1000)) ? 'disabled' : '' ?>>+</button>
                            </form>
                        </td>
                        <td>₹<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <?php 
                                $subtotal = $item['price'] * $item['quantity']; 
                                echo '₹' . number_format($subtotal, 2);
                                $total += $subtotal; 
                            ?>
                        </td>
                        <td>
                            <a href="/QaviEcommerce/user/remove_from_cart/<?= (int)$item['product_id'] ?>?csrf_token=<?= urlencode($_SESSION['csrf_token']) ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to remove this item?')"
                            ><?=Lang::get('Remove')?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong><?=Lang::get('Total')?></strong></td>
                    <td>₹<?= number_format($total, 2) ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <a href="/QaviEcommerce/user/checkout" class="btn btn-primary"><?=Lang::get('Proceed_to_Checkout')?></a>
    <?php else: ?>
        <p><?=Lang::get('Yourcartisempty.')?></p>
    <?php endif; ?>
</div>

<!-- JavaScript to force reload on back button -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity changes
    document.querySelectorAll('.quantity-form').forEach(form => {
        const input = form.querySelector('.quantity-input');
        const decreaseBtn = form.querySelector('.quantity-decrease');
        const increaseBtn = form.querySelector('.quantity-increase');
        
        // Manual input change
        input.addEventListener('change', function() {
            form.submit();
        });
        
        // Decrease button
        decreaseBtn.addEventListener('click', function() {
            input.stepDown();
            form.submit();
        });
        
        // Increase button
        increaseBtn.addEventListener('click', function() {
            input.stepUp();
            form.submit();
        });
    });
    
    // Force reload on back button
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>

