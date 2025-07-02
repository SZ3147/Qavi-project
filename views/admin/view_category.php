<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<div class="container mt-4">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Category Details</h4>
            <a href="/QaviEcommerce/admin/manageCategories" class="btn btn-outline-light btn-sm">
                ← Back
            </a>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5><strong>Name:</strong> <?= htmlspecialchars($data['category']['name']) ?></h5>
                    <p><strong>Description:</strong> <?= htmlspecialchars($data['category']['description']) ?></p>
                </div>
                <div class="col-md-4 text-md-end">
                    <p><strong>Status:</strong> 
                        <span class="badge bg-success">Enabled</span>
                    </p>
                </div>
            </div>
            <div class="mt-3">
                <a href="/QaviEcommerce/admin/editCategory/<?= $data['category']['id'] ?>" class="btn btn-primary btn-sm me-2">Edit</a>

                <a href="/QaviEcommerce/admin/addProductPage/<?= $data['category']['id'] ?>" class="btn btn-success btn-sm me-2">Add Product</a>

                <a href="/QaviEcommerce/admin/toggleCategoryStatus/<?= $data['category']['id'] ?>/0" 
                   class="btn btn-warning btn-sm me-2" 
                   onclick="return confirm('Are you sure you want to disable this category?');">
                   Disable Category
                </a>

                <a href="/QaviEcommerce/admin/deleteCategory/<?= $data['category']['id'] ?>"
                   class="btn btn-danger btn-sm" 
                   onclick="return confirm('Are you sure you want to delete this category?');">
                   Delete
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Products in this Category</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($data['products'])): ?>
                <div class="row g-4">
                    <?php foreach ($data['products'] as $product): ?>
                        <div class="col-md-4 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <a href="/admin/product/view/<?= $product['id'] ?>" style="text-decoration: none;">
                                    <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" 
                                         class="card-img-top" style="height: 200px; object-fit: cover;" alt="Product Image">
                                </a>
                                <div class="card-body">
                                    <h6 class="card-title mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($product['description']) ?></p>
                                    <p class="text-success fw-bold">₹<?= number_format($product['price'], 2) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination Controls -->
                <?php if ($data['total_pages'] > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($data['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?id=<?= $data['category']['id'] ?>&page=<?= $data['current_page'] - 1 ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                                <li class="page-item <?= $data['current_page'] == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="?id=<?= $data['category']['id'] ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($data['current_page'] < $data['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?id=<?= $data['category']['id'] ?>&page=<?= $data['current_page'] + 1 ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            <?php else: ?>
                <p class="text-muted">No products found in this category.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
