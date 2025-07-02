<?php include __DIR__ . '/../partials/user_header.php'; ?>
<?php include __DIR__ . '/../partials/user_navbar.php'; ?>

<style>
/* === Layout === */
.main-container {
    display: flex;
    min-height: calc(100vh - 120px);
}

.side-nav {
    width: 280px;
    background: #fff;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    padding: 20px 0;
    position: sticky;
    top: 80px;
    height: calc(100vh - 80px);
    overflow-y: auto;
}

.main-content {
    flex: 1;
    padding: 20px 30px;
}

/* === Side Navigation === */
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

/* === Hero Section === */
.hero {
    background: linear-gradient(135deg, rgba(231, 76, 60, 0.9), rgba(192, 57, 43, 0.9)),
                url('/QaviEcommerce/public/assets/hero-bg.jpg') center center/cover no-repeat;
    color: white;
    padding: 80px 30px;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 40px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(231, 76, 60, 0.2);
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 700px;
    margin: 0 auto;
}

.hero h1 {
    font-size: 2.8rem;
    font-weight: 800;
    margin-bottom: 15px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.hero p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    opacity: 0.9;
}

.btn-shop {
    font-size: 1rem;
    padding: 12px 40px;
    border-radius: 50px;
    font-weight: 600;
    background: white;
    color: #e74c3c;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.btn-shop:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 7px 20px rgba(0, 0, 0, 0.15);
}

/* === Section Title === */
.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 10px;
}

.section-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: #e74c3c;
}

/* === Category Cards === */
/* Category Grid Layout */
.category-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 40px;
}

/* Category Card Container */
.category-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 320px;
    transition: all 0.35s ease;
    cursor: pointer;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #e74c3c;
}

/* Image Container - Unified Style */
.category-image-container {
    height: 180px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid #eee;
    position: relative;
}

/* Actual Image Styling */
.category-image-container img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    display: block;
}

/* Placeholder Style */
.category-image-placeholder {
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #95a5a6;
    font-size: 3rem;
    border-bottom: 1px solid #eee;
}

/* Category Info Section - Improved Layout */
.category-info {
    padding: 20px;
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 140px;
}

.category-info h5 {
    font-weight: 600;
    font-size: 1.1rem;
    color: #2c3e50;
    margin-bottom: 8px;
    word-break: break-word;
}

.category-info .text-muted {
    font-size: 0.9rem;
    margin-top: auto;
    padding-top: 10px;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .category-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media (max-width: 576px) {
    .category-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .category-card {
        height: auto;
        min-height: 280px;
    }
    
    .category-image-container,
    .category-image-placeholder {
        height: 160px;
    }
}

/* === Product Cards === */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 25px;
}

.product-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border: 1px solid #eee;
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #e74c3c;
}

.product-card-img {
    height: 180px;
    width: 100%;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}

.product-card-body {
    padding: 15px;
}

.product-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: #34495e;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #e74c3c;
    margin-bottom: 12px;
}

.product-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-view {
    font-size: 0.85rem;
    font-weight: 600;
    padding: 6px 15px;
    border-radius: 20px;
    background: #e74c3c;
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-view:hover {
    background: #c0392b;
    transform: translateY(-2px);
}

.btn-wishlist {
    background: none;
    border: none;
    color: #95a5a6;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.btn-wishlist:hover {
    color: #e74c3c;
}

/* === Responsive Design === */
@media (max-width: 992px) {
    .main-container {
        flex-direction: column;
    }

    .side-nav {
        width: 100%;
        height: auto;
        position: static;
        margin-bottom: 20px;
    }

    .main-content {
        padding: 20px 15px;
    }

    .category-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}

@media (max-width: 768px) {
    .category-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
    }

    .category-image,
    .category-image-placeholder {
        height: 140px;
    }

    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
    }
}

@media (max-width: 576px) {
    .category-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>



<div class="main-container">
    <!-- Side Navigation -->
    <aside class="side-nav">
        <div class="side-nav-header">
            <h3 class="side-nav-title"><i class="fas fa-bars me-2"></i><?= Lang::get('categories') ?></h3>
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
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1><?= Lang::get('hero_title') ?></h1>
                <p><?= Lang::get('hero_subtitle') ?></p>
            </div>
        </section>
        
        <!-- Featured Categories -->
        <section>

            <h3 class="section-title"><?= Lang::get('shop_by_category') ?></h3>

            <div class="category-grid">

                <?php foreach ($data['categories'] as $category): ?>

                    <?php if (($category['product_count'] ?? 0) > 0): ?>

                        <a href="/QaviEcommerce/user/category?id=<?= $category['id'] ?>" class="text-decoration-none">

                            <div class="category-card">

                                <div class="category-image-container">

                                    <img src="/public/uploads/<?= htmlspecialchars($category['image'] ?? 'default-category.png') ?>"

                                    alt="<?= htmlspecialchars($category['name']) ?>"

                                    onerror="this.onerror=null; this.src='/QaviEcommerce/public/uploads/default-category.png'">
                                </div>
                                <div class="category-info">


                                    <h5><?= htmlspecialchars($category['name']) ?></h5>

                                    <small class="text-muted">

                                        <?= $category['product_count'] . ' ' . Lang::get('items') ?>

                                    </small>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>

    </main>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
