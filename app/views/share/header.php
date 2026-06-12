<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . " - RenderToys" : "RenderToys - Cửa hàng đồ chơi trẻ em"; ?></title>
    <!-- Nhúng Style CSS -->
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/public/css/style.css">
</head>
<body>
    <!-- Header & Navigation -->
    <header>
        <div class="nav-container">
            <a href="<?php echo BASE_PATH; ?>/product" class="logo">
                <div class="logo-icon">🧸</div>
                RenderToys
            </a>
            
            <!-- Thanh tìm kiếm sản phẩm nhanh -->
            <form action="<?php echo BASE_PATH; ?>/product" method="GET" class="search-bar">
                <input type="text" name="search" class="search-input" placeholder="Tìm sản phẩm..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="search-btn">🔍</button>
            </form>

            <?php
                // Tự phân tích request URL để đánh dấu active link
                $reqUrl = isset($_GET['url']) ? $_GET['url'] : '';
                
                // Trường hợp dùng Pretty URL thì lấy từ REQUEST_URI
                if (empty($reqUrl)) {
                    $requestUri = str_replace('\\', '/', $_SERVER['REQUEST_URI']);
                    $requestUri = explode('?', $requestUri)[0];
                    if (BASE_PATH !== '' && strpos($requestUri, BASE_PATH) === 0) {
                        $requestUri = substr($requestUri, strlen(BASE_PATH));
                    }
                    $reqUrl = trim($requestUri, '/');
                }
                
                $reqParts = explode('/', $reqUrl);
                $isCategoryModule = isset($reqParts[0]) && strtolower($reqParts[0]) === 'category';
                $isCartModule = isset($reqParts[0]) && strtolower($reqParts[0]) === 'cart';
                $isHistoryModule = isset($reqParts[0]) && strtolower($reqParts[0]) === 'history';
                $isApiModule = isset($reqParts[0]) && strtolower($reqParts[0]) === 'api';
                
                // Tính số lượng trong giỏ hàng
                $cartCount = 0;
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    $cartCount = array_sum($_SESSION['cart']);
                }
            ?>
            <div class="nav-links">
                <a href="<?php echo BASE_PATH; ?>/product" class="nav-link <?php echo (!$isCategoryModule && !$isCartModule && !$isHistoryModule && !$isApiModule) ? 'active' : ''; ?>">Đồ chơi</a>
                <a href="<?php echo BASE_PATH; ?>/category" class="nav-link <?php echo $isCategoryModule ? 'active' : ''; ?>">Danh mục</a>
                <a href="<?php echo BASE_PATH; ?>/cart" class="nav-link <?php echo $isCartModule ? 'active' : ''; ?>">
                    Giỏ hàng<?php if ($cartCount > 0): ?><span class="badge-cart-count"><?php echo $cartCount; ?></span><?php endif; ?>
                </a>
                <a href="<?php echo BASE_PATH; ?>/history" class="nav-link <?php echo $isHistoryModule ? 'active' : ''; ?>">Lịch sử</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_PATH; ?>/api" class="nav-link <?php echo $isApiModule ? 'active' : ''; ?>">API Sandbox</a>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo BASE_PATH; ?>/product/add" class="btn btn-primary">+ Thêm mới</a>
                    <?php endif; ?>
                    <div class="user-menu">
                        <div class="user-avatar"><?php echo mb_substr($_SESSION['fullname'], 0, 1, 'UTF-8'); ?></div>
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
                        <a href="<?php echo BASE_PATH; ?>/account/logout" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.8rem; margin-left: 0.5rem;">Đăng xuất</a>
                    </div>
                <?php else: ?>
                    <a href="<?php echo BASE_PATH; ?>/account/login" class="btn btn-secondary">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Hiển thị thông báo Cosmic Banner ngay dưới Header -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="cosmic-banner banner-success">
            <div class="banner-container">
                <span class="banner-icon">🚀</span>
                <span class="banner-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="cosmic-banner banner-error">
            <div class="banner-container">
                <span class="banner-icon">⚠️</span>
                <span class="banner-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['warning'])): ?>
        <div class="cosmic-banner banner-warning">
            <div class="banner-container">
                <span class="banner-icon">⚠️</span>
                <span class="banner-message"><?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Khung nội dung chính -->
    <main>
