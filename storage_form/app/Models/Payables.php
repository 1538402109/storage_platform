<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Base;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Payables extends Base
{
    use HasFactory;
    protected $table = 't_payables';


	private $LOG_CATEGORY = "应付账款管理";

	/**
	 * 往来单位分类
	 */
	public function payCategoryList($params) {
		
		$loginUserId = $this->getLoginUserId();
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$result = array();
		$result[0]["id"] = "";
		$result[0]["name"] = "[全部]";
		
		$id = $params["id"];
		if ($id == "supplier") {
			$sql = "select id, name from t_supplier_category ";
			$queryParams = array();
			$rs = $this->buildSQL("2005", "t_supplier_category", $loginUserId);
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
		} else if ($id == "factory") {
			$sql = "select id, name from t_factory_category ";
			$queryParams = array();
			
			$rs = $this->buildSQL("2005", "t_factory_category", $loginUserId);
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
			$sql = "select id,  code, name from t_customer_category ";
			$queryParams = array();
			
			$rs = $this->buildSQL("2005", "t_customer_category", $loginUserId);
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
	 * 应付账款列表
	 */
	public function payList($params) {
		
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserDataOrg"] = $this->getLoginUserDataOrg(['loginUserId'=>$this->getLonginUserId()]);
		
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$caType = $params["caType"];
		$categoryId = $params["categoryId"];
		$customerId = $params["customerId"];
		$supplierId = $params["supplierId"];
		$factoryId = $params["factoryId"];
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		if ($caType == "supplier") {
			$queryParams = array();
			$sql = "select p.id, sum(p.pay_money)pay_money, sum(p.act_money) act_money, sum(p.balance_money)balance_money, s.id as ca_id, s.code, s.name
					from t_payables p, t_supplier s
					where p.ca_id = s.id and p.ca_type = 'supplier' ";
			if ($supplierId) {
				$sql .= " and s.id = ? ";
				$queryParams[] = $supplierId;
			} else if ($categoryId) {
				$sql .= " and s.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$rs = $this->buildSQL("2005", "p", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			$sql .= " group by p.id,s.id , s.code, s.name  order by s.code
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
				$result[$i]["payMoney"] = $v["pay_money"];
				$result[$i]["actMoney"] = $v["act_money"];
				$result[$i]["balanceMoney"] = $v["balance_money"];
			}
			
			$queryParams[] = array();
			$sql = "select count(*) as cnt from t_payables p, t_supplier s
					where p.ca_id = s.id and p.ca_type = 'supplier' group by p.id,s.id , s.code, s.name  ";
			if ($supplierId) {
				$sql .= " and s.id = ? ";
				$queryParams[] = $supplierId;
			} else if ($categoryId) {
				$sql .= " and s.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$rs = $this->buildSQL("2005", "p", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			$data = DB::select($sql, $queryParams);
			$data = json_decode(json_encode($data),true);
			$cnt = $data[0]["cnt"];
			
			return array(
					"dataList" => $result,
					"totalCount" => $cnt
			);
		} else if ($caType == "factory") {
			$queryParams = array();
			$sql = "select p.id, p.pay_money, p.act_money, p.balance_money, s.id as ca_id, s.code, s.name
					from t_payables p, t_factory s
					where p.ca_id = s.id and p.ca_type = 'factory' ";
			if ($factoryId) {
				$sql .= " and s.id = ? ";
				$queryParams[] = $factoryId;
			} else if ($categoryId) {
				$sql .= " and s.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$rs = $this->buildSQL("2005", "s", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			$sql .= " order by s.code
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
				$result[$i]["payMoney"] = $v["pay_money"];
				$result[$i]["actMoney"] = $v["act_money"];
				$result[$i]["balanceMoney"] = $v["balance_money"];
			}
			
			$queryParams[] = array();
			$sql = "select count(*) as cnt from t_payables p, t_supplier s
					where p.ca_id = s.id and p.ca_type = 'factory' ";
			if ($factoryId) {
				$sql .= " and s.id = ? ";
				$queryParams[] = $factoryId;
			} else if ($categoryId) {
				$sql .= " and s.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$rs = $this->buildSQL("2005", "p", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			$data = DB::select($sql, $queryParams);
			$data = json_decode(json_encode($data),true);
			$cnt = $data[0]["cnt"];
			
			return array(
					"dataList" => $result,
					"totalCount" => $cnt
			);
		} else {
			// 客户
			$queryParams = array();
			$sql = "select p.id, p.pay_money, p.act_money, p.balance_money, s.id as ca_id, s.code, s.name
					from t_payables p, t_customer s
					where p.ca_id = s.id and p.ca_type = 'customer' ";
			if ($customerId) {
				$sql .= " and s.id = ? ";
				$queryParams[] = $customerId;
			} else if ($categoryId) {
				$sql .= " and s.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$rs = $this->buildSQL("2005", "p", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			$sql .= " order by s.code
					limit ? , ?";
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
				$result[$i]["payMoney"] = $v["pay_money"];
				$result[$i]["actMoney"] = $v["act_money"];
				$result[$i]["balanceMoney"] = $v["balance_money"];
			}
			
			$queryParams = array();
			$sql = "select count(*) as cnt from t_payables p, t_customer s
					where p.ca_id = s.id and p.ca_type = 'customer' ";
			if ($customerId) {
				$sql .= " and s.id = ? ";
				$queryParams[] = $customerId;
			} else if ($categoryId) {
				$sql .= " and s.category_id = ? ";
				$queryParams[] = $categoryId;
			}
			$rs = $this->buildSQL("2005", "p", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
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
	 * 每笔应付账款的明细记录
	 */
	public function payDetailList($params) {
		$params["loginUserId"] = $this->getLoginUserId();
		
		$queryParams = array();
		$queryParams[] = $params["caType"];
		$queryParams[] = $params["caId"];
		$loginUserId = $params["loginUserId"];
	
		
		$sql = "select id, ref_type, ref_number, pay_money, act_money, balance_money, date_created, biz_date
				from t_payables_detail
				where ca_type = ? and ca_id = ? ";
			
		$rs = $this->buildSQL("2005", "t_payables_detail", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		$sql .="	order by biz_date desc, date_created desc
		limit ? , ? ";
		$queryParams[] = $params["start"];
		$queryParams[] = $params["limit"];
		$data = DB::select($sql, $queryParams);
		$data = json_decode(json_encode($data),true);
		$result = array();
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["refType"] = $v["ref_type"];
			$result[$i]["refNumber"] = $v["ref_number"];
			$result[$i]["bizDT"] = date("Y-m-d", strtotime($v["biz_date"]));
			$result[$i]["dateCreated"] = $v["date_created"];
			$result[$i]["payMoney"] = $v["pay_money"];
			$result[$i]["actMoney"] = $v["act_money"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
		}
		
		$sql = "select count(*) as cnt from t_payables_detail
				where ca_type = ? and ca_id = ?  ";
				$queryParams = array();
				$queryParams[] = $caType;
				$queryParams[] = $caId;
				$rs = $this->buildSQL("2005", "t_payables_detail", $loginUserId);
				if ($rs) {
					$sql .= " and " . $rs[0];
					$queryParams = array_merge($queryParams, $rs[1]);
				}
		$data = DB::select($sql,$queryParams);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 应付账款的付款记录
	 */
	public function payRecordList($params) {
		$params["loginUserDataOrg"] = $this->getLoginUserDataOrg(['loginUserId'=>$this->getLonginUserId()]);
		$dao = new PayablesDAO($this->db());
		return $dao->payRecordList($params);
		$refType = $params["refType"];
		$refNumber = $params["refNumber"];
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$sql = "select u.name as biz_user_name, bu.name as input_user_name, p.id,
				p.act_money, p.biz_date, p.date_created, p.remark
				from t_payment p, t_user u, t_user bu
				where p.ref_type = ? and p.ref_number = ?
				and  p.pay_user_id = u.id and p.input_user_id = bu.id
				order by p.date_created desc
				limit ?, ? ";
		$data = DB::select($sql, [$refType, $refNumber, $start, $limit]);
		$data = json_decode(json_encode($data),true);
		$result = array();
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["actMoney"] = $v["act_money"];
			$result[$i]["bizDate"] = date("Y-m-d", strtotime($v["biz_date"]));
			$result[$i]["dateCreated"] = $v["date_created"];
			$result[$i]["bizUserName"] = $v["biz_user_name"];
			$result[$i]["inputUserName"] = $v["input_user_name"];
			$result[$i]["remark"] = $v["remark"];
		}
		
		$sql = "select count(*) as cnt from t_payment
				where ref_type = ? and ref_number = ? ";
		$data = DB::select($sql, [$refType, $refNumber]);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => 0
		);
	}

	/**
	 * 新增付款记录
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function addPayment1($params) {
		
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
		if ($refType == "采购入库") {
			$sql = "select id from t_pw_bill where ref = ? ";
			$data = DB::select($sql, [$refNumber]);
			if (! $data) {
				return $this->bad("单号为 {$refNumber} 的采购入库不存在，无法付款");
			}
			$data = json_decode(json_encode($data),true);
			$billId = $data[0]["id"];
		}
		
		// 检查付款人是否存在
		$userDAO = new User();
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("付款人不存在，无法付款");
		}
		
		// 检查付款日期是否正确
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("付款日期不正确");
		}
		
		$sql = "insert into t_payment (id, act_money, biz_date, date_created, input_user_id,
				pay_user_id,  bill_id,  ref_type, ref_number, remark, data_org, company_id)
				values (?, ?, ?, now(), ?, ?, ?, ?, ?, ?, ?, ?)";
		
		$rc = DB::insert($sql, [$this->newId(), $actMoney, $bizDT, $loginUserId, $bizUserId, 
						$billId, $refType, $refNumber, $remark, $dataOrg, $companyId]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 应付明细账
		$sql = "select balance_money, act_money, ca_type, ca_id, company_id
				from t_payables_detail
				where ref_type = ? and ref_number = ? ";
		$data = DB::select($sql, [$refType, $refNumber]);
		if (! $data) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		$data = json_decode(json_encode($data),true);
		$caType = $data[0]["ca_type"];
		$caId = $data[0]["ca_id"];
		$companyId = $data[0]["company_id"];
		$balanceMoney = $data[0]["balance_money"];
		$actMoneyNew = $data[0]["act_money"];
		$actMoneyNew += $actMoney;
		$balanceMoney -= $actMoney;
		$sql = "update t_payables_detail
				set act_money = ?, balance_money = ?
				where ref_type = ? and ref_number = ?
				and ca_id = ? and ca_type = ? ";
		$rc = DB::update($sql, [$actMoneyNew, $balanceMoney, $refType, $refNumber, $caId, $caType]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 应付总账
		$sql = "select sum(pay_money) as sum_pay_money, sum(act_money) as sum_act_money
				from t_payables_detail
				where ca_type = ? and ca_id = ? and company_id = ? ";
		$data = DB::select($sql, [$caType, $caId, $companyId]);
		if (! $data) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		$data = json_decode(json_encode($data),true);
		$sumPayMoney = $data[0]["sum_pay_money"];
		$sumActMoney = $data[0]["sum_act_money"];
		if (! $sumPayMoney) {
			$sumPayMoney = 0;
		}
		if (! $sumActMoney) {
			$sumActMoney = 0;
		}
		$sumBalanceMoney = $sumPayMoney - $sumActMoney;
		
		$sql = "update t_payables
				set act_money = ?, balance_money = ?
				where ca_type = ? and ca_id = ? and company_id = ? ";
		$rc = DB::update($sql, [$sumActMoney, $sumBalanceMoney, $caType, $caId, $companyId]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}

	/**
	 * 付款记录
	 */
	public function addPayment($params) {
		
		$params["companyId"] = $this->getCompanyId();
		$params["dataOrg"] = $this->getLoginUserDataOrg(['loginUserId'=>$this->getLonginUserId()]);
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserDataOrg"] = $this->getLoginUserDataOrg(['loginUserId'=>$this->getLonginUserId()]);
		DB::beginTransaction();
		
		$rc = $this->addPayment1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}
		
		$refType = $params["refType"];
		$refNumber = $params["refNumber"];
		$actMoney = $params["actMoney"];
		$log = "为 {$refType} - 单号：{$refNumber} 付款：{$actMoney}元";
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		DB::commit();
		
		return $this->ok();
	}

	public function refreshPayInfo($params) {
		
		$id = $params["id"];
		$data = DB::select("select act_money, balance_money from t_payables  where id = ? ", [$id]);
		$data = json_decode(json_encode($data),true);
		return array(
				"actMoney" => $data[0]["act_money"],
				"balanceMoney" => $data[0]["balance_money"]
		);
	}

	public function refreshPayDetailInfo($params) {
		
		$id = $params["id"];
		$data = DB::select(
				"select act_money, balance_money from t_payables_detail  where id = ? ", [$id]);
		$data = json_decode(json_encode($data),true);
		return array(
				"actMoney" => $data[0]["act_money"],
				"balanceMoney" => $data[0]["balance_money"]
		);
	}
}
