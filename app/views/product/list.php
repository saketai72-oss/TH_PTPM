<?php if (!isset($_GET['search']) || empty($_GET['search'])): ?>
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Khám Phá Hệ Mặt Trời Kỳ Vĩ</h1>
            <p class="hero-subtitle">Sở hữu và khám phá những hành tinh tuyệt đẹp nhất trong vũ trụ bao la ngay hôm nay.</p>
            <div class="hero-buttons">
                <a href="#planet-catalog" class="btn btn-primary">🪐 Khám phá ngay</a>
                <a href="<?php echo BASE_PATH; ?>/category" class="btn btn-secondary">🛰️ Phân loại</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="page-header" id="planet-catalog">
    <div>
        <h1 class="page-title">Hệ Mặt Trời & Các Hành Tinh</h1>
        <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
            <p style="color: var(--text-secondary); margin-top: 0.5rem;">Kết quả tìm kiếm cho: <strong>"<?php echo htmlspecialchars($_GET['search']); ?>"</strong></p>
        <?php endif; ?>
    </div>
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="<?php echo BASE_PATH; ?>/product/add" class="btn btn-primary">+ Thêm hành tinh</a>
    <?php endif; ?>
</div>

<?php if (empty($products)): ?>
    <!-- Trạng thái trống (Empty State) -->
    <div class="empty-state">
        <div class="empty-icon">🪐</div>
        <h2 class="empty-title">Không tìm thấy hành tinh nào</h2>
        <p class="empty-desc">Thử tìm kiếm với từ khóa khác hoặc bấm nút thêm mới dưới đây.</p>
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="<?php echo BASE_PATH; ?>/product/add" class="btn btn-primary">Thêm hành tinh mới</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <!-- Danh sách sản phẩm dạng lưới (Grid) -->
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <?php 
                // Định hình hình ảnh và lớp nền gradient sang trọng cho sản phẩm không có ảnh thực tế
                $imagePath = BASE_PATH . '/public/uploads/' . $product['image'];
                $hasImage = !empty($product['image']) && file_exists(__DIR__ . '/../../../public/uploads/' . $product['image']);
                
                // Chọn class nền gradient phù hợp theo tên danh mục
                $gradientClass = 'placeholder-img-default';
                if ($product['category_id'] == 1) {
                    $gradientClass = 'placeholder-img-1'; // Điện thoại
                } elseif ($product['category_id'] == 2) {
                    $gradientClass = 'placeholder-img-2'; // Laptop
                } elseif ($product['category_id'] == 3) {
                    $gradientClass = 'placeholder-img-3'; // Phụ kiện
                }
            ?>
            <div class="product-card">
                <div class="card-img-container">
                    <?php if ($hasImage): ?>
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <!-- Gradient card thay cho ảnh trống trông cao cấp -->
                        <div class="card-img-placeholder <?php echo $gradientClass; ?>">
                            <span><?php echo htmlspecialchars($product['name']); ?></span>
                        </div>
                    <?php endif; ?>
                    <span class="card-category-badge"><?php echo htmlspecialchars($product['category_name']); ?></span>
                </div>
                
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="card-description"><?php echo htmlspecialchars($product['description']); ?></p>
                    
                    <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <span>Kho: 
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <strong><?php echo $product['stock_quantity']; ?></strong> chiếc
                            <?php else: ?>
                                <span style="color: var(--danger); font-weight: 700;">🚫 Hết hàng</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="card-footer">
                        <span class="card-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                        <div class="card-actions">
                            <!-- Thêm vào giỏ hàng -->
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <a href="<?php echo BASE_PATH; ?>/cart/add/<?php echo $product['id']; ?>" class="action-link" title="Thêm vào giỏ hàng" style="background-color: var(--primary-light); color: var(--primary); border-color: var(--primary);">🛒</a>
                            <?php else: ?>
                                <span class="action-link" title="Đã hết hàng" style="background-color: var(--border); color: var(--text-muted); cursor: not-allowed; border-color: var(--border);">🔒</span>
                            <?php endif; ?>
                            <!-- Xem chi tiết -->
                            <a href="<?php echo BASE_PATH; ?>/product/show/<?php echo $product['id']; ?>" class="action-link" title="Xem chi tiết">👁️</a>
                            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <!-- Chỉnh sửa -->
                                <a href="<?php echo BASE_PATH; ?>/product/edit/<?php echo $product['id']; ?>" class="action-link" title="Chỉnh sửa">✏️</a>
                                <!-- Xóa -->
                                <a href="<?php echo BASE_PATH; ?>/product/delete/<?php echo $product['id']; ?>" class="action-link btn-delete-action" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">🗑️</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
