<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

<style>
    .category-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: none;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .category-header {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        padding: 1rem;
    }
    .category-actions .btn {
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
</style>

<div class="container-fluid py-4">

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['info_message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['info_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['info_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Category Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/QaviEcommerce/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Categories</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/QaviEcommerce/admin/addCategoryForm" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Add New Category
            </a>
        </div>
    </div>

    <div class="card category-card mb-4">
        <div class="card-header category-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Categories</h5>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="Search categories..." id="categorySearch">
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($data['categories'])): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="categoriesTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th width="120">Status</th>
                                <th width="220" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['categories'] as $index => $category): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <?php if (!empty($category['image'])): ?>
                                                    <img src="/public/uploads/<?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['name']) ?>" class="rounded-circle" width="40" height="40">
                                                <?php else: ?>
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-folder text-white"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0"><?= htmlspecialchars($category['name']) ?></h6>
                                                <small class="text-muted">ID: <?= $category['id'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($category['description']) ?>">
                                            <?= htmlspecialchars($category['description']) ?>
                                        </p>
                                    </td>
                                    <td>
                                        <?php if ($category['status'] == 1): ?>
                                            <span class="status-badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="status-badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">


                                        <div class="d-flex justify-content-center flex-wrap gap-1">


                                        
                                            <a href="/QaviEcommerce/admin/viewCategory/<?= $category['id'] ?>" class="btn btn-sm btn-info" title="View">

                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/QaviEcommerce/admin/editCategory/<?= $category['id'] ?>" class="btn btn-sm btn-warning" title="Edit">

                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/QaviEcommerce/admin/addProductPage/<?= $category['id'] ?>" class="btn btn-sm btn-success" title="Add Product">

                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <a href="/QaviEcommerce/admin/deleteCategory/<?= $category['id'] ?>" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                            <?php if ($category['status'] == 1): ?>

                                                <a href="/QaviEcommerce/admin/toggleCategoryStatus/<?= $category['id'] ?>/0" class="btn btn-sm btn-secondary" title="Disable">

                                                    <i class="fas fa-toggle-on"></i>
                                                </a>
                                            <?php else: ?>

                                                <a href="/QaviEcommerce/admin/toggleCategoryStatus/<?= $category['id'] ?>/1" class="btn btn-sm btn-success" title="Enable">

                                                    <i class="fas fa-toggle-off"></i>
                                                </a>
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
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4>No Categories Found</h4>
                    <p class="text-muted">You haven't created any categories yet. Get started by adding your first category.</p>
                    <a href="/QaviEcommerce/admin/addCategoryForm" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-2"></i> Create Category
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($data['categories'])): ?>

            <div class="card-footer bg-light d-flex justify-content-between align-items-center">

                <div class="text-muted small">

                    Showing <strong><?= count($data['categories']) ?></strong> of <strong><?= $data['total_categories'] ?></strong> categories

                </div>
                <nav>

                    <ul class="pagination mb-0">

                        <?php
                        // Get all query parameters except 'page'
                        $queryParams = $_GET;
                        unset($queryParams['page']);
                        $queryString = http_build_query($queryParams);
                        ?>
                
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
                
                        <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                            <li class="page-item <?= ($i == $data['current_page']) ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= $queryString ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                
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
        <?php endif; ?>

<script>
// Category search functionality
document.getElementById('categorySearch').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#categoriesTable tbody tr');
    
    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const description = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        if (name.includes(searchValue) || description.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});


</script>