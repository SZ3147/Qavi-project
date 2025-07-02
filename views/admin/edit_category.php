<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

<div class="container mt-5">
    <h2>Edit Category</h2>

    <form action="/QaviEcommerce/admin/updateCategory" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($data['category']['id']) ?>">
        <input type="hidden" name="old_image" value="<?= htmlspecialchars($data['category']['image']) ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($data['category']['name']) ?>">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Category Description</label>
            <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($data['category']['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="category_image" class="form-label">Category Image</label>

            
            <?php if (!empty($data['category']['image'])): ?>
                <div class="mb-2">
                    <img src="/public/uploads/<?= htmlspecialchars($data['category']['image']) ?>" 
                         alt="Current Category Image" 
                         style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px;">
                </div>

                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" id="remove_image" name="remove_image" value="1">
                    <label class="form-check-label" for="remove_image">Remove current image</label>
                </div>
            <?php endif; ?>

            <input type="file" name="category_image" id="category_image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="1" <?= $data['category']['status'] == 1 ? 'selected' : '' ?>>Enabled</option>
                <option value="0" <?= $data['category']['status'] == 0 ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/QaviEcommerce/admin/manageCategories" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
