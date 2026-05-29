<style>
    /* Universe Space Theme */
    .universe-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(ellipse at center, #0a0a2a 0%, #000000 100%);
        z-index: -2;
        overflow: hidden;
    }

    /* Stars container */
    .stars-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
    }

    /* Star styling */
    .star {
        position: absolute;
        background: white;
        border-radius: 50%;
        animation: floatStar linear infinite;
        pointer-events: none;
    }

    .star-glowing {
        box-shadow: 0 0 10px currentColor;
    }

    /* Colored stars */
    .star-red { background: #ff4444; box-shadow: 0 0 8px #ff4444; }
    .star-blue { background: #44aaff; box-shadow: 0 0 8px #44aaff; }
    .star-green { background: #44ff44; box-shadow: 0 0 8px #44ff44; }
    .star-yellow { background: #ffdd44; box-shadow: 0 0 10px #ffdd44; }
    .star-purple { background: #cc44ff; box-shadow: 0 0 8px #cc44ff; }
    .star-orange { background: #ff8844; box-shadow: 0 0 8px #ff8844; }
    .star-pink { background: #ff66aa; box-shadow: 0 0 8px #ff66aa; }
    .star-cyan { background: #44ffee; box-shadow: 0 0 8px #44ffee; }

    /* Twinkling stars */
    .star-twinkle {
        animation: floatStar linear infinite, twinkle 2s ease-in-out infinite;
    }

    @keyframes floatStar {
        0% {
            transform: translateY(100vh) translateX(0) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            transform: translateY(-20vh) translateX(var(--drift)) rotate(360deg);
            opacity: 0;
        }
    }

    @keyframes twinkle {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.3); }
    }

    /* Nebula effect */
    .nebula {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 60%;
        height: 60%;
        background: radial-gradient(ellipse, rgba(100, 50, 200, 0.15), transparent);
        border-radius: 50%;
        filter: blur(60px);
        transform: translate(-50%, -50%);
        z-index: -1;
        animation: nebulaPulse 8s ease-in-out infinite;
    }

    .nebula-2 {
        width: 40%;
        height: 40%;
        background: radial-gradient(ellipse, rgba(50, 150, 200, 0.12), transparent);
        animation: nebulaPulse 12s ease-in-out infinite reverse;
    }

    @keyframes nebulaPulse {
        0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
        50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.2); }
    }

    /* Shooting stars */
    .shooting-star {
        position: fixed;
        width: 2px;
        height: 2px;
        background: linear-gradient(90deg, white, transparent);
        animation: shootingStar 3s linear infinite;
        pointer-events: none;
        z-index: -1;
    }

    @keyframes shootingStar {
        0% {
            transform: translateX(-100px) translateY(-100px) rotate(45deg);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        30% {
            opacity: 1;
        }
        100% {
            transform: translateX(calc(100vw + 100px)) translateY(calc(100vh + 100px)) rotate(45deg);
            opacity: 0;
        }
    }

    /* Cart content styles - transparent/glassmorphism */
    .cart-content {
        position: relative;
        z-index: 1;
        background: rgba(10, 10, 30, 0.75);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        margin: 20px auto;
        padding: 20px;
        max-width: 1400px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
    }

    .page-header {
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
        border-radius: 16px;
        padding: 16px 24px;
        margin-bottom: 24px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .page-title {
        background: linear-gradient(135deg, #fff, #aaccff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 0 0 30px rgba(100, 100, 255, 0.5);
    }

    .cart-items-card, .cart-checkout-card {
        background: rgba(20, 20, 50, 0.7);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .cart-item {
        background: rgba(30, 30, 60, 0.5);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        background: rgba(50, 50, 90, 0.6);
        border-color: rgba(100, 100, 255, 0.4);
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
    }

    .form-control {
        background: rgba(10, 10, 30, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }

    .form-control:focus {
        background: rgba(20, 20, 40, 0.8);
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .form-label {
        color: rgba(255, 255, 255, 0.9);
    }

    .empty-state {
        background: rgba(20, 20, 50, 0.6);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 40px;
    }

    /* Planet orbiting decoration */
    .planet {
        position: fixed;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255,200,100,0.8), rgba(200,100,50,0.4));
        box-shadow: 0 0 30px rgba(255,200,100,0.3);
        z-index: -1;
        pointer-events: none;
        animation: orbit 20s linear infinite;
    }

    @keyframes orbit {
        from { transform: rotate(0deg) translateX(calc(100vw - 80px)) rotate(0deg); }
        to { transform: rotate(360deg) translateX(calc(100vw - 80px)) rotate(-360deg); }
    }
</style>

<!-- Universe Background -->
<div class="universe-bg"></div>
<div class="stars-container" id="starsContainer"></div>
<div class="nebula"></div>
<div class="nebula nebula-2"></div>

<!-- Planet decoration -->
<div class="planet" style="top: 10%; left: -40px; width: 100px; height: 100px; background: radial-gradient(circle at 30% 30%, #ff9966, #ff5e62);"></div>
<div class="planet" style="bottom: 5%; right: -30px; width: 60px; height: 60px; background: radial-gradient(circle at 30% 30%, #43c6ac, #191654); animation-duration: 25s;"></div>

<div class="cart-content">
    <div class="page-header">
        <h1 class="page-title">🌌 Giỏ Hàng Của Bạn 🚀</h1>
        <a href="<?php echo BASE_PATH; ?>/product" class="btn btn-secondary">&larr; Tiếp tục mua sắm</a>
    </div>

    <?php if (empty($cartItems)): ?>
        <!-- Trạng thái giỏ hàng trống (Empty Cart) -->
        <div class="empty-state">
            <div class="empty-icon">🌠</div>
            <h2 class="empty-title">Giỏ hàng của bạn đang trống ✨</h2>
            <p class="empty-desc">Bạn chưa thêm bất kỳ sản phẩm nào vào giỏ hàng. Hãy lựa chọn các thiết bị công nghệ đỉnh cao của chúng tôi.</p>
            <a href="<?php echo BASE_PATH; ?>/product" class="btn btn-primary">🛍️ Mua sắm ngay</a>
        </div>
    <?php else: ?>
        
        <div class="cart-layout">
            
            <!-- Cột bên trái: Danh sách sản phẩm trong giỏ hàng -->
            <div class="cart-items-card">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 0.75rem;">✨ Sản phẩm chọn mua ✨</h2>
                
                <form action="<?php echo BASE_PATH; ?>/cart/update" method="POST">
                    <?php foreach ($cartItems as $item): ?>
                        <?php 
                            $imagePath = BASE_PATH . '/public/uploads/' . $item['image'];
                            $hasImage = !empty($item['image']) && file_exists(__DIR__ . '/../../../public/uploads/' . $item['image']);
                            
                            $gradientClass = 'placeholder-img-default';
                            if ($item['category_id'] == 1) {
                                $gradientClass = 'placeholder-img-1';
                            } elseif ($item['category_id'] == 2) {
                                $gradientClass = 'placeholder-img-2';
                            } elseif ($item['category_id'] == 3) {
                                $gradientClass = 'placeholder-img-3';
                            }
                        ?>
                        <div class="cart-item">
                            <!-- Hình ảnh thu nhỏ -->
                            <div class="cart-item-img-container">
                                <?php if ($hasImage): ?>
                                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-img">
                                <?php else: ?>
                                    <div class="cart-item-img <?php echo $gradientClass; ?>" style="display:flex; align-items:center; justify-content:center; color:white; font-size:0.6rem; font-weight:bold; text-align:center; padding: 0.25rem;">
                                        <span>⭐ <?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Thông tin sản phẩm -->
                            <div class="cart-item-info">
                                <span class="cart-item-category">🌟 <?php echo htmlspecialchars($item['category_name']); ?></span>
                                <h3 class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <div class="cart-item-price">💰 <?php echo number_format($item['price'], 0, ',', '.'); ?>đ</div>
                            </div>

                            <!-- Số lượng và Thao tác -->
                            <div class="cart-item-qty-actions" style="display:flex; flex-direction:column; align-items:center;">
                                <div style="display:flex; align-items:center; gap:0.25rem;">
                                    <label style="font-size:0.75rem; color:rgba(255,255,255,0.7)">SL: </label>
                                    <input type="number" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>" class="qty-input">
                                    <a href="<?php echo BASE_PATH; ?>/cart/delete/<?php echo $item['id']; ?>" class="action-link btn-delete-action" title="Xóa khỏi giỏ hàng" style="margin-left: 0.25rem;" onclick="return confirm('Bạn muốn xóa sản phẩm này khỏi giỏ hàng?');">🗑️</a>
                                </div>
                                <span style="font-size: 0.7rem; color: rgba(255,255,255,0.5); margin-top: 0.25rem;">📦 Kho: <?php echo $item['stock_quantity']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div style="text-align: right; margin-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 1.5rem;">
                        <button type="submit" class="btn btn-secondary">🔄 Cập nhật giỏ hàng</button>
                    </div>
                </form>
            </div>

            <!-- Cột bên phải: Tóm tắt giỏ hàng và Form đặt hàng/thanh toán -->
            <div class="cart-checkout-card">
                <h2 class="summary-title">🪐 Đơn hàng & Thanh toán 🌍</h2>
                
                <!-- Tóm tắt đơn giá -->
                <div class="summary-row">
                    <span>📊 Tạm tính (<?php echo array_sum($_SESSION['cart']); ?> sản phẩm)</span>
                    <span style="font-weight:600; color:white"><?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</span>
                </div>
                <div class="summary-row">
                    <span>🚚 Phí vận chuyển</span>
                    <span style="color:#44ffaa; font-weight:600;">✨ Miễn phí ✨</span>
                </div>
                <div class="summary-row total-row">
                    <span>💫 Tổng cộng</span>
                    <span><?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</span>
                </div>

                <!-- Form điền thông tin đặt hàng -->
                <form action="<?php echo BASE_PATH; ?>/cart/checkout" method="POST" style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 1.5rem;">
                    <h3 style="font-size:1rem; font-weight:700; margin-bottom: 1rem; color:white">👨‍🚀 Thông tin giao nhận</h3>
                    
                    <!-- Họ tên -->
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="customer_name" class="form-label">👤 Họ và tên khách hàng <span style="color:#ff6666">*</span></label>
                        <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Nhập họ và tên" required>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="customer_phone" class="form-label">📱 Số điện thoại <span style="color:#ff6666">*</span></label>
                        <input type="tel" id="customer_phone" name="customer_phone" class="form-control" placeholder="Ví dụ: 0987654321" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="customer_email" class="form-label">✉️ Địa chỉ Email <span style="color:#ff6666">*</span></label>
                        <input type="email" id="customer_email" name="customer_email" class="form-control" placeholder="Ví dụ: email@gmail.com" required>
                    </div>

                    <!-- Địa chỉ nhận hàng -->
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="customer_address" class="form-label">🏠 Địa chỉ nhận hàng <span style="color:#ff6666">*</span></label>
                        <textarea id="customer_address" name="customer_address" class="form-control" style="min-height: 80px;" placeholder="Nhập địa chỉ giao hàng cụ thể..." required></textarea>
                    </div>

                    <!-- Nút Xác nhận đặt hàng -->
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; font-size: 1rem;">
                        🚀 Xác nhận đặt hàng
                    </button>
                </form>
            </div>

        </div>

    <?php endif; ?>
</div>

<script>
    // Create colorful floating stars
    function createStars() {
        const starsContainer = document.getElementById('starsContainer');
        const colors = ['star-red', 'star-blue', 'star-green', 'star-yellow', 'star-purple', 'star-orange', 'star-pink', 'star-cyan'];
        const starCount = 150;
        
        for (let i = 0; i < starCount; i++) {
            const star = document.createElement('div');
            const colorClass = colors[Math.floor(Math.random() * colors.length)];
            const size = Math.random() * 3 + 1;
            const duration = Math.random() * 10 + 5;
            const delay = Math.random() * 15;
            const startX = Math.random() * window.innerWidth;
            const drift = (Math.random() - 0.5) * 200;
            
            star.className = `star ${colorClass}`;
            if (Math.random() > 0.7) {
                star.classList.add('star-twinkle');
            }
            
            star.style.width = `${size}px`;
            star.style.height = `${size}px`;
            star.style.left = `${startX}px`;
            star.style.animationDuration = `${duration}s`;
            star.style.animationDelay = `${delay}s`;
            star.style.setProperty('--drift', `${drift}px`);
            star.style.opacity = Math.random() * 0.7 + 0.3;
            
            starsContainer.appendChild(star);
        }
        
        // Add shooting stars periodically
        setInterval(() => {
            const shootingStar = document.createElement('div');
            shootingStar.className = 'shooting-star';
            const startX = Math.random() * window.innerWidth;
            const startY = Math.random() * window.innerHeight;
            shootingStar.style.left = `${startX}px`;
            shootingStar.style.top = `${startY}px`;
            shootingStar.style.animationDuration = `${Math.random() * 2 + 2}s`;
            document.body.appendChild(shootingStar);
            
            setTimeout(() => {
                shootingStar.remove();
            }, 3000);
        }, 4000);
    }
    
    // Create additional floating particles
    function createFloatingParticles() {
        const container = document.getElementById('starsContainer');
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.style.position = 'absolute';
            particle.style.width = '2px';
            particle.style.height = '2px';
            particle.style.background = `hsl(${Math.random() * 360}, 100%, 70%)`;
            particle.style.borderRadius = '50%';
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            particle.style.animation = `floatStar ${Math.random() * 15 + 8}s linear infinite`;
            particle.style.animationDelay = `${Math.random() * 20}s`;
            particle.style.opacity = Math.random() * 0.5;
            container.appendChild(particle);
        }
    }
    
    // Initialize stars when page loads
    window.addEventListener('load', () => {
        createStars();
        createFloatingParticles();
    });
    
    // Resize handling - regenerate stars on resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const starsContainer = document.getElementById('starsContainer');
            starsContainer.innerHTML = '';
            createStars();
            createFloatingParticles();
        }, 500);
    });
</script>