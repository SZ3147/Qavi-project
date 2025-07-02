<aside class="side-nav">
    <div class="side-nav-header">
        <h3 class="side-nav-title"><i class="fas fa-bars me-2"></i>Categories</h3>
    </div>

    <nav>
        <?php foreach ($data['categories'] as $category): ?>

            <?php if (($category['product_count'] ?? 0) > 0): ?>

                <a href="/QaviEcommerce/user/category?id=<?= $category['id'] ?>" class="nav-category">


                    <i class="fas fa-<?= htmlspecialchars($category['icon'] ?? 'tag') ?>"></i>
                    

                    <?= htmlspecialchars($category['name']) ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

    </nav>

    <!-- <div class="side-nav-header mt-4">
        <h3 class="side-nav-title"><i class="fas fa-filter me-2"></i>Filters</h3>
    </div>

    <div class="px-3">
        <h6 class="fw-bold mb-2">Price Range</h6>
        <div class="range-slider mb-3">
            <input type="range" class="form-range" min="0" max="10000" step="100" id="priceRange">
            <div class="d-flex justify-content-between">
                <small>₹0</small>
                <small>₹10,000</small>
            </div>
        </div>

        <h6 class="fw-bold mb-2 mt-3">Sort By</h6>
        <select class="form-select form-select-sm">
            <option>Featured</option>
            <option>Price: Low to High</option>
            <option>Price: High to Low</option>
            <option>Newest Arrivals</option>
            <option>Best Selling</option>
        </select>
    </div>
</aside> -->
