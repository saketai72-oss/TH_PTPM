<?php

class CartController {
    private $productModel;
    private $orderModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
    }

    // Hiển thị giỏ hàng và form thanh toán
    public function index() {
        $cartItems = [];
        $totalAmount = 0.0;
        
        // Đọc dữ liệu từ Session giỏ hàng
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $product = $this->productModel->getById($id);
                if ($product) {
                    $product['quantity'] = $quantity;
                    $product['subtotal'] = $product['price'] * $quantity;
                    $totalAmount += $product['subtotal'];
                    $cartItems[] = $product;
                }
            }
        }
        
        SessionLogger::log("Xem giỏ hàng");

        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/cart/index.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Thêm một sản phẩm vào giỏ hàng
    public function add($id) {
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            $_SESSION['error'] = "Không tìm thấy sản phẩm cần thêm vào giỏ hàng.";
            header("Location: " . BASE_PATH . "/product");
            exit();
        }

        // Kiểm tra sản phẩm còn hàng không
        if ($product['stock_quantity'] <= 0) {
            $_SESSION['error'] = "Sản phẩm <strong>\"" . htmlspecialchars($product['name']) . "\"</strong> đã hết hàng.";
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: " . BASE_PATH . "/product");
            }
            exit();
        }

        // Khởi tạo mảng giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Kiểm tra tồn kho trước khi tăng số lượng
        $currentQty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id] : 0;
        if ($currentQty + 1 > $product['stock_quantity']) {
            $_SESSION['error'] = "Không thể thêm. Số lượng trong giỏ đã đạt giới hạn tồn kho (" . $product['stock_quantity'] . ") của sản phẩm này.";
        } else {
            $_SESSION['cart'][$id] = $currentQty + 1;
            SessionLogger::log("Thêm vào giỏ hàng: " . $product['name'] . " (Số lượng: 1)");
            $_SESSION['success'] = "Đã thêm <strong>\"" . htmlspecialchars($product['name']) . "\"</strong> vào giỏ hàng!";
        }
        
        // Quay lại trang trước
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: " . BASE_PATH . "/cart");
        }
        exit();
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];
            
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $hasWarning = false;
                foreach ($quantities as $id => $qty) {
                    $qty = intval($qty);
                    $product = $this->productModel->getById($id);
                    
                    if (!$product || $qty <= 0) {
                        unset($_SESSION['cart'][$id]);
                    } else {
                        // Kiểm tra tồn kho của sản phẩm
                        if ($qty > $product['stock_quantity']) {
                            $_SESSION['cart'][$id] = $product['stock_quantity'];
                            $hasWarning = true;
                        } else {
                            $_SESSION['cart'][$id] = $qty;
                        }
                    }
                }
                if ($hasWarning) {
                    $_SESSION['warning'] = "Một số sản phẩm đã được tự động giới hạn theo số lượng tồn kho thực tế.";
                } else {
                    SessionLogger::log("Cập nhật số lượng giỏ hàng");
                    $_SESSION['success'] = "Cập nhật giỏ hàng thành công!";
                }
            }
        }
        header("Location: " . BASE_PATH . "/cart");
        exit();
    }

    // Xóa một sản phẩm khỏi giỏ hàng
    public function delete($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
            SessionLogger::log("Xóa sản phẩm khỏi giỏ hàng");
            $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng.";
        } else {
            $_SESSION['error'] = "Không tìm thấy sản phẩm trong giỏ hàng.";
        }
        header("Location: " . BASE_PATH . "/cart");
        exit();
    }

    // Xử lý thanh toán và đặt hàng
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
            $customer_email = isset($_POST['customer_email']) ? trim($_POST['customer_email']) : '';
            $customer_phone = isset($_POST['customer_phone']) ? trim($_POST['customer_phone']) : '';
            $customer_address = isset($_POST['customer_address']) ? trim($_POST['customer_address']) : '';
            
            // Kiểm tra giỏ hàng rỗng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                $_SESSION['error'] = "Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm.";
                header("Location: " . BASE_PATH . "/product");
                exit();
            }

            // Kiểm tra thông tin nhập liệu bắt buộc
            if (empty($customer_name) || empty($customer_phone) || empty($customer_address) || empty($customer_email)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin thanh toán.";
                header("Location: " . BASE_PATH . "/cart");
                exit();
            }

            // Tính tổng tiền đơn hàng dựa trên CSDL thực tế và kiểm tra tồn kho một lần nữa
            $total_amount = 0.0;
            $itemsToSave = [];
            
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $product = $this->productModel->getById($id);
                if ($product) {
                    // Kiểm tra tồn kho trước khi đặt hàng
                    if ($quantity > $product['stock_quantity']) {
                        $_SESSION['error'] = "Đặt hàng thất bại. Sản phẩm <strong>\"" . htmlspecialchars($product['name']) . "\"</strong> chỉ còn <strong>" . $product['stock_quantity'] . "</strong> sản phẩm trong kho (Bạn yêu cầu: " . $quantity . "). Vui lòng cập nhật lại giỏ hàng.";
                        header("Location: " . BASE_PATH . "/cart");
                        exit();
                    }
                    
                    $subtotal = $product['price'] * $quantity;
                    $total_amount += $subtotal;
                    $itemsToSave[] = [
                        'product_id' => $id,
                        'price' => $product['price'],
                        'quantity' => $quantity
                    ];
                }
            }

            // Sinh mã hóa đơn duy nhất không trùng lặp (ORD-YYYYMMDD-XXXXXX)
            $order_code = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            // Lưu đơn hàng tổng quan
            $order_id = $this->orderModel->createOrder(
                $customer_name, 
                $customer_email, 
                $customer_phone, 
                $customer_address, 
                $total_amount,
                $order_code
            );

            if ($order_id) {
                // Lưu các sản phẩm chi tiết của đơn hàng và trừ kho
                foreach ($itemsToSave as $item) {
                    $this->orderModel->createOrderDetail(
                        $order_id, 
                        $item['product_id'], 
                        $item['price'], 
                        $item['quantity']
                    );
                    
                    // Trừ kho của sản phẩm
                    $this->productModel->deductStock($item['product_id'], $item['quantity']);
                }
                
                // Xóa giỏ hàng sau khi đặt thành công
                unset($_SESSION['cart']);
                
                SessionLogger::log("Đặt hàng thành công! Mã đơn hàng: " . $order_code);
                $_SESSION['success'] = "Đặt hàng thành công! Mã đơn hàng của bạn là: <strong>" . $order_code . "</strong>";
                header("Location: " . BASE_PATH . "/product");
                exit();
            } else {
                $_SESSION['error'] = "Đã xảy ra lỗi khi tạo đơn hàng. Vui lòng thử lại.";
                header("Location: " . BASE_PATH . "/cart");
                exit();
            }
        } else {
            header("Location: " . BASE_PATH . "/cart");
            exit();
        }
    }
}
