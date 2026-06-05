-- SQL Script for creating database and tables for Space Store (Planets Catalog)
CREATE DATABASE IF NOT EXISTS `webbanhang` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `webbanhang`;

-- Table structure for categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for products
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `image` VARCHAR(255) NULL,
  `category_id` INT NOT NULL,
  `stock_quantity` INT NOT NULL DEFAULT 10,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data for toy categories
INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Đồ chơi lắp ráp & Trí tuệ', 'Các bộ lắp ráp Lego, rubik và đồ chơi kích thích tư duy logic phát triển trí tuệ của trẻ'),
(2, 'Đồ chơi vận động ngoài trời', 'Xe đạp, xe chòi chân, bóng và các dụng cụ giúp phát triển thể chất của bé'),
(3, 'Đồ chơi mô hình & Búp bê', 'Búp bê thời trang, siêu nhân biến hình, xe mô hình sắc sảo dành cho cả bé trai và bé gái');

-- Sample data for toy products
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`, `stock_quantity`) VALUES
(1, 'Bộ lắp ráp Lego Classic 1500 chi tiết', 'Bộ gạch xếp hình Lego Classic đa sắc màu cho bé tự do sáng tạo thế giới của riêng mình. Chất liệu nhựa ABS nguyên sinh tuyệt đối an toàn.', 790000.00, NULL, 1, 15),
(2, 'Xe chòi chân thăng bằng Joovy', 'Thiết kế khí động học chắc chắn, khung thép carbon nhẹ giúp bé rèn luyện thể chất và khả năng giữ thăng bằng từ nhỏ.', 450000.00, NULL, 2, 20),
(3, 'Búp bê Barbie Công chúa lấp lánh', 'Bộ búp bê Barbie thời trang lộng lẫy mang lại những giờ phút vui chơi nhập vai thế giới thần tiên đầy sắc màu cho bé gái.', 320000.00, NULL, 3, 12),
(4, 'Đất nặn Play-Doh 10 hũ màu sắc', 'Bộ đất nặn cao cấp làm từ bột mì tự nhiên không độc hại, kích thích kỹ năng vận động tinh và sự khéo léo của đôi bàn tay.', 190000.00, NULL, 1, 25),
(5, 'Robot biến hình Transformers Bumblebee', 'Mô hình robot tự động chuyển đổi từ dạng chiến binh sang siêu xe thể thao Chevrolet Camaro chỉ với 15 bước lắp ráp.', 580000.00, NULL, 3, 8),
(6, 'Rubik Gan 356 M thế hệ mới', 'Khối rubik nam châm chuyên nghiệp xoay cực êm, chống pop tốt, thích hợp cho bé luyện tập tốc độ và sự tập trung.', 220000.00, NULL, 1, 30);

-- Table structure for orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_code` VARCHAR(50) UNIQUE NOT NULL,
  `customer_name` VARCHAR(255) NOT NULL,
  `customer_email` VARCHAR(255) NOT NULL,
  `customer_phone` VARCHAR(255) NOT NULL,
  `customer_address` TEXT NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `quantity` INT NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for users (Bài 4 - Xác thực người dùng)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `fullname` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default accounts (admin / admin123, user / user123)
INSERT INTO `users` (`username`, `fullname`, `password`, `role`) VALUES
('admin', 'Quản trị viên', '$2y$10$.PgouZ8XfN8L760gAcopQORB5l3rsj2G3pxj/QGQmwLkS6IOnEJVG', 'admin'),
('user', 'Người dùng thử', '$2y$10$KOW/fe3jiv.n0PTTOdDIhe0hm7MeEU.2fpdM39Aba68.KWdVvfq6.', 'user');

-- Table structure for cart (Lưu trữ giỏ hàng của người dùng)
CREATE TABLE IF NOT EXISTS `cart` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  UNIQUE KEY `user_product` (`user_id`, `product_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
