<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?> 

<div class="container mt-5">
    <h2>Add Product</h2>
    <form method="POST" action="/QaviEcommerce/admin/addProduct" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Description</label>
                <input type="text" name="description" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" required min="0">
            </div>
            <div class="col-md-4">
                <label>Price</label>
                <input type="number" name="price" class="form-control" required step="0.01">
            </div>
            <!-- Category Select Dropdown -->
            <div class="col-md-4">
                <label>Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php if (!empty($data['categories'])): ?>
                        <?php foreach ($data['categories'] as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No categories available</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Add</button>
                <a href="/QaviEcommerce/admin/manageProducts" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
