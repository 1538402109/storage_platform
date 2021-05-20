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

 Date: 19/05/2021 23:01:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_recent_fid
-- ----------------------------
DROP TABLE IF EXISTS `t_recent_fid`;
CREATE TABLE `t_recent_fid`  (
  `fid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `click_count` int(11) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_recent_fid
-- ----------------------------
INSERT INTO `t_recent_fid` VALUES ('-8999', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 5);
INSERT INTO `t_recent_fid` VALUES ('-8996', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 3);
INSERT INTO `t_recent_fid` VALUES ('1003', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 2);
INSERT INTO `t_recent_fid` VALUES ('1002', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 2);
INSERT INTO `t_recent_fid` VALUES ('1001', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 12);
INSERT INTO `t_recent_fid` VALUES ('-8997', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 2);
INSERT INTO `t_recent_fid` VALUES ('2001', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 3);
INSERT INTO `t_recent_fid` VALUES ('1004', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 2);
INSERT INTO `t_recent_fid` VALUES ('2005', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 1);
INSERT INTO `t_recent_fid` VALUES ('1007', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 3);
INSERT INTO `t_recent_fid` VALUES ('2004', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 1);
INSERT INTO `t_recent_fid` VALUES ('2000', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 2);
INSERT INTO `t_recent_fid` VALUES ('2009', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 1);
INSERT INTO `t_recent_fid` VALUES ('-9997', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 3);
INSERT INTO `t_recent_fid` VALUES ('2027', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 2);
INSERT INTO `t_recent_fid` VALUES ('2033', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B', 1);

SET FOREIGN_KEY_CHECKS = 1;
