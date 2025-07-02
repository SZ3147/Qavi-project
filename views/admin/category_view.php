<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2>Product Details</h2>
            <a href="/QaviEcommerce/admin/products" class="btn btn-secondary float-end">Back to Products</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?php if (!empty($product['image'])): ?>
                        <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" 
                             class="img-fluid rounded" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php else: ?>
                        <div class="bg-light p-5 text-center">
                            <span class="text-muted">No image available</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="text-muted">Category: <?= htmlspecialchars($product['category_name']) ?></p>
                    <hr>
                    <h4 class="text-primary">$<?= number_format($product['price'], 2) ?></h4>
                    <p><strong>Stock:</strong> <?= $product['stock'] ?? 'N/A' ?></p>
                    <p><strong>Description:</strong></p>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <p><strong>Created At:</strong> <?= date('M d, Y h:i A', strtotime($product['created_at'])) ?></p>
                    <p><strong>Updated At:</strong> <?= date('M d, Y h:i A', strtotime($product['updated_at'])) ?></p>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="/QaviEcommerce/admin/editProductPage/<?= $product['id'] ?>" 
               class="btn btn-primary">Edit Product</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
