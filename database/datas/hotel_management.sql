-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.21 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8 */;
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
	(1, 'PE101MA1', 'E101', 1, '2014-09-01 09:00:00', '2014-09-01 11:47:00', 3, 80000, 2, 24000, 80000, 120000, '2020-11-13 00:52:33', '2020-11-12 17:52:34'),
	(2, 'PE102MA1', 'E102', 1, '2014-09-01 15:20:00', '2014-09-01 17:00:00', 2, 70000, 1, 12000, 70000, 90000, '2020-11-13 00:52:33', '2020-11-12 17:52:34'),
	(3, 'PE101MA2', 'E101', 2, '2014-09-02 21:00:00', '2014-09-03 09:15:00', 12, 150000, 2, 24000, 150000, 150000, '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(4, 'PE102MA2', 'E102', 3, '2014-09-02 16:00:00', '2014-09-05 12:00:00', 68, 750000, 1, 12000, 750000, 750000, '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(5, 'PE103MA1', 'E103', 3, '2014-09-03 13:05:00', NULL, -13, 2500000, 10, 120000, 2500000, 2500000, '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(6, 'PE101MA3', 'E101', 3, '2014-09-03 07:20:00', NULL, -7, 0, 0, 0, 0, 0, '2020-11-13 00:52:33', '2020-11-13 00:52:33');
/*!40000 ALTER TABLE `bills` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.migrations: 0 rows
DELETE FROM `migrations`;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
	(2, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
	(3, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
	(4, '2016_06_01_000004_create_oauth_clients_table', 1),
	(5, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.oauth_access_tokens: 0 rows
DELETE FROM `oauth_access_tokens`;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
	('51a6e6d2c8836a412b760d74bc4cfdbee2ea6c8877fa053a6245769065a65e799a09fd63a32e7414', 4, 1, 'Personal Access Token', '[]', 1, '2020-11-12 15:34:49', '2020-11-12 15:34:49', '2020-11-19 15:34:49'),
	('7cb18c6d53b1c5f62c7f537284f1680bb858210e14d87117afecdf55fc25d8ed9c745010c96c7edf', 4, 1, 'Personal Access Token', '[]', 0, '2020-11-12 17:43:58', '2020-11-12 17:43:58', '2020-11-19 17:43:58');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.oauth_auth_codes
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.oauth_auth_codes: 0 rows
DELETE FROM `oauth_auth_codes`;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.oauth_clients: 0 rows
DELETE FROM `oauth_clients`;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'Laravel Personal Access Client', 'dwSNdUFaH9z1fFRUDt7x4brPjdfbRGv2fvOzmgUp', NULL, 'http://localhost', 1, 0, 0, '2020-11-12 15:13:17', '2020-11-12 15:13:17'),
	(2, NULL, 'Laravel Password Grant Client', '6ulUguOJRPSUr2FginGeUQLZEaETdRex7pBxEuYf', 'users', 'http://localhost', 0, 1, 0, '2020-11-12 15:13:17', '2020-11-12 15:13:17');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.oauth_personal_access_clients
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.oauth_personal_access_clients: 0 rows
DELETE FROM `oauth_personal_access_clients`;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
	(1, 1, '2020-11-12 15:13:17', '2020-11-12 15:13:17');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.oauth_refresh_tokens: 0 rows
DELETE FROM `oauth_refresh_tokens`;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.products: ~8 rows (approximately)
DELETE FROM `products`;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `product_code`, `product_name`, `product_unit`, `product_first_amount`, `product_amount`, `product_input_price`, `product_sale_price`, `created_at`, `updated_at`) VALUES
	(1, 'BCDR', 'Bàn chải đánh răng', 'Cái', 0, 20, 12000, 17000, '2020-11-13 00:51:44', '2020-11-12 17:52:34'),
	(2, 'BCS', 'Bao cao su', 'Cái', 0, 20, 10000, 20000, '2020-11-13 00:51:44', '2020-11-12 17:52:34'),
	(3, 'BIA', 'Bia', 'Lon', 0, 20, 7000, 10000, '2020-11-13 00:51:44', '2020-11-12 17:52:34'),
	(4, 'KR', 'Kem đánh răng', 'Cái', 0, 20, 7000, 10000, '2020-11-13 00:51:44', '2020-11-12 17:52:34'),
	(5, 'NS', 'Nước suối nhỏ', 'Chai', 0, 20, 5000, 10000, '2020-11-13 00:51:44', '2020-11-12 17:52:34'),
	(6, 'NT', 'Nước tăng lực', 'Lon', 0, 20, 10000, 15000, '2020-11-13 00:51:44', '2020-11-12 17:52:34'),
	(7, 'NX', 'Nước xúc miệng nhỏ', 'Bình', 0, 20, 15000, 25000, '2020-11-13 00:51:44', '2020-11-12 17:52:34'),
	(8, 'TA', 'Tăm ráy tai', 'Gói', 0, 20, 3000, 5000, '2020-11-13 00:51:44', '2020-11-12 17:52:34');
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
	(1, 'BCDR', 20, 240000, '2014-08-30', '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(2, 'BCS', 20, 200000, '2014-08-30', '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(3, 'BIA', 20, 140000, '2014-08-30', '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(4, 'KR', 20, 140000, '2014-08-30', '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(5, 'NS', 20, 100000, '2014-08-30', '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(6, 'NT', 20, 200000, '2014-08-30', '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(7, 'NX', 20, 300000, '2014-08-30', '2020-11-13 00:52:33', '2020-11-13 00:52:33'),
	(8, 'TA', 20, 60000, '2014-08-30', '2020-11-13 00:52:33', '2020-11-13 00:52:33');
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
	(1, 'PE101MA1', 'BCS', 1, 20000, '2014-09-01', '2020-11-13 00:52:34', '2020-11-13 00:52:34'),
	(2, 'PE102MA1', 'NS', 2, 20000, '2014-09-01', '2020-11-13 00:52:34', '2020-11-13 00:52:34'),
	(3, 'PE101MA1', 'BIA', 2, 20000, '2014-09-01', '2020-11-13 00:52:34', '2020-11-13 00:52:34');
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
	(1, 'E101', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(2, 'E102', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(3, 'E103', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(4, 'E201', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(5, 'E202', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(6, 'E203', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(7, 'E301', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(8, 'E302', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(9, 'E303', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(10, 'E401', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(11, 'E402', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(12, 'E403', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(13, 'E501', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(14, 'E502', '2020-11-13 00:51:44', '2020-11-13 00:51:44'),
	(15, 'E503', '2020-11-13 00:51:44', '2020-11-13 00:51:44');
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;

-- Dumping structure for table hotel_manager.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `api_token` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_token` (`api_token`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table hotel_manager.users: 0 rows
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `api_token`, `remember_token`, `created_at`, `updated_at`) VALUES
	(4, 'admin', 'admin@gmail.com', NULL, '$2y$10$iwLgjE95gh1bZQH2Ynw.V.6HSl71EsmblEP98K1NWceCa0Er7.PAm', NULL, NULL, '2020-11-12 15:29:41', '2020-11-12 15:29:41');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
