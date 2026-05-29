<div class="page-header">
    <h1 class="page-title">Chỉnh Sửa Hành Tinh</h1>
    <a href="<?php echo BASE_PATH; ?>/product" class="btn btn-secondary">&larr; Quay lại danh sách</a>
</div>

<div class="form-card">
    <form action="<?php echo BASE_PATH; ?>/product/edit/<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
        
        <!-- Tên hành tinh -->
        <div class="form-group">
            <label for="name" class="form-label">Tên hành tinh <span style="color:var(--danger)">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>

        <!-- Phân loại hành tinh -->
        <div class="form-group">
            <label for="category_id" class="form-label">Phân loại hành tinh <span style="color:var(--danger)">*</span></label>
            <select id="category_id" name="category_id" class="form-control" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Giá trị -->
        <div class="form-group">
            <label for="price" class="form-label">Giá trị hành tinh (VNĐ) <span style="color:var(--danger)">*</span></label>
            <input type="number" id="price" name="price" min="0" step="1000" class="form-control" value="<?php echo floatval($product['price']); ?>" required>
        </div>

        <!-- Số lượng sẵn có -->
        <div class="form-group">
            <label for="stock_quantity" class="form-label">Số lượng sẵn có <span style="color:var(--danger)">*</span></label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" class="form-control" value="<?php echo intval($product['stock_quantity']); ?>" required>
        </div>

        <!-- Ảnh hành tinh và Tải lên ảnh mới -->
        <div class="form-group">
            <label for="image" class="form-label">Hình ảnh hành tinh</label>
            
            <?php if (!empty($product['image']) && file_exists(__DIR__ . '/../../../public/uploads/' . $product['image'])): ?>
                <div style="margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem;">
                    <img src="<?php echo BASE_PATH . '/public/uploads/' . $product['image']; ?>" alt="Ảnh hiện tại" style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                    <div>
                        <span style="font-size: 0.85rem; color: var(--text-secondary); display: block;">Ảnh hiện tại: <?php echo htmlspecialchars($product['image']); ?></span>
                        <span style="font-size: 0.75rem; color: var(--text-muted); ">Tải tệp tin mới lên nếu muốn thay đổi ảnh cũ.</span>
                    </div>
                </div>
            <?php endif; ?>

            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>

        <!-- Mô tả -->
        <div class="form-group">
            <label for="description" class="form-label">Mô tả chi tiết</label>
            <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <!-- Nút hành động -->
        <div class="form-actions">
            <a href="<?php echo BASE_PATH; ?>/product" class="btn btn-secondary">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>

    </form>
</div>
