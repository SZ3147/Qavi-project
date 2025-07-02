<?php include __DIR__ . '/../partials/user_header.php'; ?>
<?php include __DIR__ . '/../partials/user_navbar.php'; ?>

<div class="container my-5" style="max-width: 1000px; font-family: 'Segoe UI', sans-serif;">
    <h2 class="mb-4 fw-bold"><?=Lang::get('Your_Orders')?></h2>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info"><?=Lang::get('You_havenot_placed_any_orders_yet.')?></div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <!-- Order Header -->
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-3 border-bottom pb-3">
                        <div>
                            <h5 class="mb-1"><?=Lang::get('Order_no')?><?= htmlspecialchars($order['id']) ?></h5>
                            <div class="text-muted small"><?=Lang::get('Placed_on')?> <?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></div>
                        </div>
                        <div>
                            <?php
                                $status = strtoupper($order['status']);
                                $badgeClass = match ($status) {
                                    'PENDING'   => 'warning',
                                    'SHIPPED'   => 'primary',
                                    'DELIVERED' => 'success',
                                    default     => 'secondary',
                                };
                                $translatedStatus = Lang::get($status);
                            ?>
                            <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($translatedStatus) ?></span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><?=Lang::get('product')?></th>
                                    <th class="text-end"><?=Lang::get('price')?></th>
                                    <th class="text-center"><?=Lang::get('Quantity')?></th>
                                    <th class="text-end"><?=Lang::get('sub_total')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/public/uploads/<?= htmlspecialchars($item['image']) ?>" onerror="this.onerror=null; this.src='/QaviEcommerce/public/uploads/default.jpg'" alt="<?= htmlspecialchars($item['product_name']) ?>" class="me-3 rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                <span><?= htmlspecialchars($item['product_name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-end">₹<?= number_format($item['price'], 2) ?></td>
                                        <td class="text-center"><?= (int)$item['quantity'] ?></td>
                                        <td class="text-end fw-semibold">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Summary -->
                    <div class="row border-top pt-3 mt-3">
                        <div class="col-md-7 mb-3">
                            <h6 class="fw-semibold"><?=Lang::get('Shipping_Address')?></h6>
                            <p class="mb-1"><?= nl2br(htmlspecialchars($order['address'])) ?></p>
                            <p class="mb-1"><?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> - <?= htmlspecialchars($order['zip']) ?></p>
                            <p class="mb-0"><?=Lang::get('Phone')?>: <?= htmlspecialchars($order['phone']) ?></p>
                        </div>
                        <div class="col-md-5 text-end">
                            <p class="mb-2"><strong><?=Lang::get('Payment_Method:')?></strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                            <p class="fs-5 fw-bold"><?=Lang::get('Total')?> ₹<?= number_format($order['total_amount'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Enhanced Pagination -->
        <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <nav class="d-flex justify-content-center mt-4">
                <ul class="pagination">
                    <!-- First Page Link -->
                    <?php if ($pagination['current_page'] > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=1" aria-label="First">
                                <span aria-hidden="true">&laquo;&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Previous Page Link -->
                    <?php if ($pagination['current_page'] > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php 
                    $start = max(1, $pagination['current_page'] - 2);
                    $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                    
                    for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= ($i == $pagination['current_page']) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Page Link -->
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Last Page Link -->
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pagination['total_pages'] ?>" aria-label="Last">
                                <span aria-hidden="true">&raquo;&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="text-center text-muted small mt-2">
                Showing page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?> 
                (<?= $pagination['total_orders'] ?> total orders)
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>