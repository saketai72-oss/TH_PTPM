<div class="page-header">
    <h1 class="page-title">Chỉnh Sửa Danh Mục</h1>
    <a href="<?php echo BASE_PATH; ?>/category" class="btn btn-secondary">&larr; Quay lại danh sách</a>
</div>

<div class="form-card">
    <form action="<?php echo BASE_PATH; ?>/category/edit/<?php echo $category['id']; ?>" method="POST">
        
        <!-- Tên danh mục -->
        <div class="form-group">
            <label for="name" class="form-label">Tên danh mục <span style="color:var(--danger)">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        </div>

        <!-- Mô tả danh mục -->
        <div class="form-group">
            <label for="description" class="form-label">Mô tả danh mục</label>
            <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($category['description']); ?></textarea>
        </div>

        <!-- Nút hành động -->
        <div class="form-actions">
            <a href="<?php echo BASE_PATH; ?>/category" class="btn btn-secondary">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>

    </form>
</div>
