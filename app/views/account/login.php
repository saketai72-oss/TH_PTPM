<div class="auth-container">
    <div class="form-card auth-card">
        <h2 class="auth-title">Đăng Nhập</h2>
        <p class="auth-subtitle">Kết nối với hành trình khám phá vũ trụ của bạn</p>
        
        <form action="<?php echo BASE_PATH; ?>/account/login" method="POST">
            <!-- Tên đăng nhập -->
            <div class="form-group">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Nhập tên đăng nhập của bạn" required autofocus>
                </div>
            </div>

            <!-- Mật khẩu -->
            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu</label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>
            </div>

            <!-- Nút đăng nhập -->
            <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>
        </form>

        <div class="auth-footer">
            <span>Chưa có tài khoản? <a href="<?php echo BASE_PATH; ?>/account/register" class="auth-link">Đăng ký ngay</a></span>
        </div>

        <div class="test-accounts-panel" style="margin-top: 1.5rem; border-top: 1px solid var(--border); padding-top: 1.5rem; text-align: left;">
            <span style="font-size: 0.85rem; color: var(--text-secondary); display: block; margin-bottom: 0.75rem; font-weight: 600;">🔑 Các tài khoản thử nghiệm hiện có:</span>
            <div style="font-size: 0.8rem; line-height: 1.6; color: var(--text-muted);">
                <div style="margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                    <span>👑 <strong>Quản trị (Admin):</strong> <code>admin</code> / <code>admin123</code></span>
                    <span class="badge" style="background-color: var(--primary-light); color: var(--text-primary); border: 1px solid var(--border); font-size: 0.7rem; padding: 0.1rem 0.4rem;">Trang Sandbox</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span>👤 <strong>Người dùng (User):</strong> <code>user</code> / <code>user123</code></span>
                    <span class="badge badge-neutral" style="font-size: 0.7rem; padding: 0.1rem 0.4rem;">Chỉ xem/Mua hàng</span>
                </div>
            </div>
        </div>
    </div>
</div>
