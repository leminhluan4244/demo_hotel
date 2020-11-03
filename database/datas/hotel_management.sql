-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.21 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for hotel_manager
CREATE DATABASE IF NOT EXISTS `hotel_manager` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `hotel_manager`;

-- Dumping structure for table hotel_manager.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_code` varchar(20) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_unit` varchar(20) DEFAULT NULL,
  `product_amount` int DEFAULT NULL,
  `product_input_price` int DEFAULT NULL,
  `product_sale_price` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='products information';

-- Dumping data for table hotel_manager.products: ~8 rows (approximately)
DELETE FROM `products`;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `product_code`, `product_name`, `product_unit`, `product_amount`, `product_input_price`, `product_sale_price`, `created_at`, `updated_at`) VALUES
	(1, 'BCDR', 'Bàn chải đánh răng', 'Cái', 0, 12000, 17000, '2020-11-03 23:57:15', '2020-11-03 23:57:15'),
	(2, 'BCS', 'Bao cao su', 'Cái', 0, 10000, 20000, '2020-11-03 23:57:15', '2020-11-03 23:57:15'),
	(3, 'BIA', 'Bia', 'Lon', 0, 7000, 10000, '2020-11-03 23:57:15', '2020-11-03 23:57:15'),
	(4, 'KR', 'Kem đánh răng', 'Cái', 0, 7000, 10000, '2020-11-03 23:57:15', '2020-11-03 23:57:15'),
	(5, 'NS', 'Nước suối nhỏ', 'Chai', 0, 5000, 10000, '2020-11-03 23:57:15', '2020-11-03 23:57:15'),
	(6, 'NT', 'Nước tăng lực', 'Lon', 0, 10000, 15000, '2020-11-03 23:57:15', '2020-11-03 23:57:15'),
	(7, 'NX', 'Nước xúc miệng nhỏ', 'Bình', 0, 15000, 25000, '2020-11-03 23:57:15', '2020-11-03 23:57:15'),
	(8, 'TA', 'Tăm ráy tai', 'Gói', 0, 3000, 5000, '2020-11-03 23:57:15', '2020-11-03 23:57:15');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.product_sales
CREATE TABLE IF NOT EXISTS `product_sales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_code` varchar(20) DEFAULT NULL,
  `room_code` varchar(20) DEFAULT NULL,
  `sales_date` date DEFAULT NULL,
  `sales_amount` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_code_UNIQUE` (`product_code`),
  UNIQUE KEY `room_code_UNIQUE` (`room_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='product sales';

-- Dumping data for table hotel_manager.product_sales: ~0 rows (approximately)
DELETE FROM `product_sales`;
/*!40000 ALTER TABLE `product_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_sales` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `room_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `room_price` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.rooms: ~0 rows (approximately)
DELETE FROM `rooms`;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.room_histories
CREATE TABLE IF NOT EXISTS `room_histories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.room_histories: ~0 rows (approximately)
DELETE FROM `room_histories`;
/*!40000 ALTER TABLE `room_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_histories` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.room_sales
CREATE TABLE IF NOT EXISTS `room_sales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `room_start_time` datetime DEFAULT NULL,
  `room_end_time` datetime DEFAULT NULL,
  `room_salse_type` int DEFAULT NULL,
  `room_total_time` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.room_sales: ~0 rows (approximately)
DELETE FROM `room_sales`;
/*!40000 ALTER TABLE `room_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_sales` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
