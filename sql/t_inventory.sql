/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : localhost:3306
 Source Schema         : jtpsi

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 16/05/2021 20:59:37
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_inventory
-- ----------------------------
DROP TABLE IF EXISTS `t_inventory`;
CREATE TABLE `t_inventory`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `balance_count` decimal(19, 8) NOT NULL,
  `balance_money` decimal(19, 2) NOT NULL,
  `balance_price` decimal(19, 2) NOT NULL,
  `goods_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `in_count` decimal(19, 8) NULL DEFAULT NULL,
  `in_money` decimal(19, 2) NULL DEFAULT NULL,
  `in_price` decimal(19, 2) NULL DEFAULT NULL,
  `out_count` decimal(19, 8) NULL DEFAULT NULL,
  `out_money` decimal(19, 2) NULL DEFAULT NULL,
  `out_price` decimal(19, 2) NULL DEFAULT NULL,
  `afloat_count` decimal(19, 8) NULL DEFAULT NULL,
  `afloat_money` decimal(19, 2) NULL DEFAULT NULL,
  `afloat_price` decimal(19, 2) NULL DEFAULT NULL,
  `warehouse_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `data_org` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `company_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_inventory
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
