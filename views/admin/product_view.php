<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

<div class="container mt-5">
    <h2>Product Details</h2>
    <a href="/QaviEcommerce/admin/products" class="btn btn-secondary mb-3">← Back to Products</a>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td><?= $product['id'] ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?= htmlspecialchars($product['name']) ?></td>
        </tr>
        <tr>
            <th>Category</th>
            <td><?= htmlspecialchars($product['category_name']) ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></td>
        </tr>
        <tr>
            <th>Quantity</th>
            <td><?= htmlspecialchars($product['quantity']) ?></td>
        </tr>
        <tr>
            <th>Price</th>
            <td>$<?= number_format($product['price'], 2) ?></td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
                <?php if (!empty($product['image'])): ?>
                    <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" width="150">
                <?php else: ?>
                    <span class="text-muted">No image available</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
