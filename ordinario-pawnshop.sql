/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100411
 Source Host           : localhost:3306
 Source Schema         : ordinario-pawnshop

 Target Server Type    : MySQL
 Target Server Version : 100411
 File Encoding         : 65001

 Date: 26/09/2020 13:05:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for attachments
-- ----------------------------
DROP TABLE IF EXISTS `attachments`;
CREATE TABLE `attachments`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attachments
-- ----------------------------
INSERT INTO `attachments` VALUES (1, 'Drivers License', NULL, '2020-09-18 06:49:29', '2020-09-18 06:49:29');

-- ----------------------------
-- Table structure for auctions
-- ----------------------------
DROP TABLE IF EXISTS `auctions`;
CREATE TABLE `auctions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `control_id` bigint(20) UNSIGNED NOT NULL,
  `auction_date` date NOT NULL,
  `inventory_auction_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(10, 4) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for branches
-- ----------------------------
DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of branches
-- ----------------------------
INSERT INTO `branches` VALUES (1, 'Main', 'Daet, Camarines Norte 4600', '915-198-482-000', '0547310600', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `suffix` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birthdate` date NOT NULL,
  `sex` enum('male','female') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `civil_status` enum('single','married','seperated','divorced','widowed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternate_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `present_address` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `present_address_two` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `present_area` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `present_city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `present_zip_code` smallint(6) NOT NULL,
  `permanent_address` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permanent_address_two` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permanent_area` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permanent_city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permanent_zip_code` smallint(6) NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES (1, 'Chona', 'Nano', 'Almadrones', NULL, '1988-11-21', 'female', 'single', 'test@test.com', '09503800220', '09503800220', 'Calangcawan Norte', 'Vinzons', 'CN', 'Camarines Norte', 4603, 'Calangcawan Norte', 'Vinzons', 'CN', 'Camarines Norte', 4603, NULL, '2020-09-24 05:30:55', '2020-09-24 05:30:55');
INSERT INTO `customers` VALUES (2, 'Rogelio', 'V', 'Bongat', 'Jr.', '1988-05-24', 'male', 'single', 'test@test.com', '09306565905', '09306565905', 'Mantagpac', 'Daet', 'CN', 'Camarines Norte', 4600, 'Mantagpac', 'Daet', 'CN', 'Camarines Norte', 4600, NULL, '2020-09-24 06:08:37', '2020-09-24 06:08:37');
INSERT INTO `customers` VALUES (3, 'Rosalinda', 'Bayta', 'Bacea', NULL, '1974-07-27', 'female', 'married', 'test@test.com', '09474395268', '09474395268', 'Cobangbang', 'Daet', 'CN', 'Camarines Norte', 4600, 'Cobangbang', 'Daet', 'CN', 'Camarines Norte', 4600, NULL, '2020-09-24 06:35:23', '2020-09-24 06:35:23');
INSERT INTO `customers` VALUES (4, 'Melinda', 'Jamito', 'Ponayo', NULL, '1985-09-08', 'female', 'single', 'test@test.com', '09309844301', '09309844301', 'Block 30 Lot 15A', 'Harmony Village II', 'Mancruz', 'Daet, Camarines Norte', 4600, 'Block 30 Lot 15A', 'Harmony Village II', 'Mancruz', 'Daet, Camarines Norte', 4600, NULL, '2020-09-24 07:54:47', '2020-09-24 07:54:47');
INSERT INTO `customers` VALUES (5, 'ADELA', 'X', 'MAGANA', NULL, '1988-01-01', 'female', 'married', 'test@test.com', '000001', '000001', 'ITOMANG', 'Talisay', 'CN', 'Camarines Norte', 4602, 'ITOMANG', 'Talisay', 'CN', 'Camarines Norte', 4602, NULL, '2020-09-24 08:29:09', '2020-09-24 08:29:09');
INSERT INTO `customers` VALUES (6, 'Gloria', 'Urbano', 'Lleno', NULL, '1950-02-20', 'female', 'married', 'test@test.com', '09176290406', '09176290406', 'Purok 3', 'Matnog', 'Basud', 'Camarines Norte', 4608, 'Purok 3', 'Matnog', 'Basud', 'Camarines Norte', 4608, NULL, '2020-09-24 08:35:14', '2020-09-24 08:35:14');
INSERT INTO `customers` VALUES (7, 'Norly', 'Prades', 'Asutilla', NULL, '1974-03-24', 'female', 'married', 'test@test.com', '09108893701', '09108893701', 'Hinipaan', 'Mercedes', 'CN', 'Camarines Norte', 4601, 'Hinipaan', 'Mercedes', 'CN', 'Camarines Norte', 4601, NULL, '2020-09-24 08:41:31', '2020-09-24 08:41:31');
INSERT INTO `customers` VALUES (8, 'Margee', 'Palacio', 'Rajas', NULL, '1991-08-04', 'female', 'married', 'test@test.com', '09999967100', '09999967100', 'Purok 4', 'San Vicente Road', 'Alawihao', 'Daet, Camarines Norte', 4600, 'Purok 4', 'San Vicente Road', 'Alawihao', 'Daet, Camarines Norte', 4600, NULL, '2020-09-24 08:52:29', '2020-09-24 08:52:29');
INSERT INTO `customers` VALUES (9, 'Sylvia', 'H', 'Paloma', NULL, '1969-04-07', 'female', 'married', 'test@test.com', '09394096482', '09394096482', 'Santa Elena', 'CN', 'CN', 'Camarines Norte', 4611, 'Santa Elena', 'CN', 'CN', 'Camarines Norte', 4611, NULL, '2020-09-24 09:07:54', '2020-09-24 09:07:54');
INSERT INTO `customers` VALUES (10, 'Bella', NULL, 'Factor', NULL, '1977-05-29', 'female', 'married', 'test@test.com', '09090909099', '09090909099', 'Pamorangon', 'Daet', 'CN', 'Camarines Norte', 4600, 'Pamorangon', 'Daet', 'CN', 'Camarines Norte', 4600, NULL, '2020-09-25 04:26:21', '2020-09-25 04:26:21');
INSERT INTO `customers` VALUES (11, 'Jeanelyn', 'Teves', 'Penalosa', NULL, '1982-04-03', 'female', 'married', 'test@test.com', '09205621028', '09205621028', 'Mercedes', 'CN', 'CN', 'Camarines Norte', 4601, 'Mercedes', 'CN', 'CN', 'Camarines Norte', 4601, NULL, '2020-09-25 05:48:26', '2020-09-25 05:48:26');

-- ----------------------------
-- Table structure for customers_attachments
-- ----------------------------
DROP TABLE IF EXISTS `customers_attachments`;
CREATE TABLE `customers_attachments`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `attachment_id` bigint(20) UNSIGNED NOT NULL,
  `number` int(11) NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of customers_attachments
-- ----------------------------
INSERT INTO `customers_attachments` VALUES (1, 1, 1, 1000002, '0_1600925455.png', NULL, '2020-09-24 05:30:55', '2020-09-24 05:30:55');
INSERT INTO `customers_attachments` VALUES (2, 2, 1, 1000002, '0_1600927717.png', NULL, '2020-09-24 06:08:37', '2020-09-24 06:08:37');
INSERT INTO `customers_attachments` VALUES (3, 3, 1, 1000002, '0_1600929323.png', NULL, '2020-09-24 06:35:23', '2020-09-24 06:35:23');
INSERT INTO `customers_attachments` VALUES (4, 4, 1, 1000002, '0_1600934087.png', NULL, '2020-09-24 07:54:47', '2020-09-24 07:54:47');
INSERT INTO `customers_attachments` VALUES (5, 5, 1, 1000002, '0_1600936149.png', NULL, '2020-09-24 08:29:09', '2020-09-24 08:29:09');
INSERT INTO `customers_attachments` VALUES (6, 6, 1, 1000002, '0_1600936514.png', NULL, '2020-09-24 08:35:14', '2020-09-24 08:35:14');
INSERT INTO `customers_attachments` VALUES (7, 7, 1, 1000002, '0_1600936891.png', NULL, '2020-09-24 08:41:31', '2020-09-24 08:41:31');
INSERT INTO `customers_attachments` VALUES (8, 8, 1, 1000002, '0_1600937549.png', NULL, '2020-09-24 08:52:29', '2020-09-24 08:52:29');
INSERT INTO `customers_attachments` VALUES (9, 9, 1, 1000002, '0_1600938474.png', NULL, '2020-09-24 09:07:54', '2020-09-24 09:07:54');
INSERT INTO `customers_attachments` VALUES (10, 10, 1, 1000002, '0_1601007981.png', NULL, '2020-09-25 04:26:21', '2020-09-25 04:26:21');
INSERT INTO `customers_attachments` VALUES (11, 11, 1, 1000002, '0_1601012908.png', NULL, '2020-09-25 05:48:28', '2020-09-25 05:48:28');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for inventories
-- ----------------------------
DROP TABLE IF EXISTS `inventories`;
CREATE TABLE `inventories`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_status` enum('New','Old') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `item_category_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `maturity_date` date NULL DEFAULT NULL,
  `expiration_date` date NULL DEFAULT NULL,
  `auction_date` date NULL DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of inventories
-- ----------------------------
INSERT INTO `inventories` VALUES (1, 'New', 1, 1, 1, 'A01-1901-0014', '027574', '2019-03-04', NULL, NULL, NULL, 1, NULL, 0, '2020-09-24 05:38:07', '2020-09-24 05:56:57');
INSERT INTO `inventories` VALUES (2, 'New', 2, 1, 1, 'A01-1808-0025', '027557', '2019-03-08', NULL, NULL, NULL, 1, NULL, 0, '2020-09-24 06:15:19', '2020-09-24 06:22:46');
INSERT INTO `inventories` VALUES (3, 'New', 3, 1, 1, 'A01-1901-0010', '31638', '2020-09-03', '2020-10-03', '2021-01-03', '2021-03-03', 1, NULL, 0, '2020-09-24 06:40:45', '2020-09-24 07:39:43');
INSERT INTO `inventories` VALUES (4, 'New', 4, 1, 1, 'A01-2005-0006', '030938', '2020-05-16', '2020-06-16', '2020-09-16', '2020-11-16', 1, NULL, 0, '2020-09-24 07:56:16', '2020-09-24 07:56:16');
INSERT INTO `inventories` VALUES (5, 'New', 4, 1, 1, 'A01-1909-0061', '31639', '2020-05-07', NULL, NULL, NULL, 1, NULL, 0, '2020-09-24 08:10:58', '2020-09-24 08:25:30');
INSERT INTO `inventories` VALUES (6, 'New', 5, 1, 1, 'A01-2005-0005', '030908', '2020-05-11', '2020-06-11', '2020-09-11', '2020-11-11', 1, NULL, 0, '2020-09-24 08:30:35', '2020-09-24 08:30:35');
INSERT INTO `inventories` VALUES (7, 'New', 6, 1, 1, 'A01-2008-0032', '031527', '2020-08-15', '2020-09-15', '2020-12-15', '2021-02-15', 1, NULL, 0, '2020-09-24 08:36:47', '2020-09-24 08:36:47');
INSERT INTO `inventories` VALUES (8, 'New', 7, 1, 1, 'A01-2001-0004', '031714', '2020-09-15', '2020-10-15', '2021-01-15', '2021-03-15', 1, NULL, 0, '2020-09-24 08:43:10', '2020-09-24 08:47:56');
INSERT INTO `inventories` VALUES (9, 'New', 8, 1, 1, 'A01-1909-0003', '030246', '2020-01-08', '2020-02-08', '2020-05-08', '2020-07-08', 1, NULL, 0, '2020-09-24 08:54:10', '2020-09-24 08:56:09');
INSERT INTO `inventories` VALUES (10, 'New', 9, 1, 1, 'A01-1911-0050', '30950', '2020-05-18', '2020-06-18', '2020-09-18', '2020-11-18', 1, NULL, 0, '2020-09-24 09:09:19', '2020-09-24 09:10:40');
INSERT INTO `inventories` VALUES (11, 'New', 10, 1, 1, 'A01-1910-0006', '031439', '2020-08-01', '2020-09-01', '2020-12-01', '2021-02-01', 1, NULL, 0, '2020-09-25 05:11:10', '2020-09-25 05:54:44');
INSERT INTO `inventories` VALUES (12, 'New', 11, 1, 1, 'A01-1809-0017', '027309', '2019-06-07', NULL, NULL, NULL, 1, NULL, 0, '2020-09-25 05:50:07', '2020-09-25 06:40:22');

-- ----------------------------
-- Table structure for inventory_auctions
-- ----------------------------
DROP TABLE IF EXISTS `inventory_auctions`;
CREATE TABLE `inventory_auctions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `control_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for inventory_items
-- ----------------------------
DROP TABLE IF EXISTS `inventory_items`;
CREATE TABLE `inventory_items`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `item_type_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_type_weight` double(10, 4) NULL DEFAULT NULL,
  `item_name_weight` double(10, 4) NULL DEFAULT NULL,
  `item_karat` smallint(6) NULL DEFAULT NULL,
  `item_karat_weight` double(10, 4) NULL DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of inventory_items
-- ----------------------------
INSERT INTO `inventory_items` VALUES (1, 1, 1, 'Pendant', 1.1000, 0.0000, 24, 1.1000, 'A01-1901-0014\r\n1 pc pendant\r\nYG def', '6_1600925888.png', NULL, 0, '2020-09-24 05:38:08', '2020-09-24 05:38:08');
INSERT INTO `inventory_items` VALUES (2, 2, 1, 'Variety', 4.9000, 0.1000, 18, 4.8000, 'A01-1808-0025\r\n1 pc chain \r\n1 pc ring w/stone yg def', '0_1600928119.png', NULL, 0, '2020-09-24 06:15:19', '2020-09-24 06:15:19');
INSERT INTO `inventory_items` VALUES (3, 3, 1, 'Chain', 2.4000, 0.0000, 14, 2.4000, 'A01-1901-0010\r\n1 pc chain \r\nYG def', '0_1600929646.png', NULL, 0, '2020-09-24 06:40:46', '2020-09-24 06:40:46');
INSERT INTO `inventory_items` VALUES (4, 4, 1, 'Ring', 4.0000, 0.3000, 18, 3.7000, 'A01-2005-0006\r\n1pc ring w/ stone yg def', '0_1600934176.png', NULL, 0, '2020-09-24 07:56:16', '2020-09-24 07:56:16');
INSERT INTO `inventory_items` VALUES (5, 5, 1, 'Earrings', 2.4000, 0.0000, 18, 2.4000, 'A01-1909-0061\r\n1 pr ago-go earring yg def', '0_1600935058.png', NULL, 0, '2020-09-24 08:10:58', '2020-09-24 08:10:58');
INSERT INTO `inventory_items` VALUES (6, 6, 1, 'Ring', 1.6000, 0.0000, 18, 1.6000, 'A01-2005-0005\r\n1pc ring w/stone YG def', '0_1600936235.png', NULL, 0, '2020-09-24 08:30:35', '2020-09-24 08:30:35');
INSERT INTO `inventory_items` VALUES (7, 7, 1, 'Ring', 2.4000, 0.0000, 22, 2.4000, 'A01-2008-0032\r\n1 pc band ring yg def', '0_1600936607.png', NULL, 0, '2020-09-24 08:36:48', '2020-09-24 08:36:48');
INSERT INTO `inventory_items` VALUES (8, 8, 1, 'Ring', 1.2000, 0.2000, 18, 1.0000, 'A01-2001-0004\r\n1 pc ring w/ stone (stone 0.2g)\r\nYG def', '0_1600936990.png', NULL, 0, '2020-09-24 08:43:10', '2020-09-24 08:43:10');
INSERT INTO `inventory_items` VALUES (9, 9, 1, 'Variety', 2.9000, 0.0000, 18, 2.9000, 'A01-1909-0003\r\n1 pc pendant, 1 pc bracelet (putol), 1 pc chain (putol) yg def', '0_1600937650.png', NULL, 0, '2020-09-24 08:54:10', '2020-09-24 08:54:10');
INSERT INTO `inventory_items` VALUES (10, 10, 1, 'Ring', 3.2000, 0.4000, 18, 2.8000, 'A01-1911-0050\r\n1 pc ring w/ pearl yg def', '0_1600938559.png', NULL, 0, '2020-09-24 09:09:19', '2020-09-24 09:09:19');
INSERT INTO `inventory_items` VALUES (11, 11, 1, 'Ring', 1.0000, 0.0000, 18, 1.0000, 'A01-1910-0006\r\n1 pc ring\r\nYG def approx.', '0_1601010670.png', NULL, 0, '2020-09-25 05:11:10', '2020-09-25 05:11:10');
INSERT INTO `inventory_items` VALUES (12, 12, 1, 'Ring', 3.6000, 0.0000, 14, 3.6000, 'A01-1809-0017\r\n1 ring yg def', '0_1601013007.png', NULL, 0, '2020-09-25 05:50:07', '2020-09-25 05:50:07');

-- ----------------------------
-- Table structure for inventory_other_charges
-- ----------------------------
DROP TABLE IF EXISTS `inventory_other_charges`;
CREATE TABLE `inventory_other_charges`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `other_charges_id` bigint(20) UNSIGNED NOT NULL,
  `amount` double(10, 4) NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of inventory_other_charges
-- ----------------------------
INSERT INTO `inventory_other_charges` VALUES (1, 3, 21, 2, 104.0000, NULL, NULL, NULL);
INSERT INTO `inventory_other_charges` VALUES (2, 5, 28, 3, 172.0000, NULL, NULL, NULL);
INSERT INTO `inventory_other_charges` VALUES (3, 8, 34, 4, 72.0000, NULL, NULL, NULL);
INSERT INTO `inventory_other_charges` VALUES (4, 10, 42, 5, 200.0000, NULL, NULL, NULL);
INSERT INTO `inventory_other_charges` VALUES (5, 11, 47, 6, 34.0000, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for item_categories
-- ----------------------------
DROP TABLE IF EXISTS `item_categories`;
CREATE TABLE `item_categories`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of item_categories
-- ----------------------------
INSERT INTO `item_categories` VALUES (1, 'Jewelry', '2020-09-18 13:44:29', NULL);
INSERT INTO `item_categories` VALUES (2, 'Non-Jewelry', '2020-09-18 13:44:29', NULL);

-- ----------------------------
-- Table structure for item_types
-- ----------------------------
DROP TABLE IF EXISTS `item_types`;
CREATE TABLE `item_types`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_category_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `item_types_item_category_id_item_type_unique`(`item_category_id`, `item_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of item_types
-- ----------------------------
INSERT INTO `item_types` VALUES (1, 1, 'Gold', '2020-09-18 13:44:29', NULL);
INSERT INTO `item_types` VALUES (2, 1, 'Silver', '2020-09-18 13:44:29', NULL);
INSERT INTO `item_types` VALUES (3, 1, 'Platinum', '2020-09-18 13:44:29', NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1908 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1884, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (1885, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (1886, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (1887, '2020_04_25_111148_create_branches_table', 1);
INSERT INTO `migrations` VALUES (1888, '2020_04_27_054334_add_soft_delete_branch_table', 1);
INSERT INTO `migrations` VALUES (1889, '2020_04_27_080325_add_column_user_table', 1);
INSERT INTO `migrations` VALUES (1890, '2020_04_28_064358_add_soft_delete_user_table', 1);
INSERT INTO `migrations` VALUES (1891, '2020_04_29_110733_create_customers_table', 1);
INSERT INTO `migrations` VALUES (1892, '2020_05_02_065124_create_customers_attachments_table', 1);
INSERT INTO `migrations` VALUES (1893, '2020_05_02_065230_create_attachments_table', 1);
INSERT INTO `migrations` VALUES (1894, '2020_05_30_125006_create_rates_table', 1);
INSERT INTO `migrations` VALUES (1895, '2020_05_31_124601_create_item_types_table', 1);
INSERT INTO `migrations` VALUES (1896, '2020_06_04_073350_create_item_categories_table', 1);
INSERT INTO `migrations` VALUES (1897, '2020_06_12_094745_create_inventories_table', 1);
INSERT INTO `migrations` VALUES (1898, '2020_06_12_100154_create_inventory_items_table', 1);
INSERT INTO `migrations` VALUES (1899, '2020_06_19_020652_create_tickets_table', 1);
INSERT INTO `migrations` VALUES (1900, '2020_06_19_021310_create_other_charges_table', 1);
INSERT INTO `migrations` VALUES (1901, '2020_06_20_072838_create_inventory_other_charges_table', 1);
INSERT INTO `migrations` VALUES (1902, '2020_08_06_065356_create_ticket_items_table', 1);
INSERT INTO `migrations` VALUES (1903, '2020_08_06_085814_create_payments_table', 1);
INSERT INTO `migrations` VALUES (1904, '2020_08_06_103723_create_pawn_tickets_table', 1);
INSERT INTO `migrations` VALUES (1905, '2020_08_21_050950_create_inventory_auctions_table', 1);
INSERT INTO `migrations` VALUES (1906, '2020_08_26_073038_create_notices_table', 1);
INSERT INTO `migrations` VALUES (1907, '2020_09_02_030222_create_auctions_table', 1);

-- ----------------------------
-- Table structure for notices
-- ----------------------------
DROP TABLE IF EXISTS `notices`;
CREATE TABLE `notices`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `notice_yr` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notice_ctrl` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notice_date` date NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for other_charges
-- ----------------------------
DROP TABLE IF EXISTS `other_charges`;
CREATE TABLE `other_charges`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `charge_type` enum('discount','charges') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `charge_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(10, 4) NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of other_charges
-- ----------------------------
INSERT INTO `other_charges` VALUES (1, 'charges', 'Doc Stamp Tax', 20.0000, NULL, '2020-09-18 06:23:16', '2020-09-18 06:23:16');
INSERT INTO `other_charges` VALUES (2, 'discount', 'Covid-19', 104.0000, NULL, '2020-09-24 07:37:23', '2020-09-24 07:37:23');
INSERT INTO `other_charges` VALUES (3, 'discount', 'Covid-19', 172.0000, NULL, '2020-09-24 08:25:03', '2020-09-24 08:25:03');
INSERT INTO `other_charges` VALUES (4, 'discount', 'Covid-19', 72.0000, NULL, '2020-09-24 08:45:17', '2020-09-24 08:45:17');
INSERT INTO `other_charges` VALUES (5, 'discount', 'Discount 1', 200.0000, NULL, '2020-09-24 09:10:21', '2020-09-24 09:10:21');
INSERT INTO `other_charges` VALUES (6, 'discount', 'Covid-19', 34.0000, NULL, '2020-09-25 05:54:02', '2020-09-25 05:54:02');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pawn_tickets
-- ----------------------------
DROP TABLE IF EXISTS `pawn_tickets`;
CREATE TABLE `pawn_tickets`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pawn_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pawn_tickets
-- ----------------------------
INSERT INTO `pawn_tickets` VALUES (1, 1, 1, NULL, '2020-09-24 05:38:08', '2020-09-24 05:38:08');
INSERT INTO `pawn_tickets` VALUES (2, 1, 2, NULL, '2020-09-24 05:39:57', '2020-09-24 05:39:57');
INSERT INTO `pawn_tickets` VALUES (3, 1, 3, NULL, '2020-09-24 05:56:56', '2020-09-24 05:56:56');
INSERT INTO `pawn_tickets` VALUES (4, 4, 4, NULL, '2020-09-24 06:15:19', '2020-09-24 06:15:19');
INSERT INTO `pawn_tickets` VALUES (5, 4, 5, NULL, '2020-09-24 06:17:01', '2020-09-24 06:17:01');
INSERT INTO `pawn_tickets` VALUES (6, 4, 6, NULL, '2020-09-24 06:18:00', '2020-09-24 06:18:00');
INSERT INTO `pawn_tickets` VALUES (7, 4, 7, NULL, '2020-09-24 06:22:46', '2020-09-24 06:22:46');
INSERT INTO `pawn_tickets` VALUES (8, 8, 8, NULL, '2020-09-24 06:40:46', '2020-09-24 06:40:46');
INSERT INTO `pawn_tickets` VALUES (9, 8, 9, NULL, '2020-09-24 06:46:50', '2020-09-24 06:46:50');
INSERT INTO `pawn_tickets` VALUES (10, 8, 10, NULL, '2020-09-24 06:47:48', '2020-09-24 06:47:48');
INSERT INTO `pawn_tickets` VALUES (11, 8, 11, NULL, '2020-09-24 06:48:37', '2020-09-24 06:48:37');
INSERT INTO `pawn_tickets` VALUES (12, 8, 12, NULL, '2020-09-24 06:49:45', '2020-09-24 06:49:45');
INSERT INTO `pawn_tickets` VALUES (13, 8, 13, NULL, '2020-09-24 06:50:32', '2020-09-24 06:50:32');
INSERT INTO `pawn_tickets` VALUES (14, 8, 14, NULL, '2020-09-24 06:51:25', '2020-09-24 06:51:25');
INSERT INTO `pawn_tickets` VALUES (15, 8, 15, NULL, '2020-09-24 07:33:01', '2020-09-24 07:33:01');
INSERT INTO `pawn_tickets` VALUES (16, 8, 16, NULL, '2020-09-24 07:33:35', '2020-09-24 07:33:35');
INSERT INTO `pawn_tickets` VALUES (17, 8, 17, NULL, '2020-09-24 07:34:28', '2020-09-24 07:34:28');
INSERT INTO `pawn_tickets` VALUES (18, 8, 18, NULL, '2020-09-24 07:35:09', '2020-09-24 07:35:09');
INSERT INTO `pawn_tickets` VALUES (19, 8, 19, NULL, '2020-09-24 07:35:44', '2020-09-24 07:35:44');
INSERT INTO `pawn_tickets` VALUES (20, 8, 20, NULL, '2020-09-24 07:36:10', '2020-09-24 07:36:10');
INSERT INTO `pawn_tickets` VALUES (21, 8, 21, NULL, '2020-09-24 07:37:41', '2020-09-24 07:37:41');
INSERT INTO `pawn_tickets` VALUES (22, 8, 22, NULL, '2020-09-24 07:38:50', '2020-09-24 07:38:50');
INSERT INTO `pawn_tickets` VALUES (23, 8, 23, NULL, '2020-09-24 07:39:14', '2020-09-24 07:39:14');
INSERT INTO `pawn_tickets` VALUES (24, 8, 24, NULL, '2020-09-24 07:39:43', '2020-09-24 07:39:43');
INSERT INTO `pawn_tickets` VALUES (25, 25, 25, NULL, '2020-09-24 07:56:16', '2020-09-24 07:56:16');
INSERT INTO `pawn_tickets` VALUES (26, 26, 26, NULL, '2020-09-24 08:10:58', '2020-09-24 08:10:58');
INSERT INTO `pawn_tickets` VALUES (27, 26, 27, NULL, '2020-09-24 08:22:45', '2020-09-24 08:22:45');
INSERT INTO `pawn_tickets` VALUES (28, 26, 28, NULL, '2020-09-24 08:25:30', '2020-09-24 08:25:30');
INSERT INTO `pawn_tickets` VALUES (29, 29, 29, NULL, '2020-09-24 08:30:35', '2020-09-24 08:30:35');
INSERT INTO `pawn_tickets` VALUES (30, 30, 30, NULL, '2020-09-24 08:36:47', '2020-09-24 08:36:47');
INSERT INTO `pawn_tickets` VALUES (31, 31, 31, NULL, '2020-09-24 08:43:10', '2020-09-24 08:43:10');
INSERT INTO `pawn_tickets` VALUES (32, 31, 32, NULL, '2020-09-24 08:43:52', '2020-09-24 08:43:52');
INSERT INTO `pawn_tickets` VALUES (33, 31, 33, NULL, '2020-09-24 08:44:38', '2020-09-24 08:44:38');
INSERT INTO `pawn_tickets` VALUES (34, 31, 34, NULL, '2020-09-24 08:45:58', '2020-09-24 08:45:58');
INSERT INTO `pawn_tickets` VALUES (35, 31, 35, NULL, '2020-09-24 08:46:32', '2020-09-24 08:46:32');
INSERT INTO `pawn_tickets` VALUES (36, 31, 36, NULL, '2020-09-24 08:47:16', '2020-09-24 08:47:16');
INSERT INTO `pawn_tickets` VALUES (37, 31, 37, NULL, '2020-09-24 08:47:56', '2020-09-24 08:47:56');
INSERT INTO `pawn_tickets` VALUES (38, 38, 38, NULL, '2020-09-24 08:54:10', '2020-09-24 08:54:10');
INSERT INTO `pawn_tickets` VALUES (39, 38, 39, NULL, '2020-09-24 08:55:29', '2020-09-24 08:55:29');
INSERT INTO `pawn_tickets` VALUES (40, 38, 40, NULL, '2020-09-24 08:56:09', '2020-09-24 08:56:09');
INSERT INTO `pawn_tickets` VALUES (41, 41, 41, NULL, '2020-09-24 09:09:19', '2020-09-24 09:09:19');
INSERT INTO `pawn_tickets` VALUES (42, 41, 42, NULL, '2020-09-24 09:10:40', '2020-09-24 09:10:40');
INSERT INTO `pawn_tickets` VALUES (43, 43, 43, NULL, '2020-09-25 05:11:10', '2020-09-25 05:11:10');
INSERT INTO `pawn_tickets` VALUES (44, 43, 44, NULL, '2020-09-25 05:16:08', '2020-09-25 05:16:08');
INSERT INTO `pawn_tickets` VALUES (45, 45, 45, NULL, '2020-09-25 05:50:07', '2020-09-25 05:50:07');
INSERT INTO `pawn_tickets` VALUES (46, 45, 46, NULL, '2020-09-25 05:51:05', '2020-09-25 05:51:05');
INSERT INTO `pawn_tickets` VALUES (47, 43, 47, NULL, '2020-09-25 05:54:44', '2020-09-25 05:54:44');
INSERT INTO `pawn_tickets` VALUES (48, 45, 48, NULL, '2020-09-25 06:40:22', '2020-09-25 06:40:22');

-- ----------------------------
-- Table structure for payments
-- ----------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_type` enum('renew','redeem') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `or_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(10, 4) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payments
-- ----------------------------
INSERT INTO `payments` VALUES (1, 'renew', 1, 2, '00001', 69.0000, NULL, '2020-09-24 05:39:57', '2020-09-24 05:39:57');
INSERT INTO `payments` VALUES (2, 'redeem', 1, 3, '00002', 2369.0000, NULL, '2020-09-24 05:56:57', '2020-09-24 05:56:57');
INSERT INTO `payments` VALUES (3, 'renew', 2, 5, '00003', 1863.0000, NULL, '2020-09-24 06:17:01', '2020-09-24 06:17:01');
INSERT INTO `payments` VALUES (4, 'renew', 2, 6, '00004', 243.0000, NULL, '2020-09-24 06:18:00', '2020-09-24 06:18:00');
INSERT INTO `payments` VALUES (5, 'redeem', 2, 7, '00005', 8343.0000, NULL, '2020-09-24 06:22:46', '2020-09-24 06:22:46');
INSERT INTO `payments` VALUES (6, 'renew', 3, 9, '00006', 78.0000, NULL, '2020-09-24 06:46:50', '2020-09-24 06:46:50');
INSERT INTO `payments` VALUES (7, 'renew', 3, 10, '00007', 78.0000, NULL, '2020-09-24 06:47:48', '2020-09-24 06:47:48');
INSERT INTO `payments` VALUES (8, 'renew', 3, 11, '00008', 78.0000, NULL, '2020-09-24 06:48:37', '2020-09-24 06:48:37');
INSERT INTO `payments` VALUES (9, 'renew', 3, 12, '00009', 78.0000, NULL, '2020-09-24 06:49:45', '2020-09-24 06:49:45');
INSERT INTO `payments` VALUES (10, 'renew', 3, 13, '00010', 78.0000, NULL, '2020-09-24 06:50:32', '2020-09-24 06:50:32');
INSERT INTO `payments` VALUES (11, 'renew', 3, 14, '00011', 78.0000, NULL, '2020-09-24 06:51:25', '2020-09-24 06:51:25');
INSERT INTO `payments` VALUES (12, 'renew', 3, 15, '00012', 78.0000, NULL, '2020-09-24 07:33:01', '2020-09-24 07:33:01');
INSERT INTO `payments` VALUES (13, 'renew', 3, 16, '00013', 78.0000, NULL, '2020-09-24 07:33:35', '2020-09-24 07:33:35');
INSERT INTO `payments` VALUES (14, 'renew', 3, 17, '00014', 78.0000, NULL, '2020-09-24 07:34:28', '2020-09-24 07:34:28');
INSERT INTO `payments` VALUES (15, 'renew', 3, 18, '00015', 78.0000, NULL, '2020-09-24 07:35:09', '2020-09-24 07:35:09');
INSERT INTO `payments` VALUES (16, 'renew', 3, 19, '00016', 78.0000, NULL, '2020-09-24 07:35:44', '2020-09-24 07:35:44');
INSERT INTO `payments` VALUES (17, 'renew', 3, 20, '00017', 78.0000, NULL, '2020-09-24 07:36:10', '2020-09-24 07:36:10');
INSERT INTO `payments` VALUES (18, 'renew', 3, 21, '00018', 234.0000, NULL, '2020-09-24 07:37:41', '2020-09-24 07:37:41');
INSERT INTO `payments` VALUES (19, 'renew', 3, 22, '00019', 78.0000, NULL, '2020-09-24 07:38:50', '2020-09-24 07:38:50');
INSERT INTO `payments` VALUES (20, 'renew', 3, 23, '00020', 78.0000, NULL, '2020-09-24 07:39:14', '2020-09-24 07:39:14');
INSERT INTO `payments` VALUES (21, 'renew', 3, 24, '00021', 78.0000, NULL, '2020-09-24 07:39:43', '2020-09-24 07:39:43');
INSERT INTO `payments` VALUES (22, 'renew', 5, 27, '00022', 0.0000, NULL, '2020-09-24 08:22:45', '2020-09-24 08:22:45');
INSERT INTO `payments` VALUES (23, 'redeem', 5, 28, '00023', 5676.0000, NULL, '2020-09-24 08:25:30', '2020-09-24 08:25:30');
INSERT INTO `payments` VALUES (24, 'renew', 8, 32, '00024', 54.0000, NULL, '2020-09-24 08:43:52', '2020-09-24 08:43:52');
INSERT INTO `payments` VALUES (25, 'renew', 8, 33, '00025', 54.0000, NULL, '2020-09-24 08:44:38', '2020-09-24 08:44:38');
INSERT INTO `payments` VALUES (26, 'renew', 8, 34, '00026', 162.0000, NULL, '2020-09-24 08:45:59', '2020-09-24 08:45:59');
INSERT INTO `payments` VALUES (27, 'renew', 8, 35, '00027', 54.0000, NULL, '2020-09-24 08:46:32', '2020-09-24 08:46:32');
INSERT INTO `payments` VALUES (28, 'renew', 8, 36, '00028', 54.0000, NULL, '2020-09-24 08:47:16', '2020-09-24 08:47:16');
INSERT INTO `payments` VALUES (29, 'renew', 8, 37, '00029', 54.0000, NULL, '2020-09-24 08:47:56', '2020-09-24 08:47:56');
INSERT INTO `payments` VALUES (30, 'renew', 9, 39, '00030', 360.0000, NULL, '2020-09-24 08:55:29', '2020-09-24 08:55:29');
INSERT INTO `payments` VALUES (31, 'renew', 9, 40, '00031', 585.0000, NULL, '2020-09-24 08:56:09', '2020-09-24 08:56:09');
INSERT INTO `payments` VALUES (32, 'renew', 10, 42, '00032', 1200.0000, NULL, '2020-09-24 09:10:40', '2020-09-24 09:10:40');
INSERT INTO `payments` VALUES (33, 'renew', 11, 44, '00033', 391.0000, NULL, '2020-09-25 05:16:08', '2020-09-25 05:16:08');
INSERT INTO `payments` VALUES (34, 'renew', 12, 46, '00034', 648.0000, NULL, '2020-09-25 05:51:05', '2020-09-25 05:51:05');
INSERT INTO `payments` VALUES (35, 'renew', 11, 47, '00035', 357.0000, NULL, '2020-09-25 05:54:44', '2020-09-25 05:54:44');
INSERT INTO `payments` VALUES (36, 'redeem', 12, 48, '00036', 4428.0000, NULL, '2020-09-25 06:40:22', '2020-09-25 06:40:22');

-- ----------------------------
-- Table structure for rates
-- ----------------------------
DROP TABLE IF EXISTS `rates`;
CREATE TABLE `rates`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `item_type_id` bigint(20) UNSIGNED NOT NULL,
  `karat` int(11) NULL DEFAULT NULL,
  `gram` double(8, 4) NULL DEFAULT NULL,
  `regular_rate` double(10, 4) NULL DEFAULT NULL,
  `special_rate` double(10, 4) NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rates_branch_id_item_type_id_karat_unique`(`branch_id`, `item_type_id`, `karat`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rates
-- ----------------------------
INSERT INTO `rates` VALUES (1, 1, 1, 24, 0.9990, 2000.0000, 2100.0000, NULL, '2020-09-18 05:49:00', '2020-09-18 05:49:00');
INSERT INTO `rates` VALUES (2, 1, 1, 23, 0.9580, 1900.0000, 2000.0000, NULL, '2020-09-18 05:49:25', '2020-09-18 05:49:25');
INSERT INTO `rates` VALUES (3, 1, 1, 22, 0.9160, 1900.0000, 2000.0000, NULL, '2020-09-18 05:49:50', '2020-09-18 05:49:50');
INSERT INTO `rates` VALUES (4, 1, 1, 21, 0.8750, 1800.0000, 1900.0000, NULL, '2020-09-18 05:50:27', '2020-09-18 05:50:27');
INSERT INTO `rates` VALUES (5, 1, 1, 20, 0.8330, 1700.0000, 1800.0000, NULL, '2020-09-18 05:50:55', '2020-09-18 05:50:55');
INSERT INTO `rates` VALUES (6, 1, 1, 18, 0.7500, 1700.0000, 1800.0000, NULL, '2020-09-18 05:51:22', '2020-09-18 05:51:22');
INSERT INTO `rates` VALUES (7, 1, 1, 16, 0.6670, 1000.0000, 1100.0000, NULL, '2020-09-18 05:51:47', '2020-09-18 05:51:47');
INSERT INTO `rates` VALUES (8, 1, 1, 14, 0.5830, 1100.0000, 1200.0000, NULL, '2020-09-18 05:52:08', '2020-09-18 05:52:08');
INSERT INTO `rates` VALUES (9, 1, 1, 13, 0.5830, 1000.0000, 1100.0000, NULL, '2020-09-18 05:54:09', '2020-09-18 05:54:09');
INSERT INTO `rates` VALUES (10, 1, 1, 12, 0.5000, 300.0000, 400.0000, NULL, '2020-09-18 05:54:27', '2020-09-18 05:54:27');
INSERT INTO `rates` VALUES (11, 1, 1, 10, 0.4170, 300.0000, 400.0000, NULL, '2020-09-18 05:55:12', '2020-09-18 05:55:12');
INSERT INTO `rates` VALUES (12, 1, 1, 9, 0.4170, 300.0000, 400.0000, NULL, '2020-09-18 05:55:34', '2020-09-18 05:55:34');
INSERT INTO `rates` VALUES (13, 1, 1, 8, 0.3330, 0.0000, 0.0000, NULL, '2020-09-18 05:55:49', '2020-09-18 05:55:49');
INSERT INTO `rates` VALUES (14, 1, 1, 6, 0.2500, 0.0000, 0.0000, NULL, '2020-09-18 05:56:08', '2020-09-18 05:56:08');
INSERT INTO `rates` VALUES (15, 1, 1, 4, 0.1670, 0.0000, 0.0000, NULL, '2020-09-18 05:56:23', '2020-09-18 05:56:23');
INSERT INTO `rates` VALUES (16, 1, 2, 925, 0.9000, 0.0000, 0.0000, NULL, '2020-09-18 05:57:26', '2020-09-18 05:57:26');
INSERT INTO `rates` VALUES (17, 1, 2, 750, 0.7000, 0.0000, 0.0000, NULL, '2020-09-18 05:57:43', '2020-09-18 05:57:43');
INSERT INTO `rates` VALUES (18, 1, 3, 1000, 1.0000, 2300.0000, 2500.0000, NULL, '2020-09-18 05:58:29', '2020-09-18 05:58:29');
INSERT INTO `rates` VALUES (19, 1, 3, 950, 0.9000, 2070.0000, 2250.0000, NULL, '2020-09-18 05:58:53', '2020-09-18 05:58:53');
INSERT INTO `rates` VALUES (20, 1, 3, 900, 0.8000, 1840.0000, 2000.0000, NULL, '2020-09-18 05:59:14', '2020-09-18 05:59:14');
INSERT INTO `rates` VALUES (21, 1, 3, 850, 0.7000, 1610.0000, 1750.0000, NULL, '2020-09-18 05:59:44', '2020-09-18 05:59:44');
INSERT INTO `rates` VALUES (22, 1, 3, 800, 0.6000, 1380.0000, 1500.0000, NULL, '2020-09-18 06:00:25', '2020-09-18 06:00:25');
INSERT INTO `rates` VALUES (23, 1, 3, 750, 0.5000, 1150.0000, 1250.0000, NULL, '2020-09-18 06:00:51', '2020-09-18 06:00:51');
INSERT INTO `rates` VALUES (24, 1, 3, 700, 0.4000, 920.0000, 1000.0000, NULL, '2020-09-18 06:01:15', '2020-09-18 06:01:15');

-- ----------------------------
-- Table structure for ticket_items
-- ----------------------------
DROP TABLE IF EXISTS `ticket_items`;
CREATE TABLE `ticket_items`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_item_id` bigint(20) UNSIGNED NOT NULL,
  `item_type_appraised_value` double(10, 4) NOT NULL,
  `item_name_appraised_value` double(10, 4) NOT NULL,
  `item_status` enum('old','new') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ticket_items
-- ----------------------------
INSERT INTO `ticket_items` VALUES (1, 1, 1, 2100.0000, 2310.0000, 'new', NULL, '2020-09-24 05:38:08', '2020-09-24 05:38:08');
INSERT INTO `ticket_items` VALUES (2, 4, 2, 1700.0000, 8160.0000, 'new', NULL, '2020-09-24 06:15:20', '2020-09-24 06:15:20');
INSERT INTO `ticket_items` VALUES (3, 8, 3, 1100.0000, 2640.0000, 'new', NULL, '2020-09-24 06:40:46', '2020-09-24 06:40:46');
INSERT INTO `ticket_items` VALUES (4, 25, 4, 1700.0000, 6290.0000, 'new', NULL, '2020-09-24 07:56:16', '2020-09-24 07:56:16');
INSERT INTO `ticket_items` VALUES (5, 26, 5, 1800.0000, 4320.0000, 'new', NULL, '2020-09-24 08:10:58', '2020-09-24 08:10:58');
INSERT INTO `ticket_items` VALUES (6, 29, 6, 1700.0000, 2720.0000, 'new', NULL, '2020-09-24 08:30:35', '2020-09-24 08:30:35');
INSERT INTO `ticket_items` VALUES (7, 30, 7, 1900.0000, 4560.0000, 'new', NULL, '2020-09-24 08:36:48', '2020-09-24 08:36:48');
INSERT INTO `ticket_items` VALUES (8, 31, 8, 1700.0000, 1700.0000, 'new', NULL, '2020-09-24 08:43:10', '2020-09-24 08:43:10');
INSERT INTO `ticket_items` VALUES (9, 38, 9, 1800.0000, 5220.0000, 'new', NULL, '2020-09-24 08:54:10', '2020-09-24 08:54:10');
INSERT INTO `ticket_items` VALUES (10, 41, 10, 1800.0000, 5040.0000, 'new', NULL, '2020-09-24 09:09:19', '2020-09-24 09:09:19');
INSERT INTO `ticket_items` VALUES (11, 43, 11, 1700.0000, 1700.0000, 'new', NULL, '2020-09-25 05:11:10', '2020-09-25 05:11:10');
INSERT INTO `ticket_items` VALUES (12, 45, 12, 1100.0000, 3960.0000, 'new', NULL, '2020-09-25 05:50:07', '2020-09-25 05:50:07');

-- ----------------------------
-- Table structure for tickets
-- ----------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `attachment_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` enum('pawn','renew','redeem','repawn','auction') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `maturity_date` date NULL DEFAULT NULL,
  `expiration_date` date NULL DEFAULT NULL,
  `auction_date` date NULL DEFAULT NULL,
  `advance_interest` double(10, 4) NULL DEFAULT NULL,
  `interest` double(10, 4) NULL DEFAULT NULL,
  `penalty` double(10, 4) NULL DEFAULT NULL,
  `discount` double(10, 4) NULL DEFAULT NULL,
  `charges` double(10, 4) NULL DEFAULT NULL,
  `attachment_number` int(11) NOT NULL,
  `appraised_value` double(10, 4) NULL DEFAULT NULL,
  `principal` double(10, 4) NULL DEFAULT NULL,
  `net` double(10, 4) NULL DEFAULT NULL,
  `interbranch` smallint(6) NULL DEFAULT NULL,
  `interbranch_renewal` smallint(6) NULL DEFAULT NULL,
  `authorized_representative` smallint(6) NULL DEFAULT NULL,
  `interest_percentage` smallint(6) NOT NULL DEFAULT 3,
  `penalty_percentage` smallint(6) NOT NULL DEFAULT 2,
  `is_special_rate` smallint(6) NOT NULL DEFAULT 0,
  `processed_by` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `repawn` smallint(6) NOT NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tickets
-- ----------------------------
INSERT INTO `tickets` VALUES (1, 1, 1, '027320', 'pawn', '2019-01-08', '2019-02-08', '2019-05-08', '2019-07-08', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 2310.0000, 2300.0000, 2300.0000, NULL, NULL, NULL, 3, 2, 1, 1, NULL, 1, 0, '2020-09-24 05:38:08', '2020-09-24 05:56:57');
INSERT INTO `tickets` VALUES (2, 1, 1, '027574', 'renew', '2019-02-07', '2019-03-07', '2019-06-07', '2019-08-07', 0.0000, 69.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 69.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 05:39:57', '2020-09-24 05:39:57');
INSERT INTO `tickets` VALUES (3, 1, 1, '027574', 'redeem', '2019-03-04', NULL, NULL, NULL, 0.0000, 69.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 2369.0000, 0, NULL, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 05:56:56', '2020-09-24 05:56:56');
INSERT INTO `tickets` VALUES (4, 2, 1, '026293', 'pawn', '2018-08-14', '2018-09-14', '2018-12-14', '2019-02-14', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 8160.0000, 8100.0000, 8100.0000, NULL, NULL, NULL, 3, 2, 0, 1, NULL, 1, 0, '2020-09-24 06:15:19', '2020-09-24 06:22:46');
INSERT INTO `tickets` VALUES (5, 2, 1, '027268', 'renew', '2019-01-03', '2019-02-03', '2019-05-03', '2019-07-03', 0.0000, 1215.0000, 648.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 1863.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:17:01', '2020-09-24 06:17:01');
INSERT INTO `tickets` VALUES (6, 2, 1, '027557', 'renew', '2019-02-05', '2019-03-05', '2019-06-05', '2019-08-05', 0.0000, 243.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 243.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:18:00', '2020-09-24 06:18:00');
INSERT INTO `tickets` VALUES (7, 2, 1, '027557', 'redeem', '2019-03-08', NULL, NULL, NULL, 0.0000, 243.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 8343.0000, 0, NULL, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:22:46', '2020-09-24 06:22:46');
INSERT INTO `tickets` VALUES (8, 3, 1, '027305', 'pawn', '2019-01-07', '2019-02-07', '2019-05-07', '2019-07-07', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 2640.0000, 2600.0000, 2600.0000, NULL, NULL, NULL, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:40:46', '2020-09-24 06:40:46');
INSERT INTO `tickets` VALUES (9, 3, 1, '027590', 'renew', '2019-02-11', '2019-03-11', '2019-06-11', '2019-08-11', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:46:49', '2020-09-24 06:46:49');
INSERT INTO `tickets` VALUES (10, 3, 1, '027849', 'renew', '2019-03-14', '2019-04-14', '2019-07-14', '2019-09-14', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:47:48', '2020-09-24 06:47:48');
INSERT INTO `tickets` VALUES (11, 3, 1, '028137', 'renew', '2019-04-17', '2019-05-17', '2019-08-17', '2019-10-17', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:48:37', '2020-09-24 06:48:37');
INSERT INTO `tickets` VALUES (12, 3, 1, '028395', 'renew', '2019-05-20', '2019-06-20', '2019-09-20', '2019-11-20', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:49:44', '2020-09-24 06:49:44');
INSERT INTO `tickets` VALUES (13, 3, 1, '028652', 'renew', '2019-06-25', '2019-07-25', '2019-10-25', '2019-12-25', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:50:32', '2020-09-24 06:50:32');
INSERT INTO `tickets` VALUES (14, 3, 1, '028930', 'renew', '2019-07-30', '2019-08-30', '2019-11-30', '2020-01-30', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 06:51:25', '2020-09-24 06:51:25');
INSERT INTO `tickets` VALUES (15, 3, 1, '029215', 'renew', '2019-09-04', '2019-10-04', '2020-01-04', '2020-03-04', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:33:00', '2020-09-24 07:33:00');
INSERT INTO `tickets` VALUES (16, 3, 1, '029516', 'renew', '2019-10-09', '2019-11-09', '2020-02-09', '2020-04-09', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:33:35', '2020-09-24 07:33:35');
INSERT INTO `tickets` VALUES (17, 3, 1, '029822', 'renew', '2019-11-14', '2019-12-14', '2020-03-14', '2020-05-14', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:34:28', '2020-09-24 07:34:28');
INSERT INTO `tickets` VALUES (18, 3, 1, '030098', 'renew', '2019-12-18', '2020-01-18', '2020-04-18', '2020-06-18', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:35:09', '2020-09-24 07:35:09');
INSERT INTO `tickets` VALUES (19, 3, 1, '030365', 'renew', '2020-01-22', '2020-02-22', '2020-05-22', '2020-07-22', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:35:44', '2020-09-24 07:35:44');
INSERT INTO `tickets` VALUES (20, 3, 1, '030660', 'renew', '2020-02-26', '2020-03-26', '2020-06-26', '2020-08-26', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:36:10', '2020-09-24 07:36:10');
INSERT INTO `tickets` VALUES (21, 3, 1, '030978', 'renew', '2020-05-23', '2020-06-23', '2020-09-23', '2020-11-23', 0.0000, 234.0000, 104.0000, 104.0000, 0.0000, 1000002, NULL, NULL, 234.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:37:41', '2020-09-24 07:37:41');
INSERT INTO `tickets` VALUES (22, 3, 1, '031205', 'renew', '2020-06-27', '2020-07-27', '2020-10-27', '2020-12-27', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:38:50', '2020-09-24 07:38:50');
INSERT INTO `tickets` VALUES (23, 3, 1, '031432', 'renew', '2020-07-31', '2020-08-31', '2020-11-30', '2021-01-30', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:39:14', '2020-09-24 07:39:14');
INSERT INTO `tickets` VALUES (24, 3, 1, '31638', 'renew', '2020-09-03', '2020-10-03', '2021-01-03', '2021-03-03', 0.0000, 78.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 78.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:39:42', '2020-09-24 07:39:42');
INSERT INTO `tickets` VALUES (25, 4, 1, '030938', 'pawn', '2020-05-16', '2020-06-16', '2020-09-16', '2020-11-16', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 6290.0000, 5200.0000, 5200.0000, NULL, NULL, NULL, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 07:56:16', '2020-09-24 07:56:16');
INSERT INTO `tickets` VALUES (26, 5, 1, '029385', 'pawn', '2019-09-23', '2019-10-23', '2020-01-23', '2020-03-23', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 4320.0000, 4300.0000, 4300.0000, NULL, NULL, NULL, 3, 2, 1, 1, NULL, 1, 0, '2020-09-24 08:10:58', '2020-09-24 08:25:30');
INSERT INTO `tickets` VALUES (27, 5, 1, '31639', 'renew', '2020-01-27', '2020-02-27', '2020-05-27', '2020-07-27', 0.0000, 516.0000, 258.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 774.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:22:45', '2020-09-24 08:22:45');
INSERT INTO `tickets` VALUES (28, 5, 1, '31639', 'redeem', '2020-05-07', NULL, NULL, NULL, 0.0000, 516.0000, 258.0000, 172.0000, 0.0000, 1000002, NULL, NULL, 4902.0000, 0, NULL, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:25:30', '2020-09-24 08:25:30');
INSERT INTO `tickets` VALUES (29, 6, 1, '030908', 'pawn', '2020-05-11', '2020-06-11', '2020-09-11', '2020-11-11', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 2720.0000, 2700.0000, 2700.0000, NULL, NULL, NULL, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:30:35', '2020-09-24 08:30:35');
INSERT INTO `tickets` VALUES (30, 7, 1, '031527', 'pawn', '2020-08-15', '2020-09-15', '2020-12-15', '2021-02-15', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 4560.0000, 4000.0000, 4000.0000, NULL, NULL, NULL, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:36:47', '2020-09-24 08:36:47');
INSERT INTO `tickets` VALUES (31, 8, 1, '030204', 'pawn', '2020-01-03', '2020-02-03', '2020-05-03', '2020-07-03', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 1700.0000, 1800.0000, 1800.0000, NULL, NULL, NULL, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:43:10', '2020-09-24 08:43:10');
INSERT INTO `tickets` VALUES (32, 8, 1, '030469', 'renew', '2020-02-03', '2020-03-03', '2020-06-03', '2020-08-03', 0.0000, 54.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 54.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:43:52', '2020-09-24 08:43:52');
INSERT INTO `tickets` VALUES (33, 8, 1, '030706', 'renew', '2020-03-02', '2020-04-02', '2020-07-02', '2020-09-02', 0.0000, 54.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 54.0000, 1, 1, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:44:38', '2020-09-24 08:44:38');
INSERT INTO `tickets` VALUES (34, 8, 1, '031031', 'renew', '2020-06-01', '2020-07-01', '2020-10-01', '2020-12-01', 0.0000, 162.0000, 72.0000, 72.0000, 0.0000, 1000002, NULL, NULL, 162.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:45:58', '2020-09-24 08:45:58');
INSERT INTO `tickets` VALUES (35, 8, 1, '031270', 'renew', '2020-07-06', '2020-08-06', '2020-11-06', '2021-01-06', 0.0000, 54.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 54.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:46:32', '2020-09-24 08:46:32');
INSERT INTO `tickets` VALUES (36, 8, 1, '031506', 'renew', '2020-08-10', '2020-09-10', '2020-12-10', '2021-02-10', 0.0000, 54.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 54.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:47:15', '2020-09-24 08:47:15');
INSERT INTO `tickets` VALUES (37, 8, 1, '031714', 'renew', '2020-09-15', '2020-10-15', '2021-01-15', '2021-03-15', 0.0000, 54.0000, 0.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 54.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:47:56', '2020-09-24 08:47:56');
INSERT INTO `tickets` VALUES (38, 9, 1, '029194', 'pawn', '2019-09-02', '2019-10-02', '2020-01-02', '2020-03-02', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 5220.0000, 4500.0000, 4500.0000, NULL, NULL, NULL, 3, 2, 1, 1, NULL, 0, 0, '2020-09-24 08:54:10', '2020-09-24 08:54:10');
INSERT INTO `tickets` VALUES (39, 9, 1, '029521', 'renew', '2019-10-09', '2019-11-09', '2020-02-09', '2020-04-09', 0.0000, 270.0000, 90.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 360.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:55:29', '2020-09-24 08:55:29');
INSERT INTO `tickets` VALUES (40, 9, 1, '030246', 'renew', '2020-01-08', '2020-02-08', '2020-05-08', '2020-07-08', 0.0000, 405.0000, 180.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 585.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 08:56:08', '2020-09-24 08:56:08');
INSERT INTO `tickets` VALUES (41, 10, 1, '029908', 'pawn', '2019-11-23', '2019-12-23', '2020-03-23', '2020-05-23', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 5040.0000, 5000.0000, 5000.0000, NULL, NULL, NULL, 3, 2, 1, 1, NULL, 0, 0, '2020-09-24 09:09:19', '2020-09-24 09:09:19');
INSERT INTO `tickets` VALUES (42, 10, 1, '30950', 'renew', '2020-05-18', '2020-06-18', '2020-09-18', '2020-11-18', 0.0000, 900.0000, 500.0000, 200.0000, 0.0000, 1000002, NULL, NULL, 1200.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-24 09:10:40', '2020-09-24 09:10:40');
INSERT INTO `tickets` VALUES (43, 11, 1, '029478', 'pawn', '2019-10-04', '2019-11-04', '2020-02-04', '2020-04-04', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 1700.0000, 1700.0000, 1700.0000, NULL, NULL, NULL, 3, 2, 0, 1, NULL, 0, 0, '2020-09-25 05:11:10', '2020-09-25 05:11:10');
INSERT INTO `tickets` VALUES (44, 11, 1, '030710', 'renew', '2020-03-02', '2020-04-02', '2020-07-02', '2020-09-02', 0.0000, 255.0000, 136.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 391.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-25 05:16:08', '2020-09-25 05:16:08');
INSERT INTO `tickets` VALUES (45, 12, 1, '026493', 'pawn', '2018-09-13', '2018-10-13', '2019-01-13', '2019-03-13', NULL, NULL, NULL, 0.0000, 0.0000, 1000002, 3960.0000, 3600.0000, 3600.0000, NULL, NULL, NULL, 3, 2, 0, 1, NULL, 1, 0, '2020-09-25 05:50:07', '2020-09-25 06:40:22');
INSERT INTO `tickets` VALUES (46, 12, 1, '027309', 'renew', '2019-01-07', '2019-02-07', '2019-05-07', '2019-07-07', 0.0000, 432.0000, 216.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 648.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-25 05:51:05', '2020-09-25 05:51:05');
INSERT INTO `tickets` VALUES (47, 11, 1, '031439', 'renew', '2020-08-01', '2020-09-01', '2020-12-01', '2021-02-01', 0.0000, 255.0000, 136.0000, 34.0000, 0.0000, 1000002, NULL, NULL, 357.0000, 0, 0, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-25 05:54:44', '2020-09-25 05:54:44');
INSERT INTO `tickets` VALUES (48, 12, 1, '027309', 'redeem', '2019-06-07', NULL, NULL, NULL, 0.0000, 540.0000, 288.0000, 0.0000, 0.0000, 1000002, NULL, NULL, 4428.0000, 0, NULL, 0, 3, 2, 0, 1, NULL, 0, 0, '2020-09-25 06:40:21', '2020-09-25 06:40:21');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `access` enum('Administrator','Manager','Staff') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_code` int(11) NOT NULL,
  `branches` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator', 'Admin', 'admin', '$2y$10$F5Iu0fbep2q92g./Q52b7uQukmjbpdnM/2iop6GbjLTO6S5sZd/VO', 1, 'Administrator', 1111, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (2, 'User', 'User', 'user', '$2y$10$48WIdW2L1GlSgTquiLZ.bOtkXWJBV7BuO9gLsQhXcw9fgoh9sBZgK', 1, 'Staff', 1111, '1', NULL, '2020-09-19 01:53:41', '2020-09-19 01:53:41', NULL);

SET FOREIGN_KEY_CHECKS = 1;
