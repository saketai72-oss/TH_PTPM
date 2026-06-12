<?php

class ApiController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    // Hiển thị giao diện API Sandbox để kiểm thử trực quan
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để sử dụng API Sandbox.";
            header("Location: " . BASE_PATH . "/account/login");
            exit();
        }

        $categories = $this->categoryModel->getAll();
        
        $pageTitle = "API Sandbox - Kiểm thử RESTful API";
        
        // Gọi giao diện Header, View Sandbox và Footer
        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/api/index.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // RESTful API Endpoint cho Sản phẩm (Products): /api/product/{id}
    public function product($id = null) {
        // Thiết lập header trả về định dạng JSON
        header('Content-Type: application/json; charset=utf-8');
        
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'GET':
                if ($id !== null) {
                    $this->getProductDetail($id);
                } else {
                    $this->getProductsList();
                }
                break;
                
            case 'POST':
                $this->createProduct();
                break;
                
            case 'PUT':
                $this->updateProduct($id);
                break;
                
            case 'DELETE':
                $this->deleteProduct($id);
                break;
                
            default:
                http_response_code(405);
                echo json_encode([
                    'success' => false,
                    'message' => "Phương thức $method không được hỗ trợ cho endpoint này."
                ], JSON_UNESCAPED_UNICODE);
                break;
        }
    }

    // Lấy danh sách sản phẩm (GET /api/product)
    private function getProductsList() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $products = $this->productModel->getAll($search);
        
        // Log hành động
        SessionLogger::log(empty($search) ? "API: Xem danh sách đồ chơi" : "API: Tìm kiếm đồ chơi với từ khóa '$search'");
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'count' => count($products),
            'data' => $products
        ], JSON_UNESCAPED_UNICODE);
    }

    // Lấy chi tiết một sản phẩm (GET /api/product/{id})
    private function getProductDetail($id) {
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => "Không tìm thấy đồ chơi có ID = $id"
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        SessionLogger::log("API: Xem chi tiết đồ chơi: " . $product['name']);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $product
        ], JSON_UNESCAPED_UNICODE);
    }

    // Thêm mới sản phẩm (POST /api/product)
    private function createProduct() {
        // Lấy dữ liệu payload (chấp nhận cả JSON và Form Data)
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $name = isset($input['name']) ? trim($input['name']) : '';
        $description = isset($input['description']) ? trim($input['description']) : '';
        $price = isset($input['price']) ? floatval($input['price']) : 0.0;
        $category_id = isset($input['category_id']) ? intval($input['category_id']) : 0;
        $stock_quantity = isset($input['stock_quantity']) ? intval($input['stock_quantity']) : 0;
        $image = isset($input['image']) ? trim($input['image']) : null; // Cho phép truyền ảnh dạng text hoặc để trống

        // Kiểm tra hợp lệ dữ liệu
        if (empty($name) || $price <= 0 || $category_id <= 0 || $stock_quantity < 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ. Vui lòng điền đầy đủ: name, price (>0), category_id (>0), và stock_quantity (>=0).'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Thực hiện thêm mới sản phẩm
        if ($this->productModel->create($name, $description, $price, $image, $category_id, $stock_quantity)) {
            SessionLogger::log("API: Thêm mới đồ chơi thành công: " . $name);
            
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => "Thêm sản phẩm \"$name\" thành công!",
                'data' => [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'category_id' => $category_id,
                    'stock_quantity' => $stock_quantity,
                    'image' => $image
                ]
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống khi lưu trữ sản phẩm.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    // Cập nhật sản phẩm (PUT /api/product/{id})
    private function updateProduct($id) {
        // Kiểm tra ID
        if ($id === null || $id === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu ID sản phẩm cần cập nhật.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Lấy thông tin sản phẩm hiện tại
        $product = $this->productModel->getById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => "Không tìm thấy sản phẩm có ID = $id để cập nhật."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Đọc dữ liệu PUT (JSON hoặc urlencoded)
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            parse_str(file_get_contents('php://input'), $input);
        }

        // Sử dụng giá trị cũ nếu không được truyền trong payload mới
        $name = isset($input['name']) ? trim($input['name']) : $product['name'];
        $description = isset($input['description']) ? trim($input['description']) : $product['description'];
        $price = isset($input['price']) ? floatval($input['price']) : floatval($product['price']);
        $category_id = isset($input['category_id']) ? intval($input['category_id']) : intval($product['category_id']);
        $stock_quantity = isset($input['stock_quantity']) ? intval($input['stock_quantity']) : intval($product['stock_quantity']);
        $image = isset($input['image']) ? trim($input['image']) : $product['image'];

        // Kiểm tra tính hợp lệ
        if (empty($name) || $price <= 0 || $category_id <= 0 || $stock_quantity < 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ. Vui lòng đảm bảo các trường bắt buộc không trống và giá trị hợp lệ.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Cập nhật sản phẩm
        if ($this->productModel->update($id, $name, $description, $price, $image, $category_id, $stock_quantity)) {
            SessionLogger::log("API: Cập nhật thông tin đồ chơi: " . $name);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => "Cập nhật sản phẩm \"$name\" thành công!",
                'data' => [
                    'id' => $id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'category_id' => $category_id,
                    'stock_quantity' => $stock_quantity,
                    'image' => $image
                ]
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống khi cập nhật thông tin sản phẩm.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    // Xóa sản phẩm (DELETE /api/product/{id})
    private function deleteProduct($id) {
        if ($id === null || $id === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu ID sản phẩm cần xóa.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Kiểm tra sự tồn tại của sản phẩm
        $product = $this->productModel->getById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => "Không tìm thấy sản phẩm có ID = $id để xóa."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Kiểm tra ràng buộc đơn hàng
        if ($this->productModel->isOrdered($id)) {
            http_response_code(409); // Conflict status code
            echo json_encode([
                'success' => false,
                'message' => "Không thể xóa sản phẩm \"{$product['name']}\" vì đã có đơn đặt hàng liên kết."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Thực hiện xóa
        if ($this->productModel->delete($id)) {
            SessionLogger::log("API: Xóa đồ chơi: " . $product['name']);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => "Xóa đồ chơi \"{$product['name']}\" thành công!"
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống khi thực hiện xóa sản phẩm.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    // Hàm tiện ích kiểm tra xem người dùng hiện tại có phải admin hay không
    private function isAdmin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
    }

    // RESTful API Endpoint cho Danh mục (Categories): /api/category/{id}
    public function category($id = null) {
        // Thiết lập header trả về định dạng JSON
        header('Content-Type: application/json; charset=utf-8');
        
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'GET':
                if ($id !== null) {
                    $this->getCategoryDetail($id);
                } else {
                    $this->getCategoriesList();
                }
                break;
                
            case 'POST':
                $this->createCategory();
                break;
                
            case 'PUT':
                $this->updateCategory($id);
                break;
                
            case 'DELETE':
                $this->deleteCategory($id);
                break;
                
            default:
                http_response_code(405);
                echo json_encode([
                    'success' => false,
                    'message' => "Phương thức $method không được hỗ trợ cho endpoint này."
                ], JSON_UNESCAPED_UNICODE);
                break;
        }
    }

    // Lấy danh sách danh mục (GET /api/category)
    private function getCategoriesList() {
        $categories = $this->categoryModel->getAll();
        
        SessionLogger::log("API: Xem danh sách danh mục");
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'count' => count($categories),
            'data' => $categories
        ], JSON_UNESCAPED_UNICODE);
    }

    // Lấy chi tiết một danh mục (GET /api/category/{id})
    private function getCategoryDetail($id) {
        $category = $this->categoryModel->getById($id);
        
        if (!$category) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => "Không tìm thấy danh mục có ID = $id"
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        SessionLogger::log("API: Xem chi tiết danh mục: " . $category['name']);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $category
        ], JSON_UNESCAPED_UNICODE);
    }

    // Thêm mới danh mục (POST /api/category)
    private function createCategory() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $name = isset($input['name']) ? trim($input['name']) : '';
        $description = isset($input['description']) ? trim($input['description']) : '';

        if (empty($name)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ. Tên danh mục (name) không được để trống.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($this->categoryModel->create($name, $description)) {
            SessionLogger::log("API: Thêm mới danh mục thành công: " . $name);
            
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => "Thêm danh mục \"$name\" thành công!",
                'data' => [
                    'name' => $name,
                    'description' => $description
                ]
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống khi lưu trữ danh mục.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    // Cập nhật danh mục (PUT /api/category/{id})
    private function updateCategory($id) {
        if ($id === null || $id === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu ID danh mục cần cập nhật.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $category = $this->categoryModel->getById($id);
        if (!$category) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => "Không tìm thấy danh mục có ID = $id để cập nhật."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            parse_str(file_get_contents('php://input'), $input);
        }

        $name = isset($input['name']) ? trim($input['name']) : $category['name'];
        $description = isset($input['description']) ? trim($input['description']) : $category['description'];

        if (empty($name)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Tên danh mục không được để trống.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($this->categoryModel->update($id, $name, $description)) {
            SessionLogger::log("API: Cập nhật danh mục: " . $name);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => "Cập nhật danh mục \"$name\" thành công!",
                'data' => [
                    'id' => $id,
                    'name' => $name,
                    'description' => $description
                ]
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống khi cập nhật danh mục.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    // Xóa danh mục (DELETE /api/category/{id})
    private function deleteCategory($id) {
        if ($id === null || $id === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu ID danh mục cần xóa.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $category = $this->categoryModel->getById($id);
        if (!$category) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => "Không tìm thấy danh mục có ID = $id để xóa."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Ràng buộc số lượng sản phẩm liên kết
        $productCount = $this->categoryModel->countProducts($id);
        if ($productCount > 0) {
            http_response_code(409); // Conflict
            echo json_encode([
                'success' => false,
                'message' => "Không thể xóa danh mục \"{$category['name']}\" vì đang chứa {$productCount} sản phẩm liên kết."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($this->categoryModel->delete($id)) {
            SessionLogger::log("API: Xóa danh mục: " . $category['name']);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => "Xóa danh mục \"{$category['name']}\" thành công!"
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống khi thực hiện xóa danh mục.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
