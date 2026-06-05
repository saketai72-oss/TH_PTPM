<?php

class CartModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Lấy giỏ hàng của người dùng dưới dạng [product_id => quantity]
    public function getCartByUserId($user_id) {
        $query = "SELECT product_id, quantity FROM cart WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $cart = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cart[$row['product_id']] = intval($row['quantity']);
        }
        return $cart;
    }

    // Đồng bộ giỏ hàng từ session vào cơ sở dữ liệu
    public function syncCart($user_id, $cart) {
        $this->db->beginTransaction();
        try {
            // 1. Xóa giỏ hàng cũ của người dùng
            $deleteQuery = "DELETE FROM cart WHERE user_id = :user_id";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $deleteStmt->execute();

            // 2. Chèn lại giỏ hàng mới
            if (!empty($cart) && is_array($cart)) {
                $insertQuery = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
                $insertStmt = $this->db->prepare($insertQuery);
                
                foreach ($cart as $product_id => $quantity) {
                    $quantity = intval($quantity);
                    if ($quantity > 0) {
                        $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $insertStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                        $insertStmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                        $insertStmt->execute();
                    }
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Xóa sạch giỏ hàng của người dùng trong DB
    public function clearCart($user_id) {
        $query = "DELETE FROM cart WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
