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
    </div>
</div>
