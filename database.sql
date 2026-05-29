-- SQL Script for creating database and tables for Space Store (Planets Catalog)
CREATE DATABASE IF NOT EXISTS `webbanhang` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `webbanhang`;

-- Table structure for categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Sample data for space categories
INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Hành tinh đá', 'Các hành tinh có bề mặt rắn làm từ đá hoặc kim loại trong hệ Mặt Trời'),
(2, 'Hành tinh khí khổng lồ', 'Các hành tinh khổng lồ cấu tạo chủ yếu từ khí Hydro và Heli'),
(3, 'Hành tinh băng khổng lồ', 'Các hành tinh khổng lồ cấu tạo từ các hợp chất nặng hơn như nước, amoniac và metan');

-- Sample data for planet products
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`, `stock_quantity`) VALUES
(1, 'Trái Đất (Earth)', 'Hành tinh xanh xinh đẹp của chúng ta, nơi duy nhất trong vũ trụ được biết đến có sự sống phát triển mạnh mẽ. Thích hợp cho định cư lâu dài.', 99900000.00, NULL, 1, 5),
(2, 'Sao Hỏa (Mars)', 'Hành tinh đỏ huyền bí với những ngọn núi lửa khổng lồ và vết tích nước cổ xưa. Điểm đến tiếp theo cho hành trình chinh phục vũ trụ.', 85000000.00, NULL, 1, 8),
(3, 'Sao Mộc (Jupiter)', 'Gã khổng lồ khí lớn nhất Thái Dương Hệ với Vết Đỏ Lớn hoành tráng - một siêu bão khổng lồ đã hoành hành hàng trăm năm.', 150000000.00, NULL, 2, 3),
(4, 'Sao Thổ (Saturn)', 'Độc đáo với hệ thống vành đai băng đá lấp lánh kỳ vĩ bao quanh. Biểu tượng thẩm mỹ tuyệt đẹp của hệ Mặt Trời.', 180000000.00, NULL, 2, 4),
(5, 'Sao Thiên Vương (Uranus)', 'Hành tinh băng giá với trục quay nghiêng độc đáo gần như song song với mặt phẳng quỹ đạo. Khí quyển xanh lơ kỳ dị.', 120000000.00, NULL, 3, 6),
(6, 'Sao Hải Vương (Neptune)', 'Hành tinh xa xôi nhất trong hệ Mặt Trời, sở hữu những cơn gió siêu thanh mạnh nhất và sắc xanh đại dương sâu thẳm.', 130000000.00, NULL, 3, 7);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table structure for order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `quantity` INT NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
