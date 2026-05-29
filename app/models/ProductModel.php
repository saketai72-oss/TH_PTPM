<?php

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Lấy toàn bộ danh sách sản phẩm (có thể lọc theo từ khóa tìm kiếm)
    public function getAll($search = null) {
        $query = "SELECT p.*, c.name AS category_name 
                  FROM products p 
                  JOIN categories c ON p.category_id = c.id";
                  
        if ($search !== null && $search !== '') {
            $query .= " WHERE p.name LIKE :search OR p.description LIKE :search";
        }
        
        $query .= " ORDER BY p.id DESC";
        
        $stmt = $this->db->prepare($query);
        
        if ($search !== null && $search !== '') {
            $searchTerm = "%{$search}%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy thông tin chi tiết một sản phẩm theo ID
    public function getById($id) {
        $query = "SELECT p.*, c.name AS category_name 
                  FROM products p 
                  JOIN categories c ON p.category_id = c.id 
                  WHERE p.id = :id LIMIT 0,1";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Thêm mới một sản phẩm
    public function create($name, $description, $price, $image, $category_id, $stock_quantity) {
        $query = "INSERT INTO products (name, description, price, image, category_id, stock_quantity) 
                  VALUES (:name, :description, :price, :image, :category_id, :stock_quantity)";
                  
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':stock_quantity', $stock_quantity, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Cập nhật sản phẩm hiện có
    public function update($id, $name, $description, $price, $image, $category_id, $stock_quantity) {
        $query = "UPDATE products 
                  SET name = :name, description = :description, price = :price, 
                      image = :image, category_id = :category_id, stock_quantity = :stock_quantity 
                  WHERE id = :id";
                  
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':stock_quantity', $stock_quantity, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Xóa sản phẩm theo ID
    public function delete($id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Trừ số lượng tồn kho của sản phẩm khi đặt hàng thành công
    public function deductStock($id, $quantity) {
        $query = "UPDATE products 
                  SET stock_quantity = stock_quantity - :quantity 
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Kiểm tra xem sản phẩm đã từng được đặt hàng chưa
    public function isOrdered($id) {
        $query = "SELECT COUNT(*) as count FROM order_details WHERE product_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return isset($result['count']) ? intval($result['count']) > 0 : false;
    }
}
