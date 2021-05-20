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

 Date: 17/05/2021 23:03:42
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_pr_bill
-- ----------------------------
DROP TABLE IF EXISTS `t_pr_bill`;
CREATE TABLE `t_pr_bill`  (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bill_status` int(11) NOT NULL,
  `bizdt` datetime NOT NULL,
  `biz_user_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `supplier_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date_created` datetime NULL DEFAULT NULL,
  `input_user_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `inventory_money` decimal(19, 2) NULL DEFAULT NULL,
  `ref` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rejection_money` decimal(19, 2) NULL DEFAULT NULL,
  `warehouse_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pw_bill_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `receiving_type` int(11) NOT NULL DEFAULT 0,
  `data_org` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `company_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `bill_memo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_pr_bill
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
