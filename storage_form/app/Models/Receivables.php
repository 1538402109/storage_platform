<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Base;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Receivables extends Base
{
    use HasFactory;
    protected $table = 't_receivables';

	private $LOG_CATEGORY = "应收账款管理";

	/**
	 * 往来单位分类
	 */
	public function rvCategoryList($params) {
		
		$params["loginUserId"] = $this->getLoginUserId();
		
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
			$rs = $this->buildSQL("2004", "t_customer_category", $loginUserId);
			if ($rs) {
				$sql .= " where " . $rs[0];
				$queryParams = $rs[1];
			}
			
			$sql .= " order by code";
			$data = DB::select($sql, $queryParams);
			$data = json_decode(json_encode($data),true);
			foreach ( $data as $i => $v ) {
				$result[$i + 1]["id"] = $v["id"];
				$result[$i + 1]["name"] = $v["name"];
			}
		} else {
			$sql = "select id, name from t_supplier_category ";
			$queryParams = array();
			$rs = $this->buildSQL("2004", "t_supplier_category", $loginUserId);
			if ($rs) {
				$sql .= " where " . $rs[0];
				$queryParams = $rs[1];
			}
			$sql .= " order by code";
			
			$data = DB::select($sql, $queryParams);
			$data = json_decode(json_encode($data),true);
			foreach ( $data as $i => $v ) {
				$result[$i + 1]["id"] = $v["id"];
				$result[$i + 1]["name"] = $v["name"];
			}
		}
		
		return $result;
	}

	/**
	 * 应收账款列表
	 */
	public function rvList($params) {
		
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserDataOrg"] = $this->getLoginUserDataOrg(['loginUserId'=>$this->getLoginUserId()]);
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
					where (r.ca_type = ? and r.ca_id = c.id)";
			$queryParams[] = $caType;
			$rs = $this->buildSQL("2004", "r", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			
			if ($customerId) {
				$sql .= " and c.id = ? ";
				$queryParams[] = $customerId;
			} else if ($categoryId) {
				$sql .= " and c.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$sql .= " group by r.id, r.ca_id, c.code, c.name order by c.code
					limit ? , ? ";
			$queryParams[] = $start;
			$queryParams[] = $limit;
			$data = DB::select($sql, $queryParams);
			$data = json_decode(json_encode($data),true);
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
					where r.ca_type = ?  and r.ca_id = c.id  group by r.id, r.ca_id, c.code, c.name";
			$queryParams[] = $caType;
			$rs = $this->buildSQL("2004", "c", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			
			if ($customerId) {
				$sql .= " and c.id = ? ";
				$queryParams[] = $customerId;
			} else if ($categoryId) {
				$sql .= " and c.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$data = DB::select($sql, $queryParams);
			$data = json_decode(json_encode($data),true);
			$cnt = $data[0]["cnt"];
			
			return array(
					"dataList" => $result,
					"totalCount" => $cnt
			);
		} else {
			$queryParams = array();
			$sql = "select r.id, r.ca_id, c.code, c.name, r.act_money, r.balance_money, r.rv_money
					from t_receivables r, t_supplier c
					where r.ca_type = ? and r.ca_id = c.id ";
			$queryParams[] = $caType;
			
			$rs = $this->buildSQL("2004", "r", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			if ($supplierId) {
				$sql .= " and c.id = ? ";
				$queryParams[] = $supplierId;
			} else if ($categoryId) {
				$sql .= " and c.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$sql .= " order by c.code
					limit ? , ? ";
			$queryParams[] = $start;
			$queryParams[] = $limit;
			$data = DB::select($sql, $queryParams);
			$data = json_decode(json_encode($data),true);
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
					where r.ca_type = ?  and r.ca_id = c.id";
			$queryParams[] = $caType;
			
			$rs = $this->buildSQL("2004", "r", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			
			if ($supplierId) {
				$sql .= " and c.id = ? ";
				$queryParams[] = $supplierId;
			} else if ($categoryId) {
				$sql .= " and c.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$data = DB::select($sql, $queryParams);
			$data = json_decode(json_encode($data),true);
			$cnt = $data[0]["cnt"];
			
			return array(
					"dataList" => $result,
					"totalCount" => $cnt
			);
		}
	}

	/**
	 * 应收账款明细（多条件）
	 */
	public function rvDetailList2($params){
		
		$params["loginUserId"] = $this->getLoginUserId();
		
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
				where d.ca_id=c.id and d.ref_number = w.ref and w.biz_user_id = u.id and ca_type = ? ";
		
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

		$rs = $this->buildSQL("2004", "d", $loginUserId);
		if ($rs) {
			$queryParams=array();
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		$data=null;
		$sql.=" order by d.biz_date desc, d.date_created desc limit ? , ? ";
		if($rs){
			$data = DB::select($sql, [$caType,  $queryParams[0], $queryParams[1],$start, $limit]);
		}
		else{
			$data = DB::select($sql, [$caType, $start, $limit]);
		}
		$data = json_decode(json_encode($data),true);
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
				where ca_type = ? and ca_id = ? ";
		$rs = $this->buildSQL("2004", "t_receivables_detail", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
			$data = DB::select($sql, [$caType, $caId,$rs[0],$rs[1]]);
		}
		else{
			$data = DB::select($sql, [$caType, $caId]);
		}
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 应收账款的明细记录
	 */
	public function rvDetailList($params) {
		//$params["dataOrg"] =$this->getLoginUserDataOrg();
		$params["loginUserId"] = $this->getLoginUserId();
		
		$db = $this->db;
		
		$caType = $params["caType"];
		$caId = $params["caId"];
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		$loginUserId = $params["loginUserId"];
		
		$sql = "select id, rv_money, act_money, balance_money, ref_type, ref_number, date_created, biz_date,receiving_type,operator
				from t_receivables_detail
				where ca_type = ? and ca_id = ?";
		$rs = $this->buildSQL("2004", "t_receivables_detail", $loginUserId);
		$queryParams=array();
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		$data=null;
		$sql.=" order by biz_date desc, date_created desc limit ? , ? ";
		if($rs){
			$data = DB::select($sql, [$caType, $caId,  $queryParams[0],$queryParams[1],$start, $limit]);
		}
		else{
			$data = DB::select($sql, [$caType, $caId, $start, $limit]);
		}
		$data = json_decode(json_encode($data),true);
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
				where ca_type = ? and ca_id = ? ";
		$rs = $this->buildSQL("2004", "t_receivables_detail", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
			$data = DB::select($sql, [$caType, $caId,$rs[0],$rs[1]]);
		}
		else{
			$data = DB::select($sql, [$caType, $caId]);
		}
		$data = json_decode(json_encode($data),true);
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
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = $this->getLoginUserName();
		$id=$params["id"];
		$loginUserId = $params["loginUserId"];
		$loginUserName=$params["loginUserName"];
		$sql="update t_receivables_detail set receiving_type = 0 , ref_type='物流代收转应收' , operator=? where id=?";
		$rc = DB::update($sql,[$loginUserName,$id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		return "1";
	}

	/**
	 * 应收账款的收款记录
	 */
	public function rvRecordList($params) {
		
		$refType = $params["refType"];
		$refNumber = $params["refNumber"];
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$sql = "select r.id, r.act_money, r.biz_date, r.date_created, r.remark, u.name as rv_user_name,
				user.name as input_user_name
				from t_receiving r, t_user u, t_user user
				where r.rv_user_id = u.id and r.input_user_id = user.id
				  and r.ref_type = ? and r.ref_number = ?
				order by r.date_created desc
				limit ? , ? ";
		$data = DB::select($sql, [$refType, $refNumber, $start, $limit]);
		$data = json_decode(json_encode($data),true);
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
				where ref_type = ? and ref_number = ? ";
		$data = DB::select($sql, [$refType, $refNumber]);
		$data = json_decode(json_encode($data),true);
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
	public function addRvRecord1($params) {
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
			$sql = "select id from t_ws_bill where ref = ? ";
			$data = DB::select($sql, [$refNumber]);
			if (! $data) {
				return $this->bad("单号为 [{$refNumber}] 的销售出库单不存在，无法录入收款记录");
			}
			
			$data = json_decode(json_encode($data),true);
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
				values (?, ?, ?, now(), ?, ?, ?, ?, ?, ?, ?, ?)";
		
		$rc = DB::insert($sql, [$this->newId(), $actMoney, $bizDT, $loginUserId, $bizUserId, 
						$remark, $refNumber, $refType, $billId, $dataOrg, $companyId]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 应收明细账
		$sql = "select ca_id, ca_type, act_money, balance_money, company_id
				from t_receivables_detail
				where ref_number = ? and ref_type = ? ";
		$data = DB::select($sql, [$refNumber, $refType]);
		if (! $data) {
			return $this->bad("数据库错误，没有应收明细对应，无法收款");
		}
		$data = json_decode(json_encode($data),true);
		$caId = $data[0]["ca_id"];
		$caType = $data[0]["ca_type"];
		$companyId = $data[0]["company_id"];
		$actMoneyDetail = $data[0]["act_money"];
		$balanceMoneyDetail = $data[0]["balance_money"];
		$actMoneyDetail += $actMoney;
		$balanceMoneyDetail -= $actMoney;
		$sql = "update t_receivables_detail
				set act_money = ?, balance_money = ?
				where ref_number = ? and ref_type = ?
					and ca_id = ? and ca_type = ? 
					and company_id = ? ";
		$rc = DB::update($sql, [$actMoneyDetail, $balanceMoneyDetail, $refNumber, $refType, $caId, 
						$caType, $companyId]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 应收总账
		$sql = "select sum(rv_money) as sum_rv_money, sum(act_money) as sum_act_money
				from t_receivables_detail
				where ca_id = ? and ca_type = ? and company_id = ? ";
		$data = DB::select($sql, [$caId, $caType, $companyId]);
		$data = json_decode(json_encode($data),true);
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
				set act_money = ?, balance_money = ?
				where ca_id = ? and ca_type = ? and company_id = ? ";
		$rc = DB::update($sql, [$sumActMoney, $sumBalanceMoney, $caId, $caType, $companyId]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}

	/**
	 * 收款记录
	 */
	public function addRvRecord($params) {
		
		$params["companyId"] = $this->getCompanyId();
		$params["dataOrg"] = $this->getLoginUserDataOrg(['loginUserId'=>$this->getLoginUserId()]);
		$params["loginUserId"] = $this->getLoginUserId();
		
		DB::beginTransaction();
		
		$rc = $this->addRvRecord1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}
		
		// 记录业务日志
		$refType = $params["refType"];
		$refNumber = $params["refNumber"];
		$actMoney = $params["actMoney"];
		$log = "为 {$refType} - 单号：{$refNumber} 收款：{$actMoney}元";
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		DB::commit();
		
		return $this->ok();
	}

	public function refreshRvInfo($params) {
		
		$id = $params["id"];
		$sql = "select act_money, balance_money from t_receivables where id = ? ";
		$data = DB::select($sql, [$id]);
		if (! $data) {
			return $this->emptyResult();
		} else {
			$data = json_decode(json_encode($data),true);
			return array(
					"actMoney" => $data[0]["act_money"],
					"balanceMoney" => $data[0]["balance_money"]
			);
		}
	}

	public function refreshRvDetailInfo($params) {
		
		$id = $params["id"];
		$sql = "select act_money, balance_money from t_receivables_detail where id = ? ";
		$data = DB::select($sql, [$id]);
		if (! $data) {
			return $this->emptyResult();
		} else {
			$data = json_decode(json_encode($data),true);
			return array(
					"actMoney" => $data[0]["act_money"],
					"balanceMoney" => $data[0]["balance_money"]
			);
		}
	}

	public function getOrgCode(){
		$params["userid"]=$this->getLoginUserId();
		$userid=$params["userid"];
		$sql = "select t_org.org_code from t_user join t_org on t_user.org_id = t_org.id where t_user.id = ? ";
		$data = DB::select($sql, [$userid]);
		if (! $data) {
			return $this->emptyResult();
		} else {
			$data = json_decode(json_encode($data),true);
			return array(
					"orgCode" => $data[0]["org_code"]
			);
		}
	}
}
