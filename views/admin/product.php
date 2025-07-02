<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

<style>
    .product-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: none;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .product-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 1rem;
    }
    .product-actions .btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 3px;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        border-radius: 50rem;
    }
    .search-box {
        position: relative;
        max-width: 300px;
    }
    .search-box input {
        padding-left: 2.5rem;
        border-radius: 50rem;
    }
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    .empty-state {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 3rem;
        text-align: center;
    }
    .empty-state-icon {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 1rem;
    }
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
    }
    .product-name {
        font-weight: 600;
        color: #495057;
    }
    .category-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .price-display {
        font-weight: 700;
        color: #28a745;
        font-size: 1.1rem;
    }
    .quantity-badge {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .alert {

        font-size: 0.9rem;  /* Slightly larger than default */
        padding: 0.75rem 1.25rem;
        border-radius: 0.375rem;
        max-width: 100%;
        margin: 0 auto 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        position: relative;
        border: 1px solid transparent;
    }

/* Make alerts responsive and fit within container */
    @media (min-width: 768px) {

        .alert {

            max-width: 80%;
        }
    }

/* Alert close button styling */
.alert .btn-close {
    padding: 0.5rem 0.5rem;
    background-size: 0.75rem;
}

/* Success alert specific styling */
.alert-success {
    background-color: #d1e7dd;
    border-color: #badbcc;
    color: #0f5132;
}

/* Danger alert specific styling */
.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c2c7;
    color: #842029;
}

.alert-container {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1050;
    width: 100%;
    max-width: 600px;
}

    
</style>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert-container">
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div><?= htmlspecialchars($_SESSION['error_message']) ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert-container">
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div><?= htmlspecialchars($_SESSION['success_message']) ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Product Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/QaviEcommerce/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/QaviEcommerce/admin/addProductPage" class="btn btn-success">
                <i class="fas fa-plus-circle me-2"></i> Add New Product
            </a>
        </div>
    </div>

    <div class="card product-card mb-4">
        <div class="card-header product-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Products</h5>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="Search products..." id="productSearch">
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($data['products'])): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="productsTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Product Details</th>
                                <th>Category</th>
                                <th width="100">Price</th>
                                <th width="80">Image</th>
                                <th width="100">Quantity</th>
                                <th width="120">Status</th>
                                <th width="280" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['products'] as $index => $product): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 product-name"><?= htmlspecialchars($product['name']) ?></h6>
                                                <small class="text-muted">ID: <?= $product['id'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="category-info">
                                            <span class="fw-medium"><?= htmlspecialchars($product['category_name']) ?></span>
                                            <?php if ($product['category_status'] == 0): ?>
                                                <span class="status-badge bg-warning text-dark">Category Disabled</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="price-display">$<?= number_format($product['price'], 2) ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center product-image">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="quantity-badge"><?= htmlspecialchars($product['quantity']) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($product['status'] == 1): ?>
                                            <span class="status-badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="status-badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center flex-wrap gap-1">

                                            <a href="/QaviEcommerce/admin/viewProduct/<?= $product['id'] ?>" class="btn btn-sm btn-info" title="View <?= $product['status'] ? '' : '(Inactive Product)' ?>"><i class="fas fa-eye<?= $product['status'] ? '' : '-slash' ?>"></i></a>

                                            <a href="/QaviEcommerce/admin/editProductPage/<?= $product['id'] ?>" class="btn btn-sm btn-warning" title="Edit <?= $product['status'] ? '' : '(Inactive Product)' ?>"><i class="fas fa-edit"></i></a>

                                            <a href="/QaviEcommerce/admin/deleteProduct/<?= $product['id'] ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>

                                            <?php if ($product['status'] == 1): ?>

                                                <a href="/QaviEcommerce/admin/toggleStatus/<?= $product['id'] ?>/0" class="btn btn-sm btn-secondary" title="Disable"><i class="fas fa-toggle-on"></i></a>
                                            <?php else: ?>

                                                <a href="/QaviEcommerce/admin/toggleStatus/<?= $product['id'] ?>/1" class="btn btn-sm btn-success" title="Enable"><i class="fas fa-toggle-off"></i></a>
                                            <?php endif; ?>

                                            <?php if ($product['category_status'] == 0): ?>

                                                <span class="status-badge bg-warning text-dark">Category Disabled</span>

                                            <?php endif; ?>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h4>No Products Found</h4>
                    <p class="text-muted">You haven't added any products yet. Get started by adding your first product.</p>
                    <a href="/admin/product/add" class="btn btn-success mt-3">
                        <i class="fas fa-plus-circle me-2"></i> Create Product
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($data['products'])): ?>
            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing <strong><?= count($data['products']) ?></strong> of <strong><?= count($data['products']) ?></strong> products
                </div>
                <!-- Replace your current pagination section with this: -->
<div class="card-footer bg-light d-flex justify-content-between align-items-center">
    <div class="text-muted small">
        Showing <strong><?= count($data['products']) ?></strong> of <strong><?= $data['total_products'] ?></strong> products
    </div>
    <nav>
        <ul class="pagination mb-0">
            <?php
            // Get all query parameters except 'page'
            $queryParams = $_GET;
            unset($queryParams['page']);
            $queryString = http_build_query($queryParams);
            ?>
            
            <!-- Previous Page Link -->
            <?php if ($data['current_page'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?<?= $queryString ?>&page=<?= $data['current_page'] - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&laquo;</span>
                </li>
            <?php endif; ?>
            
            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                <li class="page-item <?= ($i == $data['current_page']) ? 'active' : '' ?>">
                    <a class="page-link" href="/QaviEcommerce/admin/products/<?= $i ?>"><?= $i ?></a>

                </li>
            <?php endfor; ?>
            
            <!-- Next Page Link -->
            <?php if ($data['current_page'] < $data['total_pages']): ?>
                <li class="page-item">
                    <a class="page-link" href="?<?= $queryString ?>&page=<?= $data['current_page'] + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&raquo;</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
// Product search functionality
document.getElementById('productSearch').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#productsTable tbody tr');
    
    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const price = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        
        if (name.includes(searchValue) || category.includes(searchValue) || price.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Confirmation for delete action
document.querySelectorAll('.btn-danger').forEach(button => {
    button.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
});

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

<!-- <?php include __DIR__ . '/../partials/footer.php'; ?> -->