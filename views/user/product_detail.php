

<?php 
include __DIR__ . '/../partials/user_header.php'; 
include __DIR__ . '/../partials/user_navbar.php'; 

$_SESSION['last_viewed_product'] = $product['id'] ?? null;

// Assign product safely
$product = $product ?? ($data['product'] ?? null);

if (!$product) {
    echo '<div class="container mt-5"><h3>Product not found.</h3></div>';
    include __DIR__ . '/../partials/footer.php';
    exit;
}
?>

<style>
    .product-detail-container {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        margin-bottom: 50px;
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .product-img-box {
        background: #fff;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        flex: 1 1 350px;
        max-width: 450px;
        height: 100%;
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
        display: block;
    }

    .product-info-box {
        flex: 1 1 350px;
        max-width: 600px;
        max-height: 500px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .product-title {
        font-size: 2.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .product-price {
        font-size: 1.8rem;
        color: #28a745;
        font-weight: 600;
        margin-top: 10px;
        margin-bottom: 1rem;
    }

    .product-description {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #555;
        margin-bottom: 1.2rem;
        white-space: pre-wrap;
    }

    .quantity-input {
        width: 100px;
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #38ef7d, #11998e);
        border: none;
        color: white;
        font-weight: 500;
        padding: 10px 25px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .btn-add-cart:hover {
        box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
        transform: translateY(-2px);
    }

    .product-info-box::-webkit-scrollbar {
        width: 8px;
    }
    .product-info-box::-webkit-scrollbar-thumb {
        background-color: rgba(40, 167, 69, 0.5);
        border-radius: 10px;
    }
    .product-info-box::-webkit-scrollbar-track {
        background-color: #f1f1f1;
        border-radius: 10px;
    }
</style>

<div class="container ">
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="product-detail-container">
        <div class="product-img-box">
            <img 
                src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" 
                class="img-fluid product-img" 
                alt="<?= htmlspecialchars($product['name']) ?>"
                onerror="this.onerror=null; this.src='/QaviEcommerce/public/uploads/default.jpg'">
        </div>
        <div class="product-info-box">
            <h2 class="product-title"><?= htmlspecialchars($product['name']) ?></h2>
            <p class="product-price"><strong><?=Lang::get('price')?></strong> ₹<?= htmlspecialchars($product['price']) ?></p>
            <p class="product-description"><strong><?=Lang::get('Description')?></strong> <?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <p><strong><?=Lang::get('Available_Stock')?></strong> <?= htmlspecialchars($product['available_quantity']) ?></p>

            <form id="add-to-cart-form" method="POST" action="/QaviEcommerce/user/add_to_cart?id=<?= $product['id'] ?>">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="mb-3">
                    <label for="quantity" class="form-label"><?=Lang::get('Quantity')?></label>
                    <input 
                        type="number" 
                        id="quantity" 
                        name="quantity" 
                        class="form-control quantity-input" 
                        value="1" 
                        min="1" 
                        max="<?= (int)$product['available_quantity'] ?>" 
                        required
                        <?= $product['available_quantity'] <= 0 ? 'disabled' : '' ?>
                    >
                </div>
                <div class="mb-3 text-danger" id="stock-message" style="display: none;"></div>

                <?php if (!isset($_SESSION['user_id'])): ?>

                    <?php 

        // Save current action to redirect after login
                    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
                    ?>
                    <div class="text-danger mb-2">

                        <?=Lang::get('Please')?> <a href="/QaviEcommerce/user/login"><?=Lang::get('login')?></a> <?=Lang::get('to_add_items_to_your_cart')?>
                    </div>
                <?php endif; ?>


                <button 
                    type="submit" 
                    class="btn btn-add-cart" 
                    <?= !isset($_SESSION['user_id']) || $product['available_quantity'] <= 0 ? 'disabled' : '' ?>
                >
                    <?=Lang::get('Add_to_Cart')?>
                </button>

                
            </form>
        </div>
    </div>
</div>

<script>
    const quantityInput = document.getElementById('quantity');
    const stockMessage = document.getElementById('stock-message');
    const availableQty = <?= (int)$product['available_quantity'] ?>;

    quantityInput?.addEventListener('input', () => {
        let requestedQty = parseInt(quantityInput.value);
        if (requestedQty > availableQty) {
            quantityInput.value = availableQty;
            stockMessage.textContent = `Only ${availableQty} item${availableQty !== 1 ? 's' : ''} available in stock. Quantity adjusted.`;
            stockMessage.style.display = 'block';
        } else {
            stockMessage.style.display = 'none';
        }
    });

    document.getElementById('add-to-cart-form').addEventListener('submit', async function(e) {
        <?php if (!isset($_SESSION['user_id'])): ?>
            e.preventDefault();
            window.location.href = '/QaviEcommerce/user/login';
            return;
        <?php endif; ?>

        e.preventDefault();

        const form = this;
        const productId = <?= $product['id'] ?>;
        const requestedQty = parseInt(quantityInput.value);
        const submitBtn = form.querySelector('button[type="submit"]');

        submitBtn.disabled = true;
        submitBtn.textContent = 'Checking...';

        try {
            const response = await fetch(`/QaviEcommerce/user/checkStock?product_id=${productId}&requested_qty=${requestedQty}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'include'
            });

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Expected JSON, got: ${text.substring(0, 100)}...`);
            }

            const data = await response.json();

            if (!response.ok || data.status === 'error') {
                stockMessage.textContent = data.message || 'Stock check failed.';
                stockMessage.style.display = 'block';
            } else {
                form.submit();
            }
        } catch (error) {
            console.error('Error:', error);
            stockMessage.textContent = `Error: ${error.message}`;
            stockMessage.style.display = 'block';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add to Cart';
        }
    });
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
