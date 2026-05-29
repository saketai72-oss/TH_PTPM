<div class="page-header">
    <h1 class="page-title">Thêm Danh Mục Mới</h1>
    <a href="<?php echo BASE_PATH; ?>/category" class="btn btn-secondary">&larr; Quay lại danh sách</a>
</div>

<div class="form-card">
    <form action="<?php echo BASE_PATH; ?>/category/add" method="POST">
        
        <!-- Tên danh mục -->
        <div class="form-group">
            <label for="name" class="form-label">Tên danh mục <span style="color:var(--danger)">*</span></label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên danh mục (ví dụ: Đồng hồ thông minh)" required>
        </div>

        <!-- Mô tả danh mục -->
        <div class="form-group">
            <label for="description" class="form-label">Mô tả danh mục</label>
            <textarea id="description" name="description" class="form-control" placeholder="Nhập mô tả ngắn gọn về nhóm sản phẩm này..."></textarea>
        </div>

        <!-- Nút hành động -->
        <div class="form-actions">
            <a href="<?php echo BASE_PATH; ?>/category" class="btn btn-secondary">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary">Lưu danh mục</button>
        </div>

    </form>
</div>
