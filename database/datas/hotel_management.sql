-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.21 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for hotel_manager
CREATE DATABASE IF NOT EXISTS `hotel_manager` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `hotel_manager`;

-- Dumping structure for table hotel_manager.bills
CREATE TABLE IF NOT EXISTS `bills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `room_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `bill_type` int DEFAULT NULL,
  `bill_start_time` datetime DEFAULT NULL,
  `bill_end_time` datetime DEFAULT NULL,
  `bill_total_time` int DEFAULT NULL,
  `bill_room_costs` int DEFAULT NULL,
  `bill_laundry_amount` int DEFAULT NULL,
  `bill_laundry_costs` int DEFAULT NULL,
  `bill_total_service_cost` int DEFAULT NULL,
  `bill_total_cost` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.bills: ~6 rows (approximately)
DELETE FROM `bills`;
/*!40000 ALTER TABLE `bills` DISABLE KEYS */;
INSERT INTO `bills` (`id`, `bill_code`, `room_code`, `bill_type`, `bill_start_time`, `bill_end_time`, `bill_total_time`, `bill_room_costs`, `bill_laundry_amount`, `bill_laundry_costs`, `bill_total_service_cost`, `bill_total_cost`, `created_at`, `updated_at`) VALUES
	(1, 'PE101MA1', 'E101', 1, '2014-09-01 09:00:00', '2014-09-01 11:47:00', 3, 80000, 2, 24000, 80000, 120000, '2020-11-07 07:38:34', '2020-11-07 00:38:35'),
	(2, 'PE102MA1', 'E102', 1, '2014-09-01 15:20:00', '2014-09-01 17:00:00', 2, 70000, 1, 12000, 70000, 90000, '2020-11-07 07:38:34', '2020-11-07 00:38:35'),
	(3, 'PE101MA2', 'E101', 2, '2014-09-02 21:00:00', '2014-09-03 09:15:00', 12, 150000, 2, 24000, 150000, 150000, '2020-11-07 07:38:34', '2020-11-07 07:38:34'),
	(4, 'PE102MA2', 'E102', 3, '2014-09-02 16:00:00', '2014-09-05 12:00:00', 68, 750000, 1, 12000, 750000, 750000, '2020-11-07 07:38:34', '2020-11-07 07:38:34'),
	(5, 'PE103MA1', 'E103', 3, '2014-09-03 13:05:00', NULL, -13, 2500000, 10, 120000, 2500000, 2500000, '2020-11-07 07:38:34', '2020-11-07 07:38:34'),
	(6, 'PE101MA3', 'E101', 3, '2014-09-03 07:20:00', NULL, -7, 0, 0, 0, 0, 0, '2020-11-07 07:38:34', '2020-11-07 07:38:34');
/*!40000 ALTER TABLE `bills` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_code` varchar(20) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_unit` varchar(20) DEFAULT NULL,
  `product_first_amount` int DEFAULT NULL,
  `product_amount` int DEFAULT NULL,
  `product_input_price` int DEFAULT NULL,
  `product_sale_price` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table hotel_manager.products: ~8 rows (approximately)
DELETE FROM `products`;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `product_code`, `product_name`, `product_unit`, `product_first_amount`, `product_amount`, `product_input_price`, `product_sale_price`, `created_at`, `updated_at`) VALUES
	(1, 'BCDR', 'Bàn chải đánh răng', 'Cái', 0, 20, 12000, 17000, '2020-11-07 07:38:13', '2020-11-07 00:38:35'),
	(2, 'BCS', 'Bao cao su', 'Cái', 0, 20, 10000, 20000, '2020-11-07 07:38:13', '2020-11-07 00:38:35'),
	(3, 'BIA', 'Bia', 'Lon', 0, 20, 7000, 10000, '2020-11-07 07:38:13', '2020-11-07 00:38:35'),
	(4, 'KR', 'Kem đánh răng', 'Cái', 0, 20, 7000, 10000, '2020-11-07 07:38:13', '2020-11-07 00:38:35'),
	(5, 'NS', 'Nước suối nhỏ', 'Chai', 0, 20, 5000, 10000, '2020-11-07 07:38:13', '2020-11-07 00:38:35'),
	(6, 'NT', 'Nước tăng lực', 'Lon', 0, 20, 10000, 15000, '2020-11-07 07:38:13', '2020-11-07 00:38:35'),
	(7, 'NX', 'Nước xúc miệng nhỏ', 'Bình', 0, 20, 15000, 25000, '2020-11-07 07:38:13', '2020-11-07 00:38:35'),
	(8, 'TA', 'Tăm ráy tai', 'Gói', 0, 20, 3000, 5000, '2020-11-07 07:38:13', '2020-11-07 00:38:35');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.product_enters
CREATE TABLE IF NOT EXISTS `product_enters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `enter_amount` int DEFAULT NULL,
  `enter_cost` int DEFAULT NULL,
  `enter_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.product_enters: ~8 rows (approximately)
DELETE FROM `product_enters`;
/*!40000 ALTER TABLE `product_enters` DISABLE KEYS */;
INSERT INTO `product_enters` (`id`, `product_code`, `enter_amount`, `enter_cost`, `enter_date`, `created_at`, `updated_at`) VALUES
	(1, 'BCDR', 20, 240000, '2014-08-30', '2020-11-07 07:38:35', '2020-11-07 07:38:35'),
	(2, 'BCS', 20, 200000, '2014-08-30', '2020-11-07 07:38:35', '2020-11-07 07:38:35'),
	(3, 'BIA', 20, 140000, '2014-08-30', '2020-11-07 07:38:35', '2020-11-07 07:38:35'),
	(4, 'KR', 20, 140000, '2014-08-30', '2020-11-07 07:38:35', '2020-11-07 07:38:35'),
	(5, 'NS', 20, 100000, '2014-08-30', '2020-11-07 07:38:35', '2020-11-07 07:38:35'),
	(6, 'NT', 20, 200000, '2014-08-30', '2020-11-07 07:38:35', '2020-11-07 07:38:35'),
	(7, 'NX', 20, 300000, '2014-08-30', '2020-11-07 07:38:35', '2020-11-07 07:38:35'),
	(8, 'TA', 20, 60000, '2014-08-30', '2020-11-07 07:38:35', '2020-11-07 07:38:35');
/*!40000 ALTER TABLE `product_enters` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.product_sales
CREATE TABLE IF NOT EXISTS `product_sales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sales_amount` int DEFAULT NULL,
  `sales_cost` int DEFAULT NULL,
  `sales_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.product_sales: ~3 rows (approximately)
DELETE FROM `product_sales`;
/*!40000 ALTER TABLE `product_sales` DISABLE KEYS */;
INSERT INTO `product_sales` (`id`, `bill_code`, `product_code`, `sales_amount`, `sales_cost`, `sales_date`, `created_at`, `updated_at`) VALUES
	(1, 'PE101MA1', 'BCS', 1, 20000, '2014-09-01', '2020-11-07 07:38:35', '2020-11-07 07:38:35'),
	(2, 'PE102MA1', 'NS', 2, 20000, '2014-09-01', '2020-11-07 07:38:35', '2020-11-07 07:38:35'),
	(3, 'PE101MA1', 'BIA', 2, 20000, '2014-09-01', '2020-11-07 07:38:35', '2020-11-07 07:38:35');
/*!40000 ALTER TABLE `product_sales` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.rooms: ~15 rows (approximately)
DELETE FROM `rooms`;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` (`id`, `room_code`, `created_at`, `updated_at`) VALUES
	(1, 'E101', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(2, 'E102', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(3, 'E103', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(4, 'E201', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(5, 'E202', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(6, 'E203', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(7, 'E301', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(8, 'E302', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(9, 'E303', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(10, 'E401', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(11, 'E402', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(12, 'E403', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(13, 'E501', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(14, 'E502', '2020-11-07 07:38:13', '2020-11-07 07:38:13'),
	(15, 'E503', '2020-11-07 07:38:13', '2020-11-07 07:38:13');
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
