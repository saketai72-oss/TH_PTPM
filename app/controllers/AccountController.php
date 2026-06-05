<?php

class AccountController {
    private $accountModel;

    private $cartModel;

    public function __construct() {
        $this->accountModel = new AccountModel();
        $this->cartModel = new CartModel();
    }

    // Trang Đăng nhập
    public function login() {
        // Nếu đã đăng nhập thì chuyển hướng về trang chủ
        if (isset($_SESSION['user_id'])) {
            header("Location: " . BASE_PATH . "/product");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if (!empty($username) && !empty($password)) {
                $user = $this->accountModel->getByUsername($username);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Thiết lập session cho người dùng
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['role'] = $user['role'];
                    
                    // Khôi phục giỏ hàng đã lưu trong database
                    $dbCart = $this->cartModel->getCartByUserId($user['id']);
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }

                    // Gộp giỏ hàng của guest (nếu có) và giỏ hàng của database
                    foreach ($dbCart as $productId => $quantity) {
                        if (isset($_SESSION['cart'][$productId])) {
                            $_SESSION['cart'][$productId] += $quantity;
                        } else {
                            $_SESSION['cart'][$productId] = $quantity;
                        }
                    }

                    // Đồng bộ lại giỏ hàng đã gộp vào database
                    $this->cartModel->syncCart($user['id'], $_SESSION['cart']);

                    // Ghi nhận lịch sử
                    SessionLogger::log("Đăng nhập thành công tài khoản: " . $username);
                    
                    $_SESSION['success'] = "Đăng nhập thành công! Chào mừng " . $user['fullname'] . " trở lại.";
                    header("Location: " . BASE_PATH . "/product");
                    exit();
                } else {
                    SessionLogger::log("Đăng nhập thất bại tài khoản: " . $username);
                    $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không chính xác.";
                }
            } else {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
            }
        }

        // Tải View Đăng nhập
        $pageTitle = "Đăng nhập";
        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/account/login.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Trang Đăng ký
    public function register() {
        // Nếu đã đăng nhập thì chuyển hướng về trang chủ
        if (isset($_SESSION['user_id'])) {
            header("Location: " . BASE_PATH . "/product");
            exit();
        }

        $username = '';
        $fullname = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

            if (!empty($username) && !empty($fullname) && !empty($password) && !empty($confirmPassword)) {
                if ($password !== $confirmPassword) {
                    $_SESSION['error'] = "Mật khẩu xác nhận không trùng khớp.";
                } else {
                    // Kiểm tra xem tên đăng nhập đã được sử dụng chưa
                    $existingUser = $this->accountModel->getByUsername($username);
                    if ($existingUser) {
                        $_SESSION['error'] = "Tên đăng nhập đã tồn tại, vui lòng chọn tên khác.";
                    } else {
                        // Tạo tài khoản mới
                        if ($this->accountModel->create($username, $fullname, $password)) {
                            SessionLogger::log("Đăng ký thành công tài khoản mới: " . $username);
                            $_SESSION['success'] = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
                            header("Location: " . BASE_PATH . "/account/login");
                            exit();
                        } else {
                            $_SESSION['error'] = "Đã xảy ra lỗi trong quá trình đăng ký. Vui lòng thử lại.";
                        }
                    }
                }
            } else {
                $_SESSION['error'] = "Vui lòng điền đầy đủ các thông tin đăng ký.";
            }
        }

        // Tải View Đăng ký
        $pageTitle = "Đăng ký tài khoản";
        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/account/register.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Chức năng Đăng xuất
    public function logout() {
        if (isset($_SESSION['username'])) {
            SessionLogger::log("Đăng xuất tài khoản: " . $_SESSION['username']);
        }
        
        // Hủy các session liên quan đến thông tin đăng nhập và giỏ hàng
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['fullname']);
        unset($_SESSION['role']);
        unset($_SESSION['cart']);
        
        $_SESSION['success'] = "Bạn đã đăng xuất tài khoản thành công.";
        header("Location: " . BASE_PATH . "/product");
        exit();
    }
}
