<?php

namespace Home\DAO;
use Home\Service\UserService;
use Home\Common\FIdConst;
use Home\Common\DateHelper;

use Org\Util\Date;

/**
 * 销售报表 DAO
 *
 * @author JIATU
 */
class SaleReportDAO extends PSIBaseExDAO {

	/**
	 * 销售日报表(按商品汇总) - 查询数据
	 *
	 * @param array $params        	
	 */
	public function saleDayByGoodsQueryData($params) {
		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$companyId = $params["companyId"];
		$loginUserId = $us->getLoginUserId();
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
	
		$page = $params["page"];
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData = $limit == - 1;
		
		$dt = $params["dt"];
		
		$sort = $params["sort"];
		$sortProperty = "goods_code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("goodsCode")) {
					$sortProperty = "goods_code";
				} else if ($sortProperty == strtolower("saleMoney")) {
					$sortProperty = "sale_money";
				} else if ($sortProperty == strtolower("saleCount")) {
					$sortProperty = "sale_count";
				} else if ($sortProperty == strtolower("rejMoney")) {
					$sortProperty = "rej_money";
				} else if ($sortProperty == strtolower("rejCount")) {
					$sortProperty = "rej_count";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		$result = [];
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_report (
					biz_dt datetime,
					goods_id varchar(255), goods_code varchar(255), goods_name varchar(255), goods_spec varchar(255), 
					unit_name varchar(255), sale_money decimal(19,2), sale_count decimal(19,8),
					rej_money decimal(19,2), rej_count decimal(19, 8), m decimal(19,2), c decimal(19,8),
					profit decimal(19,2), rate decimal(19, 2)
				)";
		$db->execute($sql);
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);

		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_DAY_BY_GOODS, "s", $loginUserId);
	
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$sql = "select g.id, g.code, g.name, g.spec, u.name as unit_name
				from t_goods g join  t_goods_unit u  on g.unit_id = u.id join (select  distinct  ot.goods_id from (
					select distinct d.goods_id
					from t_ws_bill s, t_ws_bill_detail d
					where s.id = d.wsbill_id and s.bizdt = '%s' and s.bill_status >= 1000
						and s.company_id = '%s' and ".$rsStr."
					union
					select distinct d.goods_id
					from t_sr_bill s, t_sr_bill_detail d
					where s.id = d.srbill_id and s.bizdt = '%s' and s.bill_status = 1000
						and s.company_id = '%s' and ".$rsStr."
					)ot )T on g.id = T.goods_id
					order by g.code";
		$items = $db->query($sql, $dt, $companyId, $dt, $companyId);
		
		foreach ( $items as $v ) {
			$goodsId = $v["id"];
			$goodsCode = $v["code"];
			$goodsName = $v["name"];
			$goodsSpec = $v["spec"];
			$unitName = $v["unit_name"];
			
			$sql = "select sum(d.goods_money) as goods_money, sum(d.inventory_money) as inventory_money,
						sum(convert(d.goods_count, $fmt)) as goods_count
					from t_ws_bill s, t_ws_bill_detail d
					where s.id = d.wsbill_id and s.bizdt = '%s' and d.goods_id = '%s'
					and s.bill_status >= 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $dt, $goodsId, $companyId);
			$saleCount = $data[0]["goods_count"];
			if (! $saleCount) {
				$saleCount = 0;
			}
			$saleMoney = $data[0]["goods_money"];
			if (! $saleMoney) {
				$saleMoney = 0;
			}
			$saleInventoryMoney = $data[0]["inventory_money"];
			if (! $saleInventoryMoney) {
				$saleInventoryMoney = 0;
			}
			
			$sql = "select sum(convert(d.rejection_goods_count, $fmt)) as rej_count,
						sum(d.rejection_sale_money) as rej_money,
						sum(d.inventory_money) as rej_inventory_money
					from t_sr_bill s, t_sr_bill_detail d
					where s.id = d.srbill_id and s.bizdt = '%s' and d.goods_id = '%s'
						and s.bill_status = 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $dt, $goodsId, $companyId);
			$rejCount = $data[0]["rej_count"];
			if (! $rejCount) {
				$rejCount = 0;
			}
			$rejSaleMoney = $data[0]["rej_money"];
			if (! $rejSaleMoney) {
				$rejSaleMoney = 0;
			}
			$rejInventoryMoney = $data[0]["rej_inventory_money"];
			if (! $rejInventoryMoney) {
				$rejInventoryMoney = 0;
			}
			
			$c = $saleCount - $rejCount;
			$m = $saleMoney - $rejSaleMoney;
			$c = number_format($c, $dataScale, ".", "");
			$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
			$rate = 0;
			if ($m > 0) {
				$rate = $profit / $m * 100;
			}
			
			$sql = "insert into psi_sale_report (biz_dt, goods_code, goods_name, goods_spec, unit_name, 
						sale_money, sale_count, rej_money, rej_count, m, c, profit, rate)
					values ('%s', '%s', '%s', '%s', '%s', 
							%f, %f, %f, %f, %f, %f, %f, %f)";
			$db->execute($sql, $dt, $goodsCode, $goodsName, $goodsSpec, $unitName, $saleMoney, 
					$saleCount, $rejSaleMoney, $rejCount, $m, $c, $profit, $rate);
		}
		
		$sql = "select biz_dt, goods_code, goods_name, goods_spec, unit_name,
					sale_money, convert(sale_count, $fmt) as sale_count, rej_money, 
					convert(rej_count, $fmt) as rej_count, m, convert(c, $fmt) as c, profit, rate 
				from psi_sale_report
				order by %s %s ";
		if (! $showAllData) {
			$sql .= " limit %d, %d";
		}
		if ($showAllData) {
			$data = $db->query($sql, $sortProperty, $sortDirection);
		} else {
			// 分页
			$data = $db->query($sql, $sortProperty, $sortDirection, $start, $limit);
		}
		foreach ( $data as $v ) {
			$result[] = [
					"bizDT" => $this->toYMD($v["biz_dt"]),
					"goodsCode" => $v["goods_code"],
					"goodsName" => $v["goods_name"],
					"goodsSpec" => $v["goods_spec"],
					"saleCount" => $v["sale_count"],
					"unitName" => $v["unit_name"],
					"saleMoney" => $v["sale_money"],
					"rejCount" => $v["rej_count"],
					"rejMoney" => $v["rej_money"],
					"c" => $v["c"],
					"m" => $v["m"],
					"profit" => $v["profit"],
					"rate" => $v["rate"] == 0 ? null : sprintf("%0.2f", $v["rate"]) . "%"
			];
		}
		
		$sql = "select count(*) as cnt
				from psi_sale_report
				";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_report";
		$db->execute($sql);
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}
	/*
	 * 销售日报表(按客户汇总) - 查询数据
	 */
	public function saleDayByCustomerQueryData($params) {
		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$loginUserId = $us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_DAY_BY_CUSTOMER, "s", $loginUserId);
	
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData = $limit == - 1;
		
		$dt = $params["dt"];
		
		$sort = $params["sort"];
		$sortProperty = "customer_code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("customerCode")) {
					$sortProperty = "customer_code";
				} else if ($sortProperty == strtolower("saleMoney")) {
					$sortProperty = "sale_money";
				} else if ($sortProperty == strtolower("rejMoney")) {
					$sortProperty = "rej_money";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_report (
					biz_dt datetime,
					customer_code varchar(255), customer_name varchar(255),
					sale_money decimal(19,2),
					rej_money decimal(19,2), m decimal(19,2),
					profit decimal(19,2), rate decimal(19, 2)
				)";
		$db->execute($sql);
		
		$result = [];
		
		$sql = "select c.id, c.code, c.name
				from t_customer c
				join (select distinct ot.customer_id from(
					select distinct s.customer_id
					from t_ws_bill s
					where s.bizdt = '%s' and s.bill_status >= 1000
						and s.company_id = '%s' and ".$rsStr."
					union
					select distinct s.customer_id
					from t_sr_bill s
					where s.bizdt = '%s' and s.bill_status = 1000
						and s.company_id = '%s' and ".$rsStr."
					)ot )T on c.id = T.customer_id
				order by c.code";
		$items = $db->query($sql, $dt, $companyId, $dt, $companyId);
		foreach ( $items as $v ) {
			$customerCode = $v["code"];
			$customerName = $v["name"];
			
			$customerId = $v["id"];
			$sql = "select sum(s.sale_money) as goods_money, sum(s.inventory_money) as inventory_money
					from t_ws_bill s
					where s.bizdt = '%s' and s.customer_id = '%s'
						and s.bill_status >= 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $dt, $customerId, $companyId);
			$saleMoney = $data[0]["goods_money"];
			if (! $saleMoney) {
				$saleMoney = 0;
			}
			$saleInventoryMoney = $data[0]["inventory_money"];
			if (! $saleInventoryMoney) {
				$saleInventoryMoney = 0;
			}
			
			$sql = "select sum(s.rejection_sale_money) as rej_money,
						sum(s.inventory_money) as rej_inventory_money
					from t_sr_bill s
					where s.bizdt = '%s' and s.customer_id = '%s'
						and s.bill_status = 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $dt, $customerId, $companyId);
			$rejSaleMoney = $data[0]["rej_money"];
			if (! $rejSaleMoney) {
				$rejSaleMoney = 0;
			}
			$rejInventoryMoney = $data[0]["rej_inventory_money"];
			if (! $rejInventoryMoney) {
				$rejInventoryMoney = 0;
			}
			
			$m = $saleMoney - $rejSaleMoney;
			$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
			$rate = 0;
			if ($m > 0) {
				$rate = $profit / $m * 100;
			}
			
			$sql = "insert into psi_sale_report (biz_dt, customer_code, customer_name,
						sale_money, rej_money, m, profit, rate)
					values ('%s', '%s', '%s',
							%f, %f, %f, %f, %f)";
			$db->execute($sql, $dt, $customerCode, $customerName, $saleMoney, $rejSaleMoney, $m, 
					$profit, $rate);
		}
		
		$sql = "select biz_dt, customer_code, customer_name,
					sale_money, rej_money,
					m, profit, rate
				from psi_sale_report
				order by %s %s ";
		if (! $showAllData) {
			$sql .= " limit %d, %d";
		}
		
		if ($showAllData) {
			$data = $db->query($sql, $sortProperty, $sortDirection);
		} else {
			$data = $db->query($sql, $sortProperty, $sortDirection, $start, $limit);
		}
		
		foreach ( $data as $v ) {
			$result[] = [
					"bizDT" => $this->toYMD($v["biz_dt"]),
					"customerCode" => $v["customer_code"],
					"customerName" => $v["customer_name"],
					"saleMoney" => $v["sale_money"],
					"rejMoney" => $v["rej_money"],
					"m" => $v["m"],
					"profit" => $v["profit"],
					"rate" => $v["rate"] == 0 ? null : sprintf("%0.2f", $v["rate"]) . "%"
			];
		}
		
		$sql = "select count(*) as cnt
				from psi_sale_report
				";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_report";
		$db->execute($sql);
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 销售日报表(按仓库汇总) - 查询数据
	 */
	public function saleDayByWarehouseQueryData($params) {
		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$loginUserId = $us->getLoginUserId();
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_DAY_BY_WAREHOUSE, "s", $loginUserId);
	
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData = $limit == - 1;
		
		$dt = $params["dt"];
		
		$sort = $params["sort"];
		$sortProperty = "warehouse_code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("warehouseCode")) {
					$sortProperty = "warehouse_code";
				} else if ($sortProperty == strtolower("saleMoney")) {
					$sortProperty = "sale_money";
				} else if ($sortProperty == strtolower("rejMoney")) {
					$sortProperty = "rej_money";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_report (
					biz_dt datetime,
					warehouse_code varchar(255), warehouse_name varchar(255),
					sale_money decimal(19,2),
					rej_money decimal(19,2), m decimal(19,2),
					profit decimal(19,2), rate decimal(19, 2)
				)";
		$db->execute($sql);
		
		$sql = "select w.id, w.code, w.name
				from t_warehouse w
				where w.id in(
					select distinct s.warehouse_id
					from t_ws_bill s
					where s.bizdt = '%s' and s.bill_status >= 1000
						and s.company_id = '%s' and  ".$rsStr."
					union
					select distinct s.warehouse_id
					from t_sr_bill s
					where s.bizdt = '%s' and s.bill_status = 1000
						and s.company_id = '%s' and ".$rsStr."
					)
				order by w.code ";
		$items = $db->query($sql, $dt, $companyId, $dt, $companyId);
		foreach ( $items as $i => $v ) {
			$warehouseCode = $v["code"];
			$warehouseName = $v["name"];
			
			$warehouseId = $v["id"];
			$sql = "select sum(s.sale_money) as goods_money, sum(s.inventory_money) as inventory_money
					from t_ws_bill s
					where s.bizdt = '%s' and s.warehouse_id = '%s'
						and s.bill_status >= 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $dt, $warehouseId, $companyId);
			$saleMoney = $data[0]["goods_money"];
			if (! $saleMoney) {
				$saleMoney = 0;
			}
			$saleInventoryMoney = $data[0]["inventory_money"];
			if (! $saleInventoryMoney) {
				$saleInventoryMoney = 0;
			}
			
			$sql = "select sum(s.rejection_sale_money) as rej_money,
						sum(s.inventory_money) as rej_inventory_money
					from t_sr_bill s
					where s.bizdt = '%s' and s.warehouse_id = '%s'
						and s.bill_status = 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $dt, $warehouseId, $companyId);
			$rejSaleMoney = $data[0]["rej_money"];
			if (! $rejSaleMoney) {
				$rejSaleMoney = 0;
			}
			$rejInventoryMoney = $data[0]["rej_inventory_money"];
			if (! $rejInventoryMoney) {
				$rejInventoryMoney = 0;
			}
			
			$m = $saleMoney - $rejSaleMoney;
			$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
			$rate = 0;
			if ($m > 0) {
				$rate = $profit / $m * 100;
			}
			$sql = "insert into psi_sale_report (biz_dt, warehouse_code, warehouse_name,
						sale_money, rej_money, m, profit, rate)
					values ('%s', '%s', '%s',
							%f, %f, %f, %f, %f)";
			$db->execute($sql, $dt, $warehouseCode, $warehouseName, $saleMoney, $rejSaleMoney, $m, 
					$profit, $rate);
		}
		
		$result = [];
		$sql = "select biz_dt, warehouse_code, warehouse_name,
					sale_money, rej_money,
					m, profit, rate
				from psi_sale_report
				order by %s %s ";
		if (! $showAllData) {
			$sql .= " limit %d, %d ";
		}
		if ($showAllData) {
			$data = $db->query($sql, $sortProperty, $sortDirection);
		} else {
			$data = $db->query($sql, $sortProperty, $sortDirection, $start, $limit);
		}
		foreach ( $data as $v ) {
			$result[] = [
					"bizDT" => $this->toYMD($v["biz_dt"]),
					"warehouseCode" => $v["warehouse_code"],
					"warehouseName" => $v["warehouse_name"],
					"saleMoney" => $v["sale_money"],
					"rejMoney" => $v["rej_money"],
					"m" => $v["m"],
					"profit" => $v["profit"],
					"rate" => $v["rate"] == 0 ? null : sprintf("%0.2f", $v["rate"]) . "%"
			];
		}
		
		$sql = "select count(*) as cnt
				from psi_sale_report
				";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_report";
		$db->execute($sql);
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 销售月报表(按商品汇总) - 查询数据
	 */
	public function saleMonthByGoodsQueryData($params) {
		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$loginUserId = $us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_MONTH_BY_GOODS, "s", $loginUserId);
	
	
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData = $limit == - 1;
		
		$userId = $params["userId"];
		$userSql = " and 1=1 ";
		if($userId)
		{
			$userSql = " and  s.biz_user_id = '".$userId."' ";
		}

		$sort = $params["sort"];
		$sortProperty = "goods_code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("goodsCode")) {
					$sortProperty = "goods_code";
				} else if ($sortProperty == strtolower("saleMoney")) {
					$sortProperty = "sale_money";
				} else if ($sortProperty == strtolower("saleCount")) {
					$sortProperty = "sale_count";
				} else if ($sortProperty == strtolower("rejMoney")) {
					$sortProperty = "rej_money";
				} else if ($sortProperty == strtolower("rejCount")) {
					$sortProperty = "rej_count";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		$year = $params["year"];
		$month = $params["month"];
		
		$dt = "";
		if ($month < 10) {
			$dt = "$year-0$month";
		} else {
			$dt = "$year-$month";
		}
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_report (
					biz_dt varchar(255),
					goods_id varchar(255), goods_code varchar(255), goods_name varchar(255), goods_spec varchar(255),
					unit_name varchar(255), sale_money decimal(19,2), sale_count decimal(19,8),inventory_money decimal(19,2), 
					rej_money decimal(19,2),rej_inventory_money decimal(19,2), rej_count decimal(19, 8), m decimal(19,2), c decimal(19,8),
					profit decimal(19,2), rate decimal(19, 2)
				)";
		$db->execute($sql);
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$sql = " insert into psi_sale_report (biz_dt, goods_code, goods_name, goods_spec, unit_name,sale_money, sale_count, rej_money, rej_count,inventory_money,rej_inventory_money ,m, c, profit, rate) 
				select '".$dt."',G.code,G.name,G.spec,G.unit_name,T1.goods_money,T1.goods_count,IFNULL(T2.rej_money,0)  rej_money,IFNULL(T2.rej_count,0) rej_count,T1.inventory_money,T2.rej_inventory_money,0, 0,0,0 
				from (select  g.id, g.code, g.name, g.spec, u.name as unit_name	from t_goods g join t_goods_unit u on  g.unit_id = u.id join ( select distinct  ot.goods_id from (	select distinct d.goods_id	from t_ws_bill s, t_ws_bill_detail d	
				where s.id = d.wsbill_id and year(s.bizdt) = %d and month(s.bizdt) = %d	and s.bill_status >= 1000	and s.company_id = '%s' and ".$rsStr.$userSql."	union"." ".	"select distinct d.goods_id	from t_sr_bill s, t_sr_bill_detail d	
				where s.id = d.srbill_id and year(s.bizdt) = %d and month(s.bizdt) = %d	and s.bill_status = 1000	and s.company_id = '%s'		and  ".$rsStr.$userSql."	)ot ) T on g.id = T.goods_id)G	
				left join (select  IFNULL(sum(d.goods_money),0) as goods_money,  IFNULL(sum(d.inventory_money),0) as inventory_money,	IFNULL(sum(convert(d.goods_count, ".$fmt.")),0) as goods_count,d.goods_id	from t_ws_bill s, t_ws_bill_detail d	
				where s.id = d.wsbill_id and year(s.bizdt) = %d and month(s.bizdt) = %d	and s.bill_status >= 1000 and s.company_id = '%s' and  ".$rsStr.$userSql." group by d.goods_id)T1	on G.id = T1.goods_id	
				left join (select  IFNULL(sum(convert(d.rejection_goods_count, ".$fmt.")),0) as rej_count,	IFNULL(sum(d.rejection_sale_money),0) as rej_money,	IFNULL(sum(d.inventory_money),0) as rej_inventory_money,d.goods_id	from t_sr_bill s, t_sr_bill_detail d	
				where s.id = d.srbill_id and year(s.bizdt) = %d and month(s.bizdt) = %d	and s.bill_status = 1000 and s.company_id = '%s' and  ".$rsStr.$userSql." group by d.goods_id)T2	 on T2.goods_id = G.id";					
		$db->execute($sql, $year, $month, $companyId, $year, $month, $companyId, $year, $month,  $companyId, $year, $month, $companyId);

		$sql = "select biz_dt, goods_code, goods_name, goods_spec, unit_name,
					sale_money, convert(sale_count, $fmt) as sale_count, rej_money,inventory_money,rej_inventory_money,
					convert(rej_count, $fmt) as rej_count, (sale_money-rej_money) as m, convert(sale_count-rej_count, $fmt) as c, profit, rate
				from psi_sale_report
				order by %s %s ";
		if (! $showAllData) {
			$sql .= " limit %d, %d ";
		}
		if ($showAllData) {
			$data = $db->query($sql, $sortProperty, $sortDirection);
		} else {
			$data = $db->query($sql, $sortProperty, $sortDirection, $start, $limit);
		}
		$result = [];
		foreach ( $data as $v ) {
			$profit=$v["sale_money"] -  $v["rej_money"] -$v["inventory_money"] +$v["rej_inventory_money"];
			$m =$v["sale_money"] -  $v["rej_money"];
			$rate = 0;
			if ($m > 0) {
				$rate = $profit / $m * 100;
			}
			$result[] = [
					"bizDT" => $v["biz_dt"],
					"goodsCode" => $v["goods_code"],
					"goodsName" => $v["goods_name"],
					"goodsSpec" => $v["goods_spec"],
					"saleCount" => $v["sale_count"],
					"unitName" => $v["unit_name"],
					"saleMoney" => $v["sale_money"],
					"rejCount" => $v["rej_count"],
					"rejMoney" => $v["rej_money"],
					"c" => $v["c"],
					"m" => $v["m"],
					"profit" =>$profit,
					"rate" =>$rate == 0 ? null : sprintf("%0.2f", $rate) . "%"
			];
		}
		
		$sql = "select count(*) as cnt
				from psi_sale_report
				";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_report";
		$db->execute($sql);
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 销售月报表(按客户汇总) - 查询数据
	 */
	public function saleMonthByCustomerQueryData($params) {
		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$loginUserId = $us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_MONTH_BY_CUSTOMER, "s", $loginUserId);
	
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData = $limit == - 1;
		
		$year = $params["year"];
		$month = $params["month"];
		
		$sort = $params["sort"];
		$sortProperty = "customer_code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("customerCode")) {
					$sortProperty = "customer_code";
				} else if ($sortProperty == strtolower("saleMoney")) {
					$sortProperty = "sale_money";
				} else if ($sortProperty == strtolower("rejMoney")) {
					$sortProperty = "rej_money";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_report (
					biz_dt varchar(255),
					customer_code varchar(255), customer_name varchar(255),
					sale_money decimal(19,2),
					rej_money decimal(19,2), m decimal(19,2),
					profit decimal(19,2), rate decimal(19, 2)
				)";
		$db->execute($sql);
		
		if ($month < 10) {
			$dt = "$year-0$month";
		} else {
			$dt = "$year-$month";
		}
		
		$sql = "select c.id, c.code, c.name
				from t_customer c
				join (select distinct ot.customer_id from (
					select distinct s.customer_id
					from t_ws_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.bill_status >= 1000 and s.company_id = '%s' and  ".$rsStr."
					union
					select distinct s.customer_id
					from t_sr_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.bill_status = 1000 and s.company_id = '%s'  and ".$rsStr."
					)ot )T on c.id = T.customer_id
				order by c.code ";
		$items = $db->query($sql, $year, $month, $companyId,$year, $month, $companyId);
		foreach ( $items as $i => $v ) {
			
			$customerCode = $v["code"];
			$customerName = $v["name"];
			
			$customerId = $v["id"];
			$sql = "select sum(s.sale_money) as goods_money, sum(s.inventory_money) as inventory_money
					from t_ws_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.customer_id = '%s'
						and s.bill_status >= 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $year, $month, $customerId, $companyId);
			$saleMoney = $data[0]["goods_money"];
			if (! $saleMoney) {
				$saleMoney = 0;
			}
			$saleInventoryMoney = $data[0]["inventory_money"];
			if (! $saleInventoryMoney) {
				$saleInventoryMoney = 0;
			}
			
			$sql = "select sum(s.rejection_sale_money) as rej_money,
						sum(s.inventory_money) as rej_inventory_money
					from t_sr_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.customer_id = '%s'
						and s.bill_status = 1000 and s.company_id = '%s' and  ".$rsStr." ";
			$data = $db->query($sql, $year, $month, $customerId, $companyId);
			$rejSaleMoney = $data[0]["rej_money"];
			if (! $rejSaleMoney) {
				$rejSaleMoney = 0;
			}
			$rejInventoryMoney = $data[0]["rej_inventory_money"];
			if (! $rejInventoryMoney) {
				$rejInventoryMoney = 0;
			}
			
			$m = $saleMoney - $rejSaleMoney;
			$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
			$rate = 0;
			if ($m > 0) {
				$rate = $profit / $m * 100;
			}
			
			$sql = "insert into psi_sale_report (biz_dt, customer_code, customer_name,
						sale_money, rej_money, m, profit, rate)
					values ('%s', '%s', '%s',
							%f, %f, %f, %f, %f)";
			$db->execute($sql, $dt, $customerCode, $customerName, $saleMoney, $rejSaleMoney, $m, 
					$profit, $rate);
		}
		
		$result = [];
		$sql = "select biz_dt, customer_code, customer_name,
					sale_money, rej_money,
					m, profit, rate
				from psi_sale_report
				order by %s %s ";
		if (! $showAllData) {
			$sql .= " limit %d, %d ";
		}
		if ($showAllData) {
			$data = $db->query($sql, $sortProperty, $sortDirection);
		} else {
			$data = $db->query($sql, $sortProperty, $sortDirection, $start, $limit);
		}
		foreach ( $data as $v ) {
			$result[] = [
					"bizDT" => $v["biz_dt"],
					"customerCode" => $v["customer_code"],
					"customerName" => $v["customer_name"],
					"saleMoney" => $v["sale_money"],
					"rejMoney" => $v["rej_money"],
					"m" => $v["m"],
					"profit" => $v["profit"],
					"rate" => $v["rate"] == 0 ? null : sprintf("%0.2f", $v["rate"]) . "%"
			];
		}
		
		$sql = "select count(*) as cnt
				from psi_sale_report
				";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_report";
		$db->execute($sql);
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 销售月报表(按仓库汇总) - 查询数据
	 */
	public function saleMonthByWarehouseQueryData($params) {
		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$loginUserId = $us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_MONTH_BY_WAREHOUSE, "s", $loginUserId);
	
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData = $limit == - 1;
		
		$year = $params["year"];
		$month = $params["month"];
		$dt = "";
		if ($month < 10) {
			$dt = "$year-0$month";
		} else {
			$dt = "$year-$month";
		}
		
		$sort = $params["sort"];
		$sortProperty = "warehouse_code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("warehouseCode")) {
					$sortProperty = "warehouse_code";
				} else if ($sortProperty == strtolower("saleMoney")) {
					$sortProperty = "sale_money";
				} else if ($sortProperty == strtolower("rejMoney")) {
					$sortProperty = "rej_money";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_report (
					biz_dt varchar(255),
					warehouse_code varchar(255), warehouse_name varchar(255),
					sale_money decimal(19,2),
					rej_money decimal(19,2), m decimal(19,2),
					profit decimal(19,2), rate decimal(19, 2)
				)";
		$db->execute($sql);
		
		$sql = "select w.id, w.code, w.name
				from t_warehouse w
				where w.id in(
					select distinct s.warehouse_id
					from t_ws_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.bill_status >= 1000 and s.company_id = '%s' 
						and  ".$rsStr."
					union
					select distinct s.warehouse_id
					from t_sr_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.bill_status = 1000 and s.company_id = '%s' 
						and ".$rsStr."
					)
				order by w.code ";
		$items = $db->query($sql, $year, $month, $companyId, $year, $month, $companyId);
		foreach ( $items as $v ) {
			$warehouseCode = $v["code"];
			$warehouseName = $v["name"];
			
			$warehouseId = $v["id"];
			$sql = "select sum(s.sale_money) as goods_money, sum(s.inventory_money) as inventory_money
					from t_ws_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.warehouse_id = '%s'
						and s.bill_status >= 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $year, $month, $warehouseId, $companyId);
			$saleMoney = $data[0]["goods_money"];
			if (! $saleMoney) {
				$saleMoney = 0;
			}
			$saleInventoryMoney = $data[0]["inventory_money"];
			if (! $saleInventoryMoney) {
				$saleInventoryMoney = 0;
			}
			
			$sql = "select sum(s.rejection_sale_money) as rej_money,
						sum(s.inventory_money) as rej_inventory_money
					from t_sr_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.warehouse_id = '%s'
						and s.bill_status = 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $year, $month, $warehouseId, $companyId);
			$rejSaleMoney = $data[0]["rej_money"];
			if (! $rejSaleMoney) {
				$rejSaleMoney = 0;
			}
			$rejInventoryMoney = $data[0]["rej_inventory_money"];
			if (! $rejInventoryMoney) {
				$rejInventoryMoney = 0;
			}
			
			$m = $saleMoney - $rejSaleMoney;
			$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
			$rate = 0;
			if ($m > 0) {
				$rate = $profit / $m * 100;
			}
			$sql = "insert into psi_sale_report (biz_dt, warehouse_code, warehouse_name,
						sale_money, rej_money, m, profit, rate)
					values ('%s', '%s', '%s',
							%f, %f, %f, %f, %f)";
			$db->execute($sql, $dt, $warehouseCode, $warehouseName, $saleMoney, $rejSaleMoney, $m, 
					$profit, $rate);
		}
		
		$result = [];
		$sql = "select biz_dt, warehouse_code, warehouse_name,
					sale_money, rej_money,
					m, profit, rate
				from psi_sale_report
				order by %s %s ";
		if (! $showAllData) {
			$sql .= " limit %d, %d ";
		}
		if ($showAllData) {
			$data = $db->query($sql, $sortProperty, $sortDirection);
		} else {
			$data = $db->query($sql, $sortProperty, $sortDirection, $start, $limit);
		}
		foreach ( $data as $v ) {
			$result[] = [
					"bizDT" => $v["biz_dt"],
					"warehouseCode" => $v["warehouse_code"],
					"warehouseName" => $v["warehouse_name"],
					"saleMoney" => $v["sale_money"],
					"rejMoney" => $v["rej_money"],
					"m" => $v["m"],
					"profit" => $v["profit"],
					"rate" => $v["rate"] == 0 ? null : sprintf("%0.2f", $v["rate"]) . "%"
			];
		}
		
		$sql = "select count(*) as cnt
				from psi_sale_report
				";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_report";
		$db->execute($sql);
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 销售日报表(按业务员汇总) - 查询数据
	 */
	public function saleDayByBizuserQueryData($params) {
		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$loginUserId = $us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_DAY_BY_BIZUSER, "s", $loginUserId);
	
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData = $limit == - 1;
		
		$dt = $params["dt"];
		
		$sort = $params["sort"];
		$sortProperty = "user_code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("userCode")) {
					$sortProperty = "user_code";
				} else if ($sortProperty == strtolower("saleMoney")) {
					$sortProperty = "sale_money";
				} else if ($sortProperty == strtolower("rejMoney")) {
					$sortProperty = "rej_money";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_report (
					biz_dt datetime,
					user_code varchar(255), user_name varchar(255),
					sale_money decimal(19,2),
					rej_money decimal(19,2), m decimal(19,2),
					profit decimal(19,2), rate decimal(19, 2)
				)";
		$db->execute($sql);
		
		$sql = "select u.id, u.org_code, u.name
				from t_user u
				join (select distinct ot.biz_user_id from(
					select distinct s.biz_user_id
					from t_ws_bill s
					where s.bizdt = '%s' and s.bill_status >= 1000
						and s.company_id = '%s' and  ".$rsStr."
					union
					select distinct s.biz_user_id
					from t_sr_bill s
					where s.bizdt = '%s' and s.bill_status = 1000
						and s.company_id = '%s' and ".$rsStr."
					)ot )T on u.id = T.biz_user_id
				order by u.org_code ";
		$items = $db->query($sql, $dt, $companyId, $dt, $companyId);
		foreach ( $items as $i => $v ) {
			$userName = $v["name"];
			$userCode = $v["org_code"];
			
			$userId = $v["id"];
			$sql = "select sum(s.sale_money) as goods_money, sum(s.inventory_money) as inventory_money
					from t_ws_bill s
					where s.bizdt = '%s' and s.biz_user_id = '%s'
						and s.bill_status >= 1000 and s.company_id = '%s' and ".$rsStr."";
			$data = $db->query($sql, $dt, $userId, $companyId);
			$saleMoney = $data[0]["goods_money"];
			if (! $saleMoney) {
				$saleMoney = 0;
			}
			$saleInventoryMoney = $data[0]["inventory_money"];
			if (! $saleInventoryMoney) {
				$saleInventoryMoney = 0;
			}
			
			$sql = "select sum(s.rejection_sale_money) as rej_money,
						sum(s.inventory_money) as rej_inventory_money
					from t_sr_bill s
					where s.bizdt = '%s' and s.biz_user_id = '%s'
						and s.bill_status = 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $dt, $userId, $companyId);
			$rejSaleMoney = $data[0]["rej_money"];
			if (! $rejSaleMoney) {
				$rejSaleMoney = 0;
			}
			$rejInventoryMoney = $data[0]["rej_inventory_money"];
			if (! $rejInventoryMoney) {
				$rejInventoryMoney = 0;
			}
			
			$m = $saleMoney - $rejSaleMoney;
			$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
			
			$rate = 0;
			if ($m > 0) {
				$rate = $profit / $m * 100;
			}
			$sql = "insert into psi_sale_report (biz_dt, user_code, user_name,
						sale_money, rej_money, m, profit, rate)
					values ('%s', '%s', '%s',
							%f, %f, %f, %f, %f)";
			$db->execute($sql, $dt, $userCode, $userName, $saleMoney, $rejSaleMoney, $m, $profit, 
					$rate);
		}
		
		$result = [];
		$sql = "select biz_dt, user_code, user_name,
					sale_money, rej_money,
					m, profit, rate
				from psi_sale_report
				order by %s %s ";
		if (! $showAllData) {
			$sql .= " limit %d, %d ";
		}
		if ($showAllData) {
			$data = $db->query($sql, $sortProperty, $sortDirection);
		} else {
			$data = $db->query($sql, $sortProperty, $sortDirection, $start, $limit);
		}
		foreach ( $data as $v ) {
			$result[] = [
					"bizDT" => $this->toYMD($v["biz_dt"]),
					"userCode" => $v["user_code"],
					"userName" => $v["user_name"],
					"saleMoney" => $v["sale_money"],
					"rejMoney" => $v["rej_money"],
					"m" => $v["m"],
					"profit" => $v["profit"],
					"rate" => $v["rate"] == 0 ? null : sprintf("%0.2f", $v["rate"]) . "%"
			];
		}
		
		$sql = "select count(*) as cnt
				from psi_sale_report
				";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_report";
		$db->execute($sql);
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	
	/**
	 * 销售月报表(按业务员汇总) - 查询数据
	 */
	public function saleMonthByBizuserQueryData($params) {
		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$loginUserId = $us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_MONTH_BY_BIZUSER, "s", $loginUserId);
	
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData = $limit == - 1;
		
		$year = $params["year"];
		$month = $params["month"];
		$dt = "";
		if ($month < 10) {
			$dt = "$year-0$month";
		} else {
			$dt = "$year-$month";
		}
		
		$sort = $params["sort"];
		$sortProperty = "user_code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("userCode")) {
					$sortProperty = "user_code";
				} else if ($sortProperty == strtolower("saleMoney")) {
					$sortProperty = "sale_money";
				} else if ($sortProperty == strtolower("rejMoney")) {
					$sortProperty = "rej_money";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_report (
					biz_dt varchar(255),user_id varchar(255),
					user_code varchar(255), user_name varchar(255),
					sale_money decimal(19,2),
					rej_money decimal(19,2), m decimal(19,2),
					profit decimal(19,2), rate decimal(19, 2),
					lev2_money decimal(19,2),
					exlev2_money decimal(19,2)
				)";
		$db->execute($sql);
		
		$sql = "select u.id, u.org_code as code, u.name
				from t_user u
				join (select distinct ot.biz_user_id from (
					select distinct s.biz_user_id
					from t_ws_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.bill_status >= 1000 and s.company_id = '%s' and ".$rsStr."
					union
					select distinct s.biz_user_id
					from t_sr_bill s
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.bill_status = 1000 and s.company_id = '%s' and ".$rsStr."
					)ot )T on u.id = T.biz_user_id
				order by u.org_code ";
		$items = $db->query($sql, $year, $month, $companyId, $year, $month, $companyId);
		foreach ( $items as $i => $v ) {
			$userCode = $v["code"];
			$userName = $v["name"];
			
			$userId = $v["id"];
			$sql = "select sum(s.sale_money) as goods_money, sum(s.inventory_money) as inventory_money 
			from t_ws_bill s 
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.biz_user_id = '%s'
						and s.bill_status >= 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $year, $month, $userId, $companyId);
			$saleMoney = $data[0]["goods_money"];
			if (! $saleMoney) {
				$saleMoney = 0;
			}
			$saleInventoryMoney = $data[0]["inventory_money"];
			if (! $saleInventoryMoney) {
				$saleInventoryMoney = 0;
			}

			$sql = "select sum(d.goods_count*(d.goods_price- g.lev2_sale_price))lev2_money,sum(d.goods_count*(g.lev2_sale_price-d.inventory_price ))exlev2_money
			from t_ws_bill s  join t_ws_bill_detail  d on s.id = d.wsbill_id join t_goods g on d.goods_id = g.id
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.biz_user_id = '%s'
						and s.bill_status >= 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $year, $month, $userId, $companyId);
			$lev2Money = $data[0]["lev2_money"];
			if (! $lev2Money) {
				$lev2Money = 0;
			}
			
			$exLev2Money = $data[0]["exlev2_money"];
			if (! $exLev2Money) {
				$exLev2Money = 0;
			}
			
			
			$sql = "select sum(s.rejection_sale_money) as rej_money ,sum(s.inventory_money) as rej_inventory_money 
			from t_sr_bill s 
					where year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.biz_user_id = '%s'
						and s.bill_status = 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $year, $month, $userId, $companyId);
			$rejSaleMoney = $data[0]["rej_money"];
			if (! $rejSaleMoney) {
				$rejSaleMoney = 0;
			}
			$rejInventoryMoney = $data[0]["rej_inventory_money"];
			if (! $rejInventoryMoney) {
				$rejInventoryMoney = 0;
			}

			$sql = "select  sum(d.goods_count*(d.goods_price- g.lev2_sale_price)) rej_lev2_money,sum(d.goods_count*(g.lev2_sale_price-d.inventory_price ))rej_exlev2_money
			from t_sr_bill s join t_sr_bill_detail  d on s.id = d.srbill_id join t_goods g on d.goods_id = g.id
					where year(s.bizdt) = %d and month(s.bizdt) = %d and  d.memo !='换'
						and s.biz_user_id = '%s'
						and s.bill_status = 1000 and s.company_id = '%s' and ".$rsStr." ";
			$data = $db->query($sql, $year, $month, $userId, $companyId);
			$rejLev2Money = $data[0]["rej_lev2_money"];
			if (! $rejLev2Money) {
				$rejLev2Money = 0;
			}
			$rejExLev2Money = $data[0]["rej_exlev2_money"];
			if (! $rejExLev2Money) {
				$rejExLev2Money = 0;
			}

			$m = $saleMoney - $rejSaleMoney;
			$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
			$rate = 0;
			if ($m > 0) {
				$rate = $profit / $m * 100;
			}

			$lm = $lev2Money-$rejLev2Money;//二批纯利润

			$exlm = $profit-$lm;//去除二批利润后的毛利润
			
			$sql = "insert into psi_sale_report (biz_dt, user_code,user_id, user_name,
						sale_money, rej_money, m, profit, rate,lev2_money,exlev2_money)
					values ('%s', '%s','%s', '%s',
							%f, %f, %f, %f, %f,%f,%f)";
			$db->execute($sql, $dt, $userCode,$userId, $userName, $saleMoney, $rejSaleMoney, $m, $profit, 
					$rate,$lm,$exlm);
		}
		
		$result = [];
		$sql = "select biz_dt, user_code, user_name,
					sale_money, rej_money,
					m, profit, rate,lev2_money,exlev2_money,user_id
				from psi_sale_report
				order by %s %s ";
		if (! $showAllData) {
			$sql .= " limit %d, %d ";
		}
		$data = $showAllData ? $db->query($sql, $sortProperty, $sortDirection) : $db->query($sql, 
				$sortProperty, $sortDirection, $start, $limit);
		foreach ( $data as $v ) {
			$result[] = [
					"bizDT" => $v["biz_dt"],
					"userCode" => $v["user_code"],
					"userName" => $v["user_name"],
					"userId" => $v["user_id"],
					"saleMoney" => $v["sale_money"],
					"rejMoney" => $v["rej_money"],
					"m" => $v["m"],
					"profit" => $v["profit"],
					"rate" => $v["rate"] == 0 ? null : sprintf("%0.2f", $v["rate"]) . "%",
					"lev2Money" => $v["lev2_money"],
					"exLev2Money" => $v["exlev2_money"],
			];
		}
		
		$sql = "select count(*) as cnt
				from psi_sale_report
				";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_report";
		$db->execute($sql);
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	public function saleAllDetailQueryData($params){

		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$companyId = $params["companyId"];
		$userId =$params["userId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$cid = $params["caId"];
		$loginUserId = $us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_MONTH_BY_BIZUSER, "s", $loginUserId);
	
		$start = $params["start"];
		$limit = intval($params["limit"]);


		$showAllData = $limit == - 1;
		
		$dtBegin = $params["startDate"];
		$dtEnd = $params["endDate"];
		// if ($month < 10) {
		// 	$dt = "$year-0$month";
		// } else {
		// 	$dt = "$year-$month";
		// }
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_all_report (
			biz_dt varchar(255),
			user_id varchar(255),
			user_code varchar(255), 
			user_name varchar(255),
			memo varchar(255),
			customer_name varchar(255),
			goods_name varchar(255),
			goods_count decimal(19,2),
			goods_money decimal(19,2),
			goods_price decimal(19,2),
			re_goods_count decimal(19,2),
			re_goods_money decimal(19,2),
			re_goods_price decimal(19,2),
			lev2_sale_price decimal(19,2),
			inventory_price decimal(19,2),
			inventory_money decimal(19,2)
		
		)";
		$db->execute($sql);
		$cidSql = " and 1=1 ";
		if($cid&&$cid!='null'&&$cid!='NULL')
		{
			$cidSql = " and s.customer_id =  '".$cid."' ";
		}
		$userSql = " and 1=1 ";
		if($userId)
		{
			$userSql = " and u.id =  '".$userId."' ";
		}
	
			$sql = "select s.bizdt,u.`name` biz_name,u.org_code,c.`name` as customer_name, d.memo,d.goods_count,d.goods_money,d.goods_price ,g.`name` as goods_name,g.lev2_sale_price ,d.inventory_price,d.inventory_money from t_ws_bill_detail d  join t_ws_bill s  on   d.wsbill_id = s.id
			join t_customer c on s.customer_id = c.id join t_user u on s.biz_user_id = u.id join t_goods g on d.goods_id = g.id where  
			 s.bizdt >= '". $dtBegin."' and  s.bizdt <= '". $dtEnd."'
			 ".$userSql." 	 ".$cidSql."
						and s.bill_status >= 1000 and s.company_id ='". $companyId."' and ".$rsStr." order by  s.bizdt asc ,customer_name  ";

			$itmes = $db->query($sql);

			foreach($itmes  as $item){
				
					
			$sql = "insert into psi_sale_all_report (biz_dt, user_code, user_name,
			customer_name, goods_name, goods_count, goods_money, goods_price,memo,inventory_price,inventory_money,lev2_sale_price)
		values ('%s', '%s','%s', '%s','%s',%f,%f,%f, '%s',%f,%f,%f)";
			$db->execute($sql, $item["bizdt"], $item["org_code"],$item["biz_name"], $item["customer_name"], $item["goods_name"],$item["goods_count"], $item["goods_money"], $item["goods_price"], $item["memo"],$item["inventory_price"],$item["inventory_money"],$item["lev2_sale_price"]
					);
			}


			$sql = "select s.bizdt,u.`name` biz_name,u.org_code,c.`name` as customer_name, d.memo,d.rejection_goods_count goods_count,d.rejection_sale_money goods_money,d.rejection_goods_price  goods_price ,g.`name` as goods_name,g.lev2_sale_price  ,d.inventory_price,d.inventory_money from t_sr_bill_detail d  join t_sr_bill s  on   d.srbill_id = s.id
			join t_customer c on s.customer_id = c.id join t_user u on s.biz_user_id = u.id join t_goods g on d.goods_id = g.id where  
			 s.bizdt >= '". $dtBegin."' and  s.bizdt <= '". $dtEnd."'
			 ".$userSql." ".$cidSql."
						and d.rejection_goods_count > 0 and  s.bill_status >= 1000 and s.company_id ='". $companyId."' and ".$rsStr." order by  s.bizdt asc ,customer_name  ";

			$itmes = $db->query($sql);

			foreach($itmes  as $item){
		
			$sql = "insert into psi_sale_all_report (biz_dt, user_code, user_name,
			customer_name, goods_name, re_goods_count, re_goods_money, re_goods_price,memo,inventory_price,inventory_money,lev2_sale_price)
		values ('%s', '%s','%s', '%s','%s',%f,%f,%f, '%s',%f,%f,%f)";
			$db->execute($sql, $item["bizdt"], $item["org_code"],$item["biz_name"], $item["customer_name"], $item["goods_name"],$item["goods_count"], $item["goods_money"], $item["goods_price"], $item["memo"],$item["inventory_price"],$item["inventory_money"],$item["lev2_sale_price"]
					);
			}





			// biz_dt varchar(255),
				// user_id varchar(255),
				// user_code varchar(255), 
				// user_name varchar(255),
				// customer_name varchar(255),
				// goods_name varchar(255),
				// goods_count decimal(19,2),
				// goods_money decimal(19,2),
				// goods_price decimal(19,2),
				// re_goods_count decimal(19,2),
				// re_goods_money decimal(19,2),
				// re_goods_price decimal(19,2),
				// lev2_sale_price decimal(19,2),
				// inventory_price decimal(19,2),
				// inventory_money decimal(19,2),
		$sql   = "select  * from psi_sale_all_report order by  biz_dt asc ";

		if (! $showAllData) {
			$sql .= " limit %d, %d ";
		}
		$data = $showAllData ? $db->query($sql) : $db->query($sql, 
				 $start, $limit);
		foreach ( $data as $v ) {
			$result[] = [
					"bizDT" => $this->toYMD($v["biz_dt"]),
					"userCode" => $v["user_code"],
					"userName" => $v["user_name"],
					"customerName" => $v["customer_name"],
					"goodsName" => $v["goods_name"],
					"goodsCount" => $v["goods_count"],
					"goodsMoney" => $v["goods_money"],
					"goodsPrice" => $v["goods_price"], 
					"reGoodsCount" => $v["re_goods_count"],
					"reGoodsMoney" => $v["re_goods_money"],
					"reGoodsPrice" => $v["re_goods_price"], 
					"lev2SalePrice" => $v["lev2_sale_price"],
					"inventoryPrice"=>$v["inventory_price"],
					"inventoryMoney"=>$v["inventory_money"],
					"memo"=>$v["memo"],
			];
		}
		
		$sql = "select  count(*) cnt
		from psi_sale_all_report  ";

		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		// $sql = "select  ifnull(sum(goods_money),0) sum_goods_money,   ifnull(sum(goods_money-inventory_money),0) sum_profit_money,  ifnull(sum(goods_count*(goods_price-lev2_sale_price)),0) sum_lev2_profit_money,  ifnull(sum(re_goods_count*(re_goods_price-lev2_sale_price)),0) sum_re_lev2_profit_money,   ifnull(sum(re_goods_money),0) re_sum_goods_money, ifnull(sum(re_goods_money-inventory_money),0) sum_re_profit_money from psi_sale_all_report  ";

		// $data = $db->query($sql);

		// $sumResult=[];
		// $sumResult[0]["saleMoney"] = $data[0]["sum_goods_money"];
		// $sumResult[0]["reSaleMoney"] = $data[0]["re_sum_goods_money"];
		// $sumResult[0]["trueSaleMoney"]= $sumResult[0]["saleMoney"]-$sumResult[0]["reSaleMoney"];
		// $sumResult[0]["profit"]= $data[0]["sum_profit_money"]-$data[0]["sum_re_profit_money"];
		// $sumResult[0]["lev2SaleMoney"] = $data[0]["sum_lev2_profit_money"]- $data[0]["sum_re_lev2_profit_money"];
	
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_all_report";
		$db->execute($sql);
		return array(
				"dataList" => $result,
				"totalCount" => $cnt,
				
		);
	}

	
	public function saleAllDetailQuerySumData($params){

		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$companyId = $params["companyId"];
		$userId =$params["userId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$cid = $params["caId"];
		$loginUserId = $us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_MONTH_BY_BIZUSER, "s", $loginUserId);
	
		$start = $params["start"];
		$limit = intval($params["limit"]);


		$showAllData = $limit == - 1;
		
		$dtBegin = $params["startDate"];
		$dtEnd = $params["endDate"];
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_all_report2 (
			biz_dt varchar(255),
			user_id varchar(255),
			user_code varchar(255), 
			user_name varchar(255),
			memo varchar(255),
			customer_name varchar(255),
			goods_name varchar(255),
			goods_count decimal(19,2),
			goods_money decimal(19,2),
			goods_price decimal(19,2),
			re_goods_count decimal(19,2),
			re_goods_money decimal(19,2),
			re_goods_price decimal(19,2),
			lev2_sale_price decimal(19,2),
			inventory_price decimal(19,2),
			inventory_money decimal(19,2)
		
		)";
		$db->execute($sql);
		$cidSql = " and 1=1 ";
		if($cid&&$cid!='null'&&$cid!='NULL')
		{
			$cidSql = " and s.customer_id =  '".$cid."' ";
		}
		$userSql = " and 1=1 ";
		if($userId)
		{
			$userSql = " and u.id =  '".$userId."' ";
		}
	
			$sql = "select s.bizdt,u.`name` biz_name,u.org_code,c.`name` as customer_name, d.memo,d.goods_count,d.goods_money,d.goods_price ,g.`name` as goods_name,g.lev2_sale_price ,d.inventory_price,d.inventory_money from t_ws_bill_detail d  join t_ws_bill s  on   d.wsbill_id = s.id
			join t_customer c on s.customer_id = c.id join t_user u on s.biz_user_id = u.id join t_goods g on d.goods_id = g.id where  
			 s.bizdt >= '". $dtBegin."' and  s.bizdt <= '". $dtEnd."'
			 ".$userSql." 	 ".$cidSql."
						and s.bill_status >= 1000 and s.company_id ='". $companyId."' and ".$rsStr." order by  s.bizdt asc ,customer_name  ";

			$itmes = $db->query($sql);

			foreach($itmes  as $item){
				
					
			$sql = "insert into psi_sale_all_report2 (biz_dt, user_code, user_name,
			customer_name, goods_name, goods_count, goods_money, goods_price,memo,inventory_price,inventory_money,lev2_sale_price)
		values ('%s', '%s','%s', '%s','%s',%f,%f,%f, '%s',%f,%f,%f)";
			$db->execute($sql, $item["bizdt"], $item["org_code"],$item["biz_name"], $item["customer_name"], $item["goods_name"],$item["goods_count"], $item["goods_money"], $item["goods_price"], $item["memo"],$item["inventory_price"],$item["inventory_money"],$item["lev2_sale_price"]
					);
			}

			$sql = "select s.bizdt,u.`name` biz_name,u.org_code,c.`name` as customer_name, d.memo,d.rejection_goods_count goods_count,d.rejection_sale_money goods_money,d.rejection_goods_price  goods_price ,g.`name` as goods_name,g.lev2_sale_price  ,d.inventory_price,d.inventory_money from t_sr_bill_detail d  join t_sr_bill s  on   d.srbill_id = s.id
			join t_customer c on s.customer_id = c.id join t_user u on s.biz_user_id = u.id join t_goods g on d.goods_id = g.id where  
			 s.bizdt >= '". $dtBegin."' and  s.bizdt <= '". $dtEnd."'
			 ".$userSql." ".$cidSql."
						and d.rejection_goods_count > 0 and  s.bill_status >= 1000 and s.company_id ='". $companyId."' and ".$rsStr." order by  s.bizdt asc ,customer_name  ";

			$itmes = $db->query($sql);

			foreach($itmes  as $item){
		
			$sql = "insert into psi_sale_all_report2 (biz_dt, user_code, user_name,
			customer_name, goods_name, re_goods_count, re_goods_money, re_goods_price,memo,inventory_price,inventory_money,lev2_sale_price)
		values ('%s', '%s','%s', '%s','%s',%f,%f,%f, '%s',%f,%f,%f)";
			$db->execute($sql, $item["bizdt"], $item["org_code"],$item["biz_name"], $item["customer_name"], $item["goods_name"],$item["goods_count"], $item["goods_money"], $item["goods_price"], $item["memo"],$item["inventory_price"],$item["inventory_money"],$item["lev2_sale_price"]
					);
			}




		

		$sql = "select  ifnull(sum(goods_money),0) sum_goods_money,   ifnull(sum(goods_money-inventory_money),0) sum_profit_money,  ifnull(sum(goods_count*(goods_price-lev2_sale_price)),0) sum_lev2_profit_money,  ifnull(sum(re_goods_count*(re_goods_price-lev2_sale_price)),0) sum_re_lev2_profit_money,   ifnull(sum(re_goods_money),0) re_sum_goods_money, ifnull(sum(re_goods_money-inventory_money),0) sum_re_profit_money from psi_sale_all_report2  ";

		$data = $db->query($sql);

		$sumResult=[];
		$sumResult[0]["saleMoney"] = $data[0]["sum_goods_money"];
		$sumResult[0]["reSaleMoney"] = $data[0]["re_sum_goods_money"];
		$sumResult[0]["trueSaleMoney"]= $sumResult[0]["saleMoney"]-$sumResult[0]["reSaleMoney"];
		$sumResult[0]["profit"]= $data[0]["sum_profit_money"]-$data[0]["sum_re_profit_money"];
		$sumResult[0]["lev2SaleMoney"] = $data[0]["sum_lev2_profit_money"]- $data[0]["sum_re_lev2_profit_money"];
	
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_all_report2";
		$db->execute($sql);
		return $sumResult;
	}
	/**
	 * 销售月报表详表(按业务员汇总) - 查询数据
	 */
	public function saleMonthDetailByBizuserQueryData($params) {
		$db = $this->db;
		$us = new UserService();
		$userDataOrg = $us->getLoginUserDataOrg();
		$companyId = $params["companyId"];
		$userId =$params["userId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$loginUserId = $us->getLoginUserId();
		$ds = new DataOrgDAO($db);
		$rsStr = $ds->buildSQLStr2(FIdConst::REPORT_SALE_MONTH_BY_BIZUSER, "s", $loginUserId);
	
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData =true;
		
		$year = $params["year"];
		$month = $params["month"];

		$dt = $params["bizDT"];
		// if ($month < 10) {
		// 	$dt = "$year-0$month";
		// } else {
		// 	$dt = "$year-$month";
		// }
		
		$sort = $params["sort"];
		$sortProperty = "user_code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("userCode")) {
					$sortProperty = "user_code";
				} else if ($sortProperty == strtolower("saleMoney")) {
					$sortProperty = "sale_money";
				} else if ($sortProperty == strtolower("rejMoney")) {
					$sortProperty = "rej_money";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		// 创建临时表保存数据
		$sql = "CREATE TEMPORARY TABLE psi_sale_report1 (
					biz_dt varchar(255),user_id varchar(255), 
					user_code varchar(255), user_name varchar(255),
					sale_money decimal(19,2),
					rej_money decimal(19,2), m decimal(19,2),
					profit decimal(19,2), rate decimal(19, 2),
					lev2_money decimal(19,2),
					exlev2_money decimal(19,2)
				)";
		$db->execute($sql);
		

		$currMonth = $this->toYMD($dt);
	$helper = new DateHelper();
		$items = 	$helper->get_day($currMonth,2);
		$sql = "select u.id, u.org_code as code, u.name
				from t_user u  where id = '%s' ";
		$user = $db->query($sql, $userId);
	
		foreach ( $items as $i => $v ) {
			$userCode = $user[0]["code"];
			$userName = $user[0]["name"];
			$dateC = $v;
			$sql = "select sum(s.sale_money) as goods_money, sum(s.inventory_money) as inventory_money 
			from t_ws_bill s 
					where date_format( bizdt, '%Y-%m-%d' )= '".$dateC."'
						and s.biz_user_id = '". $userId."'
						and s.bill_status >= 1000 and s.company_id = '". $companyId."' and ".$rsStr." ";
			$data = $db->query($sql);
			$saleMoney = $data[0]["goods_money"];
			if (! $saleMoney) {
				$saleMoney = 0;
			}
			$saleInventoryMoney = $data[0]["inventory_money"];
			if (! $saleInventoryMoney) {
				$saleInventoryMoney = 0;
			}

			$sql = "select sum(d.goods_count*(d.goods_price- g.lev2_sale_price))lev2_money,sum(d.goods_count*(g.lev2_sale_price-d.inventory_price ))exlev2_money
			from t_ws_bill s  join t_ws_bill_detail  d on s.id = d.wsbill_id join t_goods g on d.goods_id = g.id
					where date_format( s.bizdt, '%Y-%m-%d' ) = '".$dateC."'
						and s.biz_user_id =  '". $userId."' and  d.memo !='换'
						and s.bill_status >= 1000 and s.company_id ='". $companyId."' and ".$rsStr." ";
			$data = $db->query($sql);
			$lev2Money = $data[0]["lev2_money"];
			if (! $lev2Money) {
				$lev2Money = 0;
			}
			
			$exLev2Money = $data[0]["exlev2_money"];
			if (! $exLev2Money) {
				$exLev2Money = 0;
			}
			
			
			$sql = "select sum(s.rejection_sale_money) as rej_money ,sum(s.inventory_money) as rej_inventory_money 
			from t_sr_bill s 
					where date_format( s.bizdt, '%Y-%m-%d' ) = '". $dateC."'
						and s.biz_user_id ='". $userId."'
						and s.bill_status = 1000 and s.company_id = '". $companyId."' and ".$rsStr." ";
			$data = $db->query($sql);
			$rejSaleMoney = $data[0]["rej_money"];
			if (! $rejSaleMoney) {
				$rejSaleMoney = 0;
			}
			$rejInventoryMoney = $data[0]["rej_inventory_money"];
			if (! $rejInventoryMoney) {
				$rejInventoryMoney = 0;
			}

			$sql = "select  sum(d.goods_count*(d.goods_price- g.lev2_sale_price)) rej_lev2_money,sum(d.goods_count*(g.lev2_sale_price-d.inventory_price ))rej_exlev2_money
			from t_sr_bill s join t_sr_bill_detail  d on s.id = d.srbill_id join t_goods g on d.goods_id = g.id
					where date_format( s.bizdt, '%Y-%m-%d' ) ='". $dateC."'
						and s.biz_user_id = '". $userId."'
						and s.bill_status = 1000 and s.company_id = '". $companyId."' and ".$rsStr." ";
			$data = $db->query($sql);
			$rejLev2Money = $data[0]["rej_lev2_money"];
			if (! $rejLev2Money) {
				$rejLev2Money = 0;
			}
			$rejExLev2Money = $data[0]["rej_exlev2_money"];
			if (! $rejExLev2Money) {
				$rejExLev2Money = 0;
			}

			$m = $saleMoney - $rejSaleMoney;
			$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
			$rate = 0;
			if ($m > 0) {
				$rate = $profit / $m * 100;
			}

			$lm = $lev2Money-$rejLev2Money;//二批纯利润

			$exlm = $profit-$lm;//去除二批利润后的毛利润
			
			$sql = "insert into psi_sale_report1 (biz_dt, user_code,user_id, user_name,
						sale_money, rej_money, m, profit, rate,lev2_money,exlev2_money)
					values ('%s', '%s', '%s','%s',
							%f, %f, %f, %f, %f,%f,%f)";
			$db->execute($sql, $dateC, $userCode,$userId, $userName, $saleMoney, $rejSaleMoney, $m, $profit, 
					$rate,$lm,$exlm);
		}
		
		$result = [];
		$sql = "select biz_dt, user_code, user_name,user_id,
					sale_money, rej_money,
					m, profit, rate,lev2_money,exlev2_money
				from psi_sale_report1
				order by %s %s ";
		if (! $showAllData) {
			$sql .= " limit %d, %d ";
		}
		$data = $showAllData ? $db->query($sql, $sortProperty, $sortDirection) : $db->query($sql, 
				$sortProperty, $sortDirection, $start, $limit);
		foreach ( $data as $v ) {
			$result[] = [
					"bizDT" => $v["biz_dt"],
					"userCode" => $v["user_code"],
					"userId" => $v["user_id"],
					"userName" => $v["user_name"],
					"saleMoney" => $v["sale_money"],
					"rejMoney" => $v["rej_money"],
					"m" => $v["m"],
					"profit" => $v["profit"],
					"rate" => $v["rate"] == 0 ? null : sprintf("%0.2f", $v["rate"]) . "%",
					"lev2Money" => $v["lev2_money"],
					"exLev2Money" => $v["exlev2_money"],
			];
		}
		
		$sql = "select count(*) as cnt
				from psi_sale_report1
				";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		// 删除临时表
		$sql = "DROP TEMPORARY TABLE IF EXISTS psi_sale_report1";
		$db->execute($sql);
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}
}