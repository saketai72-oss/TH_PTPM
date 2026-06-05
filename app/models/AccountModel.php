<?php

class AccountModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Lấy thông tin người dùng bằng username
    public function getByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username LIMIT 0,1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Tạo tài khoản người dùng mới
    public function create($username, $fullname, $password) {
        $query = "INSERT INTO users (username, fullname, password) VALUES (:username, :fullname, :password)";
        $stmt = $this->db->prepare($query);
        
        // Băm mật khẩu sử dụng Bcrypt
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
}
