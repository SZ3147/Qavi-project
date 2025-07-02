<?php include __DIR__ . '/../partials/user_header.php'; ?>
<?php include __DIR__ . '/../partials/user_navbar.php'; ?>

<style>
    html, body {
        overflow-x: hidden;
    }

    .category-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 3rem 1rem;
        margin-bottom: 2rem;
        border-radius: 12px;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    .layout-wrapper {
        display: flex;
        flex-wrap: nowrap;
        width: 100%;
    }

    .side-nav-wrapper {
        width: 280px;
        flex-shrink: 0;
        background: #fff;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        padding: 20px 0;
        position: sticky;
        top: 80px;
        height: calc(100vh - 80px);
        overflow-y: auto;
        z-index: 100;
    }

    .main-content-container {
        flex-grow: 1;
        padding-left: 20px;
        padding-right: 20px;
        min-width: 0;
    }

    @media (max-width: 992px) {
        .layout-wrapper {
            flex-direction: column;
        }

        .side-nav-wrapper {
            width: 100%;
            height: auto;
            position: static;
            margin-bottom: 20px;
        }

        .main-content-container {
            padding: 0 15px;
        }
    }

    /* --- Keep your original styles below --- */
    .side-nav-header {
        padding: 0 20px 15px;
        border-bottom: 1px solid #eee;
        margin-bottom: 15px;
    }

    .side-nav-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .nav-category {
        padding: 8px 20px;
        display: block;
        color: #34495e;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .nav-category:hover,
    .nav-category.active {
        background: #f8f9fa;
        color: #e74c3c;
        border-left: 3px solid #e74c3c;
    }

    .nav-category i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    /* Responsive Adjustments for Side Nav */
    @media (max-width: 992px) {
        .side-nav {
            width: 100%;
            height: auto;
            position: static;
            margin-bottom: 20px;
        }
    }

    .category-description {
        background: rgba(255,255,255,0.15);
        padding: 1rem;
        border-radius: 10px;
        margin-top: 1rem;
        font-size: 1.1rem;
    }

    /* Product card styling */
    .product-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: 0.3s ease-in-out;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        background: #fff;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    /* Make image clickable by styling anchor */
    .product-img-link {
        display: block;
        overflow: hidden;
        border-bottom: 1px solid #eee;
        border-radius: 15px 15px 0 0;
    }

    .product-img {
        height: 220px;
        object-fit: cover;
        width: 100%;
        display: block;
        transition: transform 0.3s ease;
    }

    .product-img-link:hover .product-img {
        transform: scale(1.05);
    }

    .card-body {
        padding: 1rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-text {
        font-size: 0.95rem;
        color: #6c757d;
        flex-grow: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        margin-bottom: 1rem;
    }

    .price-tag {
        font-weight: bold;
        font-size: 1.2rem;
        color: #28a745;
    }

    .view-btn {
        background: linear-gradient(to right, #4a00e0, #8e2de2);
        border: none;
        color: white;
        padding: 8px 20px;
        font-size: 0.9rem;
        border-radius: 25px;
        transition: 0.3s ease-in-out;
        text-align: center;
        white-space: nowrap;
        flex-shrink: 0;
        text-decoration: none;
        display: inline-block;
    }

    .view-btn:hover {
        box-shadow: 0 4px 10px rgba(142, 45, 226, 0.4);
        transform: translateY(-2px);
        color: white;
    }

    /* Empty category */
    .empty-category {
        text-align: center;
        padding: 60px 15px;
    }

    .empty-category img {
        max-width: 250px;
        margin-bottom: 20px;
    }

    .empty-category h4 {
        font-size: 1.3rem;
        color: #555;
    }

    .empty-category .btn {
        margin-top: 15px;
        padding: 10px 25px;
        border-radius: 30px;
    }

    /* Search and filter section */
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin: 30px 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .filter-search-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 100%;
    }

    /* Search box */
    .search-box {
        position: relative;
        margin-left: auto;
    }

    .search-box input {
        width: 250px;
        padding: 10px 20px 10px 45px;
        font-size: 16px;
        border-radius: 30px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #667eea;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 18px;
    }

    /* Price Filter Form */
    .price-filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .price-filter-form label {
        font-weight: 600;
        white-space: nowrap;
        margin-bottom: 0;
    }

    .price-filter-form input[type="range"] {
        width: 150px;
        cursor: pointer;
    }

    .price-filter-form input[type="number"] {
        width: 80px;
        padding: 8px 10px;
        border-radius: 30px;
        border: 1px solid #ddd;
        font-size: 1rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        text-align: center;
    }

    .price-filter-form button {
        padding: 8px 20px;
        border-radius: 30px;
        border: none;
        background: linear-gradient(to right, #4a00e0, #8e2de2);
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s ease-in-out;
    }

    .price-filter-form button:hover {
        box-shadow: 0 4px 10px rgba(142, 45, 226, 0.4);
        transform: translateY(-2px);
    }

    /* Responsive fixes */
    @media (max-width: 768px) {
        .filter-search-wrapper {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }
        
        .price-filter-form, .search-box {
            width: 100%;
            margin: 0;
        }
        
        .search-box input {
            width: 100%;
        }
        
        .price-filter-form {
            flex-wrap: wrap;
        }
        
        .price-filter-form input[type="range"] {
            flex-grow: 1;
        }
    }

    /* No products found message */
    #noProductsMessage {
        display: none;
        text-align: center;
        margin: 30px 0;
        font-size: 1.2rem;
        color: #777;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    /* Main content container */
    .main-content-container {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    /* Product grid spacing */
    .product-grid-container {
        margin-top: 20px;
    }

    
</style>

<div class="container-fluid">
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <div class="side-nav-wrapper">
            <?php
            $categories = $data['categories'] ?? [];
            include __DIR__ . '/../partials/user_sidebar.php';
            ?>
        </div>

        <!-- Main Content -->
        <div class="main-content-container">
            <!-- Category Header -->
            <div class="category-header text-center">
                <h1 class="display-5 mb-0"><?= htmlspecialchars($data['category_name']) ?></h1>
                <?php if (!empty($data['category_description'])): ?>
                    <div class="category-description d-inline-block">
                        <?= nl2br(htmlspecialchars($data['category_description'])) ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($data['products'])): ?>
                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-search-wrapper">

                        <form method="GET" action="/QaviEcommerce/user/category" class="filter-search-wrapper" id="combinedFilterForm">

                            <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>" />


    <!-- Price Filter -->
                            <div class="price-filter-form">

                                <label for="maxPriceRange"><?=Lang::get('Max_Price')?></label>

                                <input type="range" id="maxPriceRange" name="max_price" min="0" max="10000" step="10"
                                value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '10000' ?>" />
                                <input type="number" id="maxPriceInput" name="max_price" min="0" max="10000" step="10"
                                value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '10000' ?>" />
                            </div>
                            <div class="search-box">

                                <i class="fas fa-search"></i>

                                <input 

                                type="text" 
                                name="search" 
                                id="productSearch"
                                placeholder="<?= Lang::get('Search_products') ?>"
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                                >
                            </div>


    <!-- Search -->
                            
                            

    <!-- Filter Button -->
                            <button type="submit"><?=Lang::get('Filter')?></button>

    <!-- Clear Button -->
                            <?php if (isset($_GET['max_price']) || isset($_GET['search'])): ?>

                                <a href="/QaviEcommerce/user/category?id=<?= htmlspecialchars($_GET['id'] ?? '') ?>"

                                class="btn btn-secondary" style="margin-left: 10px; padding: 8px 15px; border-radius: 30px; background:#6c757d; color:#fff; text-decoration:none;">
                                <?=Lang::get('Clear')?>
                                </a>
                            <?php endif; ?>
                        </form>

                    </div>
                </div>

                <!-- Product Grid -->
                <div class="product-grid-container">
                    <div class="row" id="productGrid">
                        <?php foreach ($data['products'] as $product): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 product-item" 
                                 data-name="<?= htmlspecialchars(strtolower($product['name'])) ?>" 
                                 data-price="<?= htmlspecialchars($product['price']) ?>">
                                <div class="product-card">
                                    <a href="/QaviEcommerce/user/product_detail?id=<?= (int)$product['id'] ?>" class="product-img-link" title="<?= htmlspecialchars($product['name']) ?>">
                                        <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title" title="<?= htmlspecialchars($product['name']) ?>">
                                            <?= htmlspecialchars($product['name']) ?>
                                        </h5>
                                        <p class="card-text" title="<?= htmlspecialchars($product['description'] ?? '') ?>">
                                            <?= htmlspecialchars($product['description'] ?? '') ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="price-tag">₹<?= number_format($product['price'], 2) ?></span>
                                            <a href="/QaviEcommerce/user/product_detail?id=<?= (int)$product['id'] ?>" class="btn view-btn btn-sm"><?=Lang::get('View')?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

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
                        <?php

// Keep all query parameters except 'page'
                        $queryParams = $_GET;
                        unset($queryParams['page']);
                        $queryString = http_build_query($queryParams);
                        ?>

                        <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>

                            <li class="page-item <?= ($i == $data['current_page']) ? 'active' : '' ?>">

                                 
                            <a class="page-link" href="/QaviEcommerce/user/category?<?= $queryString ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

            
            <!-- Next Page Link -->
                        <?php if ($data['current_page'] < $data['total_pages']): ?>

                            <li class="page-item">

                                <a class="page-link" href="<?= $queryString ?>&page=<?= $data['current_page'] + 1 ?>" aria-label="Next">

                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php else: ?>

                            <li class="page-item disabled">

                                <span class="page-link" aria-hidden="true">&raquo;</span>

                            </li>
                            <?php endif; ?>


                        </ul>

                <div id="noProductsMessage"><?=Lang::get('No_product_found')?></div>

                </div>
            <?php else: ?>
                <div class="empty-category">
                    <h4><?=Lang::get('No_product_available')?></h4>
                    <a href="/QaviEcommerce/user/home" class="btn btn-primary"><?=Lang::get('Browse_Other_Categories')?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($data['products'])): ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const $range = $('#maxPriceRange');
    const $input = $('#maxPriceInput');
    const $productItems = $('.product-item');
    const $noProductsMessage = $('#noProductsMessage');
    const $searchInput = $('#productSearch');

    // Sync range and input
    $range.on('input', function () {
        $input.val($(this).val());
    });
    $input.on('input', function () {
        $range.val($(this).val());
    });

    // Optional live filtering (client-side, not needed if filtering server-side)
    function filterProducts() {
        const maxPrice = parseFloat($range.val());
        const keyword = $searchInput.val().toLowerCase().trim();
        let found = false;

        $productItems.each(function () {
            const $item = $(this);
            const name = $item.data('name');
            const price = parseFloat($item.data('price'));

            const matchesPrice = price <= maxPrice;
            const matchesSearch = name.includes(keyword);

            if (matchesPrice && matchesSearch) {
                $item.show();
                found = true;
            } else {
                $item.hide();
            }
        });

        $noProductsMessage.toggle(!found);
    }

    $searchInput.on('input', function () {

        if ($(this).val().trim() === '') {

            $('#combinedFilterForm').submit();
        }
    });

    $range.on('input', filterProducts);
    $input.on('input', filterProducts);
    $searchInput.on('input', filterProducts);

    filterProducts(); // Initial check on page load
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
