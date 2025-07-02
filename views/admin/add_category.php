<?php include __DIR__ . '/../partials/admin_header.php'; ?>
<?php include __DIR__ . '/../partials/admin_navbar.php'; ?> 
<div class="container mt-5">
    <h2>Add Category</h2>

    <form action="/QaviEcommerce/admin/addCategory" method="POST" class="mb-4" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Category Description</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">


            <label for="category_image" class="form-label">Category Image</label>
            <input type="file" name="category_image" id="category_image" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status_active" value="1" checked>
                <label class="form-check-label" for="status_active">Active</label>
            </div>

            
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0">
                <label class="form-check-label" for="status_inactive">Inactive</label>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Add</button>
    </form>

    <a href="/QaviEcommerce/admin/manageCategories" class="btn btn-secondary">Back to Categories</a>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>