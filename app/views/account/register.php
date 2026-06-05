<div class="auth-container">
    <div class="form-card auth-card">
        <h2 class="auth-title">Đăng Ký Tài Khoản</h2>
        <p class="auth-subtitle">Bắt đầu hành trình sở hữu các hành tinh kỳ vĩ</p>
        
        <form action="<?php echo BASE_PATH; ?>/account/register" method="POST">
            <!-- Tên đăng nhập -->
            <div class="form-group">
                <label for="username" class="form-label">Tên đăng nhập <span style="color:var(--danger)">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Nhập tên đăng nhập (ví dụ: user123)" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required autofocus>
                </div>
            </div>

            <!-- Họ và tên -->
            <div class="form-group">
                <label for="fullname" class="form-label">Họ và tên <span style="color:var(--danger)">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">📛</span>
                    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Nhập họ và tên đầy đủ" value="<?php echo isset($fullname) ? htmlspecialchars($fullname) : ''; ?>" required>
                </div>
            </div>

            <!-- Mật khẩu -->
            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu <span style="color:var(--danger)">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>
            </div>

            <!-- Xác nhận mật khẩu -->
            <div class="form-group">
                <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span style="color:var(--danger)">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">🔄</span>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required>
                </div>
            </div>

            <!-- Nút đăng ký -->
            <button type="submit" class="btn btn-primary btn-block">Đăng Ký</button>
        </form>

        <div class="auth-footer">
            <span>Đã có tài khoản? <a href="<?php echo BASE_PATH; ?>/account/login" class="auth-link">Đăng nhập ngay</a></span>
        </div>
    </div>
</div>
