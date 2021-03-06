<?php

namespace Home\Service;

use Home\Common\FIdConst;
use Think\Think;

/**
 * 数据库升级Service
 *
 * @author JIATU
 */
class UpdateDBService extends PSIBaseService {
	
	/**
	 *
	 * @var \Think\Model
	 */
	private $db;

	public function updateDatabase() {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = M();
		
		$this->db = $db;
		
		// 检查t_psi_db_version是否存在
		if (! $this->tableExists($db, "t_psi_db_version")) {
			return $this->bad("表t_psi_db_db_version不存在，数据库结构实在是太久远了，无法升级");
		}
		
		// 检查t_psi_db_version中的版本号
		$sql = "select db_version from t_psi_db_version";
		$data = $db->query($sql);
		$dbVersion = $data[0]["db_version"];
		if ($dbVersion == $this->CURRENT_DB_VERSION) {
			return $this->bad("当前数据库是最新版本，不用升级");
		}
		
		$this->t_cash($db);
		$this->t_cash_detail($db);
		$this->t_config($db);
		$this->t_customer($db);
		$this->t_fid($db);
		$this->t_goods($db);
		$this->t_goods_category($db);
		$this->t_goods_si($db);
		$this->t_menu_item($db);
		$this->t_permission($db);
		$this->t_po_bill($db);
		$this->t_po_bill_detail($db);
		$this->t_po_pw($db);
		$this->t_pr_bill($db);
		$this->t_pre_payment($db);
		$this->t_pre_payment_detail($db);
		$this->t_pre_receiving($db);
		$this->t_pre_receiving_detail($db);
		$this->t_pw_bill($db);
		$this->t_role_permission($db);
		$this->t_supplier($db);
		$this->t_supplier_category($db);
		$this->t_sr_bill($db);
		$this->t_sr_bill_detail($db);
		$this->t_ws_bill($db);
		$this->t_ws_bill_detail($db);
		
		$this->update_20151016_01($db);
		$this->update_20151031_01($db);
		$this->update_20151102_01($db);
		$this->update_20151105_01($db);
		$this->update_20151106_01($db);
		$this->update_20151106_02($db);
		$this->update_20151108_01($db);
		$this->update_20151110_01($db);
		$this->update_20151110_02($db);
		$this->update_20151111_01($db);
		$this->update_20151112_01($db);
		$this->update_20151113_01($db);
		$this->update_20151119_01($db);
		$this->update_20151119_03($db);
		$this->update_20151121_01($db);
		$this->update_20151123_01($db);
		$this->update_20151123_02($db);
		$this->update_20151123_03($db);
		$this->update_20151124_01($db);
		$this->update_20151126_01($db);
		$this->update_20151127_01($db);
		$this->update_20151128_01($db);
		$this->update_20151128_02($db);
		$this->update_20151128_03($db);
		$this->update_20151210_01($db);
		$this->update_20160105_01($db);
		$this->update_20160105_02($db);
		$this->update_20160108_01($db);
		$this->update_20160112_01($db);
		$this->update_20160116_01($db);
		$this->update_20160116_02($db);
		$this->update_20160118_01($db);
		$this->update_20160119_01($db);
		$this->update_20160120_01($db);
		$this->update_20160219_01($db);
		$this->update_20160301_01($db);
		$this->update_20160303_01($db);
		$this->update_20160314_01($db);
		$this->update_20160620_01($db);
		$this->update_20160722_01($db);
		
		$this->update_20170405_01($db);
		$this->update_20170408_01($db);
		$this->update_20170412_01($db);
		$this->update_20170412_02($db);
		$this->update_20170503_01($db);
		$this->update_20170515_01($db);
		$this->update_20170519_01($db);
		$this->update_20170530_01($db);
		$this->update_20170604_01($db);
		
		$this->update_20170606_01();
		$this->update_20170606_02();
		$this->update_20170606_03();
		$this->update_20170607_01();
		$this->update_20170609_02();
		$this->update_20170927_01();
		$this->update_20171101_01();
		$this->update_20171102_01();
		$this->update_20171102_02();
		$this->update_20171113_01();
		$this->update_20171208_01();
		$this->update_20171214_01();
		$this->update_20171226_01();
		$this->update_20171227_01();
		$this->update_20171229_01();
		
		$this->update_20180101_01();
		$this->update_20180111_01();
		$this->update_20180115_01();
		$this->update_20180117_01();
		$this->update_20180117_02();
		$this->update_20180119_01();
		$this->update_20180119_02();
		$this->update_20180125_01();
		$this->update_20180130_01();
		$this->update_20180201_01();
		$this->update_20180202_01();
		$this->update_20180203_01();
		$this->update_20180203_02();
		$this->update_20180203_03();
		$this->update_20180219_01();
		$this->update_20180305_01();
		$this->update_20180306_01();
		$this->update_20180306_02();
		$this->update_20180307_01();
		$this->update_20180313_01();
		$this->update_20180313_02();
		$this->update_20180314_01();
		$this->update_20180314_02();
		$this->update_20180316_01();
		$this->update_20180406_01();
		$this->update_20180410_01();
		$this->update_20180501_01();
		$this->update_20180501_02();
		$this->update_20180502_01();
		$this->update_20180502_02();
		$this->update_20180502_03();
		$this->update_20180502_04();
		$this->update_20180503_01();
		$this->update_20180503_02();
		$this->update_20180503_03();
		$this->update_20180513_01();
		$this->update_20180517_01();
		$this->update_20180518_01();
		$this->update_20180522_01();
		$this->update_20180526_01();
		$this->update_20180621_01();
		$this->update_20180623_01();
		$this->update_20180825_01();
		$this->update_20180920_01();
		$this->update_20180921_01();
		$this->update_20181005_01();
		$this->update_20181005_02();
		$this->update_20181006_01();
		$this->update_20181009_01();
		$this->update_20181021_01();
		$this->update_20181023_01();
		$this->update_20181024_01();
		$this->update_20181026_01();
		$this->update_20181026_02();
		$this->update_20181104_01();
		$this->update_20181107_01();
		$this->update_20181110_01();
		$this->update_20181112_01();
		$this->update_20181113_01();
		$this->update_20181113_02();
		$this->update_20181114_01();
		$this->update_20181114_02();
		$this->update_20181118_01();
		$this->update_20181120_01();
		$this->update_20181129_01();
		$this->update_20181202_01();
		$this->update_20181205_01();
		$this->update_20181206_01();
		$this->update_20181206_02();
		$this->update_20181210_01();
		$this->update_20181210_02();
		$this->update_20181211_01();
		$this->update_20181218_01();
		$this->update_20181221_01();
		
		$this->update_20190103_01();
		$this->update_20190130_01();
		$this->update_20190213_01();
		$this->update_20190225_01();
		$this->update_20190228_01();
		$this->update_20190307_01();
		$this->update_20190311_01();
		$this->update_20190401_01();
		$this->update_20190402_01();
		$this->update_20190402_02();
		$this->update_20190415_01();
		$this->update_20190416_01();
		$this->update_20190416_02();
		$this->update_20190417_01();
		$this->update_20190418_01();
		$this->update_20190421_01();
		$this->update_20190422_01();
		$this->update_20190422_02();
		$this->update_20190422_03();
		$this->update_20190423_01();
		$this->update_20190426_01();
		$this->update_20190428_01();
		$this->update_20190428_02();
		$this->update_20190428_03();
		$this->update_20190503_01();
		$this->update_20190503_02();
		$this->update_20190504_01();
		$this->update_20190523_01();
		$this->update_20190609_01();
		$this->update_20190610_01();
		$this->update_20190619_01();
		$this->update_20190705_01();
		$this->update_20190710_01();
		$this->update_20190724_01();
		$this->update_20190821_01();
		$this->update_20190924_01();
		$this->update_20190924_02();
		$this->update_20190930_01();
		$this->update_20191009_01();
		$this->update_20191009_02();
		$this->update_20200406_01();
		$this->update_20200413_01();
		$this->update_20200426_01();
		$this->update_20200501_01();
		$this->update_20200707_01();
		$this->update_20210314_01();
		//更新t_org中新加的字段
		$this->update_20210423_01();
		//更新t_menu_item中新加的值
		$this->update_20210423_02();
		// 在表t_menu_item、t_permission、t_fid中增加信息
		$this->update_20210426_01();
		//创建人员商品绑定表
		$this->update_20210506_01();
		//添加公告功能
		$this->update_20210508_01();
		//创建公告表
		$this->update_20210510_01();
		//创建公告分类表
		$this->update_20210510_02();

		$sql = "delete from t_psi_db_version";
		$db->execute($sql);
		$sql = "insert into t_psi_db_version (db_version, update_dt) 
				values ('%s', now())";
		$db->execute($sql, $this->CURRENT_DB_VERSION);
		
		$bl = new BizlogService();
		$bl->insertBizlog("升级数据库表结构，数据库表结构版本 = " . $this->CURRENT_DB_VERSION);
		
		return $this->ok();
	}

	// ============================================
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// 注意：
	// 如果修改了数据库结构，别忘记了在InstallService中修改相应的SQL语句
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// ============================================
	private function notForgot() {
	}
	private function update_20210314_01()
	{
		$db = $this->db;
		$tableName = "t_warehouse";
		
		$columnName = "is_sale";
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int  NOT NULL DEFAULT 1;";
			$db->execute($sql);
        }
	}

	private function update_20200707_01()
	{
		$db = $this->db;
		$tableName = "t_ws_bill";
		
		$columnName = "print_flag";
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int  NOT NULL DEFAULT 0;";
			$db->execute($sql);
        }
	}
	private function update_20200501_01()
	{
		$db = $this->db;
		$tableName = "t_org";
		$columnName = "print_templet";
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) null;";
			$db->execute($sql);
		}
		$columnName = "so_bill_check";
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int  NOT NULL DEFAULT 1;";
			$db->execute($sql);
        }
	}
	private function update_20200426_01() {
		// 本次更新：采购入库单 t_pw_bill_detail    包装规格 unit_result
			$db = $this->db;
			$tableName = "t_pw_bill_detail";
			$columnName = "unit_result";
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName}  varchar(255) null;";
				$db->execute($sql);
			}
			
		}
	private function update_20200413_01()
	{
		$db = $this->db;
		$tableName = "t_warehouse";
		$columnName = "is_default";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName}  int default 0;";
			$db->execute($sql);
		}

	}
	private function update_20200406_01()
	{
		$db = $this->db;
		$tableName = "t_pw_bill_detail";
		$columnName = "batch_date";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName}  varchar(255) null;";
			$db->execute($sql);
		}

		$tableName = "t_ws_bill_detail";
		$columnName = "batch_date";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName}  varchar(255) null;";
			$db->execute($sql);
		}

		$tableName = "t_ic_bill_detail";
		$columnName = "batch_date";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName}  varchar(255) null;";
			$db->execute($sql);
		}
	
		$tableName = "t_inventory_batch";
        if (! $this->tableExists($db, $tableName)) {
            $sql = "DROP TABLE IF EXISTS `t_inventory_batch`;
			CREATE TABLE `t_inventory_batch` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`balance_count` decimal(19,0) NOT NULL DEFAULT '0' COMMENT '当前余额',
			`out_count` decimal(19,0) NOT NULL DEFAULT '0' COMMENT '已出数',
			`batch_date` date DEFAULT NULL COMMENT '生成日期',
			`batch_num` varchar(255) DEFAULT '' COMMENT '批次编号',
			`in_count` decimal(19,0) NOT NULL DEFAULT '0' COMMENT '已入总数',
			`company_id` varchar(255) DEFAULT NULL,
			`data_org` varchar(255) DEFAULT NULL,
			`warehouse_id` varchar(255) NOT NULL COMMENT '仓库ID',
			`goods_id` varchar(255) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
			";
            $db->execute($sql);
        }

	}
	private function update_20191009_02()
	{
		$db = $this->db;
		$sql = "insert into t_fid(fid,name) values('2002-08','销售出库-申请物流配送');";
		$db->execute($sql);
		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('2002-08','2002-08','销售出库-申请物流配送','按钮权限：销售出库[申请物流配送]按钮权限','销售出库','XSCK-SQWLPS',100);";
		$db->execute($sql);

	}
	//taoys
	private function update_20191009_01() {
		// 本次更新：物流应收和应付 菜单权限配置
		$db = $this->db;
		$sql = "insert into t_menu_item(id,caption,fid,parent_id,show_order) values('0607','物流应收账款','2038','06',7);";
		$db->execute($sql);
		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('2038','2038','物流应收账款管理','模块权限：通过菜单进入物流应收账款管理模块的权限','物流应收账款','WLYSZK',100);";
		$db->execute($sql);

		$sql = "insert into t_menu_item(id,caption,fid,parent_id,show_order) values('0608','物流应付账款','2039','06',8);";
		$db->execute($sql);
		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('2039','2039','物流应付账款管理','模块权限：通过菜单进入物流应收账款管理模块的权限','物流应付账款','WLYFZK',100);";
		$db->execute($sql);

	}
		//taoys 
	private function update_20190930_01() {
		// 本次更新：出库单 t_sr_bill_detail    包装规格 unit_result
			$db = $this->db;
			$tableName = "t_sr_bill_detail";
			$columnName = "unit_result";
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName}  varchar(255) null;";
				$db->execute($sql);
			}
			$tableName = "t_ws_bill_detail";
			$columnName = "unit_result";
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName}  varchar(255) null;";
				$db->execute($sql);
			}
			$tableName = "t_so_bill_detail";
			$columnName = "unit_result";
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName}  varchar(255) null;";
				$db->execute($sql);
			}
		}
	private function update_20190924_02()
	{
		$db = $this->db;
		$tableName = "t_goods";
		$columnName = "sale_price2";//预售价2
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) null;";
			$db->execute($sql);
		}
		$columnName = "sale_price3";//预售价3
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) null;";
			$db->execute($sql);
		}
		$columnName = "unit2_id";//辅助单位2
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) null;";
			$db->execute($sql);
		}
		$columnName = "unit2_decimal";
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 1;";
			$db->execute($sql);
		}
		$columnName = "unit3_id";//辅助单位3
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) null;";
			$db->execute($sql);
		}
		$columnName = "unit3_decimal";//辅助单位3 关系
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 1;";
			$db->execute($sql);
		}
		$columnName = "locality";//产地
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) null;";
			$db->execute($sql);
		}
		$columnName = "guarantee_day";//保质期天数
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 0;";
			$db->execute($sql);
		}
	
	}
		//taoys 
	private function update_20190924_01() {
	// 本次更新：出库单 t_ws_bill  申请配送状态 distribution_status
		$db = $this->db;
		$tableName = "t_ws_bill";
		$columnName = "distribution_status";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 0;";
			$db->execute($sql);
		}
	}
	private function update_20190821_01()
	{
		$db = $this->db;
		$tableName = "t_org";
		$columnName = "print_url";
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) null;";
			$db->execute($sql);
        }
	}
	// jiaowei
	private function update_20190724_01()
	{
		$db = $this->db;
		$tableName = "t_org";
		$columnName = "area_code";
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) null;";
			$db->execute($sql);
        }
	}

	//wanglei 在t_org中增加interface_style字段
	//界面风格 0 普通风格 1 深色风格
	private function update_20210423_01(){
		$db = $this->db;
		$tableName = 't_org';
		$columnName = 'interface_style';
		if(! $this->columnExists($db,$tableName,$columnName)){
			$sql = "alter table {$tableName} add {$columnName} varchar(255) not null DEFAULT 'classic';";
			$db->execute($sql);
		}
	}

	//wanglei  新增业务绑定表 t_goods_binding
	private function update_20210506_01(){
		$db = $this->db;
		$tableName = 't_goods_binding';
		if(! $this->tableExists($db,$tableName)){
			$sql = "CREATE TABLE `t_goods_binding`  (
				`personnel_id` varchar(255) NOT NULL COMMENT '人员id',
				`login_name` varchar(255) NOT NULL COMMENT '人员登录名称',
				`goods_id` varchar(255) NOT NULL COMMENT '商品id',
				`goods_name` varchar(255) NOT NULL COMMENT '商品名称',
				`goods_code` varchar(255) NOT NULL COMMENT '商品编号'
			  ) ENGINE = InnoDB COMMENT = '业务商品绑定表' ROW_FORMAT = Dynamic;
			";
			$db->execute($sql);
		}
	}

	//微信绑定/解绑 模块增加
	private function update_20210423_02(){
		//本次更新 新增模块 - 微信绑定/解绑
		$db = $this->db;

		//t_fid 表
		$sql = "insert into t_fid(fid,name) values('-10000','微信绑定/解绑')";
		$db->execute($sql);

		//t_menu_item 表
		$sql = "insert into t_menu_item(id,caption,fid,parent_id,show_order) values
		('0104','微信绑定/解绑','-10000','01','4')";
		$db->execute($sql);

		//t_permission 表
		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values
		('-9000','-10000','微信绑定/解绑','按钮权限：账户模块修改微信绑定状态','微信绑定/解绑','WXBD/JB','100')";
		$db->execute($sql);
	}

	//wangLei  增加用户管理页面
	private function update_20210426_01(){
		$db = $this->db;

		//t_menu_item
		$sql = "insert into t_menu_item(id,caption,fid,parent_id,show_order) 
		values('0906','人员管理','-10001','09','6');";
		$db->execute($sql);

		//t_fid
		$sql = "insert into t_fid(fid,name) values('-10001','人员管理');";
		$db->execute($sql);

		$sql = "insert into t_fid(fid,name) values('-10001-01','人员管理-新增人员');";
		$db->execute($sql);

		$sql = "insert into t_fid(fid,name) values('-10001-02','人员管理-编辑人员');";
		$db->execute($sql);

		$sql = "insert into t_fid(fid,name) values('-10001-03','人员管理-删除人员');";
		$db->execute($sql);

		$sql = "insert into t_fid(fid,name) values('-10001-04','人员管理-修改用户密码');";
		$db->execute($sql);

		//t_permission
		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) 
		values('-10001','-10001','人员管理','模块权限：通过菜单进入人员管理模块','人员管理','RYGL','100');";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) 
		values('-10001-01','-10001-01','人员管理-新增人员','按钮权限：人员管理模块【新增人员】按钮的权限','人员管理','RYGL-XZRY','201');";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) 
		values('-10001-02','-10001-02','人员管理-编辑人员','按钮权限：人员管理模块【编辑人员】按钮的权限','人员管理','RYGL-BJRY','202');";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) 
		values('-10001-03','-10001-03','人员管理-删除人员','按钮权限：人员管理模块【删除人员】按钮的权限','人员管理','RYGL-SCRY','203');";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) 
		values('-10001-04','-10001-04','人员管理-修改用户密码','按钮权限：人员管理模块【修改用户密码】按钮的权限','人员管理','RYGL-XGYHMM','204');";
		$db->execute($sql);
	}

	//wanglei 新增公告管理模块  包括新增公告分类,编辑公告分类,删除公告分类,新增公告,编辑公告,删除公告
	private function update_20210508_01(){
		$db = $this->db;
		
		//t_menu_item
		$sql = "insert into t_menu_item(id,caption,fid,parent_id,show_order) values('0907','公告管理','-10002','09','7')";
		$db->execute($sql);

		//t_fid
		$sql = "insert into t_fid(fid,name) values('-10002','公告管理')";
		$db->execute($sql);
		
		$sql = "insert into t_fid(fid,name) values('-10002-01','公告管理-新增公告分类')";
		$db->execute($sql);

		$sql = "insert into t_fid(fid,name) values('-10002-02','公告管理-编辑公告分类')";
		$db->execute($sql);

		$sql = "insert into t_fid(fid,name) values('-10002-03','公告管理-删除公告分类')";
		$db->execute($sql);

		$sql = "insert into t_fid(fid,name) values('-10002-04','公告管理-新增公告')";
		$db->execute($sql);

		$sql = "insert into t_fid(fid,name) values('-10002-05','公告管理-编辑公告')";
		$db->execute($sql);

		$sql = "insert into t_fid(fid,name) values('-10002-06','公告管理-删除公告')";
		$db->execute($sql);

		//t_permission
		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('-10002','-10002','公告管理','模块权限：通过菜单进入公告模块的权限', '公告管理', 'GGGL', '100')";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('-10002-01', '-10002-01', '公告管理-新增公告分类', '按钮权限：公告管理模块【新增公告分类】按钮的权限', '公告管理', 'GGGL-XZGGFL', '201')";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('-10002-02', '-10002-02', '公告管理-编辑公告分类', '按钮权限：公告管理模块【编辑公告分类】按钮的权限', '公告管理', 'GGGL-BJGGFL', '202')";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('-10002-03', '-10002-03', '公告管理-删除公告分类', '按钮权限：公告管理模块【删除公告分类】按钮的权限', '公告管理', 'GGGL-SCGGFL', '203')";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('-10002-04', '-10002-04', '公告管理-新增公告', '按钮权限：公告管理模块【新增公告】按钮的权限', '公告管理', 'GGGL-XZGG', '204')";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('-10002-05', '-10002-05', '公告管理-编辑公告', '按钮权限：公告管理模块【编辑公告】按钮的权限', '公告管理', 'GGGL-BJGG', '205')";
		$db->execute($sql);

		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('-10002-06', '-10002-06', '公告管理-删除公告', '按钮权限：公告管理模块【删除公告】按钮的权限', '公告管理', 'GGGL-SCGG', '206')";
		$db->execute($sql);
	}

	//wanglei 新增公告表 t_notice
	private function update_20210510_01(){
		$db = $this->db;
		$tableName = "t_notice";
		if(! $this->tableExists($db,$tableName)){
			$sql = "CREATE TABLE `t_notice`  (
				`id` varchar(255)  NOT NULL,
				`name` varchar(255)  NOT NULL COMMENT '公告标题',
				`address` varchar(255)  NOT NULL COMMENT '公告地址',
				`release_time` varchar(255)  NOT NULL COMMENT '发布时间',
				`user` varchar(255)  NOT NULL COMMENT '操作人',
				`category_id` varchar(255)  NOT NULL COMMENT '分类',
				`auto` int NOT NULL COMMENT '是否自动弹窗',
				`top` int NOT NULL COMMENT '是否置顶',
				`roll` int NOT NULL COMMENT '是否滚动',
				`show_number` varchar(255)  NOT NULL DEFAULT '0' COMMENT '浏览次数',
				`state` int NOT NULL COMMENT '状态',
				`org` varchar(255)  NOT NULL COMMENT '目标组织机构',
				PRIMARY KEY (`id`)
			) ENGINE = InnoDB COMMENT = '公告表'";
			$db->execute($sql);
		}
	}

	//wangei 新增公告分类表 t_notice_category
	private function update_20210510_02(){
		$db = $this->db;
		$tableName = "t_notice_category";
		if(!$this->tableExists($db,$tableName)){
			$sql = "CREATE TABLE `t_notice_category`  (
				`id` varchar(255) NOT NULL,
				`name` varchar(255) NOT NULL COMMENT '公告分类名称',
				`data_org` varchar(255) NOT NULL COMMENT '数据域',
				PRIMARY KEY (`id`)
			  	) ENGINE = InnoDB COMMENT = '公告分类表'";
			$db->execute($sql);
		}
	}

	//taoys
	private function update_20190710_01()
	{
		$db = $this->db;
		$sql = "insert into t_fid(fid,name) values('2006-07','采购退货出库-审核');";
		$db->execute($sql);
		$sql = "insert into t_fid(fid,name) values('2006-08','采购退货出库-取消审核');";
		$db->execute($sql);
		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('2006-07','2006-07','采购退货出库-审核','按钮权限：销售退货入库模块[审核]按钮权限','销售退货入库','XSTHRK_SH',100);";
		$db->execute($sql);
		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('2006-08','2006-08','采购退货出库-取消审核','按钮权限：销售退货入库模块[取消审核]按钮权限','销售退货入库','XSTHRK_QXSH',100);";
		$db->execute($sql);
	}

	//jiaowei 
	private function update_20190705_01()
	{
		$db = $this->db;
		$tableName = "t_goods";
		$columnName = "lev2_sale_price";
        if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) null;";
			$db->execute($sql);
        }
	
	}
	//taoys
	private function update_20190619_01() {
		// 本次更新：应收账款明细 t_receivables_detail  增加欠收类型 receiving_type(0 记应收账款 3 物流代收) 和 签收责任人字段 operator
		$db = $this->db;
		$sql = "insert into t_menu_item(id,caption,fid,parent_id,show_order) values('0606','应收账款明细管理','2037','06',6);";
		$db->execute($sql);
		$sql = "insert into t_permission(id,fid,name,note,category,py,show_order) values('2037','2037','应收账款明细管理','模块权限：通过菜单进入应收账款管理明细模块的权限','应收账款管理','YSZKMXGL',100);";
		$db->execute($sql);
	}

	//taoys
	private function update_20190610_01() {
		// 本次更新：应收账款明细 t_receivables_detail  增加欠收类型 receiving_type(0 记应收账款 3 物流代收) 和 签收责任人字段 operator
		$db = $this->db;
			// 字段：is_fixed
			$tableName = "t_receivables_detail";
			$columnName = "receiving_type";
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 0;";
				$db->execute($sql);
			}
			$tableName = "t_receivables_detail";
			$columnName = "operator";
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
				$db->execute($sql);
			}
			$tableName = "t_ws_bill";
			$columnName = "distribution_type";
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 0;";
				$db->execute($sql);
			}
	}

	//taoys
	private function update_20190609_01() {
		// 本次更新：销售订单表t_so_bill 增加配送类型 distribution_type
		$db = $this->db;
			// 字段：is_fixed
			$tableName = "t_so_bill";
			$columnName = "distribution_type";
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 0;";
				$db->execute($sql);
			}
	}
	//taoys
	private function update_20190523_01() {
		// 本次更新：修改t_menu_item 菜单 将首页单独展示
		$db = $this->db;
		$sql = "UPDATE t_menu_item set parent_id=null where id=0101;
				UPDATE t_menu_item set caption = '账户' where id = 01;
				UPDATE t_permission set name = '客户分类在业务单据中使用的权限' where id = '1007-02';
		";
			$db->execute($sql);
	}
	private function update_20190504_01() {
		// 本次更新：t_sysdict_record_status的字段codeInt改为code_int
		$db = $this->db;
		$tableName = "t_sysdict_record_status";
		$columnName = "codeInt";
		if ($this->columnExists($db, $tableName, $columnName)) {
			$sql = "DROP TABLE IF EXISTS `t_sysdict_record_status`;
					CREATE TABLE IF NOT EXISTS `t_sysdict_record_status` (
					  `id` varchar(255) NOT NULL,
					  `code` varchar(255) NOT NULL,
					  `code_int` int(11) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `py` varchar(255) NOT NULL,
					  `memo` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
			
			$sql = "TRUNCATE TABLE `t_sysdict_record_status`;
					INSERT INTO `t_sysdict_record_status` (`id`, `code`, `code_int`, `name`, `py`, `memo`) VALUES
					('9B90C56E-696E-11E9-B2BF-782BCBD7746B', '1', 1, '启用', 'QY', '记录处于启用状态'),
					('AC7F3FAB-696E-11E9-B2BF-782BCBD7746B', '2', 2, '停用', 'TY', '记录处于停用状态');
			";
			$db->execute($sql);
		}
	}

	private function update_20190503_02() {
		// 本次更新：t_code_table_md新增字段md_version和is_fixed
		$db = $this->db;
		
		// 字段：md_version
		$tableName = "t_code_table_md";
		$columnName = "md_version";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 1;";
			$db->execute($sql);
		}
		
		// 字段：is_fixed
		$tableName = "t_code_table_md";
		$columnName = "is_fixed";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 2;";
			$db->execute($sql);
		}
	}

	private function update_20190503_01() {
		// 本次更新：新增权限-销售订单-关闭订单
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7994', '系统数据字典'),
				('-7995', '主菜单维护'),
				('-7996', '码表设置'),
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2028-08', '销售订单-生成采购订单'),
				('2028-09', '销售订单-关闭订单/取消关闭订单'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2034', '工厂'),
				('2034-01', '工厂在业务单据中的使用权限'),
				('2034-02', '工厂分类'),
				('2034-03', '工厂-新增工厂分类'),
				('2034-04', '工厂-编辑工厂分类'),
				('2034-05', '工厂-删除工厂分类'),
				('2034-06', '工厂-新增工厂'),
				('2034-07', '工厂-编辑工厂'),
				('2034-08', '工厂-删除工厂'),
				('2035', '成品委托生产订单'),
				('2035-01', '成品委托生产订单-新建成品委托生产订单'),
				('2035-02', '成品委托生产订单-编辑成品委托生产订单'),
				('2035-03', '成品委托生产订单-删除成品委托生产订单'),
				('2035-04', '成品委托生产订单-提交成品委托生产订单'),
				('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单'),
				('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单'),
				('2035-07', '成品委托生产订单-单据生成PDF'),
				('2035-08', '成品委托生产订单-打印'),
				('2036', '成品委托生产入库'),
				('2036-01', '成品委托生产入库-新建成品委托生产入库单'),
				('2036-02', '成品委托生产入库-编辑成品委托生产入库单'),
				('2036-03', '成品委托生产入库-删除成品委托生产入库单'),
				('2036-04', '成品委托生产入库-提交入库'),
				('2036-05', '成品委托生产入库-单据生成PDF'),
				('2036-06', '成品委托生产入库-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-7994', '-7994', '系统数据字典', '模块权限：通过菜单进入系统数据字典模块的权限', '系统数据字典', 'XTSJZD', 100),
				('-7995', '-7995', '主菜单维护', '模块权限：通过菜单进入主菜单维护模块的权限', '主菜单维护', 'ZCDWH', 100),
				('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
				('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
				('2028-09', '2028-09', '销售订单-关闭订单/取消关闭订单', '按钮权限：销售订单模块[关闭销售订单]和[取消销售订单关闭状态]按钮的权限', '销售订单', 'XSDD_GBDD', 209),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
				('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
				('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
				('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
				('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
				('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
				('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
				('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
				('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
				('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
				('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
				('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
				('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
				('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
				('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
				('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
				('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
				('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
				('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
				('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
				('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
				('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
				('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
				('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
				('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20190428_03() {
		// 本次更新：新增模块-系统数据字典
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7994', '系统数据字典'),
				('-7995', '主菜单维护'),
				('-7996', '码表设置'),
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2028-08', '销售订单-生成采购订单'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2034', '工厂'),
				('2034-01', '工厂在业务单据中的使用权限'),
				('2034-02', '工厂分类'),
				('2034-03', '工厂-新增工厂分类'),
				('2034-04', '工厂-编辑工厂分类'),
				('2034-05', '工厂-删除工厂分类'),
				('2034-06', '工厂-新增工厂'),
				('2034-07', '工厂-编辑工厂'),
				('2034-08', '工厂-删除工厂'),
				('2035', '成品委托生产订单'),
				('2035-01', '成品委托生产订单-新建成品委托生产订单'),
				('2035-02', '成品委托生产订单-编辑成品委托生产订单'),
				('2035-03', '成品委托生产订单-删除成品委托生产订单'),
				('2035-04', '成品委托生产订单-提交成品委托生产订单'),
				('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单'),
				('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单'),
				('2035-07', '成品委托生产订单-单据生成PDF'),
				('2035-08', '成品委托生产订单-打印'),
				('2036', '成品委托生产入库'),
				('2036-01', '成品委托生产入库-新建成品委托生产入库单'),
				('2036-02', '成品委托生产入库-编辑成品委托生产入库单'),
				('2036-03', '成品委托生产入库-删除成品委托生产入库单'),
				('2036-04', '成品委托生产入库-提交入库'),
				('2036-05', '成品委托生产入库-单据生成PDF'),
				('2036-06', '成品委托生产入库-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 主菜单
		$sql = "TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('12', '加工', NULL, NULL, 4),
				('1201', '存货拆分', '2033', '12', 1),
				('1202', '成品委托生产', NULL, '12', 2),
				('120201', '成品委托生产订单', '2035', '1202', 1),
				('120202', '成品委托生产入库', '2036', '1202', 2),
				('04', '销售', NULL, NULL, 5),
				('0401', '销售合同', '2032', '04', 1),
				('0402', '销售订单', '2028', '04', 2),
				('0403', '销售出库', '2002', '04', 3),
				('0404', '销售退货入库', '2006', '04', 4),
				('05', '客户关系', NULL, NULL, 6),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 7),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 8),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 9),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('110103', '会计期间', '2103', '1101', 3),
				('08', '基础数据', NULL, NULL, 10),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('0805', '工厂', '2034', '08', 5),
				('09', '系统管理', NULL, NULL, 11),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '码表设置', '-7996', '0905', 1),
				('090502', '表单视图开发助手', '-7997', '0905', 2),
				('090503', '主菜单维护', '-7995', '0905', 3),
				('090504', '系统数据字典', '-7994', '0905', 4),
				('10', '帮助', NULL, NULL, 12),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-7994', '-7994', '系统数据字典', '模块权限：通过菜单进入系统数据字典模块的权限', '系统数据字典', 'XTSJZD', 100),
				('-7995', '-7995', '主菜单维护', '模块权限：通过菜单进入主菜单维护模块的权限', '主菜单维护', 'ZCDWH', 100),
				('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
				('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
				('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
				('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
				('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
				('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
				('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
				('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
				('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
				('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
				('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
				('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
				('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
				('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
				('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
				('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
				('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
				('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
				('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
				('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
				('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
				('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
				('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
				('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
				('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
				('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20190428_02() {
		// 本次更新：初始化t_sysdict_record_status的数据
		$db = $this->db;
		$sql = "TRUNCATE TABLE `t_dict_table_category`;
				INSERT INTO `t_dict_table_category` (`id`, `code`, `name`, `parent_id`) VALUES
				('01', '01', '码表', NULL);
				
				TRUNCATE TABLE `t_dict_table_md`;
				INSERT INTO `t_dict_table_md` (`id`, `code`, `name`, `table_name`, `category_id`, `memo`, `py`) VALUES
				('01', '01', '码表记录状态', 't_sysdict_record_status', '01', '用于码表', 'MBJLZT');
				
				TRUNCATE TABLE `t_sysdict_record_status`;
				INSERT INTO `t_sysdict_record_status` (`id`, `code`, `codeInt`, `name`, `py`, `memo`) VALUES
				('9B90C56E-696E-11E9-B2BF-782BCBD7746B', '1', 1, '启用', 'QY', '记录处于启用状态'),
				('AC7F3FAB-696E-11E9-B2BF-782BCBD7746B', '2', 2, '停用', 'TY', '记录处于停用状态');
		";
		$db->execute($sql);
	}

	private function update_20190428_01() {
		// 本次更新：新增表t_sysdict_record_status
		$db = $this->db;
		$tableName = "t_sysdict_record_status";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_sysdict_record_status` (
					  `id` varchar(255) NOT NULL,
					  `code` varchar(255) NOT NULL,
					  `codeInt` int(11) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `py` varchar(255) NOT NULL,
					  `memo` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20190426_01() {
		// 本次更新：t_code_table_cols_md新增字段width_in_view
		$db = $this->db;
		$tableName = "t_code_table_cols_md";
		$columnName = "width_in_view";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20190423_01() {
		// 本次更新：新增模块-主菜单维护
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7995', '主菜单维护'),
				('-7996', '码表设置'),
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2028-08', '销售订单-生成采购订单'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2034', '工厂'),
				('2034-01', '工厂在业务单据中的使用权限'),
				('2034-02', '工厂分类'),
				('2034-03', '工厂-新增工厂分类'),
				('2034-04', '工厂-编辑工厂分类'),
				('2034-05', '工厂-删除工厂分类'),
				('2034-06', '工厂-新增工厂'),
				('2034-07', '工厂-编辑工厂'),
				('2034-08', '工厂-删除工厂'),
				('2035', '成品委托生产订单'),
				('2035-01', '成品委托生产订单-新建成品委托生产订单'),
				('2035-02', '成品委托生产订单-编辑成品委托生产订单'),
				('2035-03', '成品委托生产订单-删除成品委托生产订单'),
				('2035-04', '成品委托生产订单-提交成品委托生产订单'),
				('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单'),
				('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单'),
				('2035-07', '成品委托生产订单-单据生成PDF'),
				('2035-08', '成品委托生产订单-打印'),
				('2036', '成品委托生产入库'),
				('2036-01', '成品委托生产入库-新建成品委托生产入库单'),
				('2036-02', '成品委托生产入库-编辑成品委托生产入库单'),
				('2036-03', '成品委托生产入库-删除成品委托生产入库单'),
				('2036-04', '成品委托生产入库-提交入库'),
				('2036-05', '成品委托生产入库-单据生成PDF'),
				('2036-06', '成品委托生产入库-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 主菜单
		$sql = "TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('12', '加工', NULL, NULL, 4),
				('1201', '存货拆分', '2033', '12', 1),
				('1202', '成品委托生产', NULL, '12', 2),
				('120201', '成品委托生产订单', '2035', '1202', 1),
				('120202', '成品委托生产入库', '2036', '1202', 2),
				('04', '销售', NULL, NULL, 5),
				('0401', '销售合同', '2032', '04', 1),
				('0402', '销售订单', '2028', '04', 2),
				('0403', '销售出库', '2002', '04', 3),
				('0404', '销售退货入库', '2006', '04', 4),
				('05', '客户关系', NULL, NULL, 6),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 7),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 8),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 9),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('110103', '会计期间', '2103', '1101', 3),
				('08', '基础数据', NULL, NULL, 10),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('0805', '工厂', '2034', '08', 5),
				('09', '系统管理', NULL, NULL, 11),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '码表设置', '-7996', '0905', 1),
				('090502', '表单视图开发助手', '-7997', '0905', 2),
				('090503', '主菜单维护', '-7995', '0905', 3),
				('10', '帮助', NULL, NULL, 12),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-7995', '-7995', '主菜单维护', '模块权限：通过菜单进入主菜单维护设置模块的权限', '主菜单维护', 'ZCDWH', 100),
				('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
				('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
				('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
				('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
				('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
				('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
				('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
				('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
				('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
				('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
				('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
				('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
				('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
				('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
				('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
				('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
				('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
				('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
				('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
				('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
				('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
				('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
				('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
				('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
				('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
				('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20190422_03() {
		// 本次更新：t_code_table_md新增字段fid
		$db = $this->db;
		$tableName = "t_code_table_md";
		$columnName = "fid";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20190422_02() {
		// 本次更新：新增表t_permission_plus
		$db = $this->db;
		$tableName = "t_permission_plus";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_permission_plus` (
					  `id` varchar(255) NOT NULL,
					  `fid` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `note` varchar(255) DEFAULT NULL,
					  `category` varchar(255) DEFAULT NULL,
					  `py` varchar(255) DEFAULT NULL,
					  `show_order` int(11) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20190422_01() {
		// 本次更新：新增表t_fid_plus和t_menu_item_plus
		$db = $this->db;
		
		// t_fid_plus
		$tableName = "t_fid_plus";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_fid_plus` (
					  `fid` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		// t_menu_item_plus
		$tableName = "t_menu_item_plus";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_menu_item_plus` (
					  `id` varchar(255) NOT NULL,
					  `caption` varchar(255) NOT NULL,
					  `fid` varchar(255) DEFAULT NULL,
					  `parent_id` varchar(255) DEFAULT NULL,
					  `show_order` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20190421_01() {
		// 本次更新：t_code_table_cols_md新增字段：sys_col和is_visible
		$db = $this->db;
		$tableName = "t_code_table_cols_md";
		$columnName = "sys_col";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT 1;";
			$db->execute($sql);
		}
		
		$columnName = "is_visible";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT 1;";
			$db->execute($sql);
		}
	}

	private function update_20190418_01() {
		// 本次更新：t_warehouse新增字段enabled
		$db = $this->db;
		
		$tableName = "t_warehouse";
		$columnName = "enabled";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT 1;";
			$db->execute($sql);
		}
	}

	private function update_20190417_01() {
		// 本次更新：t_code_table_cols_md新增字段must_input
		$db = $this->db;
		
		$tableName = "t_code_table_cols_md";
		$columnName = "must_input";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT 1;";
			$db->execute($sql);
		}
	}

	private function update_20190416_02() {
		// 本次更新：新增模块 - 码表设置
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7996', '码表设置'),
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2028-08', '销售订单-生成采购订单'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2034', '工厂'),
				('2034-01', '工厂在业务单据中的使用权限'),
				('2034-02', '工厂分类'),
				('2034-03', '工厂-新增工厂分类'),
				('2034-04', '工厂-编辑工厂分类'),
				('2034-05', '工厂-删除工厂分类'),
				('2034-06', '工厂-新增工厂'),
				('2034-07', '工厂-编辑工厂'),
				('2034-08', '工厂-删除工厂'),
				('2035', '成品委托生产订单'),
				('2035-01', '成品委托生产订单-新建成品委托生产订单'),
				('2035-02', '成品委托生产订单-编辑成品委托生产订单'),
				('2035-03', '成品委托生产订单-删除成品委托生产订单'),
				('2035-04', '成品委托生产订单-提交成品委托生产订单'),
				('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单'),
				('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单'),
				('2035-07', '成品委托生产订单-单据生成PDF'),
				('2035-08', '成品委托生产订单-打印'),
				('2036', '成品委托生产入库'),
				('2036-01', '成品委托生产入库-新建成品委托生产入库单'),
				('2036-02', '成品委托生产入库-编辑成品委托生产入库单'),
				('2036-03', '成品委托生产入库-删除成品委托生产入库单'),
				('2036-04', '成品委托生产入库-提交入库'),
				('2036-05', '成品委托生产入库-单据生成PDF'),
				('2036-06', '成品委托生产入库-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 菜单
		$sql = "TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('12', '加工', NULL, NULL, 4),
				('1201', '存货拆分', '2033', '12', 1),
				('1202', '成品委托生产', NULL, '12', 2),
				('120201', '成品委托生产订单', '2035', '1202', 1),
				('120202', '成品委托生产入库', '2036', '1202', 2),
				('04', '销售', NULL, NULL, 5),
				('0401', '销售合同', '2032', '04', 1),
				('0402', '销售订单', '2028', '04', 2),
				('0403', '销售出库', '2002', '04', 3),
				('0404', '销售退货入库', '2006', '04', 4),
				('05', '客户关系', NULL, NULL, 6),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 7),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 8),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 9),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('110103', '会计期间', '2103', '1101', 3),
				('08', '基础数据', NULL, NULL, 10),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('0805', '工厂', '2034', '08', 5),
				('09', '系统管理', NULL, NULL, 11),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '码表设置', '-7996', '0905', 1),
				('090502', '表单视图开发助手', '-7997', '0905', 2),
				('10', '帮助', NULL, NULL, 12),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
				('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
				('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
				('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
				('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
				('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
				('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
				('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
				('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
				('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
				('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
				('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
				('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
				('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
				('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
				('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
				('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
				('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
				('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
				('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
				('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
				('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
				('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
				('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
				('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
				('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20190416_01() {
		// 本次更新：新增表t_code_table_category、t_code_table_md和t_code_table_cols_md
		$db = $this->db;
		
		// t_code_table_category
		$tableName = "t_code_table_category";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_code_table_category` (
					  `id` varchar(255) NOT NULL,
					  `code` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `parent_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		// t_code_table_md
		$tableName = "t_code_table_md";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_code_table_md` (
					  `id` varchar(255) NOT NULL,
					  `code` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `table_name` varchar(255) NOT NULL,
					  `category_id` varchar(255) NOT NULL,
					  `memo` varchar(1000) DEFAULT NULL,
					  `py` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		// t_code_table_cols_md
		$tableName = "t_code_table_cols_md";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_code_table_cols_md` (
					  `id` varchar(255) NOT NULL,
					  `table_id` varchar(255) NOT NULL,
					  `caption` varchar(255) NOT NULL,
					  `db_field_name` varchar(255) NOT NULL,
					  `db_field_type` varchar(255) NOT NULL,
					  `db_field_length` int(11) NOT NULL,
					  `db_field_decimal` int(11) NOT NULL,
					  `show_order` int(11) NOT NULL,
					  `value_from` int(11) DEFAULT NULL,
					  `value_from_table_name` varchar(255) DEFAULT NULL,
					  `value_from_col_name` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20190415_01() {
		// 本次更新：新增表t_dict_table_category和t_dict_table_md
		$db = $this->db;
		
		$tableName = "t_dict_table_category";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_dict_table_category` (
					  `id` varchar(255) NOT NULL,
					  `code` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `parent_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		$tableName = "t_dict_table_md";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_dict_table_md` (
					  `id` varchar(255) NOT NULL,
					  `code` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `table_name` varchar(255) NOT NULL,
					  `category_id` varchar(255) NOT NULL,
					  `memo` varchar(1000) DEFAULT NULL,
					  `py` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20190402_02() {
		// 本次更新：新增表t_so_po
		$db = $this->db;
		
		$tableName = "t_so_po";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_so_po` (
					  `so_id` varchar(255) NOT NULL,
					  `po_id` varchar(255) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20190402_01() {
		// 本次更新：销售订单增加生成采购订单按钮权限
		$db = $this->db;
		
		// t_fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2028-08', '销售订单-生成采购订单'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2034', '工厂'),
				('2034-01', '工厂在业务单据中的使用权限'),
				('2034-02', '工厂分类'),
				('2034-03', '工厂-新增工厂分类'),
				('2034-04', '工厂-编辑工厂分类'),
				('2034-05', '工厂-删除工厂分类'),
				('2034-06', '工厂-新增工厂'),
				('2034-07', '工厂-编辑工厂'),
				('2034-08', '工厂-删除工厂'),
				('2035', '成品委托生产订单'),
				('2035-01', '成品委托生产订单-新建成品委托生产订单'),
				('2035-02', '成品委托生产订单-编辑成品委托生产订单'),
				('2035-03', '成品委托生产订单-删除成品委托生产订单'),
				('2035-04', '成品委托生产订单-提交成品委托生产订单'),
				('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单'),
				('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单'),
				('2035-07', '成品委托生产订单-单据生成PDF'),
				('2035-08', '成品委托生产订单-打印'),
				('2036', '成品委托生产入库'),
				('2036-01', '成品委托生产入库-新建成品委托生产入库单'),
				('2036-02', '成品委托生产入库-编辑成品委托生产入库单'),
				('2036-03', '成品委托生产入库-删除成品委托生产入库单'),
				('2036-04', '成品委托生产入库-提交入库'),
				('2036-05', '成品委托生产入库-单据生成PDF'),
				('2036-06', '成品委托生产入库-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// t_permission
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
				('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
				('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
				('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
				('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
				('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
				('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
				('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
				('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
				('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
				('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
				('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
				('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
				('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
				('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
				('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
				('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
				('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
				('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
				('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
				('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
				('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
				('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
				('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
				('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
				('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20190401_01() {
		// 本次更新：供应商关联商品
		$db = $this->db;
		
		// t_supplier新增字段goods_range
		$tableName = "t_supplier";
		$columnName = "goods_range";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 1;";
			$db->execute($sql);
		}
		
		// 新增表: t_supplier_goods_range
		$tableName = "t_supplier_goods_range";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_supplier_goods_range` (
					  `id` varchar(255) NOT NULL,
					  `supplier_id` varchar(255) NOT NULL,
					  `g_id` varchar(255) NOT NULL,
					  `g_id_type` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20190311_01() {
		// 本次更新：销售出库单增加税率字段
		$db = $this->db;
		
		$tableName = "t_ws_bill";
		$columnName = "tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		$columnName = "money_with_tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		$tableName = "t_ws_bill_detail";
		$columnName = "tax_rate";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		$columnName = "tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		$columnName = "money_with_tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20190307_01() {
		// 本次更新：采购入库单增加税率字段
		$db = $this->db;
		
		$tableName = "t_pw_bill";
		$columnName = "tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		$columnName = "money_with_tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		$tableName = "t_pw_bill_detail";
		$columnName = "tax_rate";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		$columnName = "tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		$columnName = "money_with_tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20190228_01() {
		// 本次更新：商品分类和商品增加税率字段
		$db = $this->db;
		
		// 商品分类
		$tableName = "t_goods_category";
		$columnName = "tax_rate";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		// 商品
		$tableName = "t_goods";
		$columnName = "tax_rate";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20190225_01() {
		// 本次更新： 成品委托生产入库单增加税金字段
		$db = $this->db;
		
		$tableName = "t_dmw_bill";
		$columnName = "tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		$columnName = "money_with_tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		$tableName = "t_dmw_bill_detail";
		$columnName = "tax_rate";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		$columnName = "tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
		$columnName = "money_with_tax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20190213_01() {
		// 本次更新：采购退货出库单增加备注字段
		$db = $this->db;
		
		$tableName = "t_pr_bill";
		$columnName = "bill_memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		$tableName = "t_pr_bill_detail";
		$columnName = "memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20190130_01() {
		// 本次更新：销售退货入库单增加备注字段
		$db = $this->db;
		
		$tableName = "t_sr_bill";
		$columnName = "bill_memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		$tableName = "t_sr_bill_detail";
		$columnName = "memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20190103_01() {
		// 本次更新：调拨单增加备注字段
		$db = $this->db;
		
		$tableName = "t_it_bill";
		$columnName = "bill_memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		$tableName = "t_it_bill_detail";
		$columnName = "memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20181221_01() {
		// 本次更新：新增模块 - 成品委托生产入库
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2034', '工厂'),
				('2034-01', '工厂在业务单据中的使用权限'),
				('2034-02', '工厂分类'),
				('2034-03', '工厂-新增工厂分类'),
				('2034-04', '工厂-编辑工厂分类'),
				('2034-05', '工厂-删除工厂分类'),
				('2034-06', '工厂-新增工厂'),
				('2034-07', '工厂-编辑工厂'),
				('2034-08', '工厂-删除工厂'),
				('2035', '成品委托生产订单'),
				('2035-01', '成品委托生产订单-新建成品委托生产订单'),
				('2035-02', '成品委托生产订单-编辑成品委托生产订单'),
				('2035-03', '成品委托生产订单-删除成品委托生产订单'),
				('2035-04', '成品委托生产订单-提交成品委托生产订单'),
				('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单'),
				('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单'),
				('2035-07', '成品委托生产订单-单据生成PDF'),
				('2035-08', '成品委托生产订单-打印'),
				('2036', '成品委托生产入库'),
				('2036-01', '成品委托生产入库-新建成品委托生产入库单'),
				('2036-02', '成品委托生产入库-编辑成品委托生产入库单'),
				('2036-03', '成品委托生产入库-删除成品委托生产入库单'),
				('2036-04', '成品委托生产入库-提交入库'),
				('2036-05', '成品委托生产入库-单据生成PDF'),
				('2036-06', '成品委托生产入库-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 主菜单
		$sql = "TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('12', '加工', NULL, NULL, 4),
				('1201', '存货拆分', '2033', '12', 1),
				('1202', '成品委托生产', NULL, '12', 2),
				('120201', '成品委托生产订单', '2035', '1202', 1),
				('120202', '成品委托生产入库', '2036', '1202', 2),
				('04', '销售', NULL, NULL, 5),
				('0401', '销售合同', '2032', '04', 1),
				('0402', '销售订单', '2028', '04', 2),
				('0403', '销售出库', '2002', '04', 3),
				('0404', '销售退货入库', '2006', '04', 4),
				('05', '客户关系', NULL, NULL, 6),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 7),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 8),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 9),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('110103', '会计期间', '2103', '1101', 3),
				('08', '基础数据', NULL, NULL, 10),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('0805', '工厂', '2034', '08', 5),
				('09', '系统管理', NULL, NULL, 11),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '表单视图开发助手', '-7997', '0905', 1),
				('10', '帮助', NULL, NULL, 12),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
				('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
				('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
				('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
				('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
				('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
				('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
				('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
				('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
				('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
				('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
				('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
				('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
				('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
				('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
				('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
				('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
				('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
				('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
				('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
				('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
				('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
				('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
				('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
				('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20181218_01() {
		// 本次更新：新增表 t_dmw_bill、t_dmw_bill_detail和t_dmo_dmw
		$db = $this->db;
		
		// t_dmw_bill
		$tableName = "t_dmw_bill";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_dmw_bill` (
					  `id` varchar(255) NOT NULL,
					  `ref` varchar(255) NOT NULL,
					  `factory_id` varchar(255) NOT NULL,
					  `warehouse_id` varchar(255) NOT NULL,
					  `biz_user_id` varchar(255) NOT NULL,
					  `biz_dt` datetime NOT NULL,
					  `input_user_id` varchar(255) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `bill_status` int(11) NOT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `payment_type` int(11) NOT NULL DEFAULT 0,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  `bill_memo` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		// t_dmw_bill_detail
		$tableName = "t_dmw_bill_detail";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_dmw_bill_detail` (
					  `id` varchar(255) NOT NULL,
					  `dmwbill_id` varchar(255) NOT NULL,
					  `show_order` int(11) NOT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `goods_count` decimal(19,8) NOT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `goods_price` decimal(19,2) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `memo` varchar(1000) DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  `dmobilldetail_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		// t_dmo_dmw
		$tableName = "t_dmo_dmw";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_dmo_dmw` (
					  `dmo_id` varchar(255) NOT NULL,
					  `dmw_id` varchar(255) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20181211_01() {
		// 本次更新：工厂权限细化到按钮
		$db = $this->db;
		
		// t_fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2034', '工厂'),
				('2034-01', '工厂在业务单据中的使用权限'),
				('2034-02', '工厂分类'),
				('2034-03', '工厂-新增工厂分类'),
				('2034-04', '工厂-编辑工厂分类'),
				('2034-05', '工厂-删除工厂分类'),
				('2034-06', '工厂-新增工厂'),
				('2034-07', '工厂-编辑工厂'),
				('2034-08', '工厂-删除工厂'),
				('2035', '成品委托生产订单'),
				('2035-01', '成品委托生产订单-新建成品委托生产订单'),
				('2035-02', '成品委托生产订单-编辑成品委托生产订单'),
				('2035-03', '成品委托生产订单-删除成品委托生产订单'),
				('2035-04', '成品委托生产订单-提交成品委托生产订单'),
				('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单'),
				('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单'),
				('2035-07', '成品委托生产订单-单据生成PDF'),
				('2035-08', '成品委托生产订单-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// t_permission
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
				('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
				('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
				('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
				('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
				('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
				('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
				('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
				('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
				('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
				('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
				('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
				('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
				('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
				('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
				('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
				('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
				('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20181210_02() {
		// 本次更新：新增模块 - 成品委托生产订单
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2034', '工厂'),
				('2035', '成品委托生产订单'),
				('2035-01', '成品委托生产订单-新建成品委托生产订单'),
				('2035-02', '成品委托生产订单-编辑成品委托生产订单'),
				('2035-03', '成品委托生产订单-删除成品委托生产订单'),
				('2035-04', '成品委托生产订单-提交成品委托生产订单'),
				('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单'),
				('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单'),
				('2035-07', '成品委托生产订单-单据生成PDF'),
				('2035-08', '成品委托生产订单-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 主菜单
		$sql = "TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('12', '加工', NULL, NULL, 4),
				('1201', '存货拆分', '2033', '12', 1),
				('1202', '成品委托生产', NULL, '12', 2),
				('120201', '成品委托生产订单', '2035', '1202', 1),
				('04', '销售', NULL, NULL, 5),
				('0401', '销售合同', '2032', '04', 1),
				('0402', '销售订单', '2028', '04', 2),
				('0403', '销售出库', '2002', '04', 3),
				('0404', '销售退货入库', '2006', '04', 4),
				('05', '客户关系', NULL, NULL, 6),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 7),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 8),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 9),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('110103', '会计期间', '2103', '1101', 3),
				('08', '基础数据', NULL, NULL, 10),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('0805', '工厂', '2034', '08', 5),
				('09', '系统管理', NULL, NULL, 11),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '表单视图开发助手', '-7997', '0905', 1),
				('10', '帮助', NULL, NULL, 12),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
				('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
				('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
				('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
				('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
				('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
				('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
				('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
				('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
				('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20181210_01() {
		// 本次更新：新增表t_dmo_bill和t_dmo_bill_detail
		$db = $this->db;
		
		// t_dmo_bill
		$tableName = "t_dmo_bill";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_dmo_bill` (
					  `id` varchar(255) NOT NULL,
					  `ref` varchar(255) NOT NULL,
					  `factory_id` varchar(255) NOT NULL,
					  `contact` varchar(255) NOT NULL,
					  `tel` varchar(255) DEFAULT NULL,
					  `fax` varchar(255) DEFAULT NULL,
					  `org_id` varchar(255) NOT NULL,
					  `biz_user_id` varchar(255) NOT NULL,
					  `biz_dt` datetime NOT NULL,
					  `input_user_id` varchar(255) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `bill_status` int(11) NOT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `tax` decimal(19,2) NOT NULL,
					  `money_with_tax` decimal(19,2) NOT NULL,
					  `payment_type` int(11) NOT NULL DEFAULT 0,
					  `deal_date` datetime NOT NULL,
					  `deal_address` varchar(255) DEFAULT NULL,
					  `confirm_user_id` varchar(255) DEFAULT NULL,
					  `confirm_date` datetime DEFAULT NULL,
					  `bill_memo` varchar(255) DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		// t_dmo_bill_detail
		$tableName = "t_dmo_bill_detail";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_dmo_bill_detail` (
					  `id` varchar(255) NOT NULL,
					  `dmobill_id` varchar(255) NOT NULL,
					  `show_order` int(11) NOT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `goods_count` decimal(19,8) NOT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `goods_price` decimal(19,2) NOT NULL,
					  `tax_rate` decimal(19,2) NOT NULL,
					  `tax` decimal(19,2) NOT NULL,
					  `money_with_tax` decimal(19,2) NOT NULL,
					  `dmw_count` decimal(19,8) NOT NULL,
					  `left_count` decimal(19,8) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  `memo` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20181206_02() {
		// 本次更新：新增模块 工厂
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2034', '工厂'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 主菜单
		$sql = "TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('12', '加工', NULL, NULL, 4),
				('1201', '存货拆分', '2033', '12', 1),
				('04', '销售', NULL, NULL, 5),
				('0401', '销售合同', '2032', '04', 1),
				('0402', '销售订单', '2028', '04', 2),
				('0403', '销售出库', '2002', '04', 3),
				('0404', '销售退货入库', '2006', '04', 4),
				('05', '客户关系', NULL, NULL, 6),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 7),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 8),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 9),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('110103', '会计期间', '2103', '1101', 3),
				('08', '基础数据', NULL, NULL, 10),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('0805', '工厂', '2034', '08', 5),
				('09', '系统管理', NULL, NULL, 11),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '表单视图开发助手', '-7997', '0905', 1),
				('10', '帮助', NULL, NULL, 12),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20181206_01() {
		// 本次更新：存货拆分权限细化到按钮
		$db = $this->db;
		
		// t_fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2033-01', '存货拆分-新建拆分单'),
				('2033-02', '存货拆分-编辑拆分单'),
				('2033-03', '存货拆分-删除拆分单'),
				('2033-04', '存货拆分-提交拆分单'),
				('2033-05', '存货拆分-单据生成PDF'),
				('2033-06', '存货拆分-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// t_permission
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
				('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
				('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
				('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
				('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
				('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20181205_01() {
		// 本次更新：新增表t_factory和t_factory_categroy
		$db = $this->db;
		
		// t_factory
		$tableName = "t_factory";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_factory` (
					  `id` varchar(255) NOT NULL,
					  `category_id` varchar(255) NOT NULL,
					  `code` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `contact01` varchar(255) DEFAULT NULL,
					  `tel01` varchar(255) DEFAULT NULL,
					  `mobile01` varchar(255) DEFAULT NULL,
					  `contact02` varchar(255) DEFAULT NULL,
					  `tel02` varchar(255) DEFAULT NULL,
					  `mobile02` varchar(255) DEFAULT NULL,
					  `address` varchar(255) DEFAULT NULL,
					  `py` varchar(255) DEFAULT NULL,
					  `init_receivables` decimal(19,2) DEFAULT NULL, 
					  `init_receivables_dt` datetime DEFAULT NULL, 
					  `init_payables` decimal(19,2) DEFAULT NULL, 
					  `init_payables_dt` datetime DEFAULT NULL, 
					  `bank_name` varchar(255) DEFAULT NULL,
					  `bank_account` varchar(255) DEFAULT NULL,
					  `tax_number` varchar(255) DEFAULT NULL,
					  `fax` varchar(255) DEFAULT NULL,
					  `note` varchar(255) DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  `record_status` int(11) DEFAULT 1000,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		// t_factory_category
		$tableName = "t_factory_category";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_factory_category` (
					  `id` varchar(255) NOT NULL,
					  `code` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `parent_id` varchar(255) DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  `full_name` varchar(1000) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20181202_01() {
		// 本次更新：t_pw_bill新增字段wspbill_id
		$db = $this->db;
		
		$tableName = "t_pw_bill";
		$columnName = "wspbill_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20181129_01() {
		// 本次更新：t_goods_bom和t_wsp_bill_detail_bom新增字段cost_weight
		$db = $this->db;
		
		$tableName = "t_goods_bom";
		$columnName = "cost_weight";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT 1;";
			$db->execute($sql);
		}
		
		$tableName = "t_wsp_bill_detail_bom";
		$columnName = "cost_weight";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT 1;";
			$db->execute($sql);
		}
	}

	private function update_20181120_01() {
		// 本次更新：t_wsp_bill新增字段bill_memo, t_wsp_bill_detail新增字段memo
		$db = $this->db;
		
		$tableName = "t_wsp_bill";
		$columnName = "bill_memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(1000) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		$tableName = "t_wsp_bill_detail";
		$columnName = "memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(1000) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20181118_01() {
		// 本次更新：t_goods_brand新增字段py, t_goods_unit新增字段code
		$db = $this->db;
		
		$tableName = "t_goods_brand";
		$columnName = "py";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
		// 把t_goods_barnd的py字段初始化
		$ps = new PinyinService();
		$sql = "select id, name from t_goods_brand where py is null or py = '' ";
		$data = $db->query($sql);
		foreach ( $data as $v ) {
			$id = $v["id"];
			$name = $v["name"];
			$py = $ps->toPY($name);
			
			$sql = "update t_goods_brand
					set py = '%s'
					where id = '%s' ";
			$db->execute($sql, $py, $id);
		}
		
		$tableName = "t_goods_unit";
		$columnName = "code";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20181114_02() {
		// 本次更新：t_supplier新增字段record_status
		$db = $this->db;
		
		$tableName = "t_supplier";
		$columnName = "record_status";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT 1000;";
			$db->execute($sql);
		}
	}

	private function update_20181114_01() {
		// 本次更新：t_customer新增字段record_status
		$db = $this->db;
		
		$tableName = "t_customer";
		$columnName = "record_status";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT 1000;";
			$db->execute($sql);
		}
	}

	private function update_20181113_02() {
		// 本次更新：t_goods新增字段record_status
		$db = $this->db;
		
		$tableName = "t_goods";
		$columnName = "record_status";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT 1000;";
			$db->execute($sql);
		}
	}

	private function update_20181113_01() {
		// 本次更新：新增模块-存货拆分
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2033', '存货拆分'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 主菜单
		$sql = "TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('12', '加工', NULL, NULL, 4),
				('1201', '存货拆分', '2033', '12', 1),
				('04', '销售', NULL, NULL, 5),
				('0401', '销售合同', '2032', '04', 1),
				('0402', '销售订单', '2028', '04', 2),
				('0403', '销售出库', '2002', '04', 3),
				('0404', '销售退货入库', '2006', '04', 4),
				('05', '客户关系', NULL, NULL, 6),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 7),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 8),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 9),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('110103', '会计期间', '2103', '1101', 3),
				('08', '基础数据', NULL, NULL, 10),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('09', '系统管理', NULL, NULL, 11),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '表单视图开发助手', '-7997', '0905', 1),
				('10', '帮助', NULL, NULL, 12),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20181112_01() {
		// 本次更新：新增表 t_wsp_bill、t_wsp_bill_detail、t_wsp_bill_detail_ex、t_wsp_bill_detail_bom
		$db = $this->db;
		
		$tableName = "t_wsp_bill";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_wsp_bill` (
					  `id` varchar(255) NOT NULL,
					  `ref` varchar(255) NOT NULL,
					  `from_warehouse_id` varchar(255) NOT NULL,
					  `to_warehouse_id` varchar(255) NOT NULL,
					  `bill_status` int(11) NOT NULL,
					  `bizdt` datetime NOT NULL,
					  `biz_user_id` varchar(255) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `input_user_id` varchar(255) NOT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		$tableName = "t_wsp_bill_detail";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_wsp_bill_detail` (
					  `id` varchar(255) NOT NULL,
					  `wspbill_id` varchar(255) NOT NULL,
					  `show_order` int(11) NOT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `goods_count` decimal(19,8) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		$tableName = "t_wsp_bill_detail_ex";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_wsp_bill_detail_ex` (
					  `id` varchar(255) NOT NULL,
					  `wspbill_id` varchar(255) NOT NULL,
					  `show_order` int(11) NOT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `goods_count` decimal(19,8) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  `from_goods_id` varchar(255) NOT NULL,
					  `wspbilldetail_id` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		$tableName = "t_wsp_bill_detail_bom";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_wsp_bill_detail_bom` (
					  `id` varchar(255) NOT NULL,
					  `wspbilldetail_id` varchar(255) NOT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `sub_goods_id` varchar(255) NOT NULL,
					  `parent_id` varchar(255) DEFAULT NULL,
					  `sub_goods_count` decimal(19,8) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20181110_01() {
		// 本次更新：t_so_bill_detail新增字段scbilldetail_id
		$db = $this->db;
		
		$tableName = "t_so_bill_detail";
		$columnName = "scbilldetail_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20181107_01() {
		// 本次更新：销售合同权限细化到按钮
		$db = $this->db;
		
		// t_fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2032-01', '销售合同-新建销售合同'),
				('2032-02', '销售合同-编辑销售合同'),
				('2032-03', '销售合同-删除销售合同'),
				('2032-04', '销售合同-审核/取消审核'),
				('2032-05', '销售合同-生成销售订单'),
				('2032-06', '销售合同-单据生成PDF'),
				('2032-07', '销售合同-打印'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// t_permission
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
				('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
				('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
				('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
				('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
				('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
				('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20181104_01() {
		// 本次更新：新增表 t_sc_so
		$db = $this->db;
		
		$tableName = "t_sc_so";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_sc_so` (
					  `sc_id` varchar(255) NOT NULL,
					  `so_id` varchar(255) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20181026_02() {
		// 本次更新新增模块：销售合同
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2032', '销售合同'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 菜单
		$sql = "TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('04', '销售', NULL, NULL, 4),
				('0401', '销售合同', '2032', '04', 1),
				('0402', '销售订单', '2028', '04', 2),
				('0403', '销售出库', '2002', '04', 3),
				('0404', '销售退货入库', '2006', '04', 4),
				('05', '客户关系', NULL, NULL, 5),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 6),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 7),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 8),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('110103', '会计期间', '2103', '1101', 3),
				('08', '基础数据', NULL, NULL, 9),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('09', '系统管理', NULL, NULL, 10),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '表单视图开发助手', '-7997', '0905', 1),
				('10', '帮助', NULL, NULL, 11),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20181026_01() {
		// 本次更新：新增表t_sc_bill t_sc_bill_detail
		$db = $this->db;
		
		$tableName = "t_sc_bill";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_sc_bill` (
					  `id` varchar(255) NOT NULL,
					  `ref` varchar(255) NOT NULL,
					  `customer_id` varchar(255) NOT NULL,
					  `org_id` varchar(255) NOT NULL,
					  `biz_user_id` varchar(255) NOT NULL,
					  `biz_dt` datetime NOT NULL,
					  `input_user_id` varchar(255) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `bill_status` int(11) NOT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `tax` decimal(19,2) NOT NULL,
					  `money_with_tax` decimal(19,2) NOT NULL,
					  `deal_date` datetime NOT NULL,
					  `deal_address` varchar(255) DEFAULT NULL,
					  `confirm_user_id` varchar(255) DEFAULT NULL,
					  `confirm_date` datetime DEFAULT NULL,
					  `bill_memo` varchar(255) DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  `begin_dt` date NOT NULL,
					  `end_dt` date NOT NULL,
					  `discount` int(11) NOT NULL,
					  `quality_clause` varchar(500) DEFAULT NULL,
					  `insurance_clause` varchar(500) DEFAULT NULL,
					  `transport_clause` varchar(500) DEFAULT NULL,
					  `other_clause` varchar(500) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		// t_sc_bill_detail
		$tableName = "t_sc_bill_detail";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_sc_bill_detail` (
					  `id` varchar(255) NOT NULL,
					  `scbill_id` varchar(255) NOT NULL,
					  `show_order` int(11) NOT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `goods_count` decimal(19,8) NOT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `goods_price` decimal(19,2) NOT NULL,
					  `tax_rate` decimal(19,2) NOT NULL,
					  `tax` decimal(19,2) NOT NULL,
					  `money_with_tax` decimal(19,2) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  `memo` varchar(500) DEFAULT NULL,
					  `discount` int(11) NOT NULL,
					  `so_count` decimal(19,8) NOT NULL,
					  `left_count` decimal(19,8) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20181024_01() {
		// 本次更新：新增模块-会计期间
		$db = $this->db;
		
		// fid
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2101', '会计科目'),
				('2102', '银行账户'),
				('2103', '会计期间');
		";
		$db->execute($sql);
		
		// 菜单
		$sql = "TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('04', '销售', NULL, NULL, 4),
				('0400', '销售订单', '2028', '04', 0),
				('0401', '销售出库', '2002', '04', 1),
				('0402', '销售退货入库', '2006', '04', 2),
				('05', '客户关系', NULL, NULL, 5),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 6),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 7),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 8),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('110103', '会计期间', '2103', '1101', 3),
				('08', '基础数据', NULL, NULL, 9),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('09', '系统管理', NULL, NULL, 10),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '表单视图开发助手', '-7997', '0905', 1),
				('10', '帮助', NULL, NULL, 11),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 权限
		$sql = "TRUNCATE TABLE `t_permission`;
				INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
				('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
		";
		$db->execute($sql);
	}

	private function update_20181023_01() {
		// 本次更新：新增表t_acc_period
		$db = $this->db;
		
		$tableName = "t_acc_period";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_acc_period` (
					  `id` varchar(255) NOT NULL,
					  `acc_year` int(11) NOT NULL,
					  `acc_month` int(11) NOT NULL,
					  `company_id` varchar(255) NOT NULL,
					  `acc_gl_kept` int(11) NOT NULL,
					  `acc_gl_closed` int(11) NOT NULL,
					  `acc_detail_kept` int(11) NOT NULL,
					  `acc_detail_closed` int(11) NOT NULL,
					  `period_closed` int(11) NOT NULL,
					  `year_forward` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20181021_01() {
		// 本次更新：t_acc_fmt_cols表新增字段sys_col
		$db = $this->db;
		
		$tableName = "t_acc_fmt_cols";
		$columnName = "sys_col";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20181009_01() {
		// 本次更新：t_org新增字段org_type
		$db = $this->db;
		
		$tableName = "t_org";
		$columnName = "org_type";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20181006_01() {
		// 本次更新：t_fv_md新增字段show_order
		$db = $this->db;
		
		$tableName = "t_fv_md";
		$columnName = "show_order";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 0;";
			$db->execute($sql);
		}
	}

	private function update_20181005_02() {
		// 本次更新：新增模块 - 表单视图开发助手
		$db = $this->db;
		$sql = "TRUNCATE TABLE `t_fid`;
				INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-7997', '表单视图开发助手'),
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2101', '会计科目'),
				('2102', '银行账户');
				
				TRUNCATE TABLE `t_menu_item`;
				INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('04', '销售', NULL, NULL, 4),
				('0400', '销售订单', '2028', '04', 0),
				('0401', '销售出库', '2002', '04', 1),
				('0402', '销售退货入库', '2006', '04', 2),
				('05', '客户关系', NULL, NULL, 5),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 6),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 7),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 8),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('08', '基础数据', NULL, NULL, 9),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('09', '系统管理', NULL, NULL, 10),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('0905', '二次开发', NULL, '09', 5),
				('090501', '表单视图开发助手', '-7997', '0905', 1),
				('10', '帮助', NULL, NULL, 11),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// 开发助手只能admin访问，所以不需要设置权限
	}

	private function update_20181005_01() {
		// 本次更新：新增表t_fv_md
		$db = $this->db;
		
		$tableName = "t_fv_md";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_fv_md` (
					  `id` varchar(255) NOT NULL,
					  `parent_id` varchar(255) DEFAULT NULL,
					  `prop_name` varchar(255) NOT NULL,
					  `prop_value` varchar(1000) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20180921_01() {
		// 本次更新：新增模块银行账户和它对应的权限
		$db = $this->db;
		
		// t_fid
		$sql = "TRUNCATE TABLE `t_fid`;";
		$db->execute($sql);
		$sql = "INSERT INTO `t_fid` (`fid`, `name`) VALUES
				('-9999', '重新登录'),
				('-9997', '首页'),
				('-9996', '修改我的密码'),
				('-9995', '帮助'),
				('-9994', '关于'),
				('-9993', '购买商业服务'),
				('-8999', '用户管理'),
				('-8999-01', '组织机构在业务单据中的使用权限'),
				('-8999-02', '业务员在业务单据中的使用权限'),
				('-8997', '业务日志'),
				('-8996', '权限管理'),
				('1001', '商品'),
				('1001-01', '商品在业务单据中的使用权限'),
				('1001-02', '商品分类'),
				('1002', '商品计量单位'),
				('1003', '仓库'),
				('1003-01', '仓库在业务单据中的使用权限'),
				('1004', '供应商档案'),
				('1004-01', '供应商档案在业务单据中的使用权限'),
				('1004-02', '供应商分类'),
				('1007', '客户资料'),
				('1007-01', '客户资料在业务单据中的使用权限'),
				('1007-02', '客户分类'),
				('2000', '库存建账'),
				('2001', '采购入库'),
				('2001-01', '采购入库-新建采购入库单'),
				('2001-02', '采购入库-编辑采购入库单'),
				('2001-03', '采购入库-删除采购入库单'),
				('2001-04', '采购入库-提交入库'),
				('2001-05', '采购入库-单据生成PDF'),
				('2001-06', '采购入库-采购单价和金额可见'),
				('2001-07', '采购入库-打印'),
				('2002', '销售出库'),
				('2002-01', '销售出库-销售出库单允许编辑销售单价'),
				('2002-02', '销售出库-新建销售出库单'),
				('2002-03', '销售出库-编辑销售出库单'),
				('2002-04', '销售出库-删除销售出库单'),
				('2002-05', '销售出库-提交出库'),
				('2002-06', '销售出库-单据生成PDF'),
				('2002-07', '销售出库-打印'),
				('2003', '库存账查询'),
				('2004', '应收账款管理'),
				('2005', '应付账款管理'),
				('2006', '销售退货入库'),
				('2006-01', '销售退货入库-新建销售退货入库单'),
				('2006-02', '销售退货入库-编辑销售退货入库单'),
				('2006-03', '销售退货入库-删除销售退货入库单'),
				('2006-04', '销售退货入库-提交入库'),
				('2006-05', '销售退货入库-单据生成PDF'),
				('2006-06', '销售退货入库-打印'),
				('2007', '采购退货出库'),
				('2007-01', '采购退货出库-新建采购退货出库单'),
				('2007-02', '采购退货出库-编辑采购退货出库单'),
				('2007-03', '采购退货出库-删除采购退货出库单'),
				('2007-04', '采购退货出库-提交采购退货出库单'),
				('2007-05', '采购退货出库-单据生成PDF'),
				('2007-06', '采购退货出库-打印'),
				('2008', '业务设置'),
				('2009', '库间调拨'),
				('2009-01', '库间调拨-新建调拨单'),
				('2009-02', '库间调拨-编辑调拨单'),
				('2009-03', '库间调拨-删除调拨单'),
				('2009-04', '库间调拨-提交调拨单'),
				('2009-05', '库间调拨-单据生成PDF'),
				('2009-06', '库间调拨-打印'),
				('2010', '库存盘点'),
				('2010-01', '库存盘点-新建盘点单'),
				('2010-02', '库存盘点-盘点数据录入'),
				('2010-03', '库存盘点-删除盘点单'),
				('2010-04', '库存盘点-提交盘点单'),
				('2010-05', '库存盘点-单据生成PDF'),
				('2010-06', '库存盘点-打印'),
				('2011-01', '首页-销售看板'),
				('2011-02', '首页-库存看板'),
				('2011-03', '首页-采购看板'),
				('2011-04', '首页-资金看板'),
				('2012', '报表-销售日报表(按商品汇总)'),
				('2013', '报表-销售日报表(按客户汇总)'),
				('2014', '报表-销售日报表(按仓库汇总)'),
				('2015', '报表-销售日报表(按业务员汇总)'),
				('2016', '报表-销售月报表(按商品汇总)'),
				('2017', '报表-销售月报表(按客户汇总)'),
				('2018', '报表-销售月报表(按仓库汇总)'),
				('2019', '报表-销售月报表(按业务员汇总)'),
				('2020', '报表-安全库存明细表'),
				('2021', '报表-应收账款账龄分析表'),
				('2022', '报表-应付账款账龄分析表'),
				('2023', '报表-库存超上限明细表'),
				('2024', '现金收支查询'),
				('2025', '预收款管理'),
				('2026', '预付款管理'),
				('2027', '采购订单'),
				('2027-01', '采购订单-审核/取消审核'),
				('2027-02', '采购订单-生成采购入库单'),
				('2027-03', '采购订单-新建采购订单'),
				('2027-04', '采购订单-编辑采购订单'),
				('2027-05', '采购订单-删除采购订单'),
				('2027-06', '采购订单-关闭订单/取消关闭订单'),
				('2027-07', '采购订单-单据生成PDF'),
				('2027-08', '采购订单-打印'),
				('2028', '销售订单'),
				('2028-01', '销售订单-审核/取消审核'),
				('2028-02', '销售订单-生成销售出库单'),
				('2028-03', '销售订单-新建销售订单'),
				('2028-04', '销售订单-编辑销售订单'),
				('2028-05', '销售订单-删除销售订单'),
				('2028-06', '销售订单-单据生成PDF'),
				('2028-07', '销售订单-打印'),
				('2029', '商品品牌'),
				('2030-01', '商品构成-新增子商品'),
				('2030-02', '商品构成-编辑子商品'),
				('2030-03', '商品构成-删除子商品'),
				('2031', '价格体系'),
				('2031-01', '商品-设置商品价格体系'),
				('2101', '会计科目'),
				('2102', '银行账户');
		";
		$db->execute($sql);
		
		// t_menu_item
		$sql = "TRUNCATE TABLE `t_menu_item`;";
		$db->execute($sql);
		$sql = "INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('04', '销售', NULL, NULL, 4),
				('0400', '销售订单', '2028', '04', 0),
				('0401', '销售出库', '2002', '04', 1),
				('0402', '销售退货入库', '2006', '04', 2),
				('05', '客户关系', NULL, NULL, 5),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 6),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 7),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 8),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('110102', '银行账户', '2102', '1101', 2),
				('08', '基础数据', NULL, NULL, 9),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('09', '系统管理', NULL, NULL, 10),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('10', '帮助', NULL, NULL, 11),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
		
		// t_permission
		$sql = "TRUNCATE TABLE `t_permission`;";
		$db->execute($sql);
		$sql = "INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
				('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
				('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
				('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
				('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
				('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
				('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
				('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
				('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
				('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
				('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
				('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
				('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
				('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
				('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
				('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
				('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
				('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
				('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
				('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
				('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
				('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
				('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
				('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
				('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
				('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
				('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
				('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
				('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
				('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
				('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
				('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
				('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
				('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
				('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
				('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
				('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
				('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
				('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
				('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
				('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
				('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
				('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
				('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
				('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
				('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
				('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
				('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
				('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
				('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
				('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
				('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
				('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
				('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
				('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
				('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
				('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
				('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
				('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
				('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
				('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
				('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
				('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
				('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
				('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
				('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
				('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
				('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
				('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
				('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
				('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
				('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
				('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
				('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
				('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
				('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
				('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
				('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
				('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
				('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
				('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
				('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
				('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
				('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
				('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
				('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
				('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
				('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
				('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
				('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
				('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
				('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
				('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
				('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
				('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
				('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
				('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
				('2010-02', '2010-02', '库存盘点-盘点数据录入', '按钮权限：库存盘点模块[盘点数据录入]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
				('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
				('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
				('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
				('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
				('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
				('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
				('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
				('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
				('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
				('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
				('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
				('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
				('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
				('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
				('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
				('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
				('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
				('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
				('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
				('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
				('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
				('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
				('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
				('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
				('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
				('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
				('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
				('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
				('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
				('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
				('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
				('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
				('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
				('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
				('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 205),
				('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
				('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
				('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
				('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 206),
				('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 207),
				('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
				('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
				('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
				('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
				('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
				('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
				('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
				('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100);
		";
		$db->execute($sql);
	}

	private function update_20180920_01() {
		// 本次更新：新增表t_acc_fmt、t_acc_fmt_cols
		$db = $this->db;
		
		$tableName = "t_acc_fmt";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_acc_fmt` (
					  `id` varchar(255) NOT NULL,
					  `acc_number` varchar(255) NOT NULL,
					  `subject_code` varchar(255) NOT NULL,
					  `memo` varchar(255) DEFAULT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) NOT NULL,
					  `in_use` int(11) DEFAULT 1,
					  `db_table_name_prefix` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
		
		$tableName = "t_acc_fmt_cols";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_acc_fmt_cols` (
					  `id` varchar(255) NOT NULL,
					  `fmt_id` varchar(255) NOT NULL,
					  `db_field_name` varchar(255) NOT NULL,
					  `db_field_type` varchar(255) DEFAULT NULL,
					  `db_field_length` int(11) NOT NULL,
					  `db_field_decimal` int(11) NOT NULL,
					  `show_order` int(11) NOT NULL,
					  `caption` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20180825_01() {
		// 本次更新：会计科目fid、权限和主菜单
		
		// 会计科目fid和权限
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "会计科目";
		
		$fid = FIdConst::GL_SUBJECT;
		$name = "会计科目";
		$note = "模块权限：通过菜单进入会计科目模块的权限";
		$showOrder = 100;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 会计科目主菜单
		$sql = "delete from t_menu_item";
		$db->execute($sql);
		
		$sql = "INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '文件', NULL, NULL, 1),
				('0101', '首页', '-9997', '01', 1),
				('0102', '重新登录', '-9999', '01', 2),
				('0103', '修改我的密码', '-9996', '01', 3),
				('02', '采购', NULL, NULL, 2),
				('0200', '采购订单', '2027', '02', 0),
				('0201', '采购入库', '2001', '02', 1),
				('0202', '采购退货出库', '2007', '02', 2),
				('03', '库存', NULL, NULL, 3),
				('0301', '库存账查询', '2003', '03', 1),
				('0302', '库存建账', '2000', '03', 2),
				('0303', '库间调拨', '2009', '03', 3),
				('0304', '库存盘点', '2010', '03', 4),
				('04', '销售', NULL, NULL, 4),
				('0400', '销售订单', '2028', '04', 0),
				('0401', '销售出库', '2002', '04', 1),
				('0402', '销售退货入库', '2006', '04', 2),
				('05', '客户关系', NULL, NULL, 5),
				('0501', '客户资料', '1007', '05', 1),
				('06', '资金', NULL, NULL, 6),
				('0601', '应收账款管理', '2004', '06', 1),
				('0602', '应付账款管理', '2005', '06', 2),
				('0603', '现金收支查询', '2024', '06', 3),
				('0604', '预收款管理', '2025', '06', 4),
				('0605', '预付款管理', '2026', '06', 5),
				('07', '报表', NULL, NULL, 7),
				('0701', '销售日报表', NULL, '07', 1),
				('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
				('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
				('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
				('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
				('0702', '销售月报表', NULL, '07', 2),
				('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
				('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
				('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
				('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
				('0703', '库存报表', NULL, '07', 3),
				('070301', '安全库存明细表', '2020', '0703', 1),
				('070302', '库存超上限明细表', '2023', '0703', 2),
				('0706', '资金报表', NULL, '07', 6),
				('070601', '应收账款账龄分析表', '2021', '0706', 1),
				('070602', '应付账款账龄分析表', '2022', '0706', 2),
				('11', '财务总账', NULL, NULL, 8),
				('1101', '基础数据', NULL, '11', 1),
				('110101', '会计科目', '2101', '1101', 1),
				('08', '基础数据', NULL, NULL, 9),
				('0801', '商品', NULL, '08', 1),
				('080101', '商品', '1001', '0801', 1),
				('080102', '商品计量单位', '1002', '0801', 2),
				('080103', '商品品牌', '2029', '0801', 3),
				('080104', '价格体系', '2031', '0801', 4),
				('0803', '仓库', '1003', '08', 3),
				('0804', '供应商档案', '1004', '08', 4),
				('09', '系统管理', NULL, NULL, 10),
				('0901', '用户管理', '-8999', '09', 1),
				('0902', '权限管理', '-8996', '09', 2),
				('0903', '业务日志', '-8997', '09', 3),
				('0904', '业务设置', '2008', '09', 4),
				('10', '帮助', NULL, NULL, 11),
				('1001', '使用帮助', '-9995', '10', 1),
				('1003', '关于', '-9994', '10', 3);
		";
		$db->execute($sql);
	}

	private function update_20180623_01() {
		// 本次更新：t_supplier_category新增字段full_name
		$db = $this->db;
		
		$tableName = "t_supplier_category";
		$columnName = "full_name";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(1000) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20180621_01() {
		// 本次更新：t_org - full_name字段长度改为1000
		$db = $this->db;
		
		$dbName = C('DB_NAME');
		$tableName = "t_org";
		$fieldName = "full_name";
		
		$sql = "select c.CHARACTER_MAXIMUM_LENGTH as max_length
				from information_schema.`COLUMNS` c
				where c.TABLE_SCHEMA = '%s' and c.TABLE_NAME = '%s' and c.COLUMN_NAME = '%s'";
		$data = $db->query($sql, $dbName, $tableName, $fieldName);
		if (! $data) {
			return false;
		}
		
		$maxLength = $data[0]["max_length"];
		if ($maxLength == 1000) {
			return;
		}
		
		$sql = "alter table " . $tableName . " modify column " . $fieldName . " varchar(1000) ";
		$db->execute($sql);
	}

	private function update_20180526_01() {
		// 本次更新：H5菜单-删除关于和退出
		$db = $this->db;
		$sql = "delete from t_menu_item_h5 
				where id in ('99', '9901', '9902')";
		$db->execute($sql);
	}

	private function update_20180522_01() {
		// 本次更新：H5端菜单 - 增加客户资料
		$db = $this->db;
		
		// 客户关系 - 一级菜单
		$sql = "select count(*) as cnt from t_menu_item_h5
				where id = '02' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item_h5(id, caption, fid, parent_id, show_order)
					values ('02', '客户关系', null, null, 2)";
			$db->execute($sql);
		}
		
		// 客户资料 - 二级菜单
		$fid = FIdConst::CUSTOMER;
		$name = "客户资料";
		
		$sql = "select count(*) as cnt from t_menu_item_h5
				where id = '0201' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item_h5(id, caption, fid, parent_id, show_order)
					values ('0201', '%s', '%s', '02', 1)";
			$db->execute($sql, $name, $fid);
		}
	}

	private function update_20180518_01() {
		// 本次更新：H5端菜单初始化
		$db = $this->db;
		
		// 销售 - 一级菜单
		$sql = "select count(*) as cnt from t_menu_item_h5
				where id = '01' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item_h5(id, caption, fid, parent_id, show_order)
					values ('01', '销售', null, null, 1)";
			$db->execute($sql);
		}
		
		// 销售订单 - 销售的二级菜单
		$fid = FIdConst::SALE_ORDER;
		$name = "销售订单";
		
		$sql = "select count(*) as cnt from t_menu_item_h5
				where id = '0101' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item_h5(id, caption, fid, parent_id, show_order)
					values ('0101', '%s', '%s', '01', 1)";
			$db->execute($sql, $name, $fid);
		}
		
		// 关于 - 一级菜单
		$sql = "select count(*) as cnt from t_menu_item_h5
				where id = '99' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item_h5(id, caption, fid, parent_id, show_order)
					values ('99', '关于', null, null, 99)";
			$db->execute($sql);
		}
		
		// 关于PSI - 二级菜单
		$fid = FIdConst::ABOUT;
		$name = "关于PSI";
		
		$sql = "select count(*) as cnt from t_menu_item_h5
				where id = '9901' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item_h5(id, caption, fid, parent_id, show_order)
					values ('9901', '%s', '%s', '99', 1)";
			$db->execute($sql, $name, $fid);
		}
		
		// 安全退出- 二级菜单
		$fid = FIdConst::RELOGIN;
		$name = "安全退出";
		
		$sql = "select count(*) as cnt from t_menu_item_h5
				where id = '9902' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item_h5(id, caption, fid, parent_id, show_order)
					values ('9902', '%s', '%s', '99', 2)";
			$db->execute($sql, $name, $fid);
		}
	}

	private function update_20180517_01() {
		// 本次更新：新增表 t_menu_item_h5
		$db = $this->db;
		
		$tableName = "t_menu_item_h5";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_menu_item_h5` (
					  `id` varchar(255) NOT NULL,
					  `caption` varchar(255) NOT NULL,
					  `fid` varchar(255) DEFAULT NULL,
					  `parent_id` varchar(255) DEFAULT NULL,
					  `show_order` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20180513_01() {
		// 本次更新：t_inventory_fifo、t_inventory_fifo_detail中的商品数量改为decimal(19, 8)
		$tableName = "t_inventory_fifo";
		
		$fieldName = "balance_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "in_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "out_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		
		$tableName = "t_inventory_fifo_detail";
		
		$fieldName = "balance_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "in_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "out_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180503_03() {
		// 本次更新：新增权限：商品-设置价格体系
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "商品";
		
		$fid = FIdConst::PRICE_SYSTEM_SETTING_GOODS;
		$name = "商品-设置商品价格体系";
		$note = "按钮权限：商品模块[设置商品价格体系]按钮权限";
		$showOrder = 701;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180503_02() {
		// 本次更新：新增权限 销售退货入库-打印
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "销售退货入库";
		
		$fid = FIdConst::SALE_REJECTION_PRINT;
		$name = "销售退货入库-打印";
		$note = "按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限";
		$showOrder = 206;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180503_01() {
		// 本次更新：新增权限 销售出库-打印
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "销售出库";
		
		$fid = FIdConst::WAREHOUSING_SALE_PRINT;
		$name = "销售出库-打印";
		$note = "按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限";
		$showOrder = 207;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180502_04() {
		// 本次更新：新增权限 销售订单-打印
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "销售订单";
		
		$fid = FIdConst::SALE_ORDER_PRINT;
		$name = "销售订单-打印";
		$note = "按钮权限：销售订单模块[打印预览]和[直接打印]按钮权限";
		$showOrder = 207;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180502_03() {
		// 本次更新：新增权限 库存盘点-打印
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "库存盘点";
		
		$fid = FIdConst::INVENTORY_CHECK_PRINT;
		$name = "库存盘点-打印";
		$note = "按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限";
		$showOrder = 206;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180502_02() {
		// 本次更新：新增权限 - 库间调拨-打印
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "库间调拨";
		
		$fid = FIdConst::INVENTORY_TRANSFER_PRINT;
		$name = "库间调拨-打印";
		$note = "按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限";
		$showOrder = 206;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180502_01() {
		// 本次更新：新增权限 - 采购退货出库打印
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "采购退货出库";
		
		$fid = FIdConst::PURCHASE_REJECTION_PRINT;
		$name = "采购退货出库-打印";
		$note = "按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限";
		$showOrder = 206;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180501_02() {
		// 本次更新：新增权限 - 采购入库单打印
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "采购入库";
		
		$fid = FIdConst::PURCHASE_WAREHOUSE_PRINT;
		$name = "采购入库-打印";
		$note = "按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限";
		$showOrder = 207;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180501_01() {
		// 本次更新：新增权限 - 采购订单打印
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "采购订单";
		
		$fid = FIdConst::PURCHASE_ORDER_PRINT;
		$name = "采购订单-打印";
		$note = "按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限";
		$showOrder = 208;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180410_01() {
		// 本次更新：新增权限 - 采购入库金额和单价可见
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "采购入库";
		
		$fid = FIdConst::PURCHASE_WAREHOUSE_CAN_VIEW_PRICE;
		$name = "采购入库 - 采购单价和金额可见";
		$note = "字段权限：采购入库单的采购单价和金额可以被用户查看";
		$showOrder = 206;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180406_01() {
		// 本次更新：新增表t_bank_account
		$db = $this->db;
		$tableName = "t_bank_account";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_bank_account` (
					  `id` varchar(255) NOT NULL,
					  `bank_name` varchar(255) NOT NULL,
					  `bank_number` varchar(255) NOT NULL,
					  `memo` varchar(255) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `data_org` varchar(255) NOT NULL,
					  `company_id` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20180316_01() {
		// 本次更新： t_goods_bom商品数量改为decimal(19,8)
		$tableName = "t_goods_bom";
		
		$fieldName = "sub_goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180314_02() {
		// 本次更新：t_sr_bill_detail商品数量改为decimal(19,8)
		$tableName = "t_sr_bill_detail";
		
		$fieldName = "goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "rejection_goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180314_01() {
		// 本次更新：t_ws_bill_detail商品数量改为decimal(19,8)
		$tableName = "t_ws_bill_detail";
		
		$fieldName = "goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180313_02() {
		// 本次更新：t_ic_bill_detail商品数量改为decimal(19,8)
		$tableName = "t_ic_bill_detail";
		
		$fieldName = "goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180313_01() {
		// 本次更新：t_it_bill_detail商品数量改为decimal(19,8)
		$tableName = "t_it_bill_detail";
		
		$fieldName = "goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180307_01() {
		// 本次更新：t_pr_bill_detail商品数量字段改为decimal(19,8)
		$tableName = "t_pr_bill_detail";
		
		$fieldName = "goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "rejection_goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180306_02() {
		// 本次更新：t_pw_bill_detail商品数量字段改为decimal(19,8)
		$tableName = "t_pw_bill_detail";
		
		$fieldName = "goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180306_01() {
		// 本次更新：t_inventory、t_inventory_detail中商品数量字段改为decimal(19, 8)
		$tableName = "t_inventory";
		
		$fieldName = "balance_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "in_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "out_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "afloat_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		
		$tableName = "t_inventory_detail";
		
		$fieldName = "balance_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "in_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		$fieldName = "out_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180305_01() {
		// 本次更新：修改t_po_bill_detail的字段goods_count、pw_count、left_count类型为decimal(19, 8)
		$tableName = "t_po_bill_detail";
		
		$fieldName = "goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		
		$fieldName = "pw_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		
		$fieldName = "left_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	private function update_20180219_01() {
		// 本次更新：修改t_so_bill_detail的字段goods_count、ws_count、left_count类型为decimal(19, 8)
		$tableName = "t_so_bill_detail";
		
		$fieldName = "goods_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		
		$fieldName = "ws_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
		
		$fieldName = "left_count";
		$this->changeFieldTypeToDeciaml($tableName, $fieldName);
	}

	/**
	 * 判断表的字段是否需要修改成decimal(19,8)
	 *
	 * @param string $tableName        	
	 * @param string $fieldName        	
	 * @return bool
	 */
	private function fieldNeedChangeToDec(string $tableName, string $fieldName): bool {
		$db = $this->db;
		
		$dbName = C('DB_NAME');
		
		$sql = "select DATA_TYPE as dtype, NUMERIC_PRECISION as dpre, NUMERIC_SCALE as dscale  
				from information_schema.`COLUMNS` c 
				where c.TABLE_SCHEMA = '%s' and c.TABLE_NAME = '%s' and c.COLUMN_NAME = '%s'";
		$data = $db->query($sql, $dbName, $tableName, $fieldName);
		if (! $data) {
			return false;
		}
		
		$dataType = strtolower($data[0]["dtype"]);
		$dataPrecision = $data[0]["dpre"];
		$dataScale = $data[0]["dscale"];
		
		if ($dataType == "int") {
			return true;
		}
		
		if ($dataType == "decimal") {
			if ($dataScale < 8) {
				return true;
			}
		}
		
		// int和decimal之外的均不能修改
		return false;
	}

	/**
	 * 把表字段类型修改成decimal(19, 8)
	 *
	 * @param string $talbeName        	
	 * @param string $fieldName        	
	 */
	private function changeFieldTypeToDeciaml(string $tableName, string $fieldName) {
		if (! $this->fieldNeedChangeToDec($tableName, $fieldName)) {
			return;
		}
		
		$db = $this->db;
		
		$sql = "alter table " . $tableName . " modify column " . $fieldName . " decimal(19, 8)";
		$db->execute($sql);
	}

	private function update_20180203_03() {
		// 本次更新：库存盘点权限细化到按钮
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "库存盘点";
		
		// 新建盘点单
		$fid = FIdConst::INVENTORY_CHECK_ADD;
		$name = "库存盘点-新建盘点单";
		$note = "按钮权限：库存盘点模块[新建盘点单]按钮权限";
		$showOrder = 201;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 盘点数据录入
		$fid = FIdConst::INVENTORY_CHECK_EDIT;
		$name = "库存盘点-盘点数据录入";
		$note = "按钮权限：库存盘点模块[盘点数据录入]按钮权限";
		$showOrder = 202;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 删除盘点单
		$fid = FIdConst::INVENTORY_CHECK_DELETE;
		$name = "库存盘点-删除盘点单";
		$note = "按钮权限：库存盘点模块[删除盘点单]按钮权限";
		$showOrder = 203;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 提交盘点单
		$fid = FIdConst::INVENTORY_CHECK_COMMIT;
		$name = "库存盘点-提交盘点单";
		$note = "按钮权限：库存盘点模块[提交盘点单]按钮权限";
		$showOrder = 204;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 单据生成PDF
		$fid = FIdConst::INVENTORY_CHECK_PDF;
		$name = "库存盘点-单据生成PDF";
		$note = "按钮权限：库存盘点模块[单据生成PDF]按钮权限";
		$showOrder = 205;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180203_02() {
		// 本次更新：库间调拨权限细化到按钮
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "库间调拨";
		
		// 新建调拨单
		$fid = FIdConst::INVENTORY_TRANSFER_ADD;
		$name = "库间调拨-新建调拨单";
		$note = "按钮权限：库间调拨模块[新建调拨单]按钮权限";
		$showOrder = 201;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 编辑调拨单
		$fid = FIdConst::INVENTORY_TRANSFER_EDIT;
		$name = "库间调拨-编辑调拨单";
		$note = "按钮权限：库间调拨模块[编辑调拨单]按钮权限";
		$showOrder = 202;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 删除调拨单
		$fid = FIdConst::INVENTORY_TRANSFER_DELETE;
		$name = "库间调拨-删除调拨单";
		$note = "按钮权限：库间调拨模块[删除调拨单]按钮权限";
		$showOrder = 203;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 提交调拨单
		$fid = FIdConst::INVENTORY_TRANSFER_COMMIT;
		$name = "库间调拨-提交调拨单";
		$note = "按钮权限：库间调拨模块[提交调拨单]按钮权限";
		$showOrder = 204;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 单据生成PDF
		$fid = FIdConst::INVENTORY_TRANSFER_PDF;
		$name = "库间调拨-单据生成PDF";
		$note = "按钮权限：库间调拨模块[单据生成PDF]按钮权限";
		$showOrder = 205;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180203_01() {
		// 本次更新：销售退货入库权限细化到按钮
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "销售退货入库";
		
		// 新建销售退货入库单
		$fid = FIdConst::SALE_REJECTION_ADD;
		$name = "销售退货入库-新建销售退货入库单";
		$note = "按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限";
		$showOrder = 201;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 编辑销售退货入库单
		$fid = FIdConst::SALE_REJECTION_EDIT;
		$name = "销售退货入库-编辑销售退货入库单";
		$note = "按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限";
		$showOrder = 202;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 删除销售退货入库单
		$fid = FIdConst::SALE_REJECTION_DELETE;
		$name = "销售退货入库-删除销售退货入库单";
		$note = "按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限";
		$showOrder = 203;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 提交入库
		$fid = FIdConst::SALE_REJECTION_COMMIT;
		$name = "销售退货入库-提交入库";
		$note = "按钮权限：销售退货入库模块[提交入库]按钮权限";
		$showOrder = 204;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 单据生成PDF
		$fid = FIdConst::SALE_REJECTION_PDF;
		$name = "销售退货入库-单据生成PDF";
		$note = "按钮权限：销售退货入库模块[单据生成PDF]按钮权限";
		$showOrder = 205;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180202_01() {
		// 本次更新：销售出库权限细化到按钮
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "销售出库";
		
		// 新建销售出库单
		$fid = FIdConst::WAREHOUSING_SALE_ADD;
		$name = "销售出库-新建销售出库单";
		$note = "按钮权限：销售出库模块[新建销售出库单]按钮权限";
		$showOrder = 201;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 编辑销售出库单
		$fid = FIdConst::WAREHOUSING_SALE_EDIT;
		$name = "销售出库-编辑销售出库单";
		$note = "按钮权限：销售出库模块[编辑销售出库单]按钮权限";
		$showOrder = 202;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 删除销售出库单
		$fid = FIdConst::WAREHOUSING_SALE_DELETE;
		$name = "销售出库-删除销售出库单";
		$note = "按钮权限：销售出库模块[删除销售出库单]按钮权限";
		$showOrder = 203;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 提交出库
		$fid = FIdConst::WAREHOUSING_SALE_COMMIT;
		$name = "销售出库-提交出库";
		$note = "按钮权限：销售出库模块[提交出库]按钮权限";
		$showOrder = 204;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 单据生成PDF
		$fid = FIdConst::WAREHOUSING_SALE_PDF;
		$name = "销售出库-单据生成PDF";
		$note = "按钮权限：销售出库模块[单据生成PDF]按钮权限";
		$showOrder = 205;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180201_01() {
		// 本次更新：销售订单权限细化到按钮
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "销售订单";
		
		// 新建销售订单
		$fid = FIdConst::SALE_ORDER_ADD;
		$name = "销售订单-新建销售订单";
		$note = "按钮权限：销售订单模块[新建销售订单]按钮权限";
		$showOrder = 201;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 编辑销售订单
		$fid = FIdConst::SALE_ORDER_EDIT;
		$name = "销售订单-编辑销售订单";
		$note = "按钮权限：销售订单模块[编辑销售订单]按钮权限";
		$showOrder = 202;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 删除销售订单
		$fid = FIdConst::SALE_ORDER_DELETE;
		$name = "销售订单-删除销售订单";
		$note = "按钮权限：销售订单模块[删除销售订单]按钮权限";
		$showOrder = 203;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 单据生成PDF
		$fid = FIdConst::SALE_ORDER_PDF;
		$name = "销售订单-单据生成PDF";
		$note = "按钮权限：销售订单模块[单据生成PDF]按钮权限";
		$showOrder = 206;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180130_01() {
		// 本次更新：采购退货出库权限细化到按钮
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "采购退货出库";
		
		// 新建采购退货出库单
		$fid = FIdConst::PURCHASE_REJECTION_ADD;
		$name = "采购退货出库 - 新建采购退货出库单";
		$note = "按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限";
		$showOrder = 201;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 编辑采购退货出库单
		$fid = FIdConst::PURCHASE_REJECTION_EDIT;
		$name = "采购退货出库 - 编辑采购退货出库单";
		$note = "按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限";
		$showOrder = 202;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 删除采购退货出库单
		$fid = FIdConst::PURCHASE_REJECTION_DELETE;
		$name = "采购退货出库 - 删除采购退货出库单";
		$note = "按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限";
		$showOrder = 203;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 提交采购退货出库单
		$fid = FIdConst::PURCHASE_REJECTION_COMMIT;
		$name = "采购退货出库 - 提交采购退货出库单";
		$note = "按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限";
		$showOrder = 204;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 单据生成PDF
		$fid = FIdConst::PURCHASE_REJECTION_PDF;
		$name = "采购退货出库 - 单据生成PDF";
		$note = "按钮权限：采购退货出库模块[单据生成PDF]按钮权限";
		$showOrder = 205;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180125_01() {
		// 本次更新：采购入库权限细化到按钮
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "采购入库";
		
		// 新建采购入库单
		$fid = FIdConst::PURCHASE_WAREHOUSE_ADD;
		$name = "采购入库 - 新建采购入库单";
		$note = "按钮权限：采购入库模块[新建采购入库单]按钮权限";
		$showOrder = 201;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 编辑采购入库单
		$fid = FIdConst::PURCHASE_WAREHOUSE_EDIT;
		$name = "采购入库 - 编辑采购入库单";
		$note = "按钮权限：采购入库模块[编辑采购入库单]按钮权限";
		$showOrder = 202;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 删除采购入库单
		$fid = FIdConst::PURCHASE_WAREHOUSE_DELETE;
		$name = "采购入库 - 删除采购入库单";
		$note = "按钮权限：采购入库模块[删除采购入库单]按钮权限";
		$showOrder = 203;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 提交入库
		$fid = FIdConst::PURCHASE_WAREHOUSE_COMMIT;
		$name = "采购入库 - 提交入库";
		$note = "按钮权限：采购入库模块[提交入库]按钮权限";
		$showOrder = 204;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 单据生成PDF
		$fid = FIdConst::PURCHASE_WAREHOUSE_PDF;
		$name = "采购入库 - 单据生成PDF";
		$note = "按钮权限：采购入库模块[单据生成PDF]按钮权限";
		$showOrder = 205;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180119_02() {
		// 本次更新：采购订单权限细化到按钮
		$db = $this->db;
		
		$ps = new PinyinService();
		
		$category = "采购订单";
		
		// 新建采购订单
		$fid = FIdConst::PURCHASE_ORDER_ADD;
		$name = "采购订单 - 新建采购订单";
		$note = "按钮权限：采购订单模块[新建采购订单]按钮权限";
		$showOrder = 201;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 编辑采购订单
		$fid = FIdConst::PURCHASE_ORDER_EDIT;
		$name = "采购订单 - 编辑采购订单";
		$note = "按钮权限：采购订单模块[编辑采购订单]按钮权限";
		$showOrder = 202;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 删除采购订单
		$fid = FIdConst::PURCHASE_ORDER_DELETE;
		$name = "采购订单 - 删除采购订单";
		$note = "按钮权限：采购订单模块[删除采购订单]按钮权限";
		$showOrder = 203;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 关闭订单
		$fid = FIdConst::PURCHASE_ORDER_CLOSE;
		$name = "采购订单 - 关闭订单/取消关闭订单";
		$note = "按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限";
		$showOrder = 206;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
		
		// 单据生成PDF
		$fid = FIdConst::PURCHASE_ORDER_PDF;
		$name = "采购订单 - 单据生成PDF";
		$note = "按钮权限：采购订单模块[单据生成PDF]按钮权限";
		$showOrder = 207;
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py, show_order)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %d) ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py, $showOrder);
		}
	}

	private function update_20180119_01() {
		// 本次更新：调整 t_permission的备注和排序
		
		// 销售日报表（按商品汇总）
		$this->modifyPermission("2012", 100, "模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限");
		
		// 销售日报表(按客户汇总)
		$this->modifyPermission("2013", 100, "模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限");
		
		// 销售日报表(按仓库汇总)
		$this->modifyPermission("2014", 100, "模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限");
		
		// 销售日报表(按业务员汇总)
		$this->modifyPermission("2015", 100, "模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限");
		
		// 销售月报表(按商品汇总)
		$this->modifyPermission("2016", 100, "模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限");
		
		// 销售月报表(按客户汇总)
		$this->modifyPermission("2017", 100, "模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限");
		
		// 销售月报表(按仓库汇总)
		$this->modifyPermission("2018", 100, "模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限");
		
		// 销售月报表(按业务员汇总)
		$this->modifyPermission("2019", 100, "模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限");
		
		// 安全库存明细表
		$this->modifyPermission("2020", 100, "模块权限：通过菜单进入安全库存明细表模块的权限");
		
		// 应收账款账龄分析表
		$this->modifyPermission("2021", 100, "模块权限：通过菜单进入应收账款账龄分析表模块的权限");
		
		// 应付账款账龄分析表
		$this->modifyPermission("2022", 100, "模块权限：通过菜单进入应付账款账龄分析表模块的权限");
		
		// 库存超上限明细表
		$this->modifyPermission("2023", 100, "模块权限：通过菜单进入库存超上限明细表模块的权限");
		
		// 首页-销售看板
		$this->modifyPermission("2011-01", 100, "功能权限：在首页显示销售看板");
		
		// 首页-库存看板
		$this->modifyPermission("2011-02", 100, "功能权限：在首页显示库存看板");
		
		// 首页-采购看板
		$this->modifyPermission("2011-03", 100, "功能权限：在首页显示采购看板");
		
		// 首页-资金看板
		$this->modifyPermission("2011-04", 100, "功能权限：在首页显示资金看板");
	}

	private function update_20180117_02() {
		// 本次更新：调整 t_permission的备注和排序
		
		// 应收账款管理
		$this->modifyPermission("2004", 100, "模块权限：通过菜单进入应收账款管理模块的权限");
		
		// 应付账款管理
		$this->modifyPermission("2005", 100, "模块权限：通过菜单进入应付账款管理模块的权限");
		
		// 现金收支查询
		$this->modifyPermission("2024", 100, "模块权限：通过菜单进入现金收支查询模块的权限");
		
		// 预收款管理
		$this->modifyPermission("2025", 100, "模块权限：通过菜单进入预收款管理模块的权限");
		
		// 预付款管理
		$this->modifyPermission("2026", 100, "模块权限：通过菜单进入预付款管理模块的权限");
	}

	private function update_20180117_01() {
		// 本次更新：调整 t_permission的备注和排序
		
		// 库存账查询
		$this->modifyPermission("2003", 100, "模块权限：通过菜单进入库存账查询模块的权限");
		
		// 库存建账
		$this->modifyPermission("2000", 100, "模块权限：通过菜单进入库存建账模块的权限");
		
		// 库间调拨
		$this->modifyPermission("2009", 100, "模块权限：通过菜单进入库间调拨模块的权限");
		
		// 库存盘点
		$this->modifyPermission("2010", 100, "模块权限：通过菜单进入库存盘点模块的权限");
	}

	private function update_20180115_01() {
		// 本次更新：调整 t_permission的备注和排序
		
		// 销售订单
		$this->modifyPermission("2028", 100, "模块权限：通过菜单进入销售订单模块的权限");
		$this->modifyPermission("2028-01", 204, "按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限");
		$this->modifyPermission("2028-02", 205, "按钮权限：销售订单模块[生成销售出库单]按钮的权限");
		
		// 销售出库
		$this->modifyPermission("2002", 100, "模块权限：通过菜单进入销售出库模块的权限");
		$this->modifyPermission("2002-01", 101, "功能权限：销售出库单允许编辑销售单价");
		
		// 销售退货入库
		$this->modifyPermission("2006", 100, "模块权限：通过菜单进入销售退货入库模块的权限");
	}

	private function update_20180111_01() {
		// 本次更新：调整 t_permission的备注和排序
		
		// 采购入库
		$this->modifyPermission("2001", 100, "模块权限：通过菜单进入采购入库模块的权限");
		
		// 采购退货出库
		$this->modifyPermission("2007", 100, "模块权限：通过菜单进入采购退货出库模块的权限");
	}

	private function update_20180101_01() {
		// 本次更新：调整 t_permission的备注和排序
		
		// 采购订单
		$this->modifyPermission("2027", 100, "模块权限：通过菜单进入采购订单模块的权限");
		
		$this->modifyPermission("2027-01", 204, "按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限");
		$this->modifyPermission("2027-02", 205, "按钮权限：采购订单模块[生成采购入库单]按钮权限");
	}

	private function update_20171229_01() {
		// 本次更新：调整 t_permission的备注和排序
		
		// 业务设置
		$this->modifyPermission("2008", 100, "模块权限：通过菜单进入业务设置模块的权限");
		
		// 系统日志
		$this->modifyPermission("-8997", 100, "模块权限：通过菜单进入业务日志模块的权限");
	}

	private function update_20171227_01() {
		// 本次更新：调整 t_permission的备注和排序
		
		// 权限管理
		$this->modifyPermission("-8996", 100, "模块权限：通过菜单进入权限管理模块的权限");
		
		$this->modifyPermission("-8996-01", 201, "按钮权限：权限管理模块[新增角色]按钮权限");
		$this->modifyPermission("-8996-02", 202, "按钮权限：权限管理模块[编辑角色]按钮权限");
		$this->modifyPermission("-8996-03", 203, "按钮权限：权限管理模块[删除角色]按钮权限");
	}

	private function update_20171226_01() {
		// 本次更新：调整 t_permission的备注和排序
		
		// 客户
		$this->modifyPermission("1007", 100, "模块权限：通过菜单进入客户资料模块的权限");
		
		$this->modifyPermission("1007-03", 201, "按钮权限：客户资料模块[新增客户分类]按钮权限");
		$this->modifyPermission("1007-04", 202, "按钮权限：客户资料模块[编辑客户分类]按钮权限");
		$this->modifyPermission("1007-05", 203, "按钮权限：客户资料模块[删除客户分类]按钮权限");
		$this->modifyPermission("1007-06", 204, "按钮权限：客户资料模块[新增客户]按钮权限");
		$this->modifyPermission("1007-07", 205, "按钮权限：客户资料模块[编辑客户]按钮权限");
		$this->modifyPermission("1007-08", 206, "按钮权限：客户资料模块[删除客户]按钮权限");
		$this->modifyPermission("1007-09", 207, "按钮权限：客户资料模块[导入客户]按钮权限");
		
		$this->modifyPermission("1007-01", 300, "数据域权限：客户资料在业务单据中的使用权限");
		$this->modifyPermission("1007-02", 301, "数据域权限：客户档案模块中客户分类的数据权限");
	}

	private function update_20171214_01() {
		// 本次更新： 调整 t_permission的备注和排序
		
		// 用户管理
		$this->modifyPermission("-8999", 100, "模块权限：通过菜单进入用户管理模块的权限");
		
		$this->modifyPermission("-8999-03", 201, "按钮权限：用户管理模块[新增组织机构]按钮权限");
		$this->modifyPermission("-8999-04", 202, "按钮权限：用户管理模块[编辑组织机构]按钮权限");
		$this->modifyPermission("-8999-05", 203, "按钮权限：用户管理模块[删除组织机构]按钮权限");
		$this->modifyPermission("-8999-06", 204, "按钮权限：用户管理模块[新增用户]按钮权限");
		$this->modifyPermission("-8999-07", 205, "按钮权限：用户管理模块[编辑用户]按钮权限");
		$this->modifyPermission("-8999-08", 206, "按钮权限：用户管理模块[删除用户]按钮权限");
		$this->modifyPermission("-8999-09", 207, "按钮权限：用户管理模块[修改用户密码]按钮权限");
		
		$this->modifyPermission("-8999-01", 300, "数据域权限：组织机构在业务单据中的使用权限");
		$this->modifyPermission("-8999-02", 301, "数据域权限：业务员在业务单据中的使用权限");
	}

	private function update_20171208_01() {
		// 本次更新：t_ic_bill新增字段bill_memo，t_ic_bill_detail新增字段memo
		$db = $this->db;
		
		$tableName = "t_ic_bill";
		$columnName = "bill_memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		$tableName = "t_ic_bill_detail";
		$columnName = "memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function modifyPermission($fid, $showOrder, $note) {
		$db = $this->db;
		
		$sql = "update t_permission
				set show_order = %d, note = '%s'
				where fid = '%s' ";
		$db->execute($sql, $showOrder, $note, $fid);
	}

	private function update_20171113_01() {
		// 本次更新：调整t_permission的备注和排序
		
		// 商品
		$this->modifyPermission("1001", 100, "模块权限：通过菜单进入商品模块的权限");
		
		$this->modifyPermission("1001-03", 201, "按钮权限：商品模块[新增商品分类]按钮权限");
		$this->modifyPermission("1001-04", 202, "按钮权限：商品模块[编辑商品分类]按钮权限");
		$this->modifyPermission("1001-05", 203, "按钮权限：商品模块[删除商品分类]按钮权限");
		
		$this->modifyPermission("1001-06", 204, "按钮权限：商品模块[新增商品]按钮权限");
		$this->modifyPermission("1001-07", 205, "按钮权限：商品模块[编辑商品]按钮权限");
		$this->modifyPermission("1001-08", 206, "按钮权限：商品模块[删除商品]按钮权限");
		$this->modifyPermission("1001-09", 207, "按钮权限：商品模块[导入商品]按钮权限");
		$this->modifyPermission("1001-10", 208, "按钮权限：商品模块[设置安全库存]按钮权限");
		
		$this->modifyPermission("2030-01", 209, "按钮权限：商品模块[新增子商品]按钮权限");
		$this->modifyPermission("2030-02", 210, "按钮权限：商品模块[编辑子商品]按钮权限");
		$this->modifyPermission("2030-03", 211, "按钮权限：商品模块[删除子商品]按钮权限");
		
		$this->modifyPermission("1001-01", 300, "数据域权限：商品在业务单据中的使用权限");
		$this->modifyPermission("1001-02", 301, "数据域权限：商品模块中商品分类的数据权限");
		
		$this->modifyPermission("1002", 500, "模块权限：通过菜单进入商品计量单位模块的权限");
		$this->modifyPermission("2029", 600, "模块权限：通过菜单进入商品品牌模块的权限");
		$this->modifyPermission("2031", 700, "模块权限：通过菜单进入价格体系模块的权限");
	}

	private function update_20171102_02() {
		// 本次更新： 调整 t_permission的备注和排序
		
		// 仓库
		$this->modifyPermission("1003", 100, "模块权限：通过菜单进入仓库的权限");
		$this->modifyPermission("1003-02", 201, "按钮权限：仓库模块[新增仓库]按钮权限");
		$this->modifyPermission("1003-03", 202, "按钮权限：仓库模块[编辑仓库]按钮权限");
		$this->modifyPermission("1003-04", 203, "按钮权限：仓库模块[删除仓库]按钮权限");
		$this->modifyPermission("1003-05", 204, "按钮权限：仓库模块[修改数据域]按钮权限");
		$this->modifyPermission("1003-01", 300, "数据域权限：仓库在业务单据中的使用权限");
		
		// 供应商
		$this->modifyPermission("1004", 100, "模块权限：通过菜单进入供应商档案的权限");
		$this->modifyPermission("1004-03", 201, "按钮权限：供应商档案模块[新增供应商分类]按钮权限");
		$this->modifyPermission("1004-04", 202, "按钮权限：供应商档案模块[编辑供应商分类]按钮权限");
		$this->modifyPermission("1004-05", 203, "按钮权限：供应商档案模块[删除供应商分类]按钮权限");
		$this->modifyPermission("1004-06", 204, "按钮权限：供应商档案模块[新增供应商]按钮权限");
		$this->modifyPermission("1004-07", 205, "按钮权限：供应商档案模块[编辑供应商]按钮权限");
		$this->modifyPermission("1004-08", 206, "按钮权限：供应商档案模块[删除供应商]按钮权限");
		$this->modifyPermission("1004-02", 300, "数据域权限：供应商档案模块中供应商分类的数据权限");
		$this->modifyPermission("1004-01", 301, "数据域权限：供应商档案在业务单据中的使用权限");
	}

	private function update_20171102_01() {
		// 本次更新：t_permission新增字段show_order
		$db = $this->db;
		
		$tableName = "t_permission";
		$columnName = "show_order";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20171101_01() {
		// 本次更新：t_customer新增sales_warehouse_id
		$db = $this->db;
		
		$tableName = "t_customer";
		$columnName = "sales_warehouse_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20170927_01() {
		// 本次更新：t_supplier新增字段tax_rate
		$db = $this->db;
		
		$tableName = "t_supplier";
		$columnName = "tax_rate";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20170609_02() {
		// 本次更新：修正bug - 价格体系的权限项目没有分类
		$db = $this->db;
		
		$sql = "update t_permission
				set category = '商品', py = 'JGTX', note = '通过菜单进入价格体系模块的权限'
				where id = '2031' ";
		$db->execute($sql);
	}

	private function update_20170607_01() {
		// 本次更新：新增表t_goods_price
		$db = $this->db;
		$tableName = "t_goods_price";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_goods_price` (
					  `id` varchar(255) NOT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `ps_id` varchar(255) NOT NULL,
					  `price` decimal(19,2) NOT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20170606_03() {
		// 本次更新：t_customer_category新增字段ps_id
		$db = $this->db;
		
		$tableName = "t_customer_category";
		$columnName = "ps_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20170606_02() {
		// 本次更新：新增模块价格体系
		$db = $this->db;
		
		// fid
		$fid = FIdConst::PRICE_SYSTEM;
		$name = "价格体系";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		// 权限
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					value('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
		
		// 菜单
		$sql = "select count(*) as cnt from t_menu_item
				where id = '080104' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item(id, caption, fid, parent_id, show_order)
					values ('080104', '%s', '%s', '0801', 4)";
			$db->execute($sql, $name, $fid);
		}
	}

	private function update_20170606_01() {
		// 本次更新：新增表t_price_system
		$db = $this->db;
		$tableName = "t_price_system";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_price_system` (
					  `id` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  `factor` decimal(19,2) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
			$db->execute($sql);
		}
	}

	private function update_20170604_01($db) {
		// 本次更新：新增表think_session ，把session持久化到数据库中
		$tableName = "think_session";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE `think_session` (
					  `session_id` varchar(255) NOT NULL,
					  `session_expire` int(11) NOT NULL,
					  `session_data` blob,
					  UNIQUE KEY `session_id` (`session_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
	}

	private function update_20170530_01($db) {
		// 本次更新：t_ws_bill新增字段deal_address
		$tableName = "t_ws_bill";
		$columnName = "deal_address";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20170519_01($db) {
		// 本次更新：t_pw_bill新增字段bill_memo，t_po_bill_detail新增字段memo
		$tableName = "t_pw_bill";
		$columnName = "bill_memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
		
		$tableName = "t_po_bill_detail";
		$columnName = "memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20170515_01($db) {
		// 本次更新：t_role表新增字段code
		$tableName = "t_role";
		$columnName = "code";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20170503_01($db) {
		// 本次更新：t_so_bill_detail新增字段memo
		$tableName = "t_so_bill_detail";
		$columnName = "memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20170412_02($db) {
		// 本次更新：t_ws_bill_detail新增字段sobilldetail_id
		$tableName = "t_ws_bill_detail";
		$columnName = "sobilldetail_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20170412_01($db) {
		// 本次更新：t_pw_bill_detail新增字段pobilldetail_id
		$tableName = "t_pw_bill_detail";
		$columnName = "pobilldetail_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
			$db->execute($sql);
		}
	}

	private function update_20170408_01($db) {
		// 本次更新：t_pw_bill新增字段expand_by_bom
		$tableName = "t_pw_bill";
		$columnName = "expand_by_bom";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 0;";
			$db->execute($sql);
		}
	}

	private function update_20170405_01($db) {
		// 本次更新：商品构成权限
		$ps = new PinyinService();
		$category = "商品";
		
		$fid = FIdConst::GOODS_BOM_ADD;
		$name = "商品构成-新增子商品";
		$note = "商品构成新增子商品按钮的操作权限";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		$fid = FIdConst::GOODS_BOM_EDIT;
		$name = "商品构成-编辑子商品";
		$note = "商品构成编辑子商品按钮的操作权限";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		$fid = FIdConst::GOODS_BOM_DELETE;
		$name = "商品构成-删除子商品";
		$note = "商品构成删除子商品按钮的操作权限";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
	}

	private function update_20160722_01($db) {
		// 本次跟新：t_subject表新增字段parent_id
		$tableName = "t_subject";
		$columnName = "parent_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20160620_01($db) {
		// 本次更新：新增表：t_subject
		$tableName = "t_subject";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_subject` (
					  `id` varchar(255) NOT NULL,
					  `category` int NOT NULL,
					  `code` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `is_leaf` int NOT NULL,
					  `py` varchar(255) DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
	}

	private function update_20160314_01($db) {
		// 本次更新：新增表 t_goods_bom
		$tableName = "t_goods_bom";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_goods_bom` (
					  `id` varchar(255) NOT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `sub_goods_id` varchar(255) NOT NULL,
					  `parent_id` varchar(255) DEFAULT NULL,
					  `sub_goods_count` decimal(19,2) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
	}

	private function update_20160303_01($db) {
		// 本次更新：调整菜单；新增模块：基础数据-商品品牌
		
		// 调整菜单
		$sql = "update t_menu_item
				set fid = null
				where id = '0801' ";
		$db->execute($sql);
		
		$sql = "select count(*) as cnt 
				from t_menu_item 
				where id = '080101' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item (id, caption, fid, parent_id, show_order)
				values ('080101', '商品', '1001', '0801', 1)";
			$db->execute($sql);
		}
		
		$sql = "update t_menu_item
				set parent_id = '0801', id = '080102'
				where id = '0802' ";
		$db->execute($sql);
		
		// 新增模块：基础数据-商品品牌
		$fid = FIdConst::GOODS_BRAND;
		$name = "商品品牌";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$category = "商品";
			$ps = new PinyinService();
			$py = $ps->toPY($name);
			$sql = "insert into t_permission(id, fid, name, note, category, py)
					value('%s', '%s', '%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name, $category, $py);
		}
		
		$sql = "select count(*) as cnt from t_menu_item
				where id = '080103' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item(id, caption, fid, parent_id, show_order)
					values ('080103', '%s', '%s', '0801', 3)";
			$db->execute($sql, $name, $fid);
		}
	}

	private function update_20160301_01($db) {
		// 本次更新：新增表t_goods_brand; t_goods新增字段 brand_id
		$tableName = "t_goods_brand";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_goods_brand` (
					  `id` varchar(255) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `parent_id` varchar(255) DEFAULT NULL,
					  `full_name` varchar(1000) DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
		
		$tableName = "t_goods";
		$columnName = "brand_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20160219_01($db) {
		// 本次更新：销售订单新增审核和生成销售出库单的权限
		$ps = new PinyinService();
		$category = "销售订单";
		
		$fid = FIdConst::SALE_ORDER_CONFIRM;
		$name = "销售订单 - 审核/取消审核";
		$note = "销售订单 - 审核/取消审核";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		$fid = FIdConst::SALE_ORDER_GEN_WSBILL;
		$name = "销售订单 - 生成销售出库单";
		$note = "销售订单 - 生成销售出库单";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
	}

	private function update_20160120_01($db) {
		// 本次更新：细化客户资料的权限到按钮级别
		$fid = FIdConst::CUSTOMER;
		$category = "客户管理";
		$note = "通过菜单进入客户资料模块的权限";
		$sql = "update t_permission
				set note = '%s'
				where id = '%s' ";
		$db->execute($sql, $note, $fid);
		
		$ps = new PinyinService();
		
		// 新增客户分类
		$fid = FIdConst::CUSTOMER_CATEGORY_ADD;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "新增客户分类";
			$note = "客户资料模块[新增客户分类]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑客户分类
		$fid = FIdConst::CUSTOMER_CATEGORY_EDIT;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "编辑客户分类";
			$note = "客户资料模块[编辑客户分类]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除客户分类
		$fid = FIdConst::CUSTOMER_CATEGORY_DELETE;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "删除客户分类";
			$note = "客户资料模块[删除客户分类]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 新增客户
		$fid = FIdConst::CUSTOMER_ADD;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "新增客户";
			$note = "客户资料模块[新增客户]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑客户
		$fid = FIdConst::CUSTOMER_EDIT;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "编辑客户";
			$note = "客户资料模块[编辑客户]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除客户
		$fid = FIdConst::CUSTOMER_DELETE;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "删除客户";
			$note = "客户资料模块[删除客户]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 导入客户
		$fid = FIdConst::CUSTOMER_IMPORT;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "导入客户";
			$note = "客户资料模块[导入客户]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
	}

	private function update_20160119_01($db) {
		// 本次更新：细化基础数据供应商的权限到按钮级别
		$fid = "1004";
		$category = "供应商管理";
		$note = "通过菜单进入基础数据供应商档案模块的权限";
		$sql = "update t_permission
				set note = '%s'
				where id = '%s' ";
		$db->execute($sql, $note, $fid);
		
		$ps = new PinyinService();
		
		// 新增供应商分类
		$fid = FIdConst::SUPPLIER_CATEGORY_ADD;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "新增供应商分类";
			$note = "基础数据供应商档案模块[新增供应商分类]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑供应商分类
		$fid = FIdConst::SUPPLIER_CATEGORY_EDIT;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "编辑供应商分类";
			$note = "基础数据供应商档案模块[编辑供应商分类]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除供应商分类
		$fid = FIdConst::SUPPLIER_CATEGORY_DELETE;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "删除供应商分类";
			$note = "基础数据供应商档案模块[删除供应商分类]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 新增供应商
		$fid = FIdConst::SUPPLIER_ADD;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "新增供应商";
			$note = "基础数据供应商档案模块[新增供应商]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑供应商
		$fid = FIdConst::SUPPLIER_EDIT;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "编辑供应商";
			$note = "基础数据供应商档案模块[编辑供应商]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除供应商
		$fid = FIdConst::SUPPLIER_DELETE;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "删除供应商";
			$note = "基础数据供应商档案模块[删除供应商]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
	}

	private function update_20160118_01($db) {
		// 本次更新：细化基础数据商品的权限到按钮级别
		$fid = "1001";
		$category = "商品";
		$note = "通过菜单进入基础数据商品模块的权限";
		$sql = "update t_permission
				set note = '%s'
				where id = '%s' ";
		$db->execute($sql, $note, $fid);
		
		$ps = new PinyinService();
		
		// 新增商品分类
		$fid = FIdConst::GOODS_CATEGORY_ADD;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "新增商品分类";
			$note = "基础数据商品模块[新增商品分类]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑商品分类
		$fid = FIdConst::GOODS_CATEGORY_EDIT;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "编辑商品分类";
			$note = "基础数据商品模块[编辑商品分类]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除商品分类
		$fid = FIdConst::GOODS_CATEGORY_DELETE;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "删除商品分类";
			$note = "基础数据商品模块[删除商品分类]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 新增商品
		$fid = FIdConst::GOODS_ADD;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "新增商品";
			$note = "基础数据商品模块[新增商品]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑商品
		$fid = FIdConst::GOODS_EDIT;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "编辑商品";
			$note = "基础数据商品模块[编辑商品]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除商品
		$fid = FIdConst::GOODS_DELETE;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "删除商品";
			$note = "基础数据商品模块[删除商品]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 导入商品
		$fid = FIdConst::GOODS_IMPORT;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "导入商品";
			$note = "基础数据商品模块[导入商品]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 设置商品安全库存
		$fid = FIdConst::GOODS_SI;
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "设置商品安全库存";
			$note = "基础数据商品模块[设置商品安全库存]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
	}

	private function update_20160116_02($db) {
		// 本次更新：细化基础数据仓库的权限到按钮级别
		$fid = "1003";
		$category = "仓库";
		$note = "通过菜单进入基础数据仓库模块的权限";
		$sql = "update t_permission
				set note = '%s'
				where id = '%s' ";
		$db->execute($sql, $note, $fid);
		
		$ps = new PinyinService();
		
		// 新增仓库
		$fid = "1003-02";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "新增仓库";
			$note = "基础数据仓库模块[新增仓库]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑仓库
		$fid = "1003-03";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "编辑仓库";
			$note = "基础数据仓库模块[编辑仓库]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除仓库
		$fid = "1003-04";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "删除仓库";
			$note = "基础数据仓库模块[删除仓库]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 修改仓库数据域
		$fid = "1003-05";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "修改仓库数据域";
			$note = "基础数据仓库模块[修改仓库数据域]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
	}

	private function update_20160116_01($db) {
		// 本次更新：细化用户管理模块的权限到按钮级别
		$fid = "-8999";
		$category = "用户管理";
		$note = "通过菜单进入用户管理模块的权限";
		$sql = "update t_permission
				set note = '%s',
					category = '%s'
				where id = '%s' ";
		$db->execute($sql, $note, $category, $fid);
		
		$sql = "update t_permission
				set category = '%s'
				where id in( '-8999-01', '-8999-02' ) ";
		$db->execute($sql, $category);
		
		$ps = new PinyinService();
		
		// 新增组织机构
		$fid = "-8999-03";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "用户管理-新增组织机构";
			$note = "用户管理模块[新增组织机构]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑组织机构
		$fid = "-8999-04";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "用户管理-编辑组织机构";
			$note = "用户管理模块[编辑组织机构]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除组织机构
		$fid = "-8999-05";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "用户管理-删除组织机构";
			$note = "用户管理模块[删除组织机构]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 新增用户
		$fid = "-8999-06";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "用户管理-新增用户";
			$note = "用户管理模块[新增用户]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑用户
		$fid = "-8999-07";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "用户管理-编辑用户";
			$note = "用户管理模块[编辑用户]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除用户
		$fid = "-8999-08";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "用户管理-删除用户";
			$note = "用户管理模块[删除用户]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 修改用户密码
		$fid = "-8999-09";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "用户管理-修改用户密码";
			$note = "用户管理模块[修改用户密码]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
	}

	private function update_20160112_01($db) {
		// 本次更新： 细化权限管理模块的权限到按钮级别
		$fid = "-8996";
		$category = "权限管理";
		$note = "通过菜单进入权限管理模块的权限";
		$sql = "update t_permission
				set note = '%s',
					category = '%s'
				where id = '%s' ";
		$db->execute($sql, $note, $category, $fid);
		
		$ps = new PinyinService();
		
		// 新增角色
		$fid = "-8996-01";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "权限管理-新增角色";
			$note = "权限管理模块[新增角色]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 编辑角色
		$fid = "-8996-02";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "权限管理-编辑角色";
			$note = "权限管理模块[编辑角色]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
		
		// 删除角色
		$fid = "-8996-03";
		$sql = "select count(*) as cnt from t_permission
				where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$name = "权限管理-删除角色";
			$note = "权限管理模块[删除角色]按钮的权限";
			
			$py = $ps->toPY($name);
			
			$sql = "insert into t_permission (id, fid, name, note, category, py)
				values ('%s', '%s', '%s', '%s', '%s', '%s') ";
			$db->execute($sql, $fid, $fid, $name, $note, $category, $py);
		}
	}

	private function update_20160108_01($db) {
		// 本次更新：t_permission新增字段 category、py
		$tableName = "t_permission";
		$columnName = "category";
		
		$updateData = false;
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
			
			$updateData = true;
		}
		
		$columnName = "py";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
			
			$updateData = true;
		}
		
		if (! $updateData) {
			return;
		}
		
		// 更新t_permission数据
		$ps = new PinyinService();
		$sql = "select id, name from t_permission";
		$data = $db->query($sql);
		foreach ( $data as $v ) {
			$id = $v["id"];
			$name = $v["name"];
			$sql = "update t_permission
					set py = '%s'
					where id = '%s' ";
			$db->execute($sql, $ps->toPY($name), $id);
		}
		
		// 权限分类：系统管理
		$sql = "update t_permission
				set category = '系统管理' 
				where id in ('-8996', '-8997', '-8999', '-8999-01', 
					'-8999-02', '2008')";
		$db->execute($sql);
		
		// 权限分类：商品
		$sql = "update t_permission
				set category = '商品' 
				where id in ('1001', '1001-01', '1001-02', '1002')";
		$db->execute($sql);
		
		// 权限分类：仓库
		$sql = "update t_permission
				set category = '仓库' 
				where id in ('1003', '1003-01')";
		$db->execute($sql);
		
		// 权限分类： 供应商管理
		$sql = "update t_permission
				set category = '供应商管理'
				where id in ('1004', '1004-01', '1004-02')";
		$db->execute($sql);
		
		// 权限分类：客户管理
		$sql = "update t_permission
				set category = '客户管理'
				where id in ('1007', '1007-01', '1007-02')";
		$db->execute($sql);
		
		// 权限分类：库存建账
		$sql = "update t_permission
				set category = '库存建账'
				where id in ('2000')";
		$db->execute($sql);
		
		// 权限分类：采购入库
		$sql = "update t_permission
				set category = '采购入库'
				where id in ('2001')";
		$db->execute($sql);
		
		// 权限分类：销售出库
		$sql = "update t_permission
				set category = '销售出库'
				where id in ('2002', '2002-01')";
		$db->execute($sql);
		
		// 权限分类：库存账查询
		$sql = "update t_permission
				set category = '库存账查询'
				where id in ('2003')";
		$db->execute($sql);
		
		// 权限分类：应收账款管理
		$sql = "update t_permission
				set category = '应收账款管理'
				where id in ('2004')";
		$db->execute($sql);
		
		// 权限分类：应付账款管理
		$sql = "update t_permission
				set category = '应付账款管理'
				where id in ('2005')";
		$db->execute($sql);
		
		// 权限分类：销售退货入库
		$sql = "update t_permission
				set category = '销售退货入库'
				where id in ('2006')";
		$db->execute($sql);
		
		// 权限分类：采购退货出库
		$sql = "update t_permission
				set category = '采购退货出库'
				where id in ('2007')";
		$db->execute($sql);
		
		// 权限分类：库间调拨
		$sql = "update t_permission
				set category = '库间调拨'
				where id in ('2009')";
		$db->execute($sql);
		
		// 权限分类：库存盘点
		$sql = "update t_permission
				set category = '库存盘点'
				where id in ('2010')";
		$db->execute($sql);
		
		// 权限分类：首页看板
		$sql = "update t_permission
				set category = '首页看板'
				where id in ('2011-01', '2011-02', '2011-03', '2011-04')";
		$db->execute($sql);
		
		// 权限分类：销售日报表
		$sql = "update t_permission
				set category = '销售日报表'
				where id in ('2012', '2013', '2014', '2015')";
		$db->execute($sql);
		
		// 权限分类：销售月报表
		$sql = "update t_permission
				set category = '销售月报表'
				where id in ('2016', '2017', '2018', '2019')";
		$db->execute($sql);
		
		// 权限分类：库存报表
		$sql = "update t_permission
				set category = '库存报表'
				where id in ('2020', '2023')";
		$db->execute($sql);
		
		// 权限分类：资金报表
		$sql = "update t_permission
				set category = '资金报表'
				where id in ('2021', '2022')";
		$db->execute($sql);
		
		// 权限分类：现金管理
		$sql = "update t_permission
				set category = '现金管理'
				where id in ('2024')";
		$db->execute($sql);
		
		// 权限分类：预收款管理
		$sql = "update t_permission
				set category = '预收款管理'
				where id in ('2025')";
		$db->execute($sql);
		
		// 权限分类：预付款管理
		$sql = "update t_permission
				set category = '预付款管理'
				where id in ('2026')";
		$db->execute($sql);
		
		// 权限分类：采购订单
		$sql = "update t_permission
				set category = '采购订单'
				where id in ('2027', '2027-01', '2027-02')";
		$db->execute($sql);
		
		// 权限分类：销售订单
		$sql = "update t_permission
				set category = '销售订单'
				where id in ('2028')";
		$db->execute($sql);
	}

	private function update_20160105_02($db) {
		// 本次更新：新增模块销售订单
		$fid = FIdConst::SALE_ORDER;
		$name = "销售订单";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note) 
					value('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
		
		$sql = "select count(*) as cnt from t_menu_item
				where id = '0400' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item(id, caption, fid, parent_id, show_order)
					values ('0400', '%s', '%s', '04', 0)";
			$db->execute($sql, $name, $fid);
		}
	}

	private function update_20160105_01($db) {
		// 本次更新：新增采购订单表
		$tableName = "t_so_bill";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_so_bill` (
					  `id` varchar(255) NOT NULL,
					  `bill_status` int(11) NOT NULL,
					  `biz_dt` datetime NOT NULL,
					  `deal_date` datetime NOT NULL,
					  `org_id` varchar(255) NOT NULL,
					  `biz_user_id` varchar(255) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `tax` decimal(19,2) NOT NULL,
					  `money_with_tax` decimal(19,2) NOT NULL,
					  `input_user_id` varchar(255) NOT NULL,
					  `ref` varchar(255) NOT NULL,
					  `customer_id` varchar(255) NOT NULL,
					  `contact` varchar(255) NOT NULL,
					  `tel` varchar(255) DEFAULT NULL,
					  `fax` varchar(255) DEFAULT NULL,
					  `deal_address` varchar(255) DEFAULT NULL,
					  `bill_memo` varchar(255) DEFAULT NULL,
					  `receiving_type` int(11) NOT NULL DEFAULT 0,
					  `confirm_user_id` varchar(255) DEFAULT NULL,
					  `confirm_date` datetime DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
		
		$tableName = "t_so_bill_detail";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_so_bill_detail` (
					  `id` varchar(255) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `goods_count` int(11) NOT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `goods_price` decimal(19,2) NOT NULL,
					  `sobill_id` varchar(255) NOT NULL,
					  `tax_rate` decimal(19,2) NOT NULL,
					  `tax` decimal(19,2) NOT NULL,
					  `money_with_tax` decimal(19,2) NOT NULL,
					  `ws_count` int(11) NOT NULL,
					  `left_count` int(11) NOT NULL,
					  `show_order` int(11) NOT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  `company_id` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
		
		$tableName = "t_so_ws";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_so_ws` (
					  `so_id` varchar(255) NOT NULL,
					  `ws_id` varchar(255) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
	}

	private function update_20151210_01($db) {
		// 本次更新： t_goods新增字段spec_py
		$tableName = "t_goods";
		$columnName = "spec_py";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151128_03($db) {
		// 本次更新：表新增company_id字段
		$tables = array(
				"t_biz_log",
				"t_role",
				"t_user",
				"t_warehouse",
				"t_supplier",
				"t_supplier_category",
				"t_goods",
				"t_goods_category",
				"t_goods_unit",
				"t_customer",
				"t_customer_category",
				"t_inventory",
				"t_inventory_detail",
				"t_pw_bill_detail",
				"t_payment",
				"t_ws_bill_detail",
				"t_receiving",
				"t_sr_bill_detail",
				"t_it_bill_detail",
				"t_ic_bill_detail",
				"t_pr_bill_detail",
				"t_config",
				"t_goods_si",
				"t_po_bill_detail"
		);
		$columnName = "company_id";
		foreach ( $tables as $tableName ) {
			if (! $this->tableExists($db, $tableName)) {
				continue;
			}
			
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
				$db->execute($sql);
			}
		}
	}

	private function update_20151128_02($db) {
		// 本次更新：新增商品分类权限
		$fid = "1001-02";
		$name = "商品分类";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note) 
					value('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
	}

	private function update_20151128_01($db) {
		// 本次更新：新增供应商分类权限
		$fid = "1004-02";
		$name = "供应商分类";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note) 
					value('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
	}

	private function update_20151127_01($db) {
		// 本次更新：新增客户分类权限
		$fid = "1007-02";
		$name = "客户分类";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) value('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note) 
					value('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
	}

	private function update_20151126_01($db) {
		// 本次更新：销售出库单新增备注字段
		$tableName = "t_ws_bill";
		$columnName = "memo";
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(1000) default null;";
			$db->execute($sql);
		}
		
		$tableName = "t_ws_bill_detail";
		$columnName = "memo";
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(1000) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151124_01($db) {
		// 本次更新：调拨单、盘点单新增company_id字段
		$tableName = "t_it_bill";
		$columnName = "company_id";
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$tableName = "t_ic_bill";
		$columnName = "company_id";
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151123_03($db) {
		// 本次更新：销售退货入库单新增company_id字段
		$tableName = "t_sr_bill";
		$columnName = "company_id";
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151123_02($db) {
		// 本次更新：销售出库单新增company_id字段
		$tableName = "t_ws_bill";
		$columnName = "company_id";
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151123_01($db) {
		// 本次更新： 采购退货出库单新增company_id字段
		$tableName = "t_pr_bill";
		$columnName = "company_id";
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151121_01($db) {
		// 本次更新：采购入库单主表新增company_id字段
		$tableName = "t_pw_bill";
		$columnName = "company_id";
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151119_03($db) {
		// 本次更新： 采购订单主表增加 company_id 字段
		$tableName = "t_po_bill";
		$columnName = "company_id";
		
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151119_01($db) {
		// 本次更新：和资金相关的表增加 company_id 字段
		$tableList = array(
				"t_cash",
				"t_cash_detail",
				"t_payables",
				"t_payables_detail",
				"t_pre_payment",
				"t_pre_payment_detail",
				"t_pre_receiving",
				"t_pre_receiving_detail",
				"t_receivables",
				"t_receivables_detail"
		);
		
		$columnName = "company_id";
		
		foreach ( $tableList as $tableName ) {
			if (! $this->tableExists($db, $tableName)) {
				continue;
			}
			
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
				$db->execute($sql);
			}
		}
	}

	private function update_20151113_01($db) {
		// 本次更新：t_pw_bill_detail表新增memo字段
		$tableName = "t_pw_bill_detail";
		$columnName = "memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(1000) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151112_01($db) {
		// 本次更新：t_biz_log表增加ip_from字段
		$tableName = "t_biz_log";
		$columnName = "ip_from";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151111_01($db) {
		// 本次更新：t_config表：单号前缀自定义
		$id = "9003-01";
		$name = "采购订单单号前缀";
		$value = "PO";
		$showOrder = 601;
		$sql = "select count(*) as cnt from t_config where id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('%s', '%s', '%s', '', %d)";
			$db->execute($sql, $id, $name, $value, $showOrder);
		}
		
		$id = "9003-02";
		$name = "采购入库单单号前缀";
		$value = "PW";
		$showOrder = 602;
		$sql = "select count(*) as cnt from t_config where id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('%s', '%s', '%s', '', %d)";
			$db->execute($sql, $id, $name, $value, $showOrder);
		}
		
		$id = "9003-03";
		$name = "采购退货出库单单号前缀";
		$value = "PR";
		$showOrder = 603;
		$sql = "select count(*) as cnt from t_config where id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('%s', '%s', '%s', '', %d)";
			$db->execute($sql, $id, $name, $value, $showOrder);
		}
		
		$id = "9003-04";
		$name = "销售出库单单号前缀";
		$value = "WS";
		$showOrder = 604;
		$sql = "select count(*) as cnt from t_config where id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('%s', '%s', '%s', '', %d)";
			$db->execute($sql, $id, $name, $value, $showOrder);
		}
		
		$id = "9003-05";
		$name = "销售退货入库单单号前缀";
		$value = "SR";
		$showOrder = 605;
		$sql = "select count(*) as cnt from t_config where id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('%s', '%s', '%s', '', %d)";
			$db->execute($sql, $id, $name, $value, $showOrder);
		}
		
		$id = "9003-06";
		$name = "调拨单单号前缀";
		$value = "IT";
		$showOrder = 606;
		$sql = "select count(*) as cnt from t_config where id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('%s', '%s', '%s', '', %d)";
			$db->execute($sql, $id, $name, $value, $showOrder);
		}
		
		$id = "9003-07";
		$name = "盘点单单号前缀";
		$value = "IC";
		$showOrder = 607;
		$sql = "select count(*) as cnt from t_config where id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('%s', '%s', '%s', '', %d)";
			$db->execute($sql, $id, $name, $value, $showOrder);
		}
	}

	private function update_20151110_02($db) {
		// 本次更新：t_inventory_fifo_detail表增加wsbilldetail_id字段
		$tableName = "t_inventory_fifo_detail";
		$columnName = "wsbilldetail_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151110_01($db) {
		// 本次更新： t_inventory_fifo、 t_inventory_fifo_detail表增加字段 pwbilldetail_id
		$tableName = "t_inventory_fifo";
		$columnName = "pwbilldetail_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$tableName = "t_inventory_fifo_detail";
		$columnName = "pwbilldetail_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151108_01($db) {
		// 本次更新：基础数据在业务单据中的使用权限
		$fid = "-8999-01";
		$name = "组织机构在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$fid = "-8999-02";
		$name = "业务员在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$fid = "1001-01";
		$name = "商品在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$fid = "1003-01";
		$name = "仓库在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$fid = "1004-01";
		$name = "供应商档案在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$fid = "1007-01";
		$name = "客户资料在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_fid where fid = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('%s', '%s')";
			$db->execute($sql, $fid, $name);
		}
		
		$fid = "-8999-01";
		$name = "组织机构在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
		
		$fid = "-8999-02";
		$name = "业务员在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
		
		$fid = "1001-01";
		$name = "商品在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
		
		$fid = "1003-01";
		$name = "仓库在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
		
		$fid = "1004-01";
		$name = "供应商档案在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
		
		$fid = "1007-01";
		$name = "客户资料在业务单据中的使用权限";
		$sql = "select count(*) as cnt from t_permission where id = '%s' ";
		$data = $db->query($sql, $fid);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('%s', '%s', '%s', '%s')";
			$db->execute($sql, $fid, $fid, $name, $name);
		}
	}

	private function update_20151106_02($db) {
		// 本次更新：业务设置去掉仓库设置组织结构；增加存货计价方法
		$sql = "delete from t_config where id = '1003-01' ";
		$db->execute($sql);
		
		$sql = "select count(*) as cnt from t_config where id = '1003-02' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config(id, name, value, note, show_order)
					values ('1003-02', '存货计价方法', '0', '', 401)";
			$db->execute($sql);
		}
	}

	private function update_20151106_01($db) {
		// 本次更新：先进先出，新增数据库表
		$tableName = "t_inventory_fifo";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_inventory_fifo` (
					  `id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `balance_count` decimal(19,2) NOT NULL,
					  `balance_money` decimal(19,2) NOT NULL,
					  `balance_price` decimal(19,2) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `in_count` decimal(19,2) DEFAULT NULL,
					  `in_money` decimal(19,2) DEFAULT NULL,
					  `in_price` decimal(19,2) DEFAULT NULL,
					  `out_count` decimal(19,2) DEFAULT NULL,
					  `out_money` decimal(19,2) DEFAULT NULL,
					  `out_price` decimal(19,2) DEFAULT NULL,
					  `in_ref` varchar(255) DEFAULT NULL,
					  `in_ref_type` varchar(255) NOT NULL,
					  `warehouse_id` varchar(255) NOT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
					";
			$db->execute($sql);
		}
		
		$tableName = "t_inventory_fifo_detail";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_inventory_fifo_detail` (
					  `id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `balance_count` decimal(19,2) NOT NULL,
					  `balance_money` decimal(19,2) NOT NULL,
					  `balance_price` decimal(19,2) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `in_count` decimal(19,2) DEFAULT NULL,
					  `in_money` decimal(19,2) DEFAULT NULL,
					  `in_price` decimal(19,2) DEFAULT NULL,
					  `out_count` decimal(19,2) DEFAULT NULL,
					  `out_money` decimal(19,2) DEFAULT NULL,
					  `out_price` decimal(19,2) DEFAULT NULL,
					  `warehouse_id` varchar(255) NOT NULL,
					  `data_org` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
					";
			$db->execute($sql);
		}
	}

	private function update_20151105_01($db) {
		// 本次更新： 在途库存、 商品多级分类
		$tableName = "t_inventory";
		$columnName = "afloat_count";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) default null;";
			$db->execute($sql);
		}
		$columnName = "afloat_money";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) default null;";
			$db->execute($sql);
		}
		$columnName = "afloat_price";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) default null;";
			$db->execute($sql);
		}
		
		$tableName = "t_goods_category";
		$columnName = "full_name";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(1000) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151102_01($db) {
		// 本次更新：新增表 t_role_permission_dataorg
		$tableName = "t_role_permission_dataorg";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_role_permission_dataorg` (
					  `role_id` varchar(255) DEFAULT NULL,
					  `permission_id` varchar(255) DEFAULT NULL,
					  `data_org` varchar(255) DEFAULT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
			return;
		}
	}

	private function update_20151031_01($db) {
		// 本次更新：商品 增加备注字段
		$tableName = "t_goods";
		$columnName = "memo";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(500) default null;";
			$db->execute($sql);
		}
	}

	private function update_20151016_01($db) {
		// 本次更新：表结构增加data_org字段
		$tables = array(
				"t_biz_log",
				"t_org",
				"t_role",
				"t_role_permission",
				"t_user",
				"t_warehouse",
				"t_warehouse_org",
				"t_supplier",
				"t_supplier_category",
				"t_goods",
				"t_goods_category",
				"t_goods_unit",
				"t_customer",
				"t_customer_category",
				"t_inventory",
				"t_inventory_detail",
				"t_pw_bill",
				"t_pw_bill_detail",
				"t_payables",
				"t_payables_detail",
				"t_receivables",
				"t_receivables_detail",
				"t_payment",
				"t_ws_bill",
				"t_ws_bill_detail",
				"t_receiving",
				"t_sr_bill",
				"t_sr_bill_detail",
				"t_it_bill",
				"t_it_bill_detail",
				"t_ic_bill",
				"t_ic_bill_detail",
				"t_pr_bill",
				"t_pr_bill_detail",
				"t_goods_si",
				"t_cash",
				"t_cash_detail",
				"t_pre_receiving",
				"t_pre_receiving_detail",
				"t_pre_payment",
				"t_pre_payment_detail",
				"t_po_bill",
				"t_po_bill_detail"
		);
		

		$columnName = "data_org";
		foreach ( $tables as $tableName ) {
			if (! $this->tableExists($db, $tableName)) {
				continue;
			}
			
			if (! $this->columnExists($db, $tableName, $columnName)) {
				$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
				$db->execute($sql);
			}
		}
	}

	private function t_cash($db) {
		$tableName = "t_cash";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_cash` (
					  `id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `biz_date` datetime NOT NULL,
					  `in_money` decimal(19,2) DEFAULT NULL,
					  `out_money` decimal(19,2) DEFAULT NULL,
					  `balance_money` decimal(19,2) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
			return;
		}
	}

	private function t_cash_detail($db) {
		$tableName = "t_cash_detail";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_cash_detail` (
					  `id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `biz_date` datetime NOT NULL,
					  `in_money` decimal(19,2) DEFAULT NULL,
					  `out_money` decimal(19,2) DEFAULT NULL,
					  `balance_money` decimal(19,2) NOT NULL,
					  `ref_number` varchar(255) NOT NULL,
					  `ref_type` varchar(255) NOT NULL,
					  `date_created` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
			return;
		}
	}

	private function t_config($db) {
		$tableName = "t_config";
		
		$columnName = "show_order";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) default null;";
			$db->execute($sql);
			
			$sql = "delete from t_config";
			$db->execute($sql);
		}
		
		// 移走商品双单位
		$sql = "delete from t_config where id = '1001-01'";
		$db->execute($sql);
		
		// 9000-01
		$sql = "select count(*) as cnt from t_config where id = '9000-01' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('9000-01', '公司名称', '', '', 100)";
			$db->execute($sql);
		}
		
		// 9000-02
		$sql = "select count(*) as cnt from t_config where id = '9000-02' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('9000-02', '公司地址', '', '', 101)";
			$db->execute($sql);
		}
		
		// 9000-03
		$sql = "select count(*) as cnt from t_config where id = '9000-03' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('9000-03', '公司电话', '', '', 102)";
			$db->execute($sql);
		}
		
		// 9000-04
		$sql = "select count(*) as cnt from t_config where id = '9000-04' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('9000-04', '公司传真', '', '', 103)";
			$db->execute($sql);
		}
		
		// 9000-05
		$sql = "select count(*) as cnt from t_config where id = '9000-05' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('9000-05', '公司邮编', '', '', 104)";
			$db->execute($sql);
		}
		
		// 2001-01
		$sql = "select count(*) as cnt from t_config where id = '2001-01' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('2001-01', '采购入库默认仓库', '', '', 200)";
			$db->execute($sql);
		}
		
		// 2002-02
		$sql = "select count(*) as cnt from t_config where id = '2002-02' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('2002-02', '销售出库默认仓库', '', '', 300)";
			$db->execute($sql);
		}
		
		// 2002-01
		$sql = "select count(*) as cnt from t_config where id = '2002-01' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('2002-01', '销售出库单允许编辑销售单价', '0', '当允许编辑的时候，还需要给用户赋予权限[销售出库单允许编辑销售单价]', 301)";
			$db->execute($sql);
		}
		
		// 1003-01
		$sql = "select count(*) as cnt from t_config where id = '1003-01' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('1003-01', '仓库需指定组织机构', '0', '当仓库需要指定组织机构的时候，就意味着可以控制仓库的使用人', 401)";
			$db->execute($sql);
		}
		
		// 9001-01
		$sql = "select count(*) as cnt from t_config where id = '9001-01' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('9001-01', '增值税税率', '17', '', 501)";
			$db->execute($sql);
		}
		
		// 9002-01
		$sql = "select count(*) as cnt from t_config where id = '9002-01' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_config (id, name, value, note, show_order)
					values ('9002-01', '产品名称', '佳图进销存PSI', '', 0)";
			$db->execute($sql);
		}
	}

	private function t_customer($db) {
		$tableName = "t_customer";
		
		$columnName = "address";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "address_shipping";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "address_receipt";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "bank_name";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "bank_account";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "tax_number";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "fax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "note";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function t_goods($db) {
		$tableName = "t_goods";
		
		$columnName = "bar_code";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function t_goods_category($db) {
		$tableName = "t_goods_category";
		
		$columnName = "parent_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function t_fid($db) {
		// fid 2024: 现金收支查询
		$sql = "select count(*) as cnt from t_fid where fid = '2024' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('2024', '现金收支查询')";
			$db->execute($sql);
		}
		
		// fid 2025: 预收款管理
		$sql = "select count(*) as cnt from t_fid where fid = '2025' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('2025', '预收款管理')";
			$db->execute($sql);
		}
		
		// fid 2026: 预付款管理
		$sql = "select count(*) as cnt from t_fid where fid = '2026' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('2026', '预付款管理')";
			$db->execute($sql);
		}
		
		// fid 2027: 采购订单
		$sql = "select count(*) as cnt from t_fid where fid = '2027' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('2027', '采购订单')";
			$db->execute($sql);
		}
		
		// fid 2027-01: 采购订单 - 审核
		$sql = "select count(*) as cnt from t_fid where fid = '2027-01' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('2027-01', '采购订单 - 审核/取消审核')";
			$db->execute($sql);
		}
		
		// fid 2027-02: 采购订单 - 生成采购入库单
		$sql = "select count(*) as cnt from t_fid where fid = '2027-02' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_fid(fid, name) values ('2027-02', '采购订单 - 生成采购入库单')";
			$db->execute($sql);
		}
	}

	private function t_goods_si($db) {
		$tableName = "t_goods_si";
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_goods_si` (
					  `id` varchar(255) NOT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `warehouse_id` varchar(255) NOT NULL,
					  `safety_inventory` decimal(19,2) NOT NULL,
					  `inventory_upper` decimal(19,2) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			$db->execute($sql);
			return;
		}
		
		$columnName = "inventory_upper";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} decimal(19,2) default null;";
			$db->execute($sql);
		}
	}

	private function t_menu_item($db) {
		// fid 2024: 现金收支查询
		$sql = "select count(*) as cnt from t_menu_item
				where id = '0603' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item(id, caption, fid, parent_id, show_order)
					values ('0603', '现金收支查询', '2024', '06', 3)";
			$db->execute($sql);
		}
		
		// fid 2025: 预收款管理
		$sql = "select count(*) as cnt from t_menu_item
				where id = '0604' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item(id, caption, fid, parent_id, show_order)
					values ('0604', '预收款管理', '2025', '06', 4)";
			$db->execute($sql);
		}
		
		// fid 2026: 预付款管理
		$sql = "select count(*) as cnt from t_menu_item
				where id = '0605' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item(id, caption, fid, parent_id, show_order)
					values ('0605', '预付款管理', '2026', '06', 5)";
			$db->execute($sql);
		}
		
		// fid 2027: 采购订单
		$sql = "select count(*) as cnt from t_menu_item
				where id = '0200' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_menu_item(id, caption, fid, parent_id, show_order)
					values ('0200', '采购订单', '2027', '02', 0)";
			$db->execute($sql);
		}
	}

	private function t_po_bill($db) {
		$tableName = "t_po_bill";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_po_bill` (
					  `id` varchar(255) NOT NULL,
					  `bill_status` int(11) NOT NULL,
					  `biz_dt` datetime NOT NULL,
					  `deal_date` datetime NOT NULL,
					  `org_id` varchar(255) NOT NULL,
					  `biz_user_id` varchar(255) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `tax` decimal(19,2) NOT NULL,
					  `money_with_tax` decimal(19,2) NOT NULL,
					  `input_user_id` varchar(255) NOT NULL,
					  `ref` varchar(255) NOT NULL,
					  `supplier_id` varchar(255) NOT NULL,
					  `contact` varchar(255) NOT NULL,
					  `tel` varchar(255) DEFAULT NULL,
					  `fax` varchar(255) DEFAULT NULL,
					  `deal_address` varchar(255) DEFAULT NULL,
					  `bill_memo` varchar(255) DEFAULT NULL,
					  `payment_type` int(11) NOT NULL DEFAULT 0,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
		
		$columnName = "confirm_user_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "confirm_date";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} datetime default null;";
			$db->execute($sql);
		}
	}

	private function t_po_bill_detail($db) {
		$tableName = "t_po_bill_detail";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_po_bill_detail` (
					  `id` varchar(255) NOT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `goods_id` varchar(255) NOT NULL,
					  `goods_count` int(11) NOT NULL,
					  `goods_money` decimal(19,2) NOT NULL,
					  `goods_price` decimal(19,2) NOT NULL,
					  `pobill_id` varchar(255) NOT NULL,
					  `tax_rate` decimal(19,2) NOT NULL,
					  `tax` decimal(19,2) NOT NULL,
					  `money_with_tax` decimal(19,2) NOT NULL,
					  `pw_count` int(11) NOT NULL,
					  `left_count` int(11) NOT NULL,
					  `show_order` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
	}

	private function t_po_pw($db) {
		$tableName = "t_po_pw";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_po_pw` (
					  `po_id` varchar(255) NOT NULL,
					  `pw_id` varchar(255) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
	}

	private function t_pr_bill($db) {
		$tableName = "t_pr_bill";
		
		$columnName = "receiving_type";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) not null default 0;";
			$db->execute($sql);
		}
	}

	private function t_pre_payment($db) {
		$tableName = "t_pre_payment";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_pre_payment` (
					  `id` varchar(255) NOT NULL,
					  `supplier_id` varchar(255) NOT NULL,
					  `in_money` decimal(19,2) DEFAULT NULL,
					  `out_money` decimal(19,2) DEFAULT NULL,
					  `balance_money` decimal(19,2) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
	}

	private function t_pre_payment_detail($db) {
		$tableName = "t_pre_payment_detail";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_pre_payment_detail` (
					  `id` varchar(255) NOT NULL,
					  `supplier_id` varchar(255) NOT NULL,
					  `in_money` decimal(19,2) DEFAULT NULL,
					  `out_money` decimal(19,2) DEFAULT NULL,
					  `balance_money` decimal(19,2) NOT NULL,
					  `biz_date` datetime DEFAULT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `ref_number` varchar(255) NOT NULL,
					  `ref_type` varchar(255) NOT NULL,
					  `biz_user_id` varchar(255) NOT NULL,
					  `input_user_id` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
	}

	private function t_pre_receiving($db) {
		$tableName = "t_pre_receiving";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_pre_receiving` (
					  `id` varchar(255) NOT NULL,
					  `customer_id` varchar(255) NOT NULL,
					  `in_money` decimal(19,2) DEFAULT NULL,
					  `out_money` decimal(19,2) DEFAULT NULL,
					  `balance_money` decimal(19,2) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
	}

	private function t_pre_receiving_detail($db) {
		$tableName = "t_pre_receiving_detail";
		
		if (! $this->tableExists($db, $tableName)) {
			$sql = "CREATE TABLE IF NOT EXISTS `t_pre_receiving_detail` (
					  `id` varchar(255) NOT NULL,
					  `customer_id` varchar(255) NOT NULL,
					  `in_money` decimal(19,2) DEFAULT NULL,
					  `out_money` decimal(19,2) DEFAULT NULL,
					  `balance_money` decimal(19,2) NOT NULL,
					  `biz_date` datetime DEFAULT NULL,
					  `date_created` datetime DEFAULT NULL,
					  `ref_number` varchar(255) NOT NULL,
					  `ref_type` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					";
			$db->execute($sql);
		}
		
		$columnName = "biz_user_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) not null;";
			$db->execute($sql);
		}
		
		$columnName = "input_user_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) not null;";
			$db->execute($sql);
		}
	}

	private function t_permission($db) {
		// fid 2024: 现金收支查询
		$sql = "select count(*) as cnt from t_permission where id = '2024' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('2024', '2024', '现金收支查询', '现金收支查询')";
			$db->execute($sql);
		}
		
		// fid 2025: 预收款管理
		$sql = "select count(*) as cnt from t_permission where id = '2025' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('2025', '2025', '预收款管理', '预收款管理')";
			$db->execute($sql);
		}
		
		// fid 2026: 预付款管理
		$sql = "select count(*) as cnt from t_permission where id = '2026' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('2026', '2026', '预付款管理', '预付款管理')";
			$db->execute($sql);
		}
		
		// fid 2027: 采购订单
		$sql = "select count(*) as cnt from t_permission where id = '2027' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('2027', '2027', '采购订单', '采购订单')";
			$db->execute($sql);
		}
		
		// fid 2027-01: 采购订单 - 审核/取消审核
		$sql = "select count(*) as cnt from t_permission where id = '2027-01' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('2027-01', '2027-01', '采购订单 - 审核/取消审核', '采购订单 - 审核/取消审核')";
			$db->execute($sql);
		}
		
		// fid 2027-02: 采购订单 - 生成采购入库单
		$sql = "select count(*) as cnt from t_permission where id = '2027-02' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_permission(id, fid, name, note)
					values ('2027-02', '2027-02', '采购订单 - 生成采购入库单', '采购订单 - 生成采购入库单')";
			$db->execute($sql);
		}
	}

	private function t_pw_bill($db) {
		$tableName = "t_pw_bill";
		
		$columnName = "payment_type";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) not null default 0;";
			$db->execute($sql);
		}
	}

	private function t_role_permission($db) {
		// fid 2024: 现金收支查询
		$sql = "select count(*) as cnt from t_role_permission 
				where permission_id = '2024' and role_id = 'A83F617E-A153-11E4-A9B8-782BCBD7746B' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_role_permission(role_id, permission_id)
					values ('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2024')";
			$db->execute($sql);
		}
		
		// fid 2025: 预收款管理
		$sql = "select count(*) as cnt from t_role_permission 
				where permission_id = '2025' and role_id = 'A83F617E-A153-11E4-A9B8-782BCBD7746B' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_role_permission(role_id, permission_id)
					values ('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2025')";
			$db->execute($sql);
		}
		
		// fid 2026: 预付款管理
		$sql = "select count(*) as cnt from t_role_permission 
				where permission_id = '2026' and role_id = 'A83F617E-A153-11E4-A9B8-782BCBD7746B' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_role_permission(role_id, permission_id)
					values ('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2026')";
			$db->execute($sql);
		}
		
		// fid 2027: 采购订单
		$sql = "select count(*) as cnt from t_role_permission 
				where permission_id = '2027' and role_id = 'A83F617E-A153-11E4-A9B8-782BCBD7746B' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_role_permission(role_id, permission_id)
					values ('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2027')";
			$db->execute($sql);
		}
		
		// fid 2027-01: 采购订单 - 审核/取消审核
		$sql = "select count(*) as cnt from t_role_permission 
				where permission_id = '2027-01' and role_id = 'A83F617E-A153-11E4-A9B8-782BCBD7746B' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_role_permission(role_id, permission_id)
					values ('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2027-01')";
			$db->execute($sql);
		}
		
		// fid 2027-02: 采购订单 - 生成采购入库单
		$sql = "select count(*) as cnt from t_role_permission 
				where permission_id = '2027-02' and role_id = 'A83F617E-A153-11E4-A9B8-782BCBD7746B' ";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		if ($cnt == 0) {
			$sql = "insert into t_role_permission(role_id, permission_id)
					values ('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2027-02')";
			$db->execute($sql);
		}
	}

	private function t_supplier($db) {
		$tableName = "t_supplier";
		
		$columnName = "address";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "address_shipping";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "address_receipt";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "bank_name";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "bank_account";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "tax_number";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "fax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "note";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function t_supplier_category($db) {
		$tableName = "t_supplier_category";
		
		$columnName = "parent_id";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function t_sr_bill($db) {
		$tableName = "t_sr_bill";
		
		$columnName = "payment_type";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) not null default 0;";
			$db->execute($sql);
		}
	}

	private function t_sr_bill_detail($db) {
		$tableName = "t_sr_bill_detail";
		
		$columnName = "sn_note";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function t_ws_bill($db) {
		$tableName = "t_ws_bill";
		
		$columnName = "receiving_type";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} int(11) not null default 0;";
			$db->execute($sql);
		}
	}

	private function t_ws_bill_detail($db) {
		$tableName = "t_ws_bill_detail";
		
		$columnName = "sn_note";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}
	
}