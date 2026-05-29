<?php

class Database {
    private static $conn = null;
    private $host = 'localhost';
    private $db_name = 'webbanhang';
    private $username = 'root';
    private $password = ''; // Laragon mặc định để trống mật khẩu MySQL

    private function __construct() {
        // Hàm khởi tạo private để ngăn chặn tạo instance trực tiếp (Singleton)
    }

    public static function getConnection() {
        if (self::$conn === null) {
            $db = new self();
            try {
                self::$conn = new PDO(
                    "mysql:host=" . $db->host . ";dbname=" . $db->db_name . ";charset=utf8",
                    $db->username,
                    $db->password
                );
                // Thiết lập chế độ báo lỗi ngoại lệ để dễ dàng phát hiện lỗi SQL
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Thiết lập chế độ fetch mặc định là mảng kết hợp (Associative Array)
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $exception) {
                die("Lỗi kết nối cơ sở dữ liệu: " . $exception->getMessage());
            }
        }
        return self::$conn;
    }
}
