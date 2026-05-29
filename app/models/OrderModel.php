<?php

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Tạo đơn hàng mới và trả về ID đơn hàng vừa tạo
    public function createOrder($customer_name, $customer_email, $customer_phone, $customer_address, $total_amount, $order_code) {
        $query = "INSERT INTO orders (order_code, customer_name, customer_email, customer_phone, customer_address, total_amount) 
                  VALUES (:order_code, :customer_name, :customer_email, :customer_phone, :customer_address, :total_amount)";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_code', $order_code, PDO::PARAM_STR);
        $stmt->bindParam(':customer_name', $customer_name, PDO::PARAM_STR);
        $stmt->bindParam(':customer_email', $customer_email, PDO::PARAM_STR);
        $stmt->bindParam(':customer_phone', $customer_phone, PDO::PARAM_STR);
        $stmt->bindParam(':customer_address', $customer_address, PDO::PARAM_STR);
        $stmt->bindParam(':total_amount', $total_amount);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Thêm các sản phẩm chi tiết của đơn hàng
    public function createOrderDetail($order_id, $product_id, $price, $quantity) {
        $query = "INSERT INTO order_details (order_id, product_id, price, quantity) 
                  VALUES (:order_id, :product_id, :price, :quantity)";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
