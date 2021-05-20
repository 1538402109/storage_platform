<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 应收账款 DAO
 *
 * @author JIATU
 */
class ReceivablesDAO extends PSIBaseExDAO {

	/**
	 * 往来单位分类
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function rvCategoryList($params) {
		$db = $this->db;
		$result = array();
		$result[0]["id"] = "";
		$result[0]["name"] = "[全部]";
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$id = $params["id"];
		if ($id == "customer") {
			$sql = "select id, name from t_customer_category ";
			
			$queryParams = array();
			
			$ds = new DataOrgDAO($db);
			$rs = $ds->buildSQL(FIdConst::RECEIVING, "t_customer_category", $loginUserId);
			if ($rs) {
				$sql .= " where " . $rs[0];
				$queryParams = $rs[1];
			}
			
			$sql .= " order by code";
			$data = $db->query($sql, $queryParams);
			foreach ( $data as $i => $v ) {
				$result[$i + 1]["id"] = $v["id"];
				$result[$i + 1]["name"] = $v["name"];
			}
		} else {
			$sql = "select id, name from t_supplier_category ";
			$queryParams = array();
			
			$ds = new DataOrgDAO($db);
			$rs = $ds->buildSQL(FIdConst::RECEIVING, "t_supplier_category", $loginUserId);
			if ($rs) {
				$sql .= " where " . $rs[0];
				$queryParams = $rs[1];
			}
			$sql .= " order by code";
			
			$data = $db->query($sql, $queryParams);
			foreach ( $data as $i => $v ) {
				$result[$i + 1]["id"] = $v["id"];
				$result[$i + 1]["name"] = $v["name"];
			}
		}
		
		return $result;
	}
	
	/**
	 * 应收账款列表
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function rvList($params) {
		$db = $this->db;
		
		$loginUserId = $params["loginUserId"];
		$loginUserDataOrg = $params["loginUserDataOrg"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$caType = $params["caType"];
		$categoryId = $params["categoryId"];
		$customerId = $params["customerId"];
		$supplierId = $params["supplierId"];
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		if ($caType == "customer") {
			$queryParams = array();
			$sql = "select r.id, r.ca_id, c.code, c.name, sum(r.act_money) act_money,  sum(r.balance_money) balance_money, sum(r.rv_money)rv_money
					from t_receivables r, t_customer c
					where (r.ca_type = '%s' and r.ca_id = c.id)";
			$queryParams[] = $caType;
			
			$ds = new DataOrgDAO($db);
			$rs = $ds->buildSQL(FIdConst::RECEIVING, "r", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			
			if ($customerId) {
				$sql .= " and c.id = '%s' ";
				$queryParams[] = $customerId;
			} else if ($categoryId) {
				$sql .= " and c.category_id = '%s' ";
				$queryParams[] = $categoryId;
			}
			$sql .= " group by r.id, r.ca_id, c.code, c.name order by c.code
					limit %d , %d ";
			$queryParams[] = $start;
			$queryParams[] = $limit;
			$data = $db->query($sql, $queryParams);
			$result = array();
			
			foreach ( $data as $i => $v ) {
				$result[$i]["id"] = $v["id"];
				$result[$i]["caId"] = $v["ca_id"];
				$result[$i]["code"] = $v["code"];
				$result[$i]["name"] = $v["name"];
				$result[$i]["actMoney"] = $v["act_money"];
				$result[$i]["balanceMoney"] = $v["balance_money"];
				$result[$i]["rvMoney"] = $v["rv_money"];
			}
			
			$queryParams = array();
			$sql = "select count(*) as cnt
					from t_receivables r, t_customer c  
					where r.ca_type = '%s'  and r.ca_id = c.id  group by r.id, r.ca_id, c.code, c.name";
			$queryParams[] = $caType;
			
			$ds = new DataOrgDAO($db);
			$rs = $ds->buildSQL(FIdConst::RECEIVING, "c", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			
			if ($customerId) {
				$sql .= " and c.id = '%s' ";
				$queryParams[] = $customerId;
			} else if ($categoryId) {
				$sql .= " and c.category_id = '%s' ";
				$queryParams[] = $categoryId;
			}
			$data = $db->query($sql, $queryParams);
			$cnt = $data[0]["cnt"];
			
			return array(
					"dataList" => $result,
					"totalCount" => $cnt
			);
		} else {
			$queryParams = array();
			$sql = "select r.id, r.ca_id, c.code, c.name, r.act_money, r.balance_money, r.rv_money
					from t_receivables r, t_supplier c
					where r.ca_type = '%s' and r.ca_id = c.id ";
			$queryParams[] = $caType;
			$ds = new DataOrgDAO($db);
			$rs = $ds->buildSQL(FIdConst::RECEIVING, "r", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			if ($supplierId) {
				$sql .= " and c.id = '%s' ";
				$queryParams[] = $supplierId;
			} else if ($categoryId) {
				$sql .= " and c.category_id = '%s' ";
				$queryParams[] = $categoryId;
			}
			$sql .= " order by c.code
					limit %d , %d ";
			$queryParams[] = $start;
			$queryParams[] = $limit;
			$data = $db->query($sql, $queryParams);
			$result = array();
			
			foreach ( $data as $i => $v ) {
				$result[$i]["id"] = $v["id"];
				$result[$i]["caId"] = $v["ca_id"];
				$result[$i]["code"] = $v["code"];
				$result[$i]["name"] = $v["name"];
				$result[$i]["actMoney"] = $v["act_money"];
				$result[$i]["balanceMoney"] = $v["balance_money"];
				$result[$i]["rvMoney"] = $v["rv_money"];
			}
			
			$queryParams = array();
			$sql = "select count(*) as cnt
					from t_receivables r, t_supplier c
					where r.ca_type = '%s'  and r.ca_id = c.id";
			$queryParams[] = $caType;
			$ds = new DataOrgDAO($db);
			$rs = $ds->buildSQL(FIdConst::RECEIVING, "r", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			
			if ($supplierId) {
				$sql .= " and c.id = '%s' ";
				$queryParams[] = $supplierId;
			} else if ($categoryId) {
				$sql .= " and c.category_id = '%s' ";
				$queryParams[] = $categoryId;
			}
			$data = $db->query($sql, $queryParams);
			$cnt = $data[0]["cnt"];
			
			return array(
					"dataList" => $result,
					"totalCount" => $cnt
			);
		}
	}

	/**
	 * 应收账款明细（多条件）
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function rvDetailList2($params) {
		$db = $this->db;
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$caType = $params["caType"];
		$categoryId = $params["categoryId"];
		$caId = $params["caId"];
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		$startDate=$params["startDate"];
		$endDate=$params["endDate"];
		$code=$params["code"];
		$CollectType=$params["CollectType"];
		$bizUser=$params["bizUser"];
		
		$sql = "select d.id, d.rv_money, d.act_money, d.balance_money, d.ref_type, d.ref_number, d.date_created, d.biz_date,d.receiving_type,d.operator,c.name,u.name as bname
				from t_receivables_detail d,t_customer c,t_user u,t_ws_bill w 
				where d.ca_id=c.id and d.ref_number = w.ref and w.biz_user_id = u.id and ca_type = '%s' ";
		
		if($categoryId){
			$sql.=" and c.category_id='".$categoryId."' ";
		}
		
		if($caId){
			$sql.=" and d.ca_id='".$caId."' ";
		}
		if($startDate){
			$sql.=" and d.biz_date>='".$startDate."' ";
		}
		if($endDate){
			$endDate=date("Y-m-d",strtotime($endDate));
			$sql.=" and d.biz_date<='".$endDate." 23:59:59' ";
		}
		if($code){
			$sql.=" and d.ref_number='".$code."' ";
		}
		if($CollectType||$CollectType=="0"){
			$sql.=" and d.receiving_type='".$CollectType."' ";
		}
		if($bizUser){
			$sql.=" and w.biz_user_id='".$bizUser."' ";
		}

		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::RECEIVING, "d", $loginUserId);
		if ($rs) {
			$queryParams=array();
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		$data=null;
		$sql.=" order by d.biz_date desc, d.date_created desc limit %d , %d ";
		if($rs){
			$data = $db->query($sql, $caType,  $queryParams[0], $queryParams[1],$start, $limit);
		}
		else{
			$data = $db->query($sql, $caType, $start, $limit);
		}
		$result = array();
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["refType"] = $v["ref_type"];
			$result[$i]["refNumber"] = $v["ref_number"];
			$result[$i]["dateCreated"] = $v["date_created"];
			$result[$i]["bizDT"] = date("Y-m-d", strtotime($v["biz_date"]));
			$result[$i]["rvMoney"] = $v["rv_money"];
			$result[$i]["actMoney"] = $v["act_money"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
			$result[$i]["operator"] = $v["operator"];
			$result[$i]["name"] = $v["name"];
			$result[$i]["receivingType"] = $v["receiving_type"];
			$result[$i]["bname"] = $v["bname"];
		}
		
		$sql = "select count(*) as cnt
				from t_receivables_detail
				where ca_type = '%s' and ca_id = '%s' ";
		$rs = $ds->buildSQL(FIdConst::RECEIVING, "t_receivables_detail", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
			$data = $db->query($sql, $caType, $caId,$rs[0],$rs[1]);
		}
		else{
			$data = $db->query($sql, $caType, $caId);
		}
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
		
	}

	/**
	 * 应收账款的明细记录
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function rvDetailList($params) {
		$db = $this->db;
		
		$caType = $params["caType"];
		$caId = $params["caId"];
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		$loginUserId = $params["loginUserId"];
		
		$sql = "select id, rv_money, act_money, balance_money, ref_type, ref_number, date_created, biz_date,receiving_type,operator
				from t_receivables_detail
				where ca_type = '%s' and ca_id = '%s'";
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::RECEIVING, "t_receivables_detail", $loginUserId);
		$queryParams=array();
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		$data=null;
		$sql.=" order by biz_date desc, date_created desc limit %d , %d ";
		if($rs){
			$data = $db->query($sql, $caType, $caId,  $queryParams[0],$queryParams[1],$start, $limit);
		}
		else{
			$data = $db->query($sql, $caType, $caId, $start, $limit);
		}
		$result = array();
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["refType"] = $v["ref_type"];
			$result[$i]["refNumber"] = $v["ref_number"];
			$result[$i]["dateCreated"] = $v["date_created"];
			$result[$i]["bizDT"] = date("Y-m-d", strtotime($v["biz_date"]));
			$result[$i]["rvMoney"] = $v["rv_money"];
			$result[$i]["actMoney"] = $v["act_money"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
			$result[$i]["operator"] = $v["operator"];
			$result[$i]["receivingType"] = $v["receiving_type"];
		}
		
		$sql = "select count(*) as cnt
				from t_receivables_detail
				where ca_type = '%s' and ca_id = '%s' ";
		$rs = $ds->buildSQL(FIdConst::RECEIVING, "t_receivables_detail", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
			$data = $db->query($sql, $caType, $caId,$rs[0],$rs[1]);
		}
		else{
			$data = $db->query($sql, $caType, $caId);
		}
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 将某条物流代收记录转为记应收账款
	 */
	public function changeReceivable($params){
		$db = $this->db;

		$id=$params["id"];
		$loginUserId = $params["loginUserId"];
		$loginUserName=$params["loginUserName"];
		$sql="update t_receivables_detail set receiving_type = 0 , ref_type='物流代收转应收' , operator='%s' where id='%s'";
		$rc = $db->execute($sql,$loginUserName,$id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		return "1";
	}

	/**
	 * 应收账款的收款记录
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function rvRecordList($params) {
		$db = $this->db;
		
		$refType = $params["refType"];
		$refNumber = $params["refNumber"];
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$sql = "select r.id, r.act_money, r.biz_date, r.date_created, r.remark, u.name as rv_user_name,
				user.name as input_user_name
				from t_receiving r, t_user u, t_user user
				where r.rv_user_id = u.id and r.input_user_id = user.id
				  and r.ref_type = '%s' and r.ref_number = '%s'
				order by r.date_created desc
				limit %d , %d ";
		$data = $db->query($sql, $refType, $refNumber, $start, $limit);
		$result = array();
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["actMoney"] = $v["act_money"];
			$result[$i]["bizDate"] = date("Y-m-d", strtotime($v["biz_date"]));
			$result[$i]["dateCreated"] = $v["date_created"];
			$result[$i]["bizUserName"] = $v["rv_user_name"];
			$result[$i]["inputUserName"] = $v["input_user_name"];
			$result[$i]["remark"] = $v["remark"];
		}
		
		$sql = "select count(*) as cnt
				from t_receiving
				where ref_type = '%s' and ref_number = '%s' ";
		$data = $db->query($sql, $refType, $refNumber);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 收款记录
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function addRvRecord($params) {
		$db = $this->db;
		
		$companyId = $params["companyId"];
		$dataOrg = $params["dataOrg"];
		$loginUserId = $params["loginUserId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$refType = $params["refType"];
		$refNumber = $params["refNumber"];
		$bizDT = $params["bizDT"];
		$actMoney = $params["actMoney"];
		$bizUserId = $params["bizUserId"];
		$remark = $params["remark"];
		if (! $remark) {
			$remark = "";
		}
		
		$billId = "";
		if ($refType == "销售出库") {
			$sql = "select id from t_ws_bill where ref = '%s' ";
			$data = $db->query($sql, $refNumber);
			if (! $data) {
				return $this->bad("单号为 [{$refNumber}] 的销售出库单不存在，无法录入收款记录");
			}
			
			$billId = $data[0]["id"];
		}
		
		// 检查收款人是否存在
		$userDAO = new UserDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("收款人不存在，无法收款");
		}
		
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("收款日期不正确");
		}
		
		$sql = "insert into t_receiving (id, act_money, biz_date, date_created, input_user_id,
				rv_user_id, remark, ref_number, ref_type, bill_id, data_org, company_id)
				values ('%s', %f, '%s', now(), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
		
		$rc = $db->execute($sql, $this->newId(), $actMoney, $bizDT, $loginUserId, $bizUserId, 
				$remark, $refNumber, $refType, $billId, $dataOrg, $companyId);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 应收明细账
		$sql = "select ca_id, ca_type, act_money, balance_money, company_id
				from t_receivables_detail
				where ref_number = '%s' and ref_type = '%s' ";
		$data = $db->query($sql, $refNumber, $refType);
		if (! $data) {
			return $this->bad("数据库错误，没有应收明细对应，无法收款");
		}
		$caId = $data[0]["ca_id"];
		$caType = $data[0]["ca_type"];
		$companyId = $data[0]["company_id"];
		$actMoneyDetail = $data[0]["act_money"];
		$balanceMoneyDetail = $data[0]["balance_money"];
		$actMoneyDetail += $actMoney;
		$balanceMoneyDetail -= $actMoney;
		$sql = "update t_receivables_detail
				set act_money = %f, balance_money = %f
				where ref_number = '%s' and ref_type = '%s'
					and ca_id = '%s' and ca_type = '%s' 
					and company_id = '%s' ";
		$rc = $db->execute($sql, $actMoneyDetail, $balanceMoneyDetail, $refNumber, $refType, $caId, 
				$caType, $companyId);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 应收总账
		$sql = "select sum(rv_money) as sum_rv_money, sum(act_money) as sum_act_money
				from t_receivables_detail
				where ca_id = '%s' and ca_type = '%s' and company_id = '%s' ";
		$data = $db->query($sql, $caId, $caType, $companyId);
		$sumRvMoney = $data[0]["sum_rv_money"];
		if (! $sumRvMoney) {
			$sumRvMoney = 0;
		}
		$sumActMoney = $data[0]["sum_act_money"];
		if (! $sumActMoney) {
			$sumActMoney = 0;
		}
		$sumBalanceMoney = $sumRvMoney - $sumActMoney;
		
		$sql = "update t_receivables
				set act_money = %f, balance_money = %f
				where ca_id = '%s' and ca_type = '%s' and company_id = '%s' ";
		$rc = $db->execute($sql, $sumActMoney, $sumBalanceMoney, $caId, $caType, $companyId);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}

	/**
	 * 刷新应收记录
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function refreshRvInfo($params) {
		$db = $this->db;
		
		$id = $params["id"];
		$sql = "select act_money, balance_money from t_receivables where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! $data) {
			return $this->emptyResult();
		} else {
			return array(
					"actMoney" => $data[0]["act_money"],
					"balanceMoney" => $data[0]["balance_money"]
			);
		}
	}

	/**
	 * 刷新应收记录详情
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function refreshRvDetailInfo($params) {
		$db = $this->db;
		
		$id = $params["id"];
		$sql = "select act_money, balance_money from t_receivables_detail where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! $data) {
			return $this->emptyResult();
		} else {
			return array(
					"actMoney" => $data[0]["act_money"],
					"balanceMoney" => $data[0]["balance_money"]
			);
		}
	}

	public function getOrgCode($params){
		$db = $this->db;

		$userid=$params["userid"];
		$sql = "select t_org.org_code from t_user join t_org on t_user.org_id = t_org.id where t_user.id = '%s' ";
		$data = $db->query($sql, $userid);
		if (! $data) {
			return $this->emptyResult();
		} else {
			return array(
					"orgCode" => $data[0]["org_code"]
			);
		}
	}
}