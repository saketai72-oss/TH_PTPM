<div class="page-header">
    <h1 class="page-title">Chi Tiết Hành Tinh</h1>
    <a href="<?php echo BASE_PATH; ?>/product" class="btn btn-secondary">&larr; Quay lại danh sách</a>
</div>

<?php 
    $imagePath = BASE_PATH . '/public/uploads/' . $product['image'];
    $hasImage = !empty($product['image']) && file_exists(__DIR__ . '/../../../public/uploads/' . $product['image']);
    
    $gradientClass = 'placeholder-img-default';
    if ($product['category_id'] == 1) {
        $gradientClass = 'placeholder-img-1';
    } elseif ($product['category_id'] == 2) {
        $gradientClass = 'placeholder-img-2';
    } elseif ($product['category_id'] == 3) {
        $gradientClass = 'placeholder-img-3';
    }
?>

<div class="product-detail-card">
    <!-- Cột bên trái: Ảnh hoặc Gradient -->
    <div class="detail-img-container">
        <?php if ($hasImage): ?>
            <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:100%; height:100%; object-fit:cover;">
        <?php else: ?>
            <div class="detail-img-placeholder <?php echo $gradientClass; ?>">
                <span><?php echo htmlspecialchars($product['name']); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Cột bên phải: Thông tin chi tiết -->
    <div class="detail-info">
        <span class="detail-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
        <h2 class="detail-title"><?php echo htmlspecialchars($product['name']); ?></h2>
        <div class="detail-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</div>
        
        <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
            Tình trạng: 
            <?php if ($product['stock_quantity'] > 0): ?>
                <span style="color: var(--success); font-weight: 700;">Còn hàng</span> (Còn <strong><?php echo $product['stock_quantity']; ?></strong> hành tinh trong kho)
            <?php else: ?>
                <span style="color: var(--danger); font-weight: 700;">🚫 Hết hàng</span>
            <?php endif; ?>
        </div>
        
        <p class="detail-desc">
            <strong>Mô tả chi tiết:</strong><br>
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </p>

        <div class="detail-actions">
            <!-- Nút thêm vào giỏ hàng -->
            <?php if ($product['stock_quantity'] > 0): ?>
                <a href="<?php echo BASE_PATH; ?>/cart/add/<?php echo $product['id']; ?>" class="btn btn-primary" style="background: linear-gradient(135deg, var(--secondary) 0%, #0284c7 100%);">🛒 Thêm vào giỏ hàng</a>
            <?php else: ?>
                <button class="btn btn-secondary" style="cursor: not-allowed;" disabled>🚫 Đã hết hàng</button>
            <?php endif; ?>
            <!-- Nút chỉnh sửa -->
            <a href="<?php echo BASE_PATH; ?>/product/edit/<?php echo $product['id']; ?>" class="btn btn-secondary">Chỉnh sửa</a>
            <!-- Nút xóa -->
            <a href="<?php echo BASE_PATH; ?>/product/delete/<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa hành tinh này không?');">Xóa hành tinh</a>
        </div>
    </div>
</div>
