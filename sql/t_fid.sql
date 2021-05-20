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

 Date: 19/05/2021 23:01:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_fid
-- ----------------------------
DROP TABLE IF EXISTS `t_fid`;
CREATE TABLE `t_fid`  (
  `fid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_fid
-- ----------------------------
INSERT INTO `t_fid` VALUES ('-7994', '系统数据字典');
INSERT INTO `t_fid` VALUES ('-7995', '主菜单维护');
INSERT INTO `t_fid` VALUES ('-7996', '码表设置');
INSERT INTO `t_fid` VALUES ('-7997', '表单视图开发助手');
INSERT INTO `t_fid` VALUES ('-9999', '重新登录');
INSERT INTO `t_fid` VALUES ('-9997', '首页');
INSERT INTO `t_fid` VALUES ('-9996', '修改我的密码');
INSERT INTO `t_fid` VALUES ('-9995', '帮助');
INSERT INTO `t_fid` VALUES ('-9994', '关于');
INSERT INTO `t_fid` VALUES ('-9993', '购买商业服务');
INSERT INTO `t_fid` VALUES ('-8999', '用户管理');
INSERT INTO `t_fid` VALUES ('-8999-01', '组织机构在业务单据中的使用权限');
INSERT INTO `t_fid` VALUES ('-8999-02', '业务员在业务单据中的使用权限');
INSERT INTO `t_fid` VALUES ('-8997', '业务日志');
INSERT INTO `t_fid` VALUES ('-8996', '权限管理');
INSERT INTO `t_fid` VALUES ('1001', '商品');
INSERT INTO `t_fid` VALUES ('1001-01', '商品在业务单据中的使用权限');
INSERT INTO `t_fid` VALUES ('1001-02', '商品分类');
INSERT INTO `t_fid` VALUES ('1002', '商品计量单位');
INSERT INTO `t_fid` VALUES ('1003', '仓库');
INSERT INTO `t_fid` VALUES ('1003-01', '仓库在业务单据中的使用权限');
INSERT INTO `t_fid` VALUES ('1004', '供应商档案');
INSERT INTO `t_fid` VALUES ('1004-01', '供应商档案在业务单据中的使用权限');
INSERT INTO `t_fid` VALUES ('1004-02', '供应商分类');
INSERT INTO `t_fid` VALUES ('1007', '客户资料');
INSERT INTO `t_fid` VALUES ('1007-01', '客户资料在业务单据中的使用权限');
INSERT INTO `t_fid` VALUES ('1007-02', '客户分类');
INSERT INTO `t_fid` VALUES ('2000', '库存建账');
INSERT INTO `t_fid` VALUES ('2001', '采购入库');
INSERT INTO `t_fid` VALUES ('2001-01', '采购入库-新建采购入库单');
INSERT INTO `t_fid` VALUES ('2001-02', '采购入库-编辑采购入库单');
INSERT INTO `t_fid` VALUES ('2001-03', '采购入库-删除采购入库单');
INSERT INTO `t_fid` VALUES ('2001-04', '采购入库-提交入库');
INSERT INTO `t_fid` VALUES ('2001-05', '采购入库-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2001-06', '采购入库-采购单价和金额可见');
INSERT INTO `t_fid` VALUES ('2001-07', '采购入库-打印');
INSERT INTO `t_fid` VALUES ('2002', '销售出库');
INSERT INTO `t_fid` VALUES ('2002-01', '销售出库-销售出库单允许编辑销售单价');
INSERT INTO `t_fid` VALUES ('2002-02', '销售出库-新建销售出库单');
INSERT INTO `t_fid` VALUES ('2002-03', '销售出库-编辑销售出库单');
INSERT INTO `t_fid` VALUES ('2002-04', '销售出库-删除销售出库单');
INSERT INTO `t_fid` VALUES ('2002-05', '销售出库-提交出库');
INSERT INTO `t_fid` VALUES ('2002-06', '销售出库-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2002-07', '销售出库-打印');
INSERT INTO `t_fid` VALUES ('2003', '库存账查询');
INSERT INTO `t_fid` VALUES ('2004', '应收账款管理');
INSERT INTO `t_fid` VALUES ('2005', '应付账款管理');
INSERT INTO `t_fid` VALUES ('2006', '销售退货入库');
INSERT INTO `t_fid` VALUES ('2006-01', '销售退货入库-新建销售退货入库单');
INSERT INTO `t_fid` VALUES ('2006-02', '销售退货入库-编辑销售退货入库单');
INSERT INTO `t_fid` VALUES ('2006-03', '销售退货入库-删除销售退货入库单');
INSERT INTO `t_fid` VALUES ('2006-04', '销售退货入库-提交入库');
INSERT INTO `t_fid` VALUES ('2006-05', '销售退货入库-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2006-06', '销售退货入库-打印');
INSERT INTO `t_fid` VALUES ('2007', '采购退货出库');
INSERT INTO `t_fid` VALUES ('2007-01', '采购退货出库-新建采购退货出库单');
INSERT INTO `t_fid` VALUES ('2007-02', '采购退货出库-编辑采购退货出库单');
INSERT INTO `t_fid` VALUES ('2007-03', '采购退货出库-删除采购退货出库单');
INSERT INTO `t_fid` VALUES ('2007-04', '采购退货出库-提交采购退货出库单');
INSERT INTO `t_fid` VALUES ('2007-05', '采购退货出库-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2007-06', '采购退货出库-打印');
INSERT INTO `t_fid` VALUES ('2008', '业务设置');
INSERT INTO `t_fid` VALUES ('2009', '库间调拨');
INSERT INTO `t_fid` VALUES ('2009-01', '库间调拨-新建调拨单');
INSERT INTO `t_fid` VALUES ('2009-02', '库间调拨-编辑调拨单');
INSERT INTO `t_fid` VALUES ('2009-03', '库间调拨-删除调拨单');
INSERT INTO `t_fid` VALUES ('2009-04', '库间调拨-提交调拨单');
INSERT INTO `t_fid` VALUES ('2009-05', '库间调拨-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2009-06', '库间调拨-打印');
INSERT INTO `t_fid` VALUES ('2010', '库存盘点');
INSERT INTO `t_fid` VALUES ('2010-01', '库存盘点-新建盘点单');
INSERT INTO `t_fid` VALUES ('2010-02', '库存盘点-盘点数据录入');
INSERT INTO `t_fid` VALUES ('2010-03', '库存盘点-删除盘点单');
INSERT INTO `t_fid` VALUES ('2010-04', '库存盘点-提交盘点单');
INSERT INTO `t_fid` VALUES ('2010-05', '库存盘点-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2010-06', '库存盘点-打印');
INSERT INTO `t_fid` VALUES ('2011-01', '首页-销售看板');
INSERT INTO `t_fid` VALUES ('2011-02', '首页-库存看板');
INSERT INTO `t_fid` VALUES ('2011-03', '首页-采购看板');
INSERT INTO `t_fid` VALUES ('2011-04', '首页-资金看板');
INSERT INTO `t_fid` VALUES ('2012', '报表-销售日报表(按商品汇总)');
INSERT INTO `t_fid` VALUES ('2013', '报表-销售日报表(按客户汇总)');
INSERT INTO `t_fid` VALUES ('2014', '报表-销售日报表(按仓库汇总)');
INSERT INTO `t_fid` VALUES ('2015', '报表-销售日报表(按业务员汇总)');
INSERT INTO `t_fid` VALUES ('2016', '报表-销售月报表(按商品汇总)');
INSERT INTO `t_fid` VALUES ('2017', '报表-销售月报表(按客户汇总)');
INSERT INTO `t_fid` VALUES ('2018', '报表-销售月报表(按仓库汇总)');
INSERT INTO `t_fid` VALUES ('2019', '报表-销售月报表(按业务员汇总)');
INSERT INTO `t_fid` VALUES ('2020', '报表-安全库存明细表');
INSERT INTO `t_fid` VALUES ('2021', '报表-应收账款账龄分析表');
INSERT INTO `t_fid` VALUES ('2022', '报表-应付账款账龄分析表');
INSERT INTO `t_fid` VALUES ('2023', '报表-库存超上限明细表');
INSERT INTO `t_fid` VALUES ('2024', '现金收支查询');
INSERT INTO `t_fid` VALUES ('2025', '预收款管理');
INSERT INTO `t_fid` VALUES ('2026', '预付款管理');
INSERT INTO `t_fid` VALUES ('2027', '采购订单');
INSERT INTO `t_fid` VALUES ('2027-01', '采购订单-审核/取消审核');
INSERT INTO `t_fid` VALUES ('2027-02', '采购订单-生成采购入库单');
INSERT INTO `t_fid` VALUES ('2027-03', '采购订单-新建采购订单');
INSERT INTO `t_fid` VALUES ('2027-04', '采购订单-编辑采购订单');
INSERT INTO `t_fid` VALUES ('2027-05', '采购订单-删除采购订单');
INSERT INTO `t_fid` VALUES ('2027-06', '采购订单-关闭订单/取消关闭订单');
INSERT INTO `t_fid` VALUES ('2027-07', '采购订单-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2027-08', '采购订单-打印');
INSERT INTO `t_fid` VALUES ('2028', '销售订单');
INSERT INTO `t_fid` VALUES ('2028-01', '销售订单-审核/取消审核');
INSERT INTO `t_fid` VALUES ('2028-02', '销售订单-生成销售出库单');
INSERT INTO `t_fid` VALUES ('2028-03', '销售订单-新建销售订单');
INSERT INTO `t_fid` VALUES ('2028-04', '销售订单-编辑销售订单');
INSERT INTO `t_fid` VALUES ('2028-05', '销售订单-删除销售订单');
INSERT INTO `t_fid` VALUES ('2028-06', '销售订单-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2028-07', '销售订单-打印');
INSERT INTO `t_fid` VALUES ('2028-08', '销售订单-生成采购订单');
INSERT INTO `t_fid` VALUES ('2028-09', '销售订单-关闭订单/取消关闭订单');
INSERT INTO `t_fid` VALUES ('2029', '商品品牌');
INSERT INTO `t_fid` VALUES ('2030-01', '商品构成-新增子商品');
INSERT INTO `t_fid` VALUES ('2030-02', '商品构成-编辑子商品');
INSERT INTO `t_fid` VALUES ('2030-03', '商品构成-删除子商品');
INSERT INTO `t_fid` VALUES ('2031', '价格体系');
INSERT INTO `t_fid` VALUES ('2031-01', '商品-设置商品价格体系');
INSERT INTO `t_fid` VALUES ('2032', '销售合同');
INSERT INTO `t_fid` VALUES ('2032-01', '销售合同-新建销售合同');
INSERT INTO `t_fid` VALUES ('2032-02', '销售合同-编辑销售合同');
INSERT INTO `t_fid` VALUES ('2032-03', '销售合同-删除销售合同');
INSERT INTO `t_fid` VALUES ('2032-04', '销售合同-审核/取消审核');
INSERT INTO `t_fid` VALUES ('2032-05', '销售合同-生成销售订单');
INSERT INTO `t_fid` VALUES ('2032-06', '销售合同-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2032-07', '销售合同-打印');
INSERT INTO `t_fid` VALUES ('2033', '存货拆分');
INSERT INTO `t_fid` VALUES ('2033-01', '存货拆分-新建拆分单');
INSERT INTO `t_fid` VALUES ('2033-02', '存货拆分-编辑拆分单');
INSERT INTO `t_fid` VALUES ('2033-03', '存货拆分-删除拆分单');
INSERT INTO `t_fid` VALUES ('2033-04', '存货拆分-提交拆分单');
INSERT INTO `t_fid` VALUES ('2033-05', '存货拆分-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2033-06', '存货拆分-打印');
INSERT INTO `t_fid` VALUES ('2034', '工厂');
INSERT INTO `t_fid` VALUES ('2034-01', '工厂在业务单据中的使用权限');
INSERT INTO `t_fid` VALUES ('2034-02', '工厂分类');
INSERT INTO `t_fid` VALUES ('2034-03', '工厂-新增工厂分类');
INSERT INTO `t_fid` VALUES ('2034-04', '工厂-编辑工厂分类');
INSERT INTO `t_fid` VALUES ('2034-05', '工厂-删除工厂分类');
INSERT INTO `t_fid` VALUES ('2034-06', '工厂-新增工厂');
INSERT INTO `t_fid` VALUES ('2034-07', '工厂-编辑工厂');
INSERT INTO `t_fid` VALUES ('2034-08', '工厂-删除工厂');
INSERT INTO `t_fid` VALUES ('2035', '成品委托生产订单');
INSERT INTO `t_fid` VALUES ('2035-01', '成品委托生产订单-新建成品委托生产订单');
INSERT INTO `t_fid` VALUES ('2035-02', '成品委托生产订单-编辑成品委托生产订单');
INSERT INTO `t_fid` VALUES ('2035-03', '成品委托生产订单-删除成品委托生产订单');
INSERT INTO `t_fid` VALUES ('2035-04', '成品委托生产订单-提交成品委托生产订单');
INSERT INTO `t_fid` VALUES ('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单');
INSERT INTO `t_fid` VALUES ('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单');
INSERT INTO `t_fid` VALUES ('2035-07', '成品委托生产订单-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2035-08', '成品委托生产订单-打印');
INSERT INTO `t_fid` VALUES ('2036', '成品委托生产入库');
INSERT INTO `t_fid` VALUES ('2036-01', '成品委托生产入库-新建成品委托生产入库单');
INSERT INTO `t_fid` VALUES ('2036-02', '成品委托生产入库-编辑成品委托生产入库单');
INSERT INTO `t_fid` VALUES ('2036-03', '成品委托生产入库-删除成品委托生产入库单');
INSERT INTO `t_fid` VALUES ('2036-04', '成品委托生产入库-提交入库');
INSERT INTO `t_fid` VALUES ('2036-05', '成品委托生产入库-单据生成PDF');
INSERT INTO `t_fid` VALUES ('2036-06', '成品委托生产入库-打印');
INSERT INTO `t_fid` VALUES ('2101', '会计科目');
INSERT INTO `t_fid` VALUES ('2102', '银行账户');
INSERT INTO `t_fid` VALUES ('2103', '会计期间');

SET FOREIGN_KEY_CHECKS = 1;
