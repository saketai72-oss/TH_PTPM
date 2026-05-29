<?php

class HistoryController {
    // Show user activity logs
    public function index() {
        $actions = SessionLogger::getActions();
        
        // Render headers and views
        require_once __DIR__ . '/../views/share/header.php';
        require_once __DIR__ . '/../views/history/index.php';
        require_once __DIR__ . '/../views/share/footer.php';
    }

    // Clear activity logs
    public function clear() {
        SessionLogger::clear();
        $_SESSION['success'] = "Đã xóa toàn bộ lịch sử hoạt động!";
        header("Location: " . BASE_PATH . "/history");
        exit();
    }
}
