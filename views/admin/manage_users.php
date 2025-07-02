<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

<style>
    .user-management {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .user-table {
        width: 100%;
        border-collapse: collapse;
    }
    .user-table th, .user-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }
    .user-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .user-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    .status-disabled {
        background-color: #f8d7da;
        color: #721c24;
    }
    .pagination {
        margin-top: 20px;
    }
    .pagination .page-link {
        color: #007bff;
    }
    .pagination .active .page-link {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }
</style>

<div class="container-fluid py-4 user-management">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-users me-2"></i>User Management</h2>
            <p class="text-muted mb-0">Manage all registered users</p>
        </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['users'])): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">No users found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['users'] as $user): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($user['role'] ?? 'user')) ?></td>
                        <td>
                            <?php
                            if (!empty($user['created_at']) && strtotime($user['created_at']) !== false) {
                                echo date('M j, Y', strtotime($user['created_at']));
                            } else {
                                echo 'Invalid date';
                            }
                            ?>
                        </td>
                        <td>
                            <?php $status = isset($user['status']) ? (int)$user['status'] : 0; ?>
                            <span class="user-status status-<?= $status ? 'active' : 'disabled' ?>">
                                <?= $status ? 'Active' : 'Disabled' ?>
                            </span>
                        </td>
                        <td>
                            <a href="/QaviEcommerce/admin/toggleUserStatus/<?= htmlspecialchars($user['id']) ?>/<?= $status ? 0 : 1 ?>"
                               class="btn btn-sm <?= $status ? 'btn-warning' : 'btn-success' ?>">
                               <?= $status ? 'Disable' : 'Enable' ?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($data['total_pages']) && $data['total_pages'] > 1): ?>
    <nav class="pagination justify-content-center">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                <li class="page-item <?= $i == $data['current_page'] ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<!-- 
<?php include __DIR__ . '/../partials/footer.php'; ?> -->

