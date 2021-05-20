<?php

namespace API\DAO;

use Home\DAO\PSIBaseExDAO;
use Home\Common\FIdConst;
use Home\DAO\DataOrgDAO;
use Home\DAO\BizConfigDAO;

/**
 * 用户 API DAO
 *
 * @author Taoyj
 */
class InventoryApiDAO extends PSIBaseExDAO {

	public function warehouseList($userId) {
		$db = $this->db;

		$sql = "select id, code, name, enabled from t_warehouse 
				where (inited = 1) ";
		$queryParams = [];
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::INVENTORY_QUERY, "t_warehouse", $userId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by enabled, code";
		
		return M()->query($sql, $queryParams);

	}
	
	public function inventoryGoodsInfo($params)
	{
		$id = $params["id"];
		$sql = "select balance_count,balance_money,balance_price,goods_id,
		in_count,in_money,in_price,
		out_count,out_money,out_price,
		afloat_count,afloat_money,afloat_price,
		warehouse_id from t_inventory where goods_id = '$id'";
		return M()->query($sql);
	}


	public function inventoryListAll($params) {
	
		$db = $this->db;
		$companyId = $params["companyId"];
		$userId = $params["loginUserId"];


		$ds = new DataOrgDAO($db);
		$rstr = $ds->buildSQLStr2(FIdConst::INVENTORY_QUERY, "t_warehouse", $userId);

		$waresql = "select id  as warehouse_id  from t_warehouse 
		where (inited = 1)  and is_sale = 1 and ".$rstr;

	

		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";


		
		$sql = "select v.goods_id, 
		convert(sum(v.balance_count), $fmt) as balance_count , IFNULL(goods_count,0)goods_count from t_inventory v  join (".$waresql .") t on v.warehouse_id =  t.warehouse_id  
   left join (select  sum(d.goods_count) goods_count ,d.goods_id from t_so_bill b join t_so_bill_detail d on b.id = d.sobill_id where (b.bill_status =0 or b.bill_status =1000  )   group by goods_id ) s on s.goods_id = v.goods_id  group by v.goods_id";

		$data = $db->query($sql);
		
		$result = [];
		
		foreach ( $data as $i => $v ) {
			$result[$i]["goodsId"] = $v["goods_id"];
		
			$result[$i]["balanceCount"] = ($v["balance_count"]- $v["goods_count"])>0?($v["balance_count"]- $v["goods_count"]):0;
		
		}
	
		
		return array(
				"dataList" => $result,
				"totalCount" => count( $data),
				"totalPage" =>1
		);
	}

	public function inventoryList($params) {
	
		$db = $this->db;
		$companyId = $params["companyId"];

		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$warehouseId = $params["warehouseId"];
		$code = $params["code"];
		// $name = $params["name"];
		// $spec = $params["spec"];
		// $brandId = $params["brandId"];
		$start = $params["start"];
		$limit = $params["limit"];
		$hasInv = $params["hasInv"] == "1";
		
		$sort = $params["sort"];
		$sortProperty = "g.code";
		$sortDirection = "ASC";
		if ($sort) {
			$sortJSON = json_decode(html_entity_decode($sort), true);
			if ($sortJSON) {
				$sortProperty = strtolower($sortJSON[0]["property"]);
				if ($sortProperty == strtolower("goodsCode")) {
					$sortProperty = "g.code";
				} else if ($sortProperty == strtolower("afloatCount")) {
					$sortProperty = "v.afloat_count";
				} else if ($sortProperty == strtolower("afloatPrice")) {
					$sortProperty = "v.afloat_price";
				} else if ($sortProperty == strtolower("afloatMoney")) {
					$sortProperty = "v.afloat_money";
				} else if ($sortProperty == strtolower("inCount")) {
					$sortProperty = "v.in_count";
				} else if ($sortProperty == strtolower("inPrice")) {
					$sortProperty = "v.in_price";
				} else if ($sortProperty == strtolower("inMoney")) {
					$sortProperty = "v.in_money";
				} else if ($sortProperty == strtolower("outCount")) {
					$sortProperty = "v.out_count";
				} else if ($sortProperty == strtolower("outPrice")) {
					$sortProperty = "v.out_price";
				} else if ($sortProperty == strtolower("outMoney")) {
					$sortProperty = "v.out_money";
				} else if ($sortProperty == strtolower("balanceCount")) {
					$sortProperty = "v.balance_count";
				} else if ($sortProperty == strtolower("balancePrice")) {
					$sortProperty = "v.balance_price";
				} else if ($sortProperty == strtolower("balanceMoney")) {
					$sortProperty = "v.balance_money";
				}
				
				$sortDirection = strtoupper($sortJSON[0]["direction"]);
				if ($sortDirection != "ASC" && $sortDirection != "DESC") {
					$sortDirection = "ASC";
				}
			}
		}
		
		$queryParams = [];
		$queryParams[] = $warehouseId;
		
		$sql = "select g.id, g.code, g.name, g.spec, u.name as unit_name,
				 	convert(v.in_count, $fmt) as in_count, 
					v.in_price, v.in_money, convert(v.out_count, $fmt) as out_count, v.out_price, v.out_money,
				 	convert(v.balance_count, $fmt) as balance_count, v.balance_price, v.balance_money, 
					convert(v.afloat_count, $fmt) as afloat_count,
					v.afloat_money, v.afloat_price
				from t_inventory v, t_goods g, t_goods_unit u
				where (v.warehouse_id = '%s') and (v.goods_id = g.id) and (g.unit_id = u.id) ";
		if ($code) {
			$sql .= " and (g.code like '%s' or g.name like '%s' or g.py like '%s' or g.spec like '%s')";
			$queryParams[] = "%{$code}%";
			$queryParams[] = "%{$code}%";
			$queryParams[] = "%{$code}%";
			$queryParams[] = "%{$code}%";
		}
		// if ($name) {
		// 	$sql .= " and (g.name like '%s' or g.py like '%s')";
		//$queryParams[] = "%{$name}%";
	    //	$queryParams[] = "%{$name}%";
		// }
		// if ($spec) {
		// 	$sql .= " and (g.spec like '%s')";
        //	$queryParams[] = "%{$spec}%";
		// }
		// if ($brandId) {
		// 	$sql .= " and (g.brand_id = '%s')";
		// 	$queryParams[] = $brandId;
		// }
		if ($hasInv) {
			$sql .= " and (convert(v.balance_count, $fmt) > 0) ";
		}
		$sql .= " order by %s %s
				limit %d, %d";
	
		$queryParams[] = $sortProperty;
		$queryParams[] = $sortDirection;
		$queryParams[] = $start;
		$queryParams[] = $limit;
		
		$data = $db->query($sql, $queryParams);
		
		$result = [];
		
		foreach ( $data as $i => $v ) {
			$result[$i]["goodsId"] = $v["id"];
			$result[$i]["goodsCode"] = $v["code"];
			$result[$i]["goodsName"] = $v["name"];
			$result[$i]["goodsSpec"] = $v["spec"];
			$result[$i]["unitName"] = $v["unit_name"];
			$result[$i]["inCount"] = $v["in_count"];
			$result[$i]["inPrice"] = $v["in_price"];
			$result[$i]["inMoney"] = $v["in_money"];
			$result[$i]["outCount"] = $v["out_count"];
			$result[$i]["outPrice"] = $v["out_price"];
			$result[$i]["outMoney"] = $v["out_money"];
			$result[$i]["balanceCount"] = $v["balance_count"];
			$result[$i]["balancePrice"] = $v["balance_price"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
			$result[$i]["afloatCount"] = $v["afloat_count"];
			$result[$i]["afloatPrice"] = $v["afloat_price"];
			$result[$i]["afloatMoney"] = $v["afloat_money"];
		}
		
		$queryParams = [];
		$queryParams[] = $warehouseId;
		$sql = "select count(*) as cnt 
				from t_inventory v, t_goods g, t_goods_unit u
				where (v.warehouse_id = '%s') and (v.goods_id = g.id) and (g.unit_id = u.id) ";
		if ($code) {
			$sql .= " and (g.code like '%s')";
			$queryParams[] = "%{$code}%";
		}
		if ($name) {
			$sql .= " and (g.name like '%s' or g.py like '%s')";
			$queryParams[] = "%{$name}%";
			$queryParams[] = "%{$name}%";
		}
		if ($spec) {
			$sql .= " and (g.spec like '%s')";
			$queryParams[] = "%{$spec}%";
		}
		if ($brandId) {
			$sql .= " and (g.brand_id = '%s')";
			$queryParams[] = $brandId;
		}
		if ($hasInv) {
			$sql .= " and (convert(v.balance_count, $fmt) > 0) ";
		}
		
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		$totalPage = ceil($cnt / $limit);
		
		return array(
				"dataList" => $result,
				"totalCount" => (int)$cnt,
				"totalPage" => $totalPage
		);
	}

	public function inventoryDetailList($params) {
	
		$db = $this->db;
		$companyId = $params["companyId"];
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$warehouseId = $params["warehouseId"];
		$goodsId = $params["goodsId"];
		$dtFrom = $params["dtFrom"];
		$dtTo = $params["dtTo"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$sql = "select g.id, g.code, g.name, g.spec, u.name as unit_name,
					convert(v.in_count, $fmt) as in_count, v.in_price, v.in_money, 
					convert(v.out_count, $fmt) as out_count, v.out_price, v.out_money,
					convert(v.balance_count, $fmt) as balance_count, v.balance_price, v.balance_money,
					v.biz_date,  user.name as biz_user_name, v.ref_number, v.ref_type 
				from t_inventory_detail v, t_goods g, t_goods_unit u, t_user user
				where v.warehouse_id = '%s' and v.goods_id = '%s' 
					and v.goods_id = g.id and g.unit_id = u.id 
					and v.biz_user_id = user.id 
					and (v.biz_date between '%s' and '%s' ) 
				order by v.id 
				limit %d, %d";
		$data = $db->query($sql, $warehouseId, $goodsId, $dtFrom, $dtTo, $start, $limit);
		
		$result = [];
		
		foreach ( $data as $i => $v ) {
			$result[$i]["goodsId"] = $v["id"];
			$result[$i]["goodsCode"] = $v["code"];
			$result[$i]["goodsName"] = $v["name"];
			$result[$i]["goodsSpec"] = $v["spec"];
			$result[$i]["unitName"] = $v["unit_name"];
			$result[$i]["inCount"] = $v["in_count"];
			$result[$i]["inPrice"] = $v["in_price"];
			$result[$i]["inMoney"] = $v["in_money"];
			$result[$i]["outCount"] = $v["out_count"];
			$result[$i]["outPrice"] = $v["out_price"];
			$result[$i]["outMoney"] = $v["out_money"];
			$result[$i]["balanceCount"] = $v["balance_count"];
			$result[$i]["balancePrice"] = $v["balance_price"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
			$result[$i]["bizDT"] = date("Y-m-d", strtotime($v["biz_date"]));
			$result[$i]["bizUserName"] = $v["biz_user_name"];
			$result[$i]["refNumber"] = $v["ref_number"];
			$result[$i]["refType"] = $v["ref_type"];
		}
		
		$sql = "select count(*) as cnt from t_inventory_detail" . " where warehouse_id = '%s' and goods_id = '%s' " . "     and (biz_date between '%s' and '%s')";
		$data = $db->query($sql, $warehouseId, $goodsId, $dtFrom, $dtTo);
		
		$cnt = $data[0]["cnt"];
		$totalPage = ceil($cnt / $limit);

		return array(
				"details" => $result,
				"totalCount" => (int)$cnt,
				"totalPage" => $totalPage
		);
	}

}