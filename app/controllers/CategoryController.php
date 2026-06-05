<?php

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    // Hiển thị danh sách danh mục
    public function index() {
        $categories = $this->categoryModel->getAll();
        
        // Ghi nhận hành động vào session
        SessionLogger::log("Xem danh sách danh mục đồ chơi");

        // Tạo một mảng lưu số lượng sản phẩm cho từng danh mục
        $productCounts = [];
        foreach ($categories as $category) {
            $productCounts[$category['id']] = $this->categoryModel->countProducts($category['id']);
        }
        
        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/category/list.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Thêm mới danh mục
    public function add() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này.";
            header("Location: " . BASE_PATH . "/category");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';

            if (!empty($name)) {
                if ($this->categoryModel->create($name, $description)) {
                    SessionLogger::log("Thêm mới danh mục thành công: " . $name);
                    $_SESSION['success'] = "Thêm danh mục thành công!";
                    header("Location: " . BASE_PATH . "/category");
                    exit();
                } else {
                    $_SESSION['error'] = "Có lỗi xảy ra khi lưu danh mục.";
                }
            } else {
                $_SESSION['error'] = "Tên danh mục không được để trống.";
            }
        }

        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/category/add.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Chỉnh sửa danh mục
    public function edit($id) {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này.";
            header("Location: " . BASE_PATH . "/category");
            exit();
        }

        $category = $this->categoryModel->getById($id);
        if (!$category) {
            $_SESSION['error'] = "Không tìm thấy danh mục cần chỉnh sửa.";
            header("Location: " . BASE_PATH . "/category");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';

            if (!empty($name)) {
                if ($this->categoryModel->update($id, $name, $description)) {
                    SessionLogger::log("Cập nhật thông tin danh mục: " . $name);
                    $_SESSION['success'] = "Cập nhật danh mục thành công!";
                    header("Location: " . BASE_PATH . "/category");
                    exit();
                } else {
                    $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật danh mục.";
                }
            } else {
                $_SESSION['error'] = "Tên danh mục không được để trống.";
            }
        }

        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/category/edit.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Xóa danh mục (Kiểm tra ràng buộc đếm sản phẩm)
    public function delete($id) {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này.";
            header("Location: " . BASE_PATH . "/category");
            exit();
        }

        $category = $this->categoryModel->getById($id);
        if ($category) {
            // Kiểm tra số lượng sản phẩm liên kết
            $productCount = $this->categoryModel->countProducts($id);
            
            if ($productCount > 0) {
                // Ràng buộc: Không cho phép xóa nếu có sản phẩm
                $_SESSION['error'] = "Không thể xóa danh mục <strong>\"" . htmlspecialchars($category['name']) . "\"</strong> vì hiện tại đang chứa <strong>" . $productCount . "</strong> sản phẩm.";
            } else {
                // Không có sản phẩm nào liên kết: Thực hiện xóa
                if ($this->categoryModel->delete($id)) {
                    SessionLogger::log("Xóa danh mục: " . $category['name']);
                    $_SESSION['success'] = "Xóa danh mục thành công!";
                } else {
                    $_SESSION['error'] = "Đã xảy ra lỗi khi xóa danh mục.";
                }
            }
        } else {
            $_SESSION['error'] = "Không tìm thấy danh mục để xóa.";
        }

        header("Location: " . BASE_PATH . "/category");
        exit();
    }
}
