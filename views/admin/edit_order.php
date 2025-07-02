<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

<style>
    .order-edit-form {
        max-width: 800px;
        margin: 0 auto;
    }
    .order-items-table th {
        background-color: #f8f9fa;
    }
    .order-summary {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
</style>

<div class="container py-4 order-edit-form">
    <h2 class="mb-4">
        <i class="fas fa-edit me-2"></i>
        Edit Order #<?= htmlspecialchars($data['order']['id'] ?? 'N/A') ?>
    </h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($data['order'])): ?>
        <form method="POST" action="/QaviEcommerce/admin/updateOrder">
            <input type="hidden" name="order_id" value="<?= htmlspecialchars($data['order']['id']) ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($data['order']['user_id']) ?>">

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?= htmlspecialchars($data['order']['name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" 
                                   value="<?= htmlspecialchars($data['order']['email'] ?? '') ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" 
                                   value="<?= htmlspecialchars($data['order']['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Order Date</label>
                            <input type="text" class="form-control" 
                                   value="<?= date('F j, Y g:i A', strtotime($data['order']['created_at'])) ?>" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table order-items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['order']['items'] as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/public/uploads/<?= htmlspecialchars($item['image']) ?>" width="40" height="40" class="me-2" style="object-fit: cover; border-radius: 4px;">
                                            <?= htmlspecialchars($item['product_name']) ?>
                                        </div>
                                    </td>
                                    <td>₹<?= number_format($item['price'], 2) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="order-summary mt-3">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Subtotal:</span>
                                    <span>₹<?= number_format($data['order']['total_amount'], 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Shipping:</span>
                                    <span>₹0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Tax:</span>
                                    <span>₹0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span class="text-primary">₹<?= number_format($data['order']['total_amount'], 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order Status & Shipping</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <input type="text" class="form-control" 
                                   value="<?= htmlspecialchars($data['order']['payment_method'] ?? 'Cash on Delivery') ?>" 
                                   readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Pending" <?= ($data['order']['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Shipped" <?= ($data['order']['status'] ?? '') === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="Delivered" <?= ($data['order']['status'] ?? '') === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="Cancelled" <?= ($data['order']['status'] ?? '') === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="mt-4 mb-3">Shipping Address</h6>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control"
                                   value="<?= htmlspecialchars($data['order']['address'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control"
                                   value="<?= htmlspecialchars($data['order']['city'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control"
                                   value="<?= htmlspecialchars($data['order']['state'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ZIP Code</label>
                            <input type="text" name="zip" class="form-control"
                                   value="<?= htmlspecialchars($data['order']['zip'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="/QaviEcommerce/admin/manageOrders" class="btn btn-secondary">Back to Orders</a>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Order data not available.</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
