<?php
// Bật hiển thị lỗi để phục vụ việc debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Khởi động session để lưu trữ các thông báo phản hồi (success, error)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Định nghĩa tự động nạp các tệp lớp (Autoload)
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/app/controllers/',
        __DIR__ . '/app/models/',
        __DIR__ . '/app/database/'
    ];
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Định nghĩa BASE_PATH động để quản lý đường dẫn tuyệt đối cho tài nguyên (CSS, JS, Links)
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$basePath = ($scriptName === '/') ? '' : $scriptName;
define('BASE_PATH', $basePath);

// Xử lý và phân tích URL Định tuyến (Routing)
$url = '';
if (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    // Lấy thông tin từ REQUEST_URI (cho rewrite url bằng .htaccess)
    $requestUri = str_replace('\\', '/', $_SERVER['REQUEST_URI']);
    $requestUri = explode('?', $requestUri)[0]; // Bỏ phần tham số query string (?search=...)
    
    if (BASE_PATH !== '' && strpos($requestUri, BASE_PATH) === 0) {
        $requestUri = substr($requestUri, strlen(BASE_PATH));
    }
    $url = trim($requestUri, '/');
}

$urlParts = explode('/', $url);

// Xác định Controller, Action và Tham số
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'DefaultController';
$actionName = isset($urlParts[1]) && !empty($urlParts[1]) ? $urlParts[1] : 'index';
$param = isset($urlParts[2]) ? $urlParts[2] : null;

// Nếu lớp Controller không tồn tại, sử dụng DefaultController làm mặc định
if (!class_exists($controllerName)) {
    $controllerName = 'DefaultController';
    $actionName = 'index';
}

$controllerInstance = new $controllerName();

// Thực hiện gọi Action của Controller
if (method_exists($controllerInstance, $actionName)) {
    if ($param !== null) {
        $controllerInstance->$actionName($param);
    } else {
        $controllerInstance->$actionName();
    }
} else {
    header("HTTP/1.0 404 Not Found");
    // Gọi giao diện Header
    require_once __DIR__ . '/app/views/share/header.php';
    echo "
    <div class='empty-state'>
        <div class='empty-icon'>⚠️</div>
        <h2 class='empty-title'>404 - Trang không tìm thấy</h2>
        <p class='empty-desc'>Hành động <strong>$actionName</strong> trong bộ điều hướng <strong>$controllerName</strong> không tồn tại.</p>
        <a href='" . BASE_PATH . "/product' class='btn btn-primary'>Quay lại trang chủ</a>
    </div>";
    // Gọi giao diện Footer
    require_once __DIR__ . '/app/views/share/footer.php';
}
