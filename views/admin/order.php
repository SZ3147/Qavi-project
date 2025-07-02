<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

<style>
    .order-management {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .order-management h2 {
        font-weight: bold;
    }

    .order-table {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .order-table th,
    .order-table td {
        padding: 14px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
    }

    .order-table th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .order-status {
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 4px;
        display: inline-block;
        font-size: 13px;
        text-transform: capitalize;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-shipped {
        background-color: #cce5ff;
        color: #004085;
    }

    .status-delivered {
        background-color: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .order-table td:last-child {
        white-space: nowrap;
        vertical-align: middle;
    }

    .order-table td:last-child .btn {
        display: inline-block;
        vertical-align: middle;
        margin-right: 6px;
    }

    .order-table td:last-child .btn:last-child {
        margin-right: 0;
    }

    .action-btn {
        margin-right: 6px;
    }

    .filter-buttons .btn {
        margin-left: 5px;
    }

    .filter-buttons .btn.active {
        font-weight: bold;
        background-color: #e2e6ea;
    }

    .address-truncate {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
        vertical-align: bottom;
    }
</style>

<div class="container-fluid py-4 order-management">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-shopping-bag me-2"></i>Order Management</h2>
            <p class="text-muted mb-0">List of all customer orders</p>
        </div>
        <div class="filter-buttons">
            <a href="/QaviEcommerce/admin/manageOrders" class="btn btn-sm btn-outline-secondary <?= empty($_GET['status']) ? 'active' : '' ?>">All</a>
            <a href="/QaviEcommerce/admin/orders/Pending" class="btn btn-sm btn-outline-warning <?= ($_GET['status'] ?? '') === 'Pending' ? 'active' : '' ?>">Pending</a>
            <a href="/QaviEcommerce/admin/orders/Shipped" class="btn btn-sm btn-outline-primary <?= ($_GET['status'] ?? '') === 'Shipped' ? 'active' : '' ?>">Shipped</a>
            <a href="/QaviEcommerce/admin/orders/Delivered" class="btn btn-sm btn-outline-success <?= ($_GET['status'] ?? '') === 'Delivered' ? 'active' : '' ?>">Delivered</a>
            <a href="/QaviEcommerce/admin/orders/Cancelled" class="btn btn-sm btn-outline-danger <?= ($_GET['status'] ?? '') === 'Cancelled' ? 'active' : '' ?>">Cancelled</a>
        </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Address</th>
                    <th>TotalItems</th>
                    <th>TotalAmount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['orders'])): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">No orders found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['orders'] as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td>
                                <div class="customer-name"><?= htmlspecialchars($order['name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($order['email'] ?? '') ?></small>
                            </td>
                            <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <span class="address-truncate" title="<?= htmlspecialchars($order['address'] ?? 'No address provided') ?>">
                                    <?= !empty($order['address']) ? htmlspecialchars($order['address']) : 'No address' ?>
                                </span>
                            </td>
                            <td>
                                <?= count($order['items']) ?> item<?= count($order['items']) > 1 ? 's' : '' ?>
                            </td>
                            <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <span class="order-status status-<?= strtolower($order['status']) ?>">
                                    <?= $order['status'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#orderDetailsModal<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary action-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="/QaviEcommerce/admin/editOrder/<?= $order['id'] ?>" class="btn btn-sm btn-outline-secondary action-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="/QaviEcommerce/admin/deleteOrder/<?= $order['id'] ?>" class="btn btn-sm btn-outline-danger action-btn" onclick="return confirm('Are you sure you want to delete this order?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php foreach ($data['orders'] as $order): ?>
        <div class="modal fade" id="orderDetailsModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-sm rounded-3">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-receipt me-2"></i>Order #<?= $order['id'] ?> Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <h6 class="text-muted mb-1">Customer Info</h6>
                            <p class="mb-0"><strong><?= htmlspecialchars($order['name']) ?></strong> (<?= htmlspecialchars($order['email']) ?>)</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-1">Shipping Address</h6>
                            <p class="mb-0"><?= htmlspecialchars($order['address'] ?? 'N/A') ?></p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-1">Order Date</h6>
                            <p class="mb-0"><?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Items Ordered</h6>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($order['items'] as $item): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                            <small class="text-muted d-block">₹<?= number_format($item['price'], 2) ?> × <?= $item['quantity'] ?></small>
                                        </div>
                                        <span class="fw-semibold">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end align-items-center">
                            <h5 class="mb-0">Total: <span class="text-success">₹<?= number_format($order['total_amount'], 2) ?></span></h5>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <form method="POST" action="/QaviEcommerce/admin/editOrder/<?= $order['id'] ?>" class="d-flex align-items-center w-100">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <input type="hidden" name="current_filter" value="<?= $_GET['status'] ?? '' ?>">

                            <label class="me-2 fw-semibold mb-0">Status:</label>
                            <select name="status" class="form-select form-select-sm me-3" style="width: auto;">
                                <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="Cancelled" <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>

                            <button type="submit" class="btn btn-sm btn-primary me-2">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (isset($data['total_pages']) && $data['total_pages'] > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php
                $baseUrl = '/QaviEcommerce/admin/orders';
                if (!empty($_GET['status'])) {
                    $baseUrl .= '/' . urlencode($_GET['status']);
                }
                ?>
                <?php if ($data['current_page'] > 1): ?>
                    <li class="page-item"><a class="page-link" href="<?= $baseUrl ?>/page/1"><i class="fas fa-angle-double-left"></i></a></li>
                    <li class="page-item"><a class="page-link" href="<?= $baseUrl ?>/page/<?= $data['current_page'] - 1 ?>"><i class="fas fa-angle-left"></i></a></li>
                <?php endif; ?>

                <?php
                $start = max(1, $data['current_page'] - 2);
                $end = min($data['total_pages'], $data['current_page'] + 2);
                ?>

                <?php if ($start > 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?= $i == $data['current_page'] ? 'active' : '' ?>"><a class="page-link" href="<?= $baseUrl ?>/page/<?= $i ?>"><?= $i ?></a></li>
                <?php endfor; ?>

                <?php if ($end < $data['total_pages']): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>

                <?php if ($data['current_page'] < $data['total_pages']): ?>
                    <li class="page-item"><a class="page-link" href="<?= $baseUrl ?>/page/<?= $data['current_page'] + 1 ?>"><i class="fas fa-angle-right"></i></a></li>
                    <li class="page-item"><a class="page-link" href="<?= $baseUrl ?>/page/<?= $data['total_pages'] ?>"><i class="fas fa-angle-double-right"></i></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
