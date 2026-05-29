<?php

class DefaultController {
    public function index() {
        // Chuyển hướng người dùng sang trang quản lý danh sách sản phẩm mặc định
        header("Location: " . BASE_PATH . "/product");
        exit();
    }
}
