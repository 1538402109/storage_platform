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

 Date: 18/05/2021 23:23:33
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_receivables
-- ----------------------------
DROP TABLE IF EXISTS `t_receivables`;
CREATE TABLE `t_receivables`  (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `act_money` decimal(19, 2) NOT NULL,
  `balance_money` decimal(19, 2) NOT NULL,
  `ca_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ca_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rv_money` decimal(19, 2) NOT NULL,
  `data_org` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `company_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_receivables
-- ----------------------------
INSERT INTO `t_receivables` VALUES ('04DFC20D-B812-11E4-8FC9-782BCBD7746B', 0.00, 7000.00, '04B53C5E-B812-11E4-8FC9-782BCBD7746B', 'customer', 7000.00, '01010001', '4D74E1E4-A129-11E4-9B6A-782BCBD7746B');

SET FOREIGN_KEY_CHECKS = 1;
