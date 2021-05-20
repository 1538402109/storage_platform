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

 Date: 18/05/2021 23:22:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_payables
-- ----------------------------
DROP TABLE IF EXISTS `t_payables`;
CREATE TABLE `t_payables`  (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `act_money` decimal(19, 2) NOT NULL,
  `balance_money` decimal(19, 2) NOT NULL,
  `ca_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ca_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pay_money` decimal(19, 2) NOT NULL,
  `data_org` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `company_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_payables
-- ----------------------------
INSERT INTO `t_payables` VALUES ('88072F8B-B80F-11E4-8FC9-782BCBD7746B', 0.00, 5000.00, '87D62652-B80F-11E4-8FC9-782BCBD7746B', 'supplier', 5000.00, '01010001', '4D74E1E4-A129-11E4-9B6A-782BCBD7746B');

SET FOREIGN_KEY_CHECKS = 1;
