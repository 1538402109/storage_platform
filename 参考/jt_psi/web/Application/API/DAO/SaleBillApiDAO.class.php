<?php

namespace API\DAO;

use Home\DAO\PSIBaseExDAO;
use Home\DAO\DataOrgDAO;
use Home\Common\FIdConst;
use Home\DAO\BizConfigDAO;
use Home\DAO\OrgDAO;
use Home\DAO\UserDAO;
use Home\DAO\WarehouseDAO;
use Home\DAO\CustomerDAO;
use API\DAO\UserApiDAO;
use API\DAO\CustomerApiDAO;
use Think\Log;

/**
 * 销售订单API DAO
 *
 * @author JIATU
 */
class SaleBillApiDAO extends PSIBaseExDAO {

	private function receivingTypeCodeToName($code) {
		switch ($code) {
			case 0 :
				return "记应收账款";
			case 1 :
				return "现金收款";
			case 2 :
				return "用预收款支付";
			case 3 :
				return "物流代收";
			default :
				return $code;
		}
	}

	private function wsbillStatusCodeToName($code) {
		switch ($code) {
			case 0 :
				return "待出库";
			case 1000 :
				return "已出库";
			case 2000 :
				return "已退货";
			default :
				return "";
		}
	}

	private function billStatusCodeToName($code) {
		switch ($code) {
			case 0 :
				return "待审核";
			case 1000 :
				return "已审核";
			case 2000 :
				return "部分出库";
			case 3000 :
				return "全部出库";
				case 4000 :
				return "关闭(未出库)";
				case 4001 :
				return "关闭(部分出库)";
				case 4002 :
				return "关闭(全部出库)";
			default :
				return $code;
		}
	}

	public function sobillList($params) {
		
		$db = $this->db;
		Log::record('开始sobillList执行'.   date('Y-m-d H:i:s')  , 'DEBUG');
		$start = $params["start"];
		if (! $start) {
			$start = "0";
		}
		$limit = $params["limit"];
		if (! $limit) {
			$limit = 10;
		}
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$billStatus = $params["billStatus"];
		// $ref = $params["ref"];
		// $fromDT = $params["fromDT"];
		// $toDT = $params["toDT"];
		$customerId = $params["customerId"];
		$receivingType = $params["receivingType"];
		
		$queryParams = [];
		
		$result = [];
		// $sql = "select s.id, s.ref, s.bill_status, s.goods_money,
		// 			c.name as customer_name, s.deal_date
		// 		from t_so_bill s, t_customer c
		// 		where (s.customer_id = c.id) ";

				$sql = "";
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::SALE_ORDER, "s1", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		
		if ($billStatus != - 1) {
			$sql .= " and (s.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		// if ($ref) {
		// 	$sql .= " and (s.ref like '%s') ";
		// 	$queryParams[] = "%$ref%";
		// }
		// if ($fromDT) {
		// 	$sql .= " and (s.deal_date >= '%s')";
		// 	$queryParams[] = $fromDT;
		// }
		// if ($toDT) {
		// 	$sql .= " and (s.deal_date <= '%s')";
		// 	$queryParams[] = $toDT;
		// }
		if ($customerId) {
			$sql .= " and (s.customer_id = '%s')";
			$queryParams[] = $customerId;
		}
		if ($receivingType != - 1) {
			$sql .= " and (s.receiving_type = %d) ";
			$queryParams[] = $receivingType;
		}
		$sql .= " order by s1.deal_date desc, s1.ref desc
				  limit %d , %d";
		$queryParams[] = $start;
		$queryParams[] = $limit;

		
		$sqlR = "select s.id, s.ref, s.bill_status, s.goods_money, s.tax, s.money_with_tax,
					c.name as customer_name, s.contact, s.tel, s.fax, s.deal_address,
					s.deal_date, s.receiving_type,  s.distribution_type,s.bill_memo, s.date_created,
					o.full_name as org_name, u1.name as biz_user_name, u2.name as input_user_name,
					s.confirm_user_id, s.confirm_date
				from t_so_bill s join  t_customer c on s.customer_id = c.id
				join  t_org o on s.org_id = o.id  join
				 t_user u1 on s.biz_user_id = u1.id 
				 join t_user u2  on s.input_user_id = u2.id
				 INNER JOIN(select id from t_so_bill s1 where 1=1 ".$sql. " )tm on s.id = tm.id	
				where 1=1 ";
		$data = $db->query($sqlR, $queryParams);
		// foreach ( $data as $i => $v ) {
		// 	$result[] = [
		// 			"id" => $v["id"],
		// 			"ref" => $v["ref"],
		// 			"billStatus" => $this->billStatusCodeToName($v["bill_status"]),
		// 			"billStatusStr" => $v["bill_status"],
		// 			"dealDate" => $this->toYMD($v["deal_date"]),
		// 			"goodsMoney" => $v["goods_money"],
		// 			"customerName" => $v["customer_name"]
		// 	];
		// }
		Log::record('开始sobillList循环'.   date('Y-m-d H:i:s')  , 'DEBUG');
		
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["ref"] = $v["ref"];
			$result[$i]["billStatus"] = (int)$v["bill_status"];
			$result[$i]["billStatusStr"] = $this->billStatusCodeToName($v["bill_status"]);
			$result[$i]["dealDate"] = $this->toYMD($v["deal_date"]);
			$result[$i]["dealAddress"] = $v["deal_address"];
			$result[$i]["customerName"] = $v["customer_name"];
			$result[$i]["contact"] = $v["contact"];
			$result[$i]["tel"] = $v["tel"];
			$result[$i]["fax"] = $v["fax"];
			$result[$i]["goodsMoney"] = $v["goods_money"];
			$result[$i]["tax"] = $v["tax"];
			$result[$i]["moneyWithTax"] = $v["money_with_tax"];
			// $result[$i]["receivingType"] = $v["receiving_type"];
			$result[$i]["receivingType"] =  $this->receivingTypeCodeToName($v["receiving_type"]);
			$result[$i]["distributionType"] = $v["distribution_type"];
			$result[$i]["billMemo"] = $v["bill_memo"];
			$result[$i]["bizUserName"] = $v["biz_user_name"];
			$result[$i]["orgName"] = $v["org_name"];
			$result[$i]["inputUserName"] = $v["input_user_name"];
			$result[$i]["dateCreated"] = $v["date_created"];
			
			$confirmUserId = $v["confirm_user_id"];
			if ($confirmUserId) {
				$sql = "select name from t_user where id = '%s' ";
				$d = $db->query($sql, $confirmUserId);
				if ($d) {
					$result[$i]["confirmUserName"] = $d[0]["name"];
					$result[$i]["confirmDate"] = $v["confirm_date"];
				}
			}
			
			// 查询是否生成了销售出库单
			$sql = "select count(*) as cnt from t_so_ws
					where so_id = '%s' ";
			$d = $db->query($sql, $v["id"]);
			$cnt = $d[0]["cnt"];
			$genPWBill = $cnt > 0 ? "▲" : "";
			$result[$i]["genPWBill"] = $genPWBill;
		}
		Log::record('结束sobillList循环'.   date('Y-m-d H:i:s')  , 'DEBUG');
		
		$sql = "select count(*) as cnt
				from t_so_bill s
				where 1=1 ";
				// $sql = "select count(*) as cnt
				// from t_so_bill s, t_customer c, t_org o, t_user u1, t_user u2
				// where (s.customer_id = c.id) and (s.org_id = o.id)
				// 	and (s.biz_user_id = u1.id) and (s.input_user_id = u2.id)
				// ";
		$queryParams = [];
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::SALE_ORDER, "s", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		if ($billStatus != - 1) {
			$sql .= " and (s.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		// if ($ref) {
		// 	$sql .= " and (s.ref like '%s') ";
		// 	$queryParams[] = "%$ref%";
		// }
		// if ($fromDT) {
		// 	$sql .= " and (s.deal_date >= '%s')";
		// 	$queryParams[] = $fromDT;
		// }
		// if ($toDT) {
		// 	$sql .= " and (s.deal_date <= '%s')";
		// 	$queryParams[] = $toDT;
		// }
		if ($customerId) {
			$sql .= " and (s.customer_id = '%s')";
			$queryParams[] = $customerId;
		}
		if ($receivingType != - 1) {
			$sql .= " and (s.receiving_type = %d) ";
			$queryParams[] = $receivingType;
		}
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		
		$totalPage = ceil($cnt / $limit);
		Log::record('结束sobillList操作'.   date('Y-m-d H:i:s')  , 'DEBUG');
	
		return array(
				"dataList" => $result,
				"totalPage" => $totalPage
		);
	}

	public function sobillGoods($params) {
		$db = $this->db;
		
		$start = $params["start"];
		if (! $start) {
			$start = "0";
		}
		$limit = $params["limit"];
		if (! $limit) {
			$limit = 10;
		}
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		$customerId = $params["customerId"];
		
		$queryParams = [];
		
		$result = [];
		// $sql = "select s.id, s.ref, s.bill_status, s.goods_money,
		// 			c.name as customer_name, s.deal_date
		// 		from t_so_bill s, t_customer c
		// 		where (s.customer_id = c.id) ";

	    $sql = "select s.id, s.ref, s.bill_status, s.goods_money, s.deal_date from t_so_bill s, t_customer c where (s.customer_id = c.id) ";
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::SALE_ORDER, "s", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		if ($customerId) {
			$sql .= " and (s.customer_id = '%s')";
			$queryParams[] = $customerId;
		}

		$sql .= " order by s.deal_date desc, s.ref desc
				  limit %d , %d";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = $db->query($sql, $queryParams);

		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}

		$cs = new BizConfigDAO($db);
		$dataScale = $cs->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		foreach ( $data as $i => $v ) {
			$id =  $v["id"];
			// $result[$i]["id"] = $id;
			// $result[$i]["ref"] = $v["ref"];
			// $result[$i]["billStatus"] = (int)$v["bill_status"];
			// $result[$i]["billStatusStr"] = $this->billStatusCodeToName($v["bill_status"]);
			// $result[$i]["dealDate"] = $this->toYMD($v["deal_date"]);
			// $result[$i]["goodsMoney"] = $v["goods_money"];
			// 明细表
			$sql = "select s.id, s.goods_id, g.code, g.name, g.spec,
						convert(s.goods_count, " . $fmt . ") as goods_count, s.goods_price, s.goods_money,
						s.tax_rate, s.tax, s.money_with_tax, u.name as unit_name,
						convert(s.ws_count, " . $fmt . ") as ws_count,
						convert(s.left_count, " . $fmt . ") as left_count,
						s.memo, s.scbilldetail_id
					from t_so_bill_detail s, t_goods g, t_goods_unit u
					where s.sobill_id = '%s' and s.goods_id = g.id and g.unit_id = u.id
					order by s.show_order";
			$items = [];
			$data = $db->query($sql, $id);
			foreach ( $data as $v ) {
				$lastPrice="-";//最后一次的价格
				$goodsId = $v["goods_id"];
				if($customerId!="")
				{
					//查询当前客户可选商品的销售价格，找到最近的一次的销售价
					$dateStart=date('Y-m-d',strtotime('-365 day'))." 0:00:00";//查询起始日期，为一年前
					$dateEnd=date('Y-m-d')." 23:59:59";
					$sql = "select b.id, d.goods_price from t_so_bill b, t_so_bill_detail d where b.customer_id = '%s' and d.goods_id='%s' and b.id = d.sobill_id and d.date_created between '%s' and '%s' order by d.date_created   DESC";	
					$d = $db->query($sql, $customerId,$goodsId,$dateStart,$dateEnd);
					$dCount=sizeof($d);
					if($dCount>0){
						$lastPrice = $d[0]["goods_price"];
					}
				}
				$items[] = [
					"goodsId" => $goodsId,
					"goodsCode" => $v["code"],
					"goodsName" => $v["name"],
					"goodsSpec" => $v["spec"],
					"goodsCount" => (int)$v["goods_count"],
					"goodsPrice" => (float)$v["goods_price"],
					"goodsMoney" => $v["goods_money"],
					"taxRate" => $v["tax_rate"],
					"tax" => $v["tax"],
					"moneyWithTax" => $v["money_with_tax"],
					"unitName" => $v["unit_name"],
					"memo" => $v["memo"],
					"lastPrice" => $lastPrice,
					"scbillDetailId" => $v["scbilldetail_id"],
					"wsCount" => $v["ws_count"],
					"leftCount" => $v["left_count"],
						
				];
			}
			$result[$i]["items"] = $items;

		}
				$sql = "select count(*) as cnt
				from t_so_bill s, t_customer c, t_org o, t_user u1, t_user u2
				where (s.customer_id = c.id) and (s.org_id = o.id)
					and (s.biz_user_id = u1.id) and (s.input_user_id = u2.id)
				";
		$queryParams = [];
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::SALE_ORDER, "s", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		if ($customerId) {
			$sql .= " and (s.customer_id = '%s')";
			$queryParams[] = $customerId;
		}
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		
		$totalPage = ceil($cnt / $limit);
		
		return array(
				"dataList" => $result,
				"totalPage" => $totalPage
		);
	}

	public function soBillInfo($params) {
		$db = $this->db;
		
		// 采购订单主表id
		$id = $params["id"];
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$result = [];
		
		$cs = new BizConfigDAO($db);
		$dataScale = $cs->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$sql = "select s.ref, s.deal_date, s.deal_address, s.customer_id,
					c.name as customer_name, s.contact, s.tel, s.fax, s.date_created,
					s.org_id, o.full_name, s.biz_user_id, u.name as biz_user_name,u2.name as input_user_name,
					s.receiving_type,s.distribution_type, s.bill_memo, s.bill_status,
					s.goods_money, s.tax, s.money_with_tax
				from t_so_bill s, t_customer c, t_user u, t_user u2, t_org o
				where s.id = '%s' and s.customer_Id = c.id
					and s.biz_user_id = u.id
					and s.org_id = o.id
					and s.input_user_id = u2.id";
					
		$data = $db->query($sql, $id);
		if ($data) {
			$v = $data[0];
			$result["ref"] = $v["ref"];
			$result["dealDate"] = $this->toYMD($v["deal_date"]);
			$result["dealAddress"] = $v["deal_address"];
			$result["customerId"] = $v["customer_id"];
			$result["customerName"] = $v["customer_name"];
			$result["contact"] = $v["contact"];
			$result["tel"] = $v["tel"];
			$result["fax"] = $v["fax"];
			$result["orgId"] = $v["org_id"];
			$result["orgFullName"] = $v["full_name"];
			$result["bizUserId"] = $v["biz_user_id"];
			$result["bizUserName"] = $v["biz_user_name"];
			$result["receivingType"] = (int)$v["receiving_type"];
			$result["distributionType"] = $v["distribution_type"];
			$result["receivingTypeStr"] = $this->receivingTypeCodeToName($v["receiving_type"]);
			$result["billMemo"] = $v["bill_memo"];
			$result["billStatus"] = (int)$v["bill_status"];
			$result["billStatusStr"] = $this->billStatusCodeToName($v["bill_status"]);
			$result["goodsMoney"] = $v["goods_money"];
			$result["inputUserName"] = $v["input_user_name"];
			$result["tax"] = $v["tax"];
			$result["dateCreated"] = $v["date_created"];
			$result["moneyWithTax"] = $v["money_with_tax"];
			// 明细表
			$sql = "select s.id, s.goods_id, g.code, g.name, g.spec,g.category_id,
						convert(s.goods_count, " . $fmt . ") as goods_count, s.goods_price, s.goods_money,
						s.tax_rate, s.tax, s.money_with_tax, u.name as unit_name,g.unit2_decimal,u2.name as unit2_name,g.unit3_decimal,u3.name  as unit3_name,
						convert(s.ws_count, " . $fmt . ") as ws_count,
						convert(s.left_count, " . $fmt . ") as left_count,
						s.memo, s.unit_result, s.scbilldetail_id
					from t_so_bill_detail s join  t_goods g on  s.goods_id = g.id  join t_goods_unit u on  g.unit_id = u.id left join t_goods_unit u2 on  g.unit2_id = u2.id  left join t_goods_unit u3 on  g.unit3_id = u3.id 
					where s.sobill_id = '%s' 
					order by s.show_order";
			$items = [];
			$data = $db->query($sql, $id);
			
			foreach ( $data as $v ) {
				$items[] = [
						"goodsId" => $v["goods_id"],
						"goodsCode" => $v["code"],
						"goodsName" => $v["name"],
						"goodsSpec" => $v["spec"],
						"goodsCount" => (int)$v["goods_count"],
						"goodsPrice" => (float)$v["goods_price"],
						"goodsMoney" => $v["goods_money"],
						"taxRate" => $v["tax_rate"],
						"categoryId" => $v["category_id"],
						"tax" => $v["tax"],
						"moneyWithTax" => $v["money_with_tax"],
						"unitName" => $v["unit_name"],
						"unit2Name" => $v["unit2_name"],
						"unit2Decimal" => $v["unit2_decimal"],
						"unit3Decimal" => $v["unit3_decimal"],
						"unit3Name" => $v["unit3_name"],
						"memo" => $v["memo"],
						"unitResult" => $v["unit_result"],
						"scbillDetailId" => $v["scbilldetail_id"],
						"wsCount" => $v["ws_count"],
						"leftCount" => $v["left_count"],
				];
			}

			$sql = "select w.id, w.ref, w.bizdt, c.name as customer_name, u.name as biz_user_name,
			user.name as input_user_name, h.name as warehouse_name, w.sale_money,
			w.bill_status, w.date_created, w.receiving_type, w.memo
		from t_ws_bill w, t_customer c, t_user u, t_user user, t_warehouse h,
			t_so_ws s
		where (w.customer_id = c.id) and (w.biz_user_id = u.id)
			and (w.input_user_id = user.id) and (w.warehouse_id = h.id) 
			and (w.id = s.ws_id) and (s.so_id = '%s')";

			$data = $db->query($sql, $id);
			$wsbill = array();

			foreach ( $data as $v ) {
				$item = array(
						"id" => $v["id"],
						"ref" => $v["ref"],
						"bizDate" => $this->toYMD($v["bizdt"]),
						"customerName" => $v["customer_name"],
						"warehouseName" => $v["warehouse_name"],
						"inputUserName" => $v["input_user_name"],
						"bizUserName" => $v["biz_user_name"],
						"billStatusStr" => $this->wsbillStatusCodeToName($v["bill_status"]),
						"billStatus" => $v["bill_status"],
						"amount" => $v["sale_money"],
						"dateCreated" => $v["date_created"],
						"receivingType" => $v["receiving_type"],
						"memo" => $v["memo"]
				);
				$wsbill[] = $item;
			}
			$result["wsbill"] = $wsbill;
			
			$result["items"] = $items;
		}
		
		return $result;
	}
		/**
	 * 新建销售订单
	 *
	 * @param array $bill        	
	 * @return null|array
	 */
	public function addSOBill(& $bill) {
		$db = $this->db;
		
		$dealDate = $bill["dealDate"];
		if (! $this->dateIsValid($dealDate)) {
			return $this->bad("交货日期不正确");
		}

		$customerId = $bill["customerId"];
		$CustomerApiDAO = new CustomerApiDAO($db);
		$customer = $CustomerApiDAO->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("客户不存在");
		}
		
		$orgId = $bill["orgId"];
		$orgDAO = new OrgDAO($db);
		$org = $orgDAO->getOrgById($orgId);
		if (! $org) {
			return $this->bad("组织机构不存在");
		}
		
		$bizUserId = $bill["bizUserId"];
		$userDAO = new UserApiDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("业务员不存在");
		}
		
		$receivingType = $bill["receivingType"];
		$distributionType = $bill["distributionType"];
		$contact = $bill["contact"];
		$tel = $bill["tel"];
		$fax = $bill["fax"];
		$dealAddress = $bill["dealAddress"];
		$billMemo = $bill["billMemo"];
		
		$items = $bill["items"];
		
		$companyId = $bill["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->bad("所属公司不存在");
		}
		
		$dataOrg = $bill["dataOrg"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		
		$loginUserId = $bill["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
			//默认订单状态 支持根据组织机构设置是否需要审核
			$billStatus = 0;
			$userDAO = new UserDAO($db);
			$isCheckBill= $userDAO->getIsCheckBill($loginUserId);
			if( $isCheckBill==0){
				$billStatus=1000;
			}
		// 销售合同号
		// 当销售订单是由销售合同创建的时候，销售合同号就不为空
		$scbillRef = $bill["scbillRef"];
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$id = $this->newId();
		$ref = $this->genNewBillRef($companyId);
		
		// 主表
		$sql = "insert into t_so_bill(id, ref, bill_status, deal_date, biz_dt, org_id, biz_user_id,
					goods_money, tax, money_with_tax, input_user_id, customer_id, contact, tel, fax,
					deal_address, bill_memo, receiving_type, date_created, data_org, company_id,distribution_type)
				values ('%s', '%s',%d, '%s', '%s', '%s', '%s',
					0, 0, 0, '%s', '%s', '%s', '%s', '%s',
					'%s', '%s', %d, now(), '%s', '%s',%d)";
		$rc = $db->execute($sql, $id, $ref,$billStatus, $dealDate, $dealDate, $orgId, $bizUserId, $loginUserId, 
				$customerId, $contact, $tel, $fax, $dealAddress, $billMemo, $receivingType, $dataOrg, 
				$companyId,$distributionType);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		$sql_insert = "insert into t_so_bill_detail(id, date_created, goods_id, goods_count, goods_money,
		goods_price, sobill_id, tax_rate, tax, money_with_tax, ws_count, left_count,
		show_order, data_org, company_id, memo, scbilldetail_id,unit_result)
		values ";
		$sql_insertvalues = [];
		// 明细记录
		foreach ( $items as $i => $v ) {
			$goodsId = $v["goodsId"];
			if (! $goodsId) {
				continue;
			}
			$goodsCount = $v["goodsCount"];
			//验证是否库存足够 避免下单完成无法足额出库的问题 taoys 20200428
			$sql = "select  g.`name`, IFNULL(i.balance_count,0)balance_count,IFNULL(t.goods_count,0)goods_count  from t_goods g left join  t_inventory i on g.id=i.goods_id left join (select  sum(d.left_count) goods_count ,d.goods_id from t_so_bill_detail d where  d.goods_id ='%s') t on g.id = t.goods_id
			where g.id ='%s'";
			$data = $db->query($sql, $goodsId,$goodsId);
		
			if($data){
				$name = $data[0]["name"];
				$balanceCount = $data[0]["balance_count"];//现有库存数量
				$preOutCount  = $data[0]["goods_count"];//待出库的数量
				$tempCount = $balanceCount-$preOutCount-$goodsCount;
				if($tempCount<0){
					return $this->bad('['.$name.']库存不足,请联系库房!');
				}
			}


			//end 20200428
			
			$goodsPrice = $v["goodsPrice"];
			$goodsMoney = $v["goodsMoney"];
			$taxRate = $v["taxRate"];
			$tax = $v["tax"];
			$moneyWithTax = $v["moneyWithTax"];
			$memo = $v["memo"];
			$scbillDetailId = $v["scbillDetailId"];
			$unitResult = $v["unitResult"];
			if(!$unitResult){
				$sql  = "select  unit_id, u1.name as unit_name,unit2_decimal,u2.name as unit2_name,unit3_decimal,u3.name as unit3_name from t_goods g  join t_goods_unit u1 on g.unit_id = u1.id left join  t_goods_unit u2 on g.unit2_id = u2.id left join  t_goods_unit u3 on g.unit3_id = u3.id where g.id= '%s'";
				$unitObj = $db->query($sql,$goodsId);
				if($unitObj){
					$unitName= $unitObj[0]["unit_name"];
					$unit2Decimal = $unitObj[0]["unit2_decimal"];
					$unit2Name = $unitObj[0]["unit2_name"];
					$unit3Decimal = $unitObj[0]["unit3_decimal"];
					$unit3Name = $unitObj[0]["unit3_name"];
					$unitResultTemp = "";
					$tempCount =  $goodsCount;
					if($unit3Decimal>0){
						$unit3Count = floor($tempCount/$unit3Decimal);
						if($unit3Count>0){
							$unitResultTemp =$unitResultTemp .$unit3Count.$unit3Name;
							$tempCount=$goodsCount%$unit3Decimal;
						}
					}
					if($unit2Decimal>0){
						$unit2Count = floor($tempCount/$unit2Decimal);
						if($unit2Count>0){
							$unitResultTemp =$unitResultTemp .$unit2Count.$unit2Name;
							$tempCount=$tempCount%$unit2Decimal;
						}
					}
					if($tempCount>0){
						$unitResultTemp=$unitResultTemp.$tempCount.$unitName;
					}
					$unitResult = $unitResultTemp;
				}

			}
			$sql_insertvalues[] =  "('".$this->newId()."', now(), '".$goodsId."', convert(".$goodsCount.", $fmt), ".$goodsMoney.",
			".$goodsPrice.", '".$id."', ".$taxRate.", ".$tax.", ".$moneyWithTax.", 0, convert(".$goodsCount.", $fmt),".$i.", '".$dataOrg."', '".$companyId."', '".$memo."', '".$scbillDetailId."', '".$unitResult."')";
			
			// $sql = "insert into t_so_bill_detail(id, date_created, goods_id, goods_count, goods_money,
			// 			goods_price, sobill_id, tax_rate, tax, money_with_tax, ws_count, left_count,
			// 			show_order, data_org, company_id, memo, scbilldetail_id,unit_result)
			// 		values ('%s', now(), '%s', convert(%f, $fmt), %f,
			// 			%f, '%s', %d, %f, %f, 0, convert(%f, $fmt), %d, '%s', '%s', '%s', '%s','%s')";
			// $rc = $db->execute($sql, $this->newId(), $goodsId, $goodsCount, $goodsMoney, 
			// 		$goodsPrice, $id, $taxRate, $tax, $moneyWithTax, $goodsCount, $i, $dataOrg, 
			// 		$companyId, $memo, $scbillDetailId,$unitResult);
			// if ($rc === false) {
			// 	return $this->sqlError(__METHOD__, __LINE__);
			// }
		}
			if(count(	$sql_insertvalues)>0) {
			$values = implode(",", $sql_insertvalues);
			$sql = $sql_insert.$values;
			$rc = $db->execute($sql);
		if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		// 同步主表的金额合计字段
		$sql = "select sum(goods_money) as sum_goods_money, sum(tax) as sum_tax,
					sum(money_with_tax) as sum_money_with_tax
				from t_so_bill_detail
				where sobill_id = '%s' ";
		$data = $db->query($sql, $id);
		$sumGoodsMoney = $data[0]["sum_goods_money"];
		if (! $sumGoodsMoney) {
			$sumGoodsMoney = 0;
		}
		$sumTax = $data[0]["sum_tax"];
		if (! $sumTax) {
			$sumTax = 0;
		}
		$sumMoneyWithTax = $data[0]["sum_money_with_tax"];
		if (! $sumMoneyWithTax) {
			$sumMoneyWithTax = 0;
		}
		
		$sql = "update t_so_bill
				set goods_money = %f, tax = %f, money_with_tax = %f
				where id = '%s' ";
		$rc = $db->execute($sql, $sumGoodsMoney, $sumTax, $sumMoneyWithTax, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 关联销售合同和销售订单
		if ($scbillRef) {
			$sql = "select id from t_sc_bill where ref = '%s' ";
			$data = $db->query($sql, $scbillRef);
			if ($data) {
				$scbillId = $data[0]["id"];
				
				$sql = "insert into t_sc_so(sc_id, so_id) values ('%s', '%s')";
				$rc = $db->execute($sql, $scbillId, $id);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
		}
		
		// 操作成功
		$bill["id"] = $id;
		$bill["ref"] = $ref;
		
		return null;
	}

	
	/**
	 * 生成新的销售订单号
	 *
	 * @param string $companyId        	
	 * @return string
	 */
	private function genNewBillRef($companyId) {
		$db = $this->db;
		
		$bs = new BizConfigDAO($db);
		$pre = $bs->getSOBillRefPre($companyId);
		
		$mid = date("Ymd");
		
		$sql = "select ref from t_so_bill where ref like '%s' order by ref desc limit 1";
		$data = $db->query($sql, $pre . $mid . "%");
		$sufLength = 3;
		$suf = str_pad("1", $sufLength, "0", STR_PAD_LEFT);
		if ($data) {
			$ref = $data[0]["ref"];
			$nextNumber = intval(substr($ref, strlen($pre . $mid))) + 1;
			$suf = str_pad($nextNumber, $sufLength, "0", STR_PAD_LEFT);
		}
		
		return $pre . $mid . $suf;
	}

	/**
	 * 通过销售订单id查询销售订单
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getSOBillById($id) {
		$db = $this->db;
		
		$sql = "select ref, data_org, bill_status, company_id from t_so_bill where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! $data) {
			return null;
		} else {
			return array(
					"ref" => $data[0]["ref"],
					"dataOrg" => $data[0]["data_org"],
					"billStatus" => $data[0]["bill_status"],
					"companyId" => $data[0]["company_id"]
			);
		}
	}

	/**
	 * 编辑销售订单
	 *
	 * @param array $bill        	
	 * @return null|array
	 */
	public function updateSOBill(& $bill) {
		$db = $this->db;
		
		$id = $bill["id"];
		
		$dealDate = $bill["dealDate"];
		if (! $this->dateIsValid($dealDate)) {
			return $this->bad("交货日期不正确");
		}
		
		$customerId = $bill["customerId"];
		$customerDAO = new CustomerApiDAO($db);
		$customer = $customerDAO->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("客户不存在");
		}
		
		$orgId = $bill["orgId"];
		$orgDAO = new OrgDAO($db);
		$org = $orgDAO->getOrgById($orgId);
		if (! $org) {
			return $this->bad("组织机构不存在");
		}
		
		$bizUserId = $bill["bizUserId"];
		$userDAO = new UserApiDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("业务员不存在");
		}
		
		$receivingType = $bill["receivingType"];
		$contact = $bill["contact"];
		$tel = $bill["tel"];
		$fax = $bill["fax"];
		$dealAddress = $bill["dealAddress"];
		$billMemo = $bill["billMemo"];
		
		$items = $bill["items"];
		
		$loginUserId = $bill["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$oldBill = $this->getSOBillById($id);
		
		if (! $oldBill) {
			return $this->bad("要编辑的销售订单不存在");
		}
		$ref = $oldBill["ref"];
		$dataOrg = $oldBill["dataOrg"];
		$companyId = $oldBill["companyId"];
		$billStatus = $oldBill["billStatus"];
		if ($billStatus != 0) {
			return $this->bad("当前销售订单已经审核，不能再编辑");
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$sql = "delete from t_so_bill_detail where sobill_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$sql_insert = "insert into t_so_bill_detail(id, date_created, goods_id, goods_count, goods_money,
		goods_price, sobill_id, tax_rate, tax, money_with_tax, ws_count, left_count,
		show_order, data_org, company_id, memo, scbilldetail_id,unit_result)
		values ";
		$sql_insertvalues = [];
		foreach ( $items as $i => $v ) {
			$goodsId = $v["goodsId"];
			if (! $goodsId) {
				continue;
			}

			$goodsCount = $v["goodsCount"];
			//验证是否库存足够 避免下单完成无法足额出库的问题 taoys 20200428
			$sql = "select  g.`name`, IFNULL(i.balance_count,0)balance_count,IFNULL(t.goods_count,0)goods_count  from t_goods g left join  t_inventory i on g.id=i.goods_id left join (select  sum(d.left_count) goods_count ,d.goods_id from t_so_bill_detail d where  d.goods_id ='%s') t on g.id = t.goods_id
			where g.id ='%s'";
			$data = $db->query($sql, $goodsId,$goodsId);
		
			if($data){
				$name = $data[0]["name"];
				$balanceCount = $data[0]["balance_count"];//现有库存数量
				$preOutCount  = $data[0]["goods_count"];//待出库的数量
				$tempCount = $balanceCount-$preOutCount-$goodsCount;
				if($tempCount<0){
					return $this->bad('['.$name.']库存不足,请联系库房!');
				}
			}



			$goodsCount = $v["goodsCount"];
			$goodsPrice = $v["goodsPrice"];
			$goodsMoney = $v["goodsMoney"];
			$taxRate = $v["taxRate"];
			$tax = $v["tax"];
			$moneyWithTax = $v["moneyWithTax"];
			$memo = $v["memo"];
			$scbillDetailId = $v["scbillDetailId"];
			$unitResult = '';
			if(!$unitResult||$unitResult=='NaN'){
				$sql  = "select  unit_id, u1.name as unit_name,unit2_decimal,u2.name as unit2_name,unit3_decimal,u3.name as unit3_name from t_goods g  join t_goods_unit u1 on g.unit_id = u1.id left join  t_goods_unit u2 on g.unit2_id = u2.id left join  t_goods_unit u3 on g.unit3_id = u3.id where g.id= '%s'";
				$unitObj = $db->query($sql,$goodsId);
				if($unitObj){
					$unitName= $unitObj[0]["unit_name"];
					$unit2Decimal = $unitObj[0]["unit2_decimal"];
					$unit2Name = $unitObj[0]["unit2_name"];
					$unit3Decimal = $unitObj[0]["unit3_decimal"];
					$unit3Name = $unitObj[0]["unit3_name"];
					$unitResultTemp = "";
					$tempCount =  $goodsCount;
					if($unit3Decimal>0){
						$unit3Count = floor($tempCount/$unit3Decimal);
						if($unit3Count>0){
							$unitResultTemp =$unitResultTemp .$unit3Count.$unit3Name;
							$tempCount=$goodsCount%$unit3Decimal;
						}
					}
					if($unit2Decimal>0){
						$unit2Count = floor($tempCount/$unit2Decimal);
						if($unit2Count>0){
							$unitResultTemp =$unitResultTemp .$unit2Count.$unit2Name;
							$tempCount=$tempCount%$unit2Decimal;
						}
					}
					if($tempCount>0){
						$unitResultTemp=$unitResultTemp.$tempCount.$unitName;
					}
					$unitResult = $unitResultTemp;
				}

			}
			// $sql = "insert into t_so_bill_detail(id, date_created, goods_id, goods_count, goods_money,
			// 			goods_price, sobill_id, tax_rate, tax, money_with_tax, ws_count, left_count,
			// 			show_order, data_org, company_id, memo, scbilldetail_id,unit_result)
			// 		values ('%s', now(), '%s', convert(%f, $fmt), %f,
			// 			%f, '%s', %d, %f, %f, 0, convert(%f, $fmt), %d, '%s', '%s', '%s', '%s', '%s')";
			// $rc = $db->execute($sql, $this->newId(), $goodsId, $goodsCount, $goodsMoney, 
			// 		$goodsPrice, $id, $taxRate, $tax, $moneyWithTax, $goodsCount, $i, $dataOrg, 
			// 		$companyId, $memo, $scbillDetailId,$unitResult);
			$sql_insertvalues[] =  "('".$this->newId()."', now(), '".$goodsId."', convert(".$goodsCount.", $fmt), ".$goodsMoney.",
		".$goodsPrice.", '".$id."', ".$taxRate.", ".$tax.", ".$moneyWithTax.", 0, convert(".$goodsCount.", $fmt),".$i.", '".$dataOrg."', '".$companyId."', '".$memo."', '".$scbillDetailId."', '".$unitResult."')";
			// if ($rc === false) {
			// 	return $this->sqlError(__METHOD__, __LINE__);
			// }
		}
		if(count(	$sql_insertvalues)>0) {
			$values = implode(",", $sql_insertvalues);
			$sql = $sql_insert.$values;
			$rc = $db->execute($sql);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 同步主表的金额合计字段
		$sql = "select sum(goods_money) as sum_goods_money, sum(tax) as sum_tax,
					sum(money_with_tax) as sum_money_with_tax
				from t_so_bill_detail
				where sobill_id = '%s' ";
		$data = $db->query($sql, $id);
		$sumGoodsMoney = $data[0]["sum_goods_money"];
		if (! $sumGoodsMoney) {
			$sumGoodsMoney = 0;
		}
		$sumTax = $data[0]["sum_tax"];
		if (! $sumTax) {
			$sumTax = 0;
		}
		$sumMoneyWithTax = $data[0]["sum_money_with_tax"];
		if (! $sumMoneyWithTax) {
			$sumMoneyWithTax = 0;
		}
		
		$sql = "update t_so_bill
				set goods_money = %f, tax = %f, money_with_tax = %f,
					deal_date = '%s', customer_id = '%s',
					deal_address = '%s', contact = '%s', tel = '%s', fax = '%s',
					org_id = '%s', biz_user_id = '%s', receiving_type = %d,
					bill_memo = '%s', input_user_id = '%s', date_created = now()
				where id = '%s' ";
		$rc = $db->execute($sql, $sumGoodsMoney, $sumTax, $sumMoneyWithTax, $dealDate, $customerId, 
				$dealAddress, $contact, $tel, $fax, $orgId, $bizUserId, $receivingType, $billMemo, 
				$loginUserId, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$bill["ref"] = $ref;
		
		return null;
	}



	/**
	 * 审核销售订单
	 *
	 * @param array $params        	
	 * @return null|array
	 */
	public function commitSOBill(& $params) {
		$db = $this->db;
		
		$loginUserId = $params["loginUserId"];
		
		$id = $params["id"];
		
		$bill = $this->getSOBillById($id);
		
		if (! $bill) {
			return $this->bad("要审核的销售订单不存在");
		}
		$ref = $bill["ref"];
		$billStatus = $bill["billStatus"];
		if ($billStatus > 0) {
			return $this->bad("销售订单(单号：$ref)已经被审核，不能再次审核");
		}
		
		$sql = "update t_so_bill
				set bill_status = 1000,
					confirm_user_id = '%s',
					confirm_date = now()
				where id = '%s' ";
		$rc = $db->execute($sql, $loginUserId, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$params["ref"] = $ref;
		
		return null;
	}

	/**
	 * 取消销售订单审核
	 */
	public function cancelConfirmSOBill(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$bill = $this->getSOBillById($id);
		
		if (! $bill) {
			return $this->bad("要取消审核的销售订单不存在");
		}
		$ref = $bill["ref"];
		$billStatus = $bill["billStatus"];
		if ($billStatus == 0) {
			return $this->bad("销售订单(单号:{$ref})还没有审核，无需取消审核操作");
		}
		if ($billStatus > 1000) {
			return $this->bad("销售订单(单号:{$ref})不能取消审核");
		}
		
		$sql = "select count(*) as cnt from t_so_ws where so_id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("销售订单(单号:{$ref})已经生成了销售出库单，不能取消审核");
		}
		
		$sql = "update t_so_bill
				set bill_status = 0, confirm_user_id = null, confirm_date = null
				where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$params["ref"] = $ref;
		
		// 操作成功
		return null;
	}

		/**
	 * 删除销售订单
	 *
	 * @param array $params        	
	 * @return null|array
	 */
	public function deleteSOBill(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$bill = $this->getSOBillById($id);
		
		if (! $bill) {
			return $this->bad("要删除的销售订单不存在");
		}
		$ref = $bill["ref"];
		$billStatus = $bill["billStatus"];
		if ($billStatus > 0) {
			return $this->bad("销售订单(单号：{$ref})已经审核，不能被删除");
		}
		
		$sql = "delete from t_so_bill_detail where sobill_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$sql = "delete from t_so_bill where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 删除销售订单和销售合同的关联
		$sql = "delete from t_sc_so where so_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$params["ref"] = $ref;
		
		return null;
	}

		/**
	 * 关闭销售订单
	 */
	public function closeSOBill(&$params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$sql = "select ref, bill_status
				from t_so_bill
				where id = '%s' ";
		$data = $db->query($sql, $id);
		
		if (! $data) {
			return $this->bad("要关闭的销售订单不存在");
		}
		
		$ref = $data[0]["ref"];
		$billStatus = $data[0]["bill_status"];
		
		if ($billStatus >= 4000) {
			return $this->bad("销售订单已经被关闭");
		}
		
		// 检查该销售订单是否有生成的销售出库单，并且这些销售出库单是没有提交出库的
		// 如果存在这类销售出库单，那么该销售订单不能关闭。
		$sql = "select count(*) as cnt
				from t_ws_bill w, t_so_ws s
				where w.id = s.ws_id and s.so_id = '%s'
					and w.bill_status = 0 ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			$info = "当前销售订单生成的出库单中还有没提交的，把这些出库单删除后，才能关闭采购订单";
			return $this->bad($info);
		}
		
		if ($billStatus < 1000) {
			return $this->bad("当前销售订单还没有审核，没有审核的销售订单不能关闭");
		}
		
		$newBillStatus = - 1;
		if ($billStatus == 1000) {
			// 当前订单只是审核了
			$newBillStatus = 4000;
		} else if ($billStatus == 2000) {
			// 部分出库
			$newBillStatus = 4001;
		} else if ($billStatus == 3000) {
			// 全部出库
			$newBillStatus = 4002;
		}
		
		if ($newBillStatus == - 1) {
			return $this->bad("当前销售订单的订单状态是不能识别的状态码：{$billStatus}");
		}
		
		$sql = "update t_so_bill
				set bill_status = %d
				where id = '%s' ";
		$rc = $db->execute($sql, $newBillStatus, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["ref"] = $ref;
		return null;
	}


	
	/**
	 * 取消订单关闭状态
	 */
	public function cancelClosedSOBill(&$params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$sql = "select ref, bill_status
				from t_so_bill
				where id = '%s' ";
		$data = $db->query($sql, $id);
		
		if (! $data) {
			return $this->bad("要关闭的销售订单不存在");
		}
		
		$ref = $data[0]["ref"];
		$billStatus = $data[0]["bill_status"];
		
		if ($billStatus < 4000) {
			return $this->bad("销售订单没有被关闭，无需取消");
		}
		
		$newBillStatus = - 1;
		if ($billStatus == 4000) {
			$newBillStatus = 1000;
		} else if ($billStatus == 4001) {
			$newBillStatus = 2000;
		} else if ($billStatus == 4002) {
			$newBillStatus = 3000;
		}
		
		if ($newBillStatus == - 1) {
			return $this->bad("当前销售订单的订单状态是不能识别的状态码：{$billStatus}");
		}
		
		$sql = "update t_so_bill
				set bill_status = %d
				where id = '%s' ";
		$rc = $db->execute($sql, $newBillStatus, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["ref"] = $ref;
		return null;
	}

		
	/**
	 * 新建或编辑的时候，获得销售出库单的详情
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function wsBillInfo($params) {
		$db = $this->db;
		
		$id = $params["id"];
		$sobillRef = $params["sobillRef"];
		
		$companyId = $params["companyId"];
		$loginUserId = $params["loginUserId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$result = [];
		
		$userDAO = new UserApiDAO($db);
		// $result["canEditGoodsPrice"] = $this->canEditGoodsPrice($companyId, $loginUserId);
		// $result["showAddCustomerButton"] = $userDAO->hasPermission($loginUserId, FIdConst::CUSTOMER);
		
		if (! $id) {
			// 新建销售出库单
			$result["bizUserId"] = $loginUserId;
			$result["bizUserName"] = $params["loginUserName"];
			
			$ts = new BizConfigDAO($db);
			$sql = "select value from t_config 
					where id = '2002-02' and company_id = '%s' ";
			$data = $db->query($sql, $companyId);
			if ($data) {
				$warehouseId = $data[0]["value"];
				$sql = "select id, name from t_warehouse where id = '%s' ";
				$data = $db->query($sql, $warehouseId);
				if ($data) {
					$result["warehouseId"] = $data[0]["id"];
					$result["warehouseName"] = $data[0]["name"];
				}
			}
			
			if ($sobillRef) {
				// 由销售订单生成销售出库单
				$sql = "select s.id, s.customer_id, c.name as customer_name, s.deal_date,
							s.receiving_type, s.bill_memo, s.deal_address,s.distribution_type,s.biz_user_id,u.login_name
						from t_so_bill s, t_customer c,t_user u
						where s.ref = '%s' and s.customer_id = c.id and u.id=s.biz_user_id ";
				$data = $db->query($sql, $sobillRef);
				if ($data) {
					$v = $data[0];
					$result["customerId"] = $v["customer_id"];
					$result["customerName"] = $v["customer_name"];
					$result["dealDate"] = $this->toYMD($v["deal_date"]);
					$result["receivingType"] = $v["receiving_type"];
					$result["dealAddress"] = $v["deal_address"];
					$result["distributionType"] = $v["distribution_type"];
					$result["distributionType"] = $v["distribution_type"];
					$result["memo"] = $v["bill_memo"];
					$result["bizUserId"] = $v["biz_user_id"];
					$result["bizUserName"] = $v["login_name"];
					
					$customerDAO = new CustomerDAO($db);
					$warehosue = $customerDAO->getSalesWarehouse($v["customer_id"]);
					if ($warehosue) {
						$result["warehouseId"] = $warehosue["id"];
						$result["warehouseName"] = $warehosue["name"];
					}
					
					$pobillId = $v["id"];
					// 销售订单的明细
					$items = [];
					$sql = "select s.id, s.goods_id, g.code, g.name, g.spec, u.name as unit_name,
								convert(s.goods_count, " . $fmt . ") as goods_count, 
								s.goods_price, s.goods_money, 
								convert(s.left_count, " . $fmt . ") as left_count, s.memo,
								s.tax_rate, s.tax, s.money_with_tax
							from t_so_bill_detail s, t_goods g, t_goods_unit u
							where s.sobill_id = '%s' and s.goods_id = g.id and g.unit_id = u.id
							order by s.show_order ";
					$data = $db->query($sql, $pobillId);
					foreach ( $data as $v ) {
						//获取此订单下的待出库的出库单
						$sqlTemp="select d.id,d.goods_count from t_ws_bill_detail d,t_ws_bill b where d.wsbill_id=b.id and d.sobilldetail_id='%s' and b.bill_status=0";
						$dataTemp=$db->query($sqlTemp, $v["id"]);
						$outCount=0;//待出库的数量
						if($dataTemp){
							foreach ($dataTemp as $key) {
								$outCount+=$key["goods_count"];
							}
						}
						$taxTemp=($v["left_count"]-$outCount) * $v["goods_price"]*$v["tax_rate"]/100;
						$goodsMoneyTemp=($v["left_count"]-$outCount) * $v["goods_price"];
						
						$items[] = [
								"id" => $v["id"],
								"goodsId" => $v["goods_id"],
								"goodsCode" => $v["code"],
								"goodsName" => $v["name"],
								"goodsSpec" => $v["spec"],
								"unitName" => $v["unit_name"],
								"goodsCount" => $v["left_count"]-$outCount,
								"goodsPrice" => (float)$v["goods_price"],
								"goodsMoney" => $goodsMoneyTemp,
								"soBillDetailId" => $v["id"],
								"memo" => $v["memo"],
								"taxRate" => $v["tax_rate"],
								"tax" => $taxTemp,
								"moneyWithTax" => $goodsMoneyTemp + $taxTemp
						];
					}
					
					$result["items"] = $items;
				}
			} else {
				// 销售出库单默认收款方式
				$bc = new BizConfigDAO($db);
				$result["receivingType"] = $bc->getWSBillDefaultReceving($companyId);
			}
			
			return $result;
		} else {
			// 编辑
			// 销售出库 编辑 不传ref
			$sql = "select w.id, w.ref, w.bill_status, w.bizdt, c.id as customer_id, c.name as customer_name,
					  u.id as biz_user_id, u.name as biz_user_name,
					  h.id as warehouse_id, h.name as warehouse_name,
						w.receiving_type, w.memo, w.deal_address
					from t_ws_bill w, t_customer c, t_user u, t_warehouse h
					where w.customer_id = c.id and w.biz_user_id = u.id
					  and w.warehouse_id = h.id
					  and w.id = '%s' ";
			$data = $db->query($sql, $id);
			if ($data) {
				$result["ref"] = $data[0]["ref"];
				$result["billStatus"] = $data[0]["bill_status"];
				$result["billStatusStr"] = $this->billStatusCodeToName($data[0]["bill_status"]);
				$result["bizDT"] = date("Y-m-d", strtotime($data[0]["bizdt"]));
				$result["customerId"] = $data[0]["customer_id"];
				$result["customerName"] = $data[0]["customer_name"];
				$result["warehouseId"] = $data[0]["warehouse_id"];
				$result["warehouseName"] = $data[0]["warehouse_name"];
				$result["bizUserId"] = $data[0]["biz_user_id"];
				$result["bizUserName"] = $data[0]["biz_user_name"];
				$result["receivingType"] = $data[0]["receiving_type"];
				$result["memo"] = $data[0]["memo"];
				$result["dealAddress"] = $data[0]["deal_address"];
			}
			
			$sql = "select d.id, g.id as goods_id, g.code, g.name, g.spec, u.name as unit_name, 
						convert(d.goods_count, $fmt) as goods_count,
						d.goods_price, d.goods_money, d.sn_note, d.memo, d.sobilldetail_id,
						d.tax_rate, d.tax, d.money_with_tax
					from t_ws_bill_detail d, t_goods g, t_goods_unit u
					where d.wsbill_id = '%s' and d.goods_id = g.id and g.unit_id = u.id
					order by d.show_order";
			$data = $db->query($sql, $id);
			$items = [];
			foreach ( $data as $v ) {
				$items[] = [
						"id" => $v["id"],
						"goodsId" => $v["goods_id"],
						"goodsCode" => $v["code"],
						"goodsName" => $v["name"],
						"goodsSpec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"goodsCount" => (int)$v["goods_count"],
						"goodsPrice" => (float)$v["goods_price"],
						"goodsMoney" => $v["goods_money"],
						"sn" => $v["sn_note"],
						"memo" => $v["memo"],
						"soBillDetailId" => $v["sobilldetail_id"],
						"taxRate" => $v["tax_rate"],
						"tax" => $v["tax"],
						"moneyWithTax" => $v["money_with_tax"]
				];
			}
			
			$result["items"] = $items;
			
			return $result;
		}
	}

	/**
	 * 判断是否可以编辑商品销售单价
	 *
	 * @param string $companyId        	
	 * @param string $userId        	
	 *
	 * @return boolean true:可以编辑销售单价
	 */
	private function canEditGoodsPrice($companyId, $userId) {
		// 首先判断业务设置中是否允许销售出库编辑销售单价（全局控制）
		$db = $this->db;
		$sql = "select value from t_config 
				where id = '2002-01' and company_id = '%s' ";
		$data = $db->query($sql, $companyId);
		if (! $data) {
			return false;
		}
		
		$v = intval($data[0]["value"]);
		if ($v == 0) {
			return false;
		}
		
		$us = new UserDAO($db);
		// 在业务设置中启用编辑的前提下，还需要判断对应的权限（具体的用户）
		return $us->hasPermission($userId, "2002-01");
	}

	/**
	 * 获得销售出库单主表列表
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function wsbillList($params) {
		$db = $this->db;
		
		$loginUserId = $params["loginUserId"];
		// $page = $params["page"];
	
		// $start = $params["start"];
		// $limit = $params["limit"];

		$start = $params["start"];
		if (! $start) {
			$start = "0";
		}
		$limit = $params["limit"];
		if (! $limit) {
			$limit = 10;
		}

		$billStatus = $params["billStatus"];
		$warehouseId = $params["warehouseId"];
		// $customerId = $params["customerId"];
		$receivingType = $params["receivingType"];

		// $ref = $params["ref"];
		// $fromDT = $params["fromDT"];
		// $toDT = $params["toDT"];
		// $sn = $params["sn"];
		// $goodsId = $params["goodsId"];
		
		$sql = "select w.id, w.ref, w.bizdt, c.name as customer_name, u.name as biz_user_name,
					user.name as input_user_name, h.name as warehouse_name, w.sale_money,
					w.bill_status, w.date_created, w.receiving_type, w.memo, w.deal_address,
					w.tax, w.money_with_tax,w.distribution_type
				from t_ws_bill w, t_customer c, t_user u, t_user user, t_warehouse h
				where (w.customer_id = c.id) and (w.biz_user_id = u.id)
				  and (w.input_user_id = user.id) and (w.warehouse_id = h.id) ";
		$queryParams = [];

		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::WAREHOUSING_SALE, "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		
		if ($billStatus != - 1) {
			$sql .= " and (w.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		// if ($ref) {
		// 	$sql .= " and (w.ref like '%s') ";
		// 	$queryParams[] = "%{$ref}%";
		// }
		// if ($fromDT) {
		// 	$sql .= " and (w.bizdt >= '%s') ";
		// 	$queryParams[] = $fromDT;
		// }
		// if ($toDT) {
		// 	$sql .= " and (w.bizdt <= '%s') ";
		// 	$queryParams[] = $toDT;
		// }
		// if ($customerId) {
		// 	$sql .= " and (w.customer_id = '%s') ";
		// 	$queryParams[] = $customerId;
		// }
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = '%s') ";
			$queryParams[] = $warehouseId;
		}
		// if ($sn) {
		// 	$sql .= " and (w.id in
		// 			(select d.wsbill_id from t_ws_bill_detail d
		// 			 where d.sn_note like '%s'))";
		// 	$queryParams[] = "%$sn%";
		// }
		if ($receivingType != - 1) {
			$sql .= " and (w.receiving_type = %d) ";
			$queryParams[] = $receivingType;
		}
		// if ($goodsId) {
		// 	$sql .= " and (w.id in
		// 			(select d.wsbill_id from t_ws_bill_detail d
		// 			 where d.goods_id = '%s'))";
		// 	$queryParams[] = $goodsId;
		// }
		
		$sql .= " order by w.bizdt desc, w.ref desc
				limit %d, %d";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = $db->query($sql, $queryParams);
		$result = [];
		
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"ref" => $v["ref"],
					"bizDate" => $this->toYMD($v["bizdt"]),
					"customerName" => $v["customer_name"],
					"warehouseName" => $v["warehouse_name"],
					"inputUserName" => $v["input_user_name"],
					"bizUserName" => $v["biz_user_name"],
					"billStatus" => $v["bill_status"],
					"billStatusStr" => $this->wsbillStatusCodeToName($v["bill_status"]),
					"amount" => $v["sale_money"],
					"dateCreated" => $v["date_created"],
					// "receivingType" => $v["receiving_type"],
					"receivingType" =>  $this->receivingTypeCodeToName($v["receiving_type"]),
					"distributionType" => $v["distribution_type"],
					"memo" => $v["memo"],
					"dealAddress" => $v["deal_address"],
					"tax" => $v["tax"],
					"moneyWithTax" => $v["money_with_tax"]
			];
		}
		
		$sql = "select count(*) as cnt
				from t_ws_bill w, t_customer c, t_user u, t_user user, t_warehouse h
				where (w.customer_id = c.id) and (w.biz_user_id = u.id)
				  and (w.input_user_id = user.id) and (w.warehouse_id = h.id) ";
		$queryParams = [];
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::WAREHOUSING_SALE, "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		
		if ($billStatus != - 1) {
			$sql .= " and (w.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		// if ($ref) {
		// 	$sql .= " and (w.ref like '%s') ";
		// 	$queryParams[] = "%{$ref}%";
		// }
		// if ($fromDT) {
		// 	$sql .= " and (w.bizdt >= '%s') ";
		// 	$queryParams[] = $fromDT;
		// }
		// if ($toDT) {
		// 	$sql .= " and (w.bizdt <= '%s') ";
		// 	$queryParams[] = $toDT;
		// }
		// if ($customerId) {
		// 	$sql .= " and (w.customer_id = '%s') ";
		// 	$queryParams[] = $customerId;
		// }
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = '%s') ";
			$queryParams[] = $warehouseId;
		}
		// if ($sn) {
		// 	$sql .= " and (w.id in
		// 			(select d.wsbill_id from t_ws_bill_detail d
		// 			 where d.sn_note like '%s'))";
		// 	$queryParams[] = "%$sn%";
		// }
		if ($receivingType != - 1) {
			$sql .= " and (w.receiving_type = %d) ";
			$queryParams[] = $receivingType;
		}
		// if ($goodsId) {
		// 	$sql .= " and (w.id in
		// 			(select d.wsbill_id from t_ws_bill_detail d
		// 			 where d.goods_id = '%s'))";
		// 	$queryParams[] = $goodsId;
		// }
		
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		
		return [
				"dataList" => $result,
				"totalCount" => (int)$cnt
		];
	}

		/**
	 * 获得某个销售出库单的明细记录列表
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function wsBillDetailList($params) {
		$db = $this->db;
		
		$companyId = $params["companyId"];
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$result = [];
		//商品列表
		$billId = $params["id"];
			$sql = "select w.id, w.ref, w.bizdt, c.name as customer_name, u.name as biz_user_name,
			user.name as input_user_name, h.name as warehouse_name, w.sale_money,
			w.bill_status, w.date_created, w.receiving_type, w.memo, w.deal_address,
			w.tax, w.money_with_tax,w.distribution_type
			from t_ws_bill w, t_customer c, t_user u, t_user user, t_warehouse h
			where w.id = '%s' and (w.customer_id = c.id) and (w.biz_user_id = u.id)
		 	and (w.input_user_id = user.id) and (w.warehouse_id = h.id) ";

			$data = $db->query($sql, $billId);
			if ($data) {
				$v = $data[0];
				$result["id"] = $v["id"];
				$result["ref"] = $v["ref"];
				$result["bizDate"] = $this->toYMD($v["bizdt"]);
				$result["customerName"] = $v["customer_name"];
				$result["warehouseName"] = $v["warehouse_name"];
				$result["inputUserName"] = $v["input_user_name"];
				$result["bizUserName"] = $v["biz_user_name"];
				$result["billStatus"] = $v["bill_status"];
				$result["billStatusStr"] = $this->wsbillStatusCodeToName($v["bill_status"]);
				$result["amount"] = $v["sale_money"];
				$result["dateCreated"] = $v["date_created"];
				// "receivingType" = $v["receiving_type"],
				$result["receivingType"] =  $this->receivingTypeCodeToName($v["receiving_type"]);
				$result["distributionType"] = $v["distribution_type"];
				$result["memo"] = $v["memo"];
				$result["dealAddress"] = $v["deal_address"];
				$result["tax"] = $v["tax"];
				$result["moneyWithTax"] = $v["money_with_tax"];
			
				// $result["customerId"] = $v["customer_id"];
				// $result["customerName"] = $v["customer_name"];
				// $result["dealDate"] = $this->toYMD($v["deal_date"]);
				// $result["receivingType"] = $v["receiving_type"];
				// $result["memo"] = $v["bill_memo"];
				// $result["dealAddress"] = $v["deal_address"];

				// $customerDAO = new CustomerDAO($db);
				// $warehosue = $customerDAO->getSalesWarehouse($v["customer_id"]);
				// if ($warehosue) {
				// 	$result["warehouseId"] = $warehosue["id"];
				// 	$result["warehouseName"] = $warehosue["name"];
				// }
		}

		$sql = "select d.id, g.code, g.name, g.spec, u.name as unit_name,
		convert(d.goods_count, $fmt) as goods_count,
		d.goods_price, d.goods_money, d.sn_note, d.memo
		from t_ws_bill_detail d, t_goods g, t_goods_unit u
		where d.wsbill_id = '%s' and d.goods_id = g.id and g.unit_id = u.id
		order by d.show_order";
		$data = $db->query($sql, $billId);

		$goods = [];
		
		foreach ( $data as $v ) {
			$goods[] = [
					"id" => $v["id"],
					"goodsCode" => $v["code"],
					"goodsName" => $v["name"],
					"goodsSpec" => $v["spec"],
					"unitName" => $v["unit_name"],
					"goodsCount" => (int)$v["goods_count"],
					"goodsPrice" => (float)$v["goods_price"],
					"goodsMoney" => $v["goods_money"],
					"sn" => $v["sn_note"],
					"memo" => $v["memo"],
					"taxRate" => $v["tax_rate"],
					"tax" => $v["tax"],
					"moneyWithTax" => $v["money_with_tax"]
			];
		}
		$result["goods"] = $goods;
		
		return $result;
	}


	/**
	 * 删除销售出库单
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function deleteWSBill(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$bill = $this->getWSBillById($id);
		
		if (! $bill) {
			return $this->bad("要删除的销售出库单不存在");
		}
		$ref = $bill["ref"];
		$billStatus = $bill["billStatus"];
		if ($billStatus != 0) {
			return $this->bad("销售出库单已经提交出库，不能删除");
		}
		
		$sql = "delete from t_ws_bill_detail where wsbill_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$sql = "delete from t_ws_bill where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 删除从销售订单生成的记录
		$sql = "delete from t_so_ws where ws_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$params["ref"] = $ref;
		
		// 操作成功
		return null;
	}

		/**
	 * 通过销售出库单id查询销售出库单
	 *
	 * @param string $id
	 *        	销售出库单id
	 * @return array|NULL
	 */
	public function getWSBillById($id) {
		$db = $this->db;
		
		$sql = "select ref, bill_status, data_org, company_id from t_ws_bill where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! $data) {
			return null;
		} else {
			return array(
					"ref" => $data[0]["ref"],
					"billStatus" => $data[0]["bill_status"],
					"dataOrg" => $data[0]["data_org"],
					"companyId" => $data[0]["company_id"]
			);
		}
	}


	
		/**
	 * 新建销售出库单
	 *
	 * @param array $bill        	
	 * @return NULL|array
	 */
	public function addWSBill(& $bill) {
		$db = $this->db;
		
		$bizDT = $bill["bizDT"];
		$warehouseId = $bill["warehouseId"];
		$customerId = $bill["customerId"];
		$bizUserId = $bill["bizUserId"];
		$receivingType = $bill["receivingType"];
		$billMemo = $bill["billMemo"];
		$items = $bill["items"];
		$dealAddress = $bill["dealAddress"];
		
		$sobillRef = $bill["sobillRef"];
		$distributionType =$bill["distributionType"];
		// 检查客户
		$customerDAO = new CustomerDAO($db);
		$customer = $customerDAO->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("选择的客户不存在，无法保存数据");
		}
		
		// 检查仓库
		$warehouseDAO = new WarehouseDAO($db);
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			return $this->bad("选择的仓库不存在，无法保存数据");
		}
		
		// 检查业务员
		$userDAO = new UserApiDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("选择的业务员不存在，无法保存数据");
		}
		
		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
		}

		//检查出库单出库货物数量，对比订单所剩的数量，如果高于剩余数量，则不能提交
		$sobillRef = $bill["sobillRef"];
		if($sobillRef)
		{
			foreach ( $items as $i => $v ) {
				if($v["goodsCount"]==0){
					return $this->bad("提交0条出库商品，无法保存数据");
				}

				//查询订单明细表中未出库的数量
				$sqlTemp="select d.left_count,g.name from t_so_bill_detail d,t_goods g where d.goods_id=g.id and d.id='%s'";
				$dataTemp=$db->query($sqlTemp, $v["soBillDetailId"]);
				$goodsTemp="";
				$leftCountTemp=0;
				if($dataTemp){
					$leftCountTemp=$dataTemp[0]["left_count"];
					$goodsTemp=$dataTemp[0]["name"];
				}

				//查询待出库数量
				$sqlTemp="select d.id,d.goods_count from t_ws_bill_detail d,t_ws_bill b where d.wsbill_id=b.id and d.sobilldetail_id='%s' and b.bill_status=0";
				$dataTemp=$db->query($sqlTemp, $v["soBillDetailId"]);
				$tempCount=0;
				if($dataTemp){
					$tempCount=$dataTemp[0]["goods_count"];
				}
				$leftCountTemp=$leftCountTemp-$tempCount;
				if($v["goodsCount"]>$leftCountTemp){

					return $this->bad("提交的商品“".$goodsTemp."”出库数量大于订单，无法保存数据");
				}
			}
		}
		$dataOrg = $bill["dataOrg"];
		$companyId = $bill["companyId"];
		$loginUserId = $bill["loginUserId"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		//默认订单状态 支持根据组织机构设置是否需要审核
		$billStatus = 0;
		$userDAO = new UserDAO($db);
		$isCheckBill= $userDAO->getIsCheckBill($loginUserId);
		if( $isCheckBill==0){
			$billStatus=1000;
		}
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// 主表
		$id = $this->newId();
		$ref = $this->genNewBillRef($companyId);
		$sql = "insert into t_ws_bill(id, bill_status, bizdt, biz_user_id, customer_id,  date_created,
					input_user_id, ref, warehouse_id, receiving_type, data_org, company_id, memo, deal_address,distribution_type)
				values ('%s', 0, '%s', '%s', '%s', now(), '%s', '%s', '%s', %d, '%s', '%s', '%s', '%s',%d)";
		
		$rc = $db->execute($sql, $id, $bizDT, $bizUserId, $customerId, $loginUserId, $ref, 
				$warehouseId, $receivingType, $dataOrg, $companyId, $billMemo, $dealAddress,$distributionType);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 明细表
		$sql = "insert into t_ws_bill_detail (id, date_created, goods_id,
					goods_count, goods_price, goods_money,
					show_order, wsbill_id, sn_note, data_org, memo, company_id, sobilldetail_id,
					tax_rate, tax, money_with_tax)
				values ('%s', now(), '%s', convert(%f, $fmt), %f, %f, %d, '%s', '%s', '%s', '%s', '%s', '%s',
					%d, %f, %f)";
		foreach ( $items as $i => $v ) {
			$goodsId = $v["goodsId"];
			if ($goodsId) {
				$goodsCount = $v["goodsCount"];
				$goodsPrice = floatval($v["goodsPrice"]);
				$goodsMoney = floatval($v["goodsMoney"]);
				
				$sn = $v["sn"];
				$memo = $v["memo"];
				
				$soBillDetailId = $v["soBillDetailId"];
				
				$taxRate = $v["taxRate"];
				$tax = $v["tax"];
				$moneyWithTax = $v["moneyWithTax"];
				
				$rc = $db->execute($sql, $this->newId(), $goodsId, $goodsCount, $goodsPrice, 
						$goodsMoney, $i, $id, $sn, $dataOrg, $memo, $companyId, $soBillDetailId, 
						$taxRate, $tax, $moneyWithTax);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
		}
		$sql = "select sum(goods_money) as sum_goods_money,
					sum(tax) as tax, sum(money_with_tax) as money_with_tax 
				from t_ws_bill_detail where wsbill_id = '%s' ";
		$data = $db->query($sql, $id);
		$sumGoodsMoney = $data[0]["sum_goods_money"];
		if (! $sumGoodsMoney) {
			$sumGoodsMoney = 0;
		}
		$sumTax = $data[0]["tax"];
		$sumMoneyWithTax = $data[0]["money_with_tax"];
		
		$sql = "update t_ws_bill 
				set sale_money = %f, tax = %f, money_with_tax = %f 
				where id = '%s' ";
		$rc = $db->execute($sql, $sumGoodsMoney, $sumTax, $sumMoneyWithTax, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		if ($sobillRef) {
			// 从销售订单生成销售出库单
			$sql = "select id, company_id from t_so_bill where ref = '%s' ";
			$data = $db->query($sql, $sobillRef);
			if (! $data) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			$sobillId = $data[0]["id"];
			$companyId = $data[0]["company_id"];
			
			$sql = "update t_ws_bill
					set company_id = '%s'
					where id = '%s' ";
			$rc = $db->execute($sql, $companyId, $id);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			$sql = "insert into t_so_ws(so_id, ws_id) values('%s', '%s')";
			$rc = $db->execute($sql, $sobillId, $id);
			if (! $rc) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		$bill["id"] = $id;
		$bill["ref"] = $ref;
		
		// 操作成功
		return null;
	}

		/**
	 * 编辑销售出库单
	 *
	 * @param array $bill        	
	 * @return NULL|array
	 */
	public function updateWSBill(& $bill) {
		$db = $this->db;
		
		$id = $bill["id"];
		
		$bizDT = $bill["bizDT"];
		$warehouseId = $bill["warehouseId"];
		$customerId = $bill["customerId"];
		$bizUserId = $bill["bizUserId"];
		$receivingType = $bill["receivingType"];
		$billMemo = $bill["billMemo"];
		$distributionType =$bill["distributionType"];
		$items = $bill["items"];
		$dealAddress = $bill["dealAddress"];
		
		// 检查客户
		$customerDAO = new CustomerDAO($db);
		$customer = $customerDAO->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("选择的客户不存在，无法保存数据");
		}
		
		// 检查仓库
		$warehouseDAO = new WarehouseDAO($db);
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			return $this->bad("选择的仓库不存在，无法保存数据");
		}
		
		// 检查业务员
		$userDAO = new UserApiDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("选择的业务员不存在，无法保存数据");
		}
		
		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
		}
		
		$loginUserId = $bill["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$oldBill = $this->getWSBillById($id);
		if (! $oldBill) {
			return $this->bad("要编辑的销售出库单不存在");
		}
		$ref = $oldBill["ref"];
		$billStatus = $oldBill["billStatus"];
		if ($billStatus != 0) {
			return $this->bad("销售出库单[单号：{$ref}]已经提交出库了，不能再编辑");
		}

		//检查出库单出库货物数量，对比订单所剩的数量，如果高于剩余数量，则不能提交
		$sobillRef = $bill["sobillRef"];
		if($sobillRef){
			foreach ( $items as $i => $v ) {
				if($v["goodsCount"]==0){
					return $this->bad("提交0条出库商品，无法保存数据");
				}

				//查询订单明细表中未出库的数量
				$sqlTemp="select d.left_count,g.name from t_so_bill_detail d,t_goods g where d.goods_id=g.id and d.id='%s'";
				$dataTemp=$db->query($sqlTemp, $v["soBillDetailId"]);
				$goodsTemp="";
				$leftCountTemp=0;
				if($dataTemp){
					$leftCountTemp=$dataTemp[0]["left_count"];
					$goodsTemp=$dataTemp[0]["name"];
				}

				//查询待出库数量
				$sqlTemp="select d.id,d.goods_count from t_ws_bill_detail d,t_ws_bill b where d.wsbill_id=b.id and d.sobilldetail_id='%s' and b.bill_status=0";
				$dataTemp=$db->query($sqlTemp, $v["soBillDetailId"]);
				$tempCount=0;
				if($dataTemp){
					$tempCount=$dataTemp[0]["goods_count"];
				}
				$leftCountTemp=$leftCountTemp-$tempCount;
				if($v["goodsCount"]>$leftCountTemp){

					return $this->bad("提交的商品“".$goodsTemp."”出库数量大于订单，无法保存数据");
				}
			}
		}
		$dataOrg = $oldBill["dataOrg"];
		$companyId = $oldBill["companyId"];
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$sql = "delete from t_ws_bill_detail where wsbill_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$sql = "insert into t_ws_bill_detail (id, date_created, goods_id,
					goods_count, goods_price, goods_money,
					show_order, wsbill_id, sn_note, data_org, memo, company_id, sobilldetail_id,
					tax_rate, tax, money_with_tax)
				values ('%s', now(), '%s', convert(%f, $fmt), %f, %f, %d, '%s', '%s', '%s', '%s', '%s', '%s',
					%d, %f, %f)";
		foreach ( $items as $i => $v ) {
			$goodsId = $v["goodsId"];
			if ($goodsId) {
				$goodsCount = $v["goodsCount"];
				$goodsPrice = floatval($v["goodsPrice"]);
				$goodsMoney = floatval($v["goodsMoney"]);
				
				$sn = $v["sn"];
				$memo = $v["memo"];
				
				$soBillDetailId = $v["soBillDetailId"];
				
				$taxRate = $v["taxRate"];
				$tax = $v["tax"];
				$moneyWithTax = $v["moneyWithTax"];
				
				$rc = $db->execute($sql, $this->newId(), $goodsId, $goodsCount, $goodsPrice, 
						$goodsMoney, $i, $id, $sn, $dataOrg, $memo, $companyId, $soBillDetailId, 
						$taxRate, $tax, $moneyWithTax);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
		}
		$sql = "select sum(goods_money) as sum_goods_money,
					sum(tax) as tax, sum(money_with_tax) as money_with_tax
				from t_ws_bill_detail where wsbill_id = '%s' ";
		$data = $db->query($sql, $id);
		$sumGoodsMoney = $data[0]["sum_goods_money"];
		if (! $sumGoodsMoney) {
			$sumGoodsMoney = 0;
		}
		$sumTax = $data[0]["tax"];
		$sumMoneyWithTax = $data[0]["money_with_tax"];
		
		$sql = "update t_ws_bill
				set sale_money = %f, customer_id = '%s', warehouse_id = '%s',
				biz_user_id = '%s', bizdt = '%s', receiving_type = %d,distribution_type = %d,
				memo = '%s', deal_address = '%s',
				tax = %f, money_with_tax = %f
				where id = '%s' ";
		$rc = $db->execute($sql, $sumGoodsMoney, $customerId, $warehouseId, $bizUserId, $bizDT, 
				$receivingType,$distributionType, $billMemo, $dealAddress, $sumTax, $sumMoneyWithTax, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$bill["ref"] = $ref;
		// 操作成功
		return null;
	}

	/**
	 * 提交销售出库单
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function commitWSBill(& $params) {
		$db = $this->db;
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		$loginUserId = $params["loginUserId"];
		$loginUserName= $params["loginUserName"];
		$userDataOrg = $params["userDataOrg"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$bs = new BizConfigDAO($db);
		// true: 先进先出
		$fifo = $bs->getInventoryMethod($companyId) == 1;
		
		$dataScale = $bs->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$id = $params["id"];
		
		$sql = "select ref, bill_status, customer_id, warehouse_id, biz_user_id, bizdt, sale_money,
					receiving_type, company_id, money_with_tax
				from t_ws_bill where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! $data) {
			return $this->bad("要提交的销售出库单不存在");
		}
		$ref = $data[0]["ref"];
		$bizDT = $data[0]["bizdt"];
		$bizUserId = $data[0]["biz_user_id"];
		$billStatus = $data[0]["bill_status"];
		$receivingType = $data[0]["receiving_type"];
		// money_with_tax是后加的字段，可能没值
		$saleMoney = $data[0]["money_with_tax"] ?? $data[0]["sale_money"];
		$companyId = $data[0]["company_id"];
		if ($billStatus != 0) {
			return $this->bad("销售出库单已经提交出库，不能再次提交");
		}
		$customerId = $data[0]["customer_id"];
		$warehouseId = $data[0]["warehouse_id"];
		
		$customerDAO = new CustomerDAO($db);
		$customer = $customerDAO->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("客户不存在");
		}
		
		$warehouseDAO = new WarehouseDAO($db);
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			return $this->bad("仓库不存在");
		}
		$warehouseName = $warehouse["name"];
		$inited = $warehouse["inited"];
		if ($inited != 1) {
			return $this->bad("仓库 [{$warehouseName}]还没有建账，不能进行出库操作");
		}
		
		// 检查销售出库仓库是否正确
		$salesWarehouse = $customerDAO->getSalesWarehouse($customerId);
		if ($salesWarehouse) {
			$salesWarehouseId = $salesWarehouse["id"];
			if ($salesWarehouseId != $warehouseId) {
				$salesWarehouseName = $salesWarehouse["name"];
				$info = "当前客户的销售出库仓库只能是：{$salesWarehouseName}<br/><br/>";
				$info .= "请重新编辑出库单，选择正确的销售出库仓库";
				return $this->bad($info);
			}
		}
		
		$userDAO = new UserApiDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("业务员不存在");
		}
		
		$allReceivingType = array(
				0,
				1,
				2,
				3
		);
		
		if (! in_array($receivingType, $allReceivingType)) {
			return $this->bad("收款方式不正确，无法完成提交操作");
		}
		
		$sql = "select id, goods_id, convert(goods_count, $fmt) as goods_count, goods_price, sobilldetail_id
				from t_ws_bill_detail
				where wsbill_id = '%s'
				order by show_order ";
		$items = $db->query($sql, $id);
		if (! $items) {
			return $this->bad("销售出库单没有出库商品明细记录，无法出库");
		}
		
		$bizConfigDAO = new BizConfigDAO($db);
		// 销售出库数量控制，true - 出库数量不能超过销售订单未出库数量
		$countLimit = $bizConfigDAO->getWSCountLimit($companyId) == "1";
		
		$sql = "select so_id
				from t_so_ws
				where ws_id = '%s' ";
		$data = $db->query($sql, $id);
		$soId = null;
		if ($data) {
			$soId = $data[0]["so_id"];
		}
		
		// 检查销售出库数量控制
		foreach ( $items as $i => $v ) {
			if (! $countLimit) {
				continue;
			}
			if (! $soId) {
				continue;
			}
			
			$goodsCount = $v["goods_count"];
			$soBillDetailId = $v["sobilldetail_id"];
			$sql = "select convert(left_count, $fmt) as left_count
					from t_so_bill_detail
					where id = '%s' ";
			$data = $db->query($sql, $soBillDetailId);
			if (! $data) {
				continue;
			}
			$leftCount = $data[0]["left_count"];
			if ($goodsCount > $leftCount) {
				$index = $i + 1;
				$info = "第{$index}条出库记录中销售出库数量超过销售订单上未出库数量<br/><br/>";
				$info .= "出库数量是: {$goodsCount}<br/>销售订单中未出库数量是: {$leftCount}";
				return $this->bad($info);
			}
		}
		
		foreach ( $items as $v ) {
			$itemId = $v["id"];
			$goodsId = $v["goods_id"];
			$goodsCount = $v["goods_count"];
			$goodsPrice = floatval($v["goods_price"]);
			
			$sql = "select code, name from t_goods where id = '%s' ";
			$data = $db->query($sql, $goodsId);
			if (! $data) {
				return $this->bad("要出库的商品不存在(商品后台id = {$goodsId})");
			}
			$goodsCode = $data[0]["code"];
			$goodsName = $data[0]["name"];
			if ($goodsCount <= 0) {
				// 忽略非正数
				// 一个原因：由销售订单生产销售出库单的时候，如果是部分发货，就有一些商品的数量是0
				// 当然了，数量字段出现负数基本上是其他bug导致的，这里就简单地一并忽略了
				continue;
				// return $this->bad("商品[{$goodsCode} {$goodsName}]的出库数量需要是正数");
			}
			
			$soBillDetailId = $v["sobilldetail_id"];
			
			if ($fifo) {
				// 先进先出法
				
				// 库存总账
				$sql = "select out_count, out_money, balance_count, balance_price,
						balance_money from t_inventory
						where warehouse_id = '%s' and goods_id = '%s' ";
				$data = $db->query($sql, $warehouseId, $goodsId);
				if (! $data) {
					return $this->bad(
							"商品 [{$goodsName}] 在仓库 [{$warehouseName}] 中没有存货，无法出库");
				}
				$balanceCount = $data[0]["balance_count"];
				if ($balanceCount < $goodsCount) {
					return $this->bad(
							"商品 [{$goodsName}] 在仓库 [{$warehouseName}] 中存货数量不足，无法出库");
				}
				$balancePrice = $data[0]["balance_price"];
				$balanceMoney = $data[0]["balance_money"];
				$outCount = $data[0]["out_count"];
				$outMoney = $data[0]["out_money"];
				
				$sql = "select id, balance_count, balance_price, balance_money,
								out_count, out_price, out_money, date_created
							from t_inventory_fifo
							where warehouse_id = '%s' and goods_id = '%s'
								and balance_count > 0
							order by date_created ";
				$data = $db->query($sql, $warehouseId, $goodsId);
				if (! $data) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
				
				$gc = $goodsCount;
				$fifoMoneyTotal = 0;
				for($i = 0; $i < count($data); $i ++) {
					if ($gc == 0) {
						break;
					}
					
					$fv = $data[$i];
					$fvBalanceCount = $fv["balance_count"];
					$fvId = $fv["id"];
					$fvBalancePrice = $fv["balance_price"];
					$fvBalanceMoney = $fv["balance_money"];
					$fvOutCount = $fv["out_count"];
					$fvOutMoney = $fv["out_money"];
					$fvDateCreated = $fv["date_created"];
					
					if ($fvBalanceCount >= $gc) {
						if ($fvBalanceCount > $gc) {
							$fifoMoney = $fvBalancePrice * $gc;
						} else {
							$fifoMoney = $fvBalanceMoney;
						}
						$fifoMoneyTotal += $fifoMoney;
						
						$fifoPrice = $fifoMoney / $gc;
						
						$fvOutCount += $gc;
						$fvOutMoney += $fifoMoney;
						$fvOutPrice = $fvOutMoney / $fvOutCount;
						
						$fvBalanceCount -= $gc;
						$fvBalanceMoney -= $fifoMoney;
						
						$sql = "update t_inventory_fifo
								set out_count = %d, out_price = %f, out_money = %f,
									balance_count = %d, balance_money = %f
								where id = %d ";
						$rc = $db->execute($sql, $fvOutCount, $fvOutPrice, $fvOutMoney, 
								$fvBalanceCount, $fvBalanceMoney, $fvId);
						if ($rc === false) {
							return $this->sqlError(__METHOD__, __LINE__);
						}
						
						// fifo 的明细记录
						$sql = "insert into t_inventory_fifo_detail(out_count, out_price, out_money,
								balance_count, balance_price, balance_money, warehouse_id, goods_id,
								date_created, wsbilldetail_id)
								values (%d, %f, %f, %d, %f, %f, '%s', '%s', '%s', '%s')";
						$rc = $db->execute($sql, $gc, $fifoPrice, $fifoMoney, $fvBalanceCount, 
								$fvBalancePrice, $fvBalanceMoney, $warehouseId, $goodsId, 
								$fvDateCreated, $itemId);
						if ($rc === false) {
							return $this->sqlError(__METHOD__, __LINE__);
						}
						
						$gc = 0;
					} else {
						$fifoMoneyTotal += $fvBalanceMoney;
						
						$sql = "update t_inventory_fifo
								set out_count = in_count, out_price = in_price, out_money = in_money,
									balance_count = 0, balance_money = 0
								where id = %d ";
						$rc = $db->execute($sql, $fvId);
						if ($rc === false) {
							return $this->sqlError(__METHOD__, __LINE__);
						}
						
						// fifo 的明细记录
						$sql = "insert into t_inventory_fifo_detail(out_count, out_price, out_money,
								balance_count, balance_price, balance_money, warehouse_id, goods_id,
								date_created, wsbilldetail_id)
								values (%d, %f, %f, %d, %f, %f, '%s', '%s', '%s', '%s')";
						$rc = $db->execute($sql, $fvBalanceCount, $fvBalancePrice, $fvBalanceMoney, 
								0, 0, 0, $warehouseId, $goodsId, $fvDateCreated, $itemId);
						if ($rc === false) {
							return $this->sqlError(__METHOD__, __LINE__);
						}
						
						$gc -= $fvBalanceCount;
					}
				}
				
				$fifoPrice = $fifoMoneyTotal / $goodsCount;
				
				// 更新总账
				$outCount += $goodsCount;
				$outMoney += $fifoMoneyTotal;
				$outPrice = $outMoney / $outCount;
				$balanceCount -= $goodsCount;
				if ($balanceCount == 0) {
					$balanceMoney = 0;
					$balancePrice = 0;
				} else {
					$balanceMoney -= $fifoMoneyTotal;
					$balancePrice = $balanceMoney / $balanceCount;
				}
				
				$sql = "update t_inventory
						set out_count = %d, out_price = %f, out_money = %f,
						    balance_count = %d, balance_price = %f, balance_money = %f
						where warehouse_id = '%s' and goods_id = '%s' ";
				$rc = $db->execute($sql, $outCount, $outPrice, $outMoney, $balanceCount, 
						$balancePrice, $balanceMoney, $warehouseId, $goodsId);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
				
				// 更新明细账
				$sql = "insert into t_inventory_detail(out_count, out_price, out_money,
						balance_count, balance_price, balance_money, warehouse_id,
						goods_id, biz_date, biz_user_id, date_created, ref_number, ref_type)
						values(%d, %f, %f, %d, %f, %f, '%s', '%s', '%s', '%s', now(), '%s', '销售出库')";
				$rc = $db->execute($sql, $goodsCount, $fifoPrice, $fifoMoneyTotal, $balanceCount, 
						$balancePrice, $balanceMoney, $warehouseId, $goodsId, $bizDT, $bizUserId, 
						$ref);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
				
				// 更新单据本身的记录
				$sql = "update t_ws_bill_detail
						set inventory_price = %f, inventory_money = %f
						where id = '%s' ";
				$rc = $db->execute($sql, $fifoPrice, $fifoMoneyTotal, $id);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			} else {
				// 移动平均法
				
				// 库存总账
				$sql = "select convert(out_count, $fmt) as out_count, out_money, 
							convert(balance_count, $fmt) as balance_count, balance_price,
						balance_money from t_inventory
						where warehouse_id = '%s' and goods_id = '%s' ";
				$data = $db->query($sql, $warehouseId, $goodsId);
				if (! $data) {
					return $this->bad(
							"商品 [{$goodsCode} {$goodsName}] 在仓库 [{$warehouseName}] 中没有存货，无法出库");
				}
				$balanceCount = $data[0]["balance_count"];
				if ($balanceCount < $goodsCount) {
					return $this->bad(
							"商品 [{$goodsCode} {$goodsName}] 在仓库 [{$warehouseName}] 中存货数量不足，无法出库");
				}
				$balancePrice = $data[0]["balance_price"];
				$balanceMoney = $data[0]["balance_money"];
				$outCount = $data[0]["out_count"];
				$outMoney = $data[0]["out_money"];
				$balanceCount -= $goodsCount;
				if ($balanceCount == 0) {
					// 当全部出库的时候，金额也需要全部转出去
					$outMoney += $balanceMoney;
					$outPriceDetail = $balanceMoney / $goodsCount;
					$outMoneyDetail = $balanceMoney;
					$balanceMoney = 0;
				} else {
					if ($goodsCount * $balancePrice > $balanceMoney) {
						// 因为单价是保存两位小数，所以当单价小，数量大的时候会出现这种情形
						$outMoney = $balanceMoney;
						$outPriceDetail = $balancePrice;
						$outMoneyDetail = $balanceMoney;
						$balanceMoney = 0;
					} else {
						$outMoney += $goodsCount * $balancePrice;
						$outPriceDetail = $balancePrice;
						$outMoneyDetail = $goodsCount * $balancePrice;
						$balanceMoney -= $goodsCount * $balancePrice;
					}
				}
				$outCount += $goodsCount;
				$outPrice = $outMoney / $outCount;
				
				$sql = "update t_inventory
						set out_count = convert(%f, $fmt), out_price = %f, out_money = %f,
						    balance_count = convert(%f, $fmt), balance_money = %f
						where warehouse_id = '%s' and goods_id = '%s' ";
				$rc = $db->execute($sql, $outCount, $outPrice, $outMoney, $balanceCount, 
						$balanceMoney, $warehouseId, $goodsId);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
				
				// 库存明细账
				$sql = "insert into t_inventory_detail(out_count, out_price, out_money,
						balance_count, balance_price, balance_money, warehouse_id,
						goods_id, biz_date, biz_user_id, date_created, ref_number, ref_type)
						values(convert(%f, $fmt), %f, %f, convert(%f, $fmt), %f, %f, '%s', '%s', '%s', '%s', now(), '%s', '销售出库')";
				$rc = $db->execute($sql, $goodsCount, $outPriceDetail, $outMoneyDetail, 
						$balanceCount, $balancePrice, $balanceMoney, $warehouseId, $goodsId, $bizDT, 
						$bizUserId, $ref);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
				
				// 单据本身的记录
				$sql = "update t_ws_bill_detail
						set inventory_price = %f, inventory_money = %f
						where id = '%s' ";
				$rc = $db->execute($sql, $outPriceDetail, $outMoneyDetail, $itemId);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
			
			// 同步销售订单中的出库量
			$sql = "select convert(goods_count, $fmt) as goods_count, 
							convert(ws_count, $fmt) as ws_count,
							scbilldetail_id
					from t_so_bill_detail
					where id = '%s' ";
			$soDetail = $db->query($sql, $soBillDetailId);
			if (! $soDetail) {
				// 不是由销售订单创建的出库单
				continue;
			}
			$scbillDetailId = $soDetail[0]["scbilldetail_id"];
			
			$totalGoodsCount = $soDetail[0]["goods_count"];
			$totalWSCount = $soDetail[0]["ws_count"];
			$totalWSCount += $goodsCount;
			$totalLeftCount = $totalGoodsCount - $totalWSCount;
			$sql = "update t_so_bill_detail
					set ws_count = convert(%f, $fmt), left_count = convert(%f, $fmt)
					where id = '%s' ";
			$rc = $db->execute($sql, $totalWSCount, $totalLeftCount, $soBillDetailId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			// 同步销售合同中的订单执行量
			if ($scbillDetailId) {
				$sql = "select convert(goods_count, $fmt) as goods_count, 
							convert(so_count, $fmt) as so_count
						from t_sc_bill_detail
						where id = '%s' ";
				$data = $db->query($sql, $scbillDetailId);
				if (! $data) {
					// 如果执行到这里，多半是数据库数据错误了
					continue;
				}
				
				$scGoodsCount = $data[0]["goods_count"];
				$scSoCount = $data[0]["so_count"];
				$scSoCount += $goodsCount;
				$scLeftCount = $scGoodsCount - $scSoCount;
				
				$sql = "update t_sc_bill_detail
						set so_count = convert(%f, $fmt), left_count = convert(%f, $fmt)
						where id = '%s' ";
				$rc = $db->execute($sql, $scSoCount, $scLeftCount, $scbillDetailId);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
		}
		
		if ($receivingType == 0||$receivingType==3) {
			// 记应收账款
			// 应收总账
			$sql = "select rv_money, balance_money
					from t_receivables
					where ca_id = '%s' and ca_type = 'customer' and company_id = '%s' and data_org = '%s' ";
			$data = $db->query($sql, $customerId, $companyId,$userDataOrg);
			if ($data) {
				$rvMoney = $data[0]["rv_money"];
				$balanceMoney = $data[0]["balance_money"];
				
				$rvMoney += $saleMoney;
				$balanceMoney += $saleMoney;
				
				$sql = "update t_receivables
						set rv_money = %f,  balance_money = %f
						where ca_id = '%s' and ca_type = 'customer'
							and company_id = '%s' and data_org  = '%s' ";
				$rc = $db->execute($sql, $rvMoney, $balanceMoney, $customerId, $companyId,$userDataOrg);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			} else {
		
				$sql = "insert into t_receivables (id, rv_money, act_money, balance_money,
							ca_id, ca_type, company_id,data_org)
						values ('%s', %f, 0, %f, '%s', 'customer', '%s','%s')";
				$rc = $db->execute($sql, $this->newId(), $saleMoney, $saleMoney, $customerId, 
						$companyId,$userDataOrg);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
			
			// 应收明细账
			$sql = "insert into t_receivables_detail (id, rv_money, act_money, balance_money,
					ca_id, ca_type, date_created, ref_number, ref_type, biz_date, company_id,data_org,receiving_type,operator)
					values('%s', %f, 0, %f, '%s', 'customer', now(), '%s', '销售出库', '%s', '%s','%s',%d,'%s')";
			
			$rc = $db->execute($sql, $this->newId(), $saleMoney, $saleMoney, $customerId, $ref, 
					$bizDT, $companyId,$userDataOrg,$receivingType,$loginUserName);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		} else if ($receivingType == 1) {
			// 现金收款
			$inCash = $saleMoney;
			
			$sql = "select in_money, out_money, balance_money
					from t_cash
					where biz_date = '%s' and company_id = '%s'  and data_org = '%s'";
			$data = $db->query($sql, $bizDT, $companyId,$userDataOrg);
			if (! $data) {
				// 当天首次发生现金业务
				$sql = "select sum(in_money) as sum_in_money, sum(out_money) as sum_out_money
							from t_cash
							where biz_date <= '%s' and company_id = '%s' and data_org = '%s' ";
				$data = $db->query($sql, $bizDT, $companyId,$userDataOrg);
				$sumInMoney = $data[0]["sum_in_money"];
				$sumOutMoney = $data[0]["sum_out_money"];
				if (! $sumInMoney) {
					$sumInMoney = 0;
				}
				if (! $sumOutMoney) {
					$sumOutMoney = 0;
				}
				
				$balanceCash = $sumInMoney - $sumOutMoney + $inCash;
				$sql = "insert into t_cash(in_money, balance_money, biz_date, company_id,data_org)
							values (%f, %f, '%s', '%s','%s')";
				$rc = $db->execute($sql, $inCash, $balanceCash, $bizDT, $companyId,$userDataOrg);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
				
				// 记现金明细账
				$sql = "insert into t_cash_detail(in_money, balance_money, biz_date, ref_type,
								ref_number, date_created, company_id,data_org)
							values (%f, %f, '%s', '销售出库', '%s', now(), '%s','%s')";
				$rc = $db->execute($sql, $inCash, $balanceCash, $bizDT, $ref, $companyId,$userDataOrg);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			} else {
				$balanceCash = $data[0]["balance_money"] + $inCash;
				$sumInMoney = $data[0]["in_money"] + $inCash;
				$sql = "update t_cash
						set in_money = %f, balance_money = %f
						where biz_date = '%s' and company_id = '%s' and data_org = '%s' ";
				$rc = $db->execute($sql, $sumInMoney, $balanceCash, $bizDT, $companyId,$userDataOrg);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
				
				// 记现金明细账
				$sql = "insert into t_cash_detail(in_money, balance_money, biz_date, ref_type,
							ref_number, date_created, company_id,data_org)
						values (%f, %f, '%s', '销售出库', '%s', now(), '%s','%s')";
				$rc = $db->execute($sql, $inCash, $balanceCash, $bizDT, $ref, $companyId,$userDataOrg);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
			
			// 调整业务日期之后的现金总账和明细账的余额
			$sql = "update t_cash
					set balance_money = balance_money + %f
					where biz_date > '%s' and company_id = '%s' and data_org = '%s'";
			$rc = $db->execute($sql, $inCash, $bizDT, $companyId,$userDataOrg);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			$sql = "update t_cash_detail
					set balance_money = balance_money + %f
					where biz_date > '%s' and company_id = '%s' and data_org = '%s' ";
			$rc = $db->execute($sql, $inCash, $bizDT, $companyId,$userDataOrg);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		} else if ($receivingType == 2) {
			// 2: 用预收款支付
			
			$outMoney = $saleMoney;
			
			// 预收款总账
			$sql = "select out_money, balance_money from t_pre_receiving
						where customer_id = '%s' and company_id = '%s' ";
			$data = $db->query($sql, $customerId, $companyId);
			$totalOutMoney = $data[0]["out_money"];
			if (! $totalOutMoney) {
				$totalOutMoney = 0;
			}
			$totalBalanceMoney = $data[0]["balance_money"];
			if (! $totalBalanceMoney) {
				$totalBalanceMoney = 0;
			}
			if ($totalBalanceMoney < $outMoney) {
				return $this->bad("付余款余额是{$totalBalanceMoney}元，小于销售金额，无法付款");
			}
			
			$totalOutMoney += $outMoney;
			$totalBalanceMoney -= $outMoney;
			$sql = "update t_pre_receiving
					set out_money = %f, balance_money = %f
					where customer_id = '%s' and company_id = '%s' ";
			$rc = $db->execute($sql, $totalOutMoney, $totalBalanceMoney, $customerId, $companyId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			// 预收款明细账
			$sql = "insert into t_pre_receiving_detail (id, customer_id, out_money, balance_money,
						biz_date, date_created, ref_number, ref_type, biz_user_id, input_user_id, company_id)
					values ('%s', '%s', %f, %f, '%s', now(), '%s', '销售出库', '%s', '%s', '%s')";
			$rc = $db->execute($sql, $this->newId(), $customerId, $outMoney, $totalBalanceMoney, 
					$bizDT, $ref, $bizUserId, $loginUserId, $companyId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 把单据本身设置为已经提交出库
		$sql = "select sum(inventory_money) as sum_inventory_money
				from t_ws_bill_detail
				where wsbill_id = '%s' ";
		$data = $db->query($sql, $id);
		$sumInventoryMoney = $data[0]["sum_inventory_money"];
		if (! $sumInventoryMoney) {
			$sumInventoryMoney = 0;
		}
		
		$profit = $saleMoney - $sumInventoryMoney;
		
		// 更新本单据的状态
		$sql = "update t_ws_bill
				set bill_status = 1000, inventory_money = %f, profit = %f
				where id = '%s' ";
		$rc = $db->execute($sql, $sumInventoryMoney, $profit, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 同步销售订单的状态
		$sql = "select so_id
				from t_so_ws
				where ws_id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			$soBillId = $data[0]["so_id"];
			$sql = "select count(*) as cnt
					from t_so_bill_detail
					where sobill_id = '%s' and  left_count > 0";
			$data = $db->query($sql, $soBillId);
			$cnt = $data[0]["cnt"];
			$billStatus = 1000;
			if ($cnt > 0) {
				// 部分出库
				$billStatus = 2000;
			} else {
				$billStatus = 3000;
			}
			$sql = "update t_so_bill
					set bill_status = %d
					where id = '%s' ";
			$rc = $db->execute($sql, $billStatus, $soBillId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		$params["ref"] = $ref;
		
		// 操作成功
		return null;
	}


}