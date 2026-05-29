<?php

class ProductController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    // Trang hiển thị danh sách sản phẩm (có tìm kiếm)
    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $products = $this->productModel->getAll($search);
        
        // Ghi nhận hành động vào session
        SessionLogger::log(empty($search) ? "Xem danh sách hành tinh" : "Tìm kiếm hành tinh với từ khóa: '$search'");
        
        // Tải View
        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/product/list.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Trang chi tiết sản phẩm
    public function show($id) {
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            $_SESSION['error'] = "Không tìm thấy hành tinh yêu cầu.";
            header("Location: " . BASE_PATH . "/product");
            exit();
        }

        SessionLogger::log("Xem chi tiết hành tinh: " . $product['name']);

        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/product/show.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Thêm mới sản phẩm
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
            $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
            $stock_quantity = isset($_POST['stock_quantity']) ? intval($_POST['stock_quantity']) : 0;
            
            // Xử lý tải lên hình ảnh
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                // Tạo tên file ngẫu nhiên để tránh trùng lặp
                $imageName = uniqid() . '.' . $fileExtension;
                $targetFile = $uploadDir . $imageName;
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $imageName = null; // Trở lại null nếu upload thất bại
                }
            }

            if (!empty($name) && $price > 0 && $category_id > 0 && $stock_quantity >= 0) {
                if ($this->productModel->create($name, $description, $price, $imageName, $category_id, $stock_quantity)) {
                    SessionLogger::log("Thêm mới hành tinh thành công: " . $name);
                    $_SESSION['success'] = "Thêm hành tinh thành công!";
                    header("Location: " . BASE_PATH . "/product");
                    exit();
                } else {
                    $_SESSION['error'] = "Đã xảy ra lỗi khi lưu hành tinh.";
                }
            } else {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin bắt buộc và số lượng hợp lệ.";
            }
        }

        // Phương thức GET: Hiển thị form thêm sản phẩm
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/product/add.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Chỉnh sửa sản phẩm
    public function edit($id) {
        $product = $this->productModel->getById($id);
        if (!$product) {
            $_SESSION['error'] = "Không tìm thấy sản phẩm cần chỉnh sửa.";
            header("Location: " . BASE_PATH . "/product");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
            $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
            $stock_quantity = isset($_POST['stock_quantity']) ? intval($_POST['stock_quantity']) : 0;
            
            // Xử lý giữ lại ảnh cũ hoặc thay thế ảnh mới
            $imageName = $product['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid() . '.' . $fileExtension;
                $targetFile = $uploadDir . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    // Xóa ảnh cũ nếu có
                    if ($product['image'] && file_exists($uploadDir . $product['image'])) {
                        unlink($uploadDir . $product['image']);
                    }
                } else {
                    $imageName = $product['image']; // Trở lại ảnh cũ nếu upload thất bại
                }
            }

            if (!empty($name) && $price > 0 && $category_id > 0 && $stock_quantity >= 0) {
                if ($this->productModel->update($id, $name, $description, $price, $imageName, $category_id, $stock_quantity)) {
                    SessionLogger::log("Cập nhật thông tin hành tinh: " . $name);
                    $_SESSION['success'] = "Cập nhật hành tinh thành công!";
                    header("Location: " . BASE_PATH . "/product");
                    exit();
                } else {
                    $_SESSION['error'] = "Đã xảy ra lỗi khi cập nhật thông tin hành tinh.";
                }
            } else {
                $_SESSION['error'] = "Vui lòng điền đầy đủ các thông tin bắt buộc và số lượng hợp lệ.";
            }
        }

        // Phương thức GET: Hiển thị form cập nhật
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/product/edit.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Xóa sản phẩm
    public function delete($id) {
        $product = $this->productModel->getById($id);
        if ($product) {
            // Kiểm tra xem hành tinh đã từng được đặt hàng chưa
            if ($this->productModel->isOrdered($id)) {
                $_SESSION['error'] = "Không thể xóa hành tinh <strong>\"" . htmlspecialchars($product['name']) . "\"</strong> vì đã có đơn đặt hàng liên kết với hành tinh này.";
                header("Location: " . BASE_PATH . "/product");
                exit();
            }

            // Xóa ảnh sản phẩm khỏi máy chủ
            if ($product['image']) {
                $imagePath = __DIR__ . '/../../public/uploads/' . $product['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            if ($this->productModel->delete($id)) {
                SessionLogger::log("Xóa hành tinh: " . $product['name']);
                $_SESSION['success'] = "Xóa hành tinh thành công!";
            } else {
                $_SESSION['error'] = "Không thể xóa hành tinh.";
            }
        } else {
            $_SESSION['error'] = "Không tìm thấy hành tinh để xóa.";
        }
        
        header("Location: " . BASE_PATH . "/product");
        exit();
    }
}
