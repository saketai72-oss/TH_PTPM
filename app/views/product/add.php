<div class="page-header">
    <h1 class="page-title">Thêm Hành Tinh Mới</h1>
    <a href="<?php echo BASE_PATH; ?>/product" class="btn btn-secondary">&larr; Quay lại danh sách</a>
</div>

<div class="form-card">
    <form action="<?php echo BASE_PATH; ?>/product/add" method="POST" enctype="multipart/form-data">
        
        <!-- Tên hành tinh -->
        <div class="form-group">
            <label for="name" class="form-label">Tên hành tinh <span style="color:var(--danger)">*</span></label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên hành tinh (ví dụ: Trái Đất)" required>
        </div>

        <!-- Phân loại hành tinh -->
        <div class="form-group">
            <label for="category_id" class="form-label">Phân loại hành tinh <span style="color:var(--danger)">*</span></label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="" disabled selected>-- Chọn phân loại hành tinh --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Giá trị -->
        <div class="form-group">
            <label for="price" class="form-label">Giá trị hành tinh (VNĐ) <span style="color:var(--danger)">*</span></label>
            <input type="number" id="price" name="price" min="0" step="1000" class="form-control" placeholder="Ví dụ: 100000000" required>
        </div>

        <!-- Số lượng -->
        <div class="form-group">
            <label for="stock_quantity" class="form-label">Số lượng sẵn có <span style="color:var(--danger)">*</span></label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" class="form-control" placeholder="Nhập số lượng (ví dụ: 10)" value="10" required>
        </div>

        <!-- Ảnh hành tinh -->
        <div class="form-group">
            <label for="image" class="form-label">Hình ảnh hành tinh</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Hỗ trợ định dạng: JPG, PNG, GIF, WEBP.</p>
        </div>

        <!-- Mô tả -->
        <div class="form-group">
            <label for="description" class="form-label">Mô tả chi tiết</label>
            <textarea id="description" name="description" class="form-control" placeholder="Nhập mô tả về đặc điểm, tính chất của hành tinh..."></textarea>
        </div>

        <!-- Nút hành động -->
        <div class="form-actions">
            <a href="<?php echo BASE_PATH; ?>/product" class="btn btn-secondary">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary">Lưu hành tinh</button>
        </div>

    </form>
</div>
