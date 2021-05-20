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

 Date: 16/05/2021 16:10:12
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_menu_item
-- ----------------------------
DROP TABLE IF EXISTS `t_menu_item`;
CREATE TABLE `t_menu_item`  (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `caption` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `parent_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `show_order` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_menu_item
-- ----------------------------
INSERT INTO `t_menu_item` VALUES ('01', '账户', NULL, NULL, 1);
INSERT INTO `t_menu_item` VALUES ('0101', '首页', '-9997', '01', 1);
INSERT INTO `t_menu_item` VALUES ('0102', '重新登录', '-9999', '01', 2);
INSERT INTO `t_menu_item` VALUES ('0103', '修改我的密码', '-9996', '01', 3);
INSERT INTO `t_menu_item` VALUES ('02', '采购', NULL, NULL, 2);
INSERT INTO `t_menu_item` VALUES ('0200', '采购订单', '2027', '02', 0);
INSERT INTO `t_menu_item` VALUES ('0201', '采购入库', '2001', '02', 1);
INSERT INTO `t_menu_item` VALUES ('0202', '采购退货出库', '2007', '02', 2);
INSERT INTO `t_menu_item` VALUES ('03', '库存', NULL, NULL, 3);
INSERT INTO `t_menu_item` VALUES ('0301', '库存账查询', '2003', '03', 1);
INSERT INTO `t_menu_item` VALUES ('0302', '库存建账', '2000', '03', 2);
INSERT INTO `t_menu_item` VALUES ('0303', '库间调拨', '2009', '03', 3);
INSERT INTO `t_menu_item` VALUES ('0304', '库存盘点', '2010', '03', 4);
INSERT INTO `t_menu_item` VALUES ('04', '销售', NULL, NULL, 5);
INSERT INTO `t_menu_item` VALUES ('0401', '销售合同', '2032', '04', 1);
INSERT INTO `t_menu_item` VALUES ('0402', '销售订单', '2028', '04', 2);
INSERT INTO `t_menu_item` VALUES ('0403', '销售出库', '2002', '04', 3);
INSERT INTO `t_menu_item` VALUES ('0404', '销售退货入库', '2006', '04', 4);
INSERT INTO `t_menu_item` VALUES ('05', '客户关系', NULL, NULL, 6);
INSERT INTO `t_menu_item` VALUES ('0501', '客户资料', '1007', '05', 1);
INSERT INTO `t_menu_item` VALUES ('06', '资金', NULL, NULL, 7);
INSERT INTO `t_menu_item` VALUES ('0601', '应收账款管理', '2004', '06', 1);
INSERT INTO `t_menu_item` VALUES ('0602', '应付账款管理', '2005', '06', 2);
INSERT INTO `t_menu_item` VALUES ('0603', '现金收支查询', '2024', '06', 3);
INSERT INTO `t_menu_item` VALUES ('0604', '预收款管理', '2025', '06', 4);
INSERT INTO `t_menu_item` VALUES ('0605', '预付款管理', '2026', '06', 5);
INSERT INTO `t_menu_item` VALUES ('07', '报表', NULL, NULL, 8);
INSERT INTO `t_menu_item` VALUES ('0701', '销售日报表', NULL, '07', 1);
INSERT INTO `t_menu_item` VALUES ('070101', '销售日报表(按商品汇总)', '2012', '0701', 1);
INSERT INTO `t_menu_item` VALUES ('070102', '销售日报表(按客户汇总)', '2013', '0701', 2);
INSERT INTO `t_menu_item` VALUES ('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3);
INSERT INTO `t_menu_item` VALUES ('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4);
INSERT INTO `t_menu_item` VALUES ('0702', '销售月报表', NULL, '07', 2);
INSERT INTO `t_menu_item` VALUES ('070201', '销售月报表(按商品汇总)', '2016', '0702', 1);
INSERT INTO `t_menu_item` VALUES ('070202', '销售月报表(按客户汇总)', '2017', '0702', 2);
INSERT INTO `t_menu_item` VALUES ('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3);
INSERT INTO `t_menu_item` VALUES ('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4);
INSERT INTO `t_menu_item` VALUES ('0703', '库存报表', NULL, '07', 3);
INSERT INTO `t_menu_item` VALUES ('070301', '安全库存明细表', '2020', '0703', 1);
INSERT INTO `t_menu_item` VALUES ('070302', '库存超上限明细表', '2023', '0703', 2);
INSERT INTO `t_menu_item` VALUES ('0706', '资金报表', NULL, '07', 6);
INSERT INTO `t_menu_item` VALUES ('070601', '应收账款账龄分析表', '2021', '0706', 1);
INSERT INTO `t_menu_item` VALUES ('070602', '应付账款账龄分析表', '2022', '0706', 2);
INSERT INTO `t_menu_item` VALUES ('08', '基础数据', NULL, NULL, 10);
INSERT INTO `t_menu_item` VALUES ('0801', '商品', NULL, '08', 1);
INSERT INTO `t_menu_item` VALUES ('080101', '商品', '1001', '0801', 1);
INSERT INTO `t_menu_item` VALUES ('080102', '商品计量单位', '1002', '0801', 2);
INSERT INTO `t_menu_item` VALUES ('080103', '商品品牌', '2029', '0801', 3);
INSERT INTO `t_menu_item` VALUES ('080104', '价格体系', '2031', '0801', 4);
INSERT INTO `t_menu_item` VALUES ('0803', '仓库', '1003', '08', 3);
INSERT INTO `t_menu_item` VALUES ('0804', '供应商档案', '1004', '08', 4);
INSERT INTO `t_menu_item` VALUES ('0805', '工厂', '2034', '08', 5);
INSERT INTO `t_menu_item` VALUES ('09', '系统管理', NULL, NULL, 11);
INSERT INTO `t_menu_item` VALUES ('0901', '用户管理', '-8999', '09', 1);
INSERT INTO `t_menu_item` VALUES ('0902', '权限管理', '-8996', '09', 2);
INSERT INTO `t_menu_item` VALUES ('0903', '业务日志', '-8997', '09', 3);
INSERT INTO `t_menu_item` VALUES ('0904', '业务设置', '2008', '09', 4);
INSERT INTO `t_menu_item` VALUES ('0905', '二次开发', NULL, '09', 5);
INSERT INTO `t_menu_item` VALUES ('090501', '码表设置', '-7996', '0905', 1);
INSERT INTO `t_menu_item` VALUES ('090502', '表单视图开发助手', '-7997', '0905', 2);
INSERT INTO `t_menu_item` VALUES ('090503', '主菜单维护', '-7995', '0905', 3);
INSERT INTO `t_menu_item` VALUES ('090504', '系统数据字典', '-7994', '0905', 4);
INSERT INTO `t_menu_item` VALUES ('10', '帮助', NULL, NULL, 12);
INSERT INTO `t_menu_item` VALUES ('1001', '使用帮助', '-9995', '10', 1);
INSERT INTO `t_menu_item` VALUES ('1003', '关于', '-9994', '10', 3);
INSERT INTO `t_menu_item` VALUES ('11', '财务总账', NULL, NULL, 9);
INSERT INTO `t_menu_item` VALUES ('1101', '基础数据', NULL, '11', 1);
INSERT INTO `t_menu_item` VALUES ('110101', '会计科目', '2101', '1101', 1);
INSERT INTO `t_menu_item` VALUES ('110102', '银行账户', '2102', '1101', 2);
INSERT INTO `t_menu_item` VALUES ('110103', '会计期间', '2103', '1101', 3);
INSERT INTO `t_menu_item` VALUES ('12', '加工', NULL, NULL, 4);
INSERT INTO `t_menu_item` VALUES ('1201', '存货拆分', '2033', '12', 1);
INSERT INTO `t_menu_item` VALUES ('1202', '成品委托生产', NULL, '12', 2);
INSERT INTO `t_menu_item` VALUES ('120201', '成品委托生产订单', '2035', '1202', 1);
INSERT INTO `t_menu_item` VALUES ('120202', '成品委托生产入库', '2036', '1202', 2);

SET FOREIGN_KEY_CHECKS = 1;
