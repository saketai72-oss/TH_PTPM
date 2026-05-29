<div class="page-header">
    <div>
        <h1 class="page-title">Quản Lý Danh Mục</h1>
        <p style="color: var(--text-secondary); margin-top: 0.5rem;">Thêm, chỉnh sửa và quản lý danh mục sản phẩm của cửa hàng.</p>
    </div>
    <a href="<?php echo BASE_PATH; ?>/category/add" class="btn btn-primary">+ Thêm danh mục</a>
</div>

<?php if (empty($categories)): ?>
    <div class="empty-state">
        <div class="empty-icon">📂</div>
        <h2 class="empty-title">Không tìm thấy danh mục nào</h2>
        <p class="empty-desc">Hệ thống chưa có danh mục sản phẩm nào, hãy bấm nút dưới đây để tạo mới.</p>
        <a href="<?php echo BASE_PATH; ?>/category/add" class="btn btn-primary">Thêm danh mục mới</a>
    </div>
<?php else: ?>
    <!-- Bảng hiển thị danh sách danh mục -->
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Mã ID</th>
                    <th style="width: 250px;">Tên Danh Mục</th>
                    <th>Mô Tả Danh Mục</th>
                    <th style="width: 180px; text-align: center;">Số lượng sản phẩm</th>
                    <th style="width: 150px; text-align: center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <?php 
                        $count = isset($productCounts[$category['id']]) ? $productCounts[$category['id']] : 0; 
                        $badgeClass = ($count > 0) ? 'badge-primary' : 'badge-neutral';
                    ?>
                    <tr>
                        <td>#<?php echo $category['id']; ?></td>
                        <td style="font-weight: 600; color: var(--text-primary);">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </td>
                        <td style="color: var(--text-secondary);">
                            <?php echo htmlspecialchars($category['description']); ?>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge <?php echo $badgeClass; ?>">
                                <?php echo $count; ?> sản phẩm
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 0.5rem; justify-content: center;">
                                <!-- Nút Sửa -->
                                <a href="<?php echo BASE_PATH; ?>/category/edit/<?php echo $category['id']; ?>" class="action-link" title="Chỉnh sửa">✏️</a>
                                <!-- Nút Xóa -->
                                <a href="<?php echo BASE_PATH; ?>/category/delete/<?php echo $category['id']; ?>" class="action-link btn-delete-action" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">🗑️</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
