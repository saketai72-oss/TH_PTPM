<div class="page-header">
    <div>
        <h1 class="page-title">Lịch Sử Hoạt Động</h1>
        <p style="color: var(--text-secondary); margin-top: 0.5rem;">Danh sách các hành động bạn đã thực hiện trong phiên làm việc này</p>
    </div>
    <?php if (!empty($actions)): ?>
        <a href="<?php echo BASE_PATH; ?>/history/clear" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ lịch sử không?');">🗑️ Xóa lịch sử</a>
    <?php endif; ?>
</div>

<?php if (empty($actions)): ?>
    <div class="empty-state">
        <div class="empty-icon">🪐</div>
        <h2 class="empty-title">Chưa có lịch sử hoạt động</h2>
        <p class="empty-desc">Hệ thống sẽ tự động lưu lại các hành động của bạn (như xem hành tinh, thêm giỏ hàng, đặt hàng, v.v.).</p>
        <a href="<?php echo BASE_PATH; ?>/product" class="btn btn-primary">Khám phá vũ trụ ngay</a>
    </div>
<?php else: ?>
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Thời gian</th>
                    <th style="width: 55%;">Hành động thực hiện</th>
                    <th style="width: 20%;">Địa chỉ IP</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Display in reverse chronological order (newest first)
                $reversedActions = array_reverse($actions);
                foreach ($reversedActions as $log): 
                ?>
                    <tr>
                        <td style="color: var(--secondary); font-weight: 500;"><?php echo htmlspecialchars($log['timestamp']); ?></td>
                        <td style="font-weight: 600;"><?php echo htmlspecialchars($log['action']); ?></td>
                        <td><span class="badge badge-neutral"><?php echo htmlspecialchars($log['ip']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
