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

 Date: 18/05/2021 23:22:50
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_payables_detail
-- ----------------------------
DROP TABLE IF EXISTS `t_payables_detail`;
CREATE TABLE `t_payables_detail`  (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `act_money` decimal(19, 2) NOT NULL,
  `balance_money` decimal(19, 2) NOT NULL,
  `ca_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ca_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `biz_date` datetime NULL DEFAULT NULL,
  `date_created` datetime NULL DEFAULT NULL,
  `pay_money` decimal(19, 2) NOT NULL,
  `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ref_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `data_org` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `company_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_payables_detail
-- ----------------------------
INSERT INTO `t_payables_detail` VALUES ('8800479E-B80F-11E4-8FC9-782BCBD7746B', 0.00, 5000.00, '87D62652-B80F-11E4-8FC9-782BCBD7746B', 'supplier', '2015-01-01 00:00:00', '2015-02-19 16:15:57', 5000.00, '87D62652-B80F-11E4-8FC9-782BCBD7746B', '应付账款期初建账', '01010001', '4D74E1E4-A129-11E4-9B6A-782BCBD7746B');

SET FOREIGN_KEY_CHECKS = 1;
