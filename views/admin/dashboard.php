<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

<!-- Main Content -->
<div id="main-content">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
            <div>
                <button class="btn btn-sm btn-outline-secondary mr-2">
                    <i class="fas fa-calendar-alt"></i> This Month
                </button>
                <button class="btn btn-sm btn-primary">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>
        </div>

        <!-- Summary Boxes Row 1: Orders + Categories -->
        <div class="row mb-4">
            <!-- Total Orders (first, as requested) -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-info font-weight-bold">Total Orders</h6>
                        <h3><?= $totalOrders ?></h3>
                        <small class="text-muted">All orders placed</small>
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-primary font-weight-bold">Pending Orders</h6>
                        <h3><?= $pendingOrders ?></h3>
                        <small class="text-muted">Orders waiting for processing</small>
                    </div>
                </div>
            </div>

            <!-- Success Orders -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-success font-weight-bold">Success Orders</h6>
                        <h3><?= $successOrders ?></h3>
                        <small class="text-muted">Completed orders</small>
                    </div>
                </div>
            </div>

            <!-- Cancelled Orders -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-danger font-weight-bold">Cancelled Orders</h6>
                        <h3><?= $cancelledOrders ?></h3>
                        <small class="text-muted">Cancelled orders</small>
                    </div>
                </div>
            </div>
        </div>

       
        <div class="row mb-4">
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-warning font-weight-bold">Total Categories</h6>
                        <h3><?= $categoryCount ?></h3>
                        <small class="text-muted">Available product categories</small>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-warning font-weight-bold">Total Products</h6>
                        <h3><?= $productCount ?></h3>
                        <small class="text-muted">Active products in inventory</small>
                    </div>
                </div>
            </div>

            <!-- Revenue Generated -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-danger font-weight-bold">Revenue Generated</h6>
                        <h3>$<?= number_format($totalRevenue, 2) ?></h3>
                        <small class="text-muted">Total money from orders</small>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-secondary font-weight-bold">Total Users</h6>
                        <h3><?= $totalUsers ?></h3>
                        <small class="text-muted">Registered users</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topSelling as $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product['name']) ?></td>
                                            <td>$<?= number_format($product['price'], 2) ?></td>
                                            <td><?= $product['total_quantity'] ?></td>
                                            <td>$<?= number_format($product['total_amount'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($topSelling)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">No data available.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
