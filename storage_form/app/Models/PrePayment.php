<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Base;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
class PrePayment extends Base
{
	use HasFactory;
	private $LOG_CATEGORY = "预付款管理";

	public function addPrePaymentInfo() {
		
		return array(
				"bizUserId" => $this->getLoginUserId(),
				"bizUserName" => User::getLoginUserName($this->getLoginUserId())
		);
	}

	public function returnPrePaymentInfo() {
		
		return array(
				"bizUserId" => $this->getLoginUserId(),
				"bizUserName" => User::getLoginUserName($this->getLoginUserId())
		);
	}
	/**
	 * 向供应商付预付款
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function addPrePayment1(& $params) {
		
		$companyId = $params["companyId"];
		$loginUserId = $params["loginUserId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$supplierId = $params["supplierId"];
		$bizUserId = $params["bizUserId"];
		$bizDT = $params["bizDT"];
		$inMoney = $params["inMoney"];
		
		// 检查供应商
		$supplierDAO = new Supplier();
		$supplier = $supplierDAO->getSupplierById($supplierId);
		if (! $supplier) {
			return $this->bad("供应商不存在，无法付预付款");
		}
		
		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
		}
		
		// 检查收款人是否存在
		$userDAO = new User();
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("收款人不存在");
		}
		
		$inMoney = floatval($inMoney);
		if ($inMoney <= 0) {
			return $this->bad("付款金额需要是正数");
		}
		
		$sql = "select in_money, balance_money from t_pre_payment
				where supplier_id = ? and company_id = ? ";
		$data = DB::select($sql, [$supplierId, $companyId]);
		if (! $data) {
			// 总账
			$sql = "insert into t_pre_payment(id, supplier_id, in_money, balance_money, company_id)
					values (?, ?, ?, ?, ?)";
			$rc = DB::insert($sql, [$this->newId(), $supplierId, $inMoney, $inMoney, $companyId]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			// 明细账
			$sql = "insert into t_pre_payment_detail(id, supplier_id, in_money, balance_money, date_created,
						ref_number, ref_type, biz_user_id, input_user_id, biz_date, company_id)
					values(?, ?, ?, ?, now(), '', '预付供应商采购货款', ?, ?, ?, ?)";
			$rc = DB::insert($sql, [$this->newId(), $supplierId, $inMoney, $inMoney, $bizUserId, 
								$loginUserId, $bizDT, $companyId]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		} else {
			$data = json_decode(json_encode($data),true);
			$totalInMoney = $data[0]["in_money"];
			$totalBalanceMoney = $data[0]["balance_money"];
			if (! $totalInMoney) {
				$totalInMoney = 0;
			}
			if (! $totalBalanceMoney) {
				$totalBalanceMoney = 0;
			}
			
			$totalInMoney += $inMoney;
			$totalBalanceMoney += $inMoney;
			// 总账
			$sql = "update t_pre_payment
					set in_money = ?, balance_money = ?
					where supplier_id = ? and company_id = ? ";
			$rc = DB::update($sql, [$totalInMoney, $totalBalanceMoney, $supplierId, $companyId]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			// 明细账
			$sql = "insert into t_pre_payment_detail(id, supplier_id, in_money, balance_money, date_created,
						ref_number, ref_type, biz_user_id, input_user_id, biz_date, company_id)
					values(?, ?, ?, ?, now(), '', '预付供应商采购货款', ?, ?, ?, ?)";
			$rc = DB::insert($sql, [$this->newId(), $supplierId, $inMoney, $totalBalanceMoney, 
								$bizUserId, $loginUserId, $bizDT, $companyId]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		$params["supplierName"] = $supplier["name"];
		
		// 操作成功
		return null;
	}

	/**
	 * 向供应商付预付款
	 */
	public function addPrePayment($params) {
		
		$params["companyId"] = User::getCompanyId();
		$params["loginUserId"] = $this->getLoginUserId();
		
		DB::beginTransaction();
		
		$rc = $this->addPrePayment1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}
		
		// 记录业务日志
		$supplierName = $params["supplierName"];
		$inMoney = $params["inMoney"];
		$log = "付供应商[{$supplierName}]预付款：{$inMoney}元";
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		DB::commit();
		
		return $this->ok();
	}

	/**
	 * 供应商退回预收款
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function returnPrePayment1(& $params) {
		
		$companyId = $params["companyId"];
		$loginUserId = $params["loginUserId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$supplierId = $params["supplierId"];
		$bizUserId = $params["bizUserId"];
		$bizDT = $params["bizDT"];
		$inMoney = $params["inMoney"];
		
		// 检查供应商
		$supplierDAO = new Supplier();
		$supplier = $supplierDAO->getSupplierById($supplierId);
		if (! $supplier) {
			return $this->bad("供应商不存在，无法收款");
		}
		
		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
		}
		
		// 检查收款人是否存在
		$userDAO = new User();
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("收款人不存在");
		}
		
		$inMoney = floatval($inMoney);
		if ($inMoney <= 0) {
			return $this->bad("收款金额需要是正数");
		}
		
		$supplierName = $supplier["name"];
		
		$sql = "select balance_money, in_money from t_pre_payment
				where supplier_id = ? and company_id = ? ";
		$data = DB::select($sql, [$supplierId, $companyId]);
		$data = json_decode(json_encode($data),true);
		$balanceMoney = $data[0]["balance_money"];
		if (! $balanceMoney) {
			$balanceMoney = 0;
		}
		
		if ($balanceMoney < $inMoney) {
			$info = "退款金额{$inMoney}元超过余额。<br /><br />供应商[{$supplierName}]的预付款余额是{$balanceMoney}元";
			return $this->bad($info);
		}
		$totalInMoney = $data[0]["in_money"];
		if (! $totalInMoney) {
			$totalInMoney = 0;
		}
		
		// 总账
		$sql = "update t_pre_payment
				set in_money = ?, balance_money = ?
				where supplier_id = ? and company_id = ? ";
		$totalInMoney -= $inMoney;
		$balanceMoney -= $inMoney;
		$rc = DB::update($sql, [$totalInMoney, $balanceMoney, $supplierId, $companyId]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 明细账
		$sql = "insert into t_pre_payment_detail(id, supplier_id, in_money, balance_money,
					biz_date, date_created, ref_number, ref_type, biz_user_id, input_user_id,
					company_id)
				values (?, ?, ?, ?, ?, now(), '', '供应商退回采购预付款', ?, ?, ?)";
		$rc = DB::insert($sql, [$this->newId(), $supplierId, - $inMoney, $balanceMoney, $bizDT, 
						$bizUserId, $loginUserId, $companyId]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$params["supplierName"] = $supplierName;
		
		return null;
	}

	/**
	 * 供应商退回预收款
	 */
	public function returnPrePayment($params) {
		
		$params["companyId"] = User::getCompanyId();
		$params["loginUserId"] = $this->getLoginUserId();
		
		DB::beginTransaction();
		
		$rc = $this->returnPrePayment1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}
		
		// 记录业务日志
		$supplierName = $params["supplierName"];
		$inMoney = $params["inMoney"];
		$log = "供应商[{$supplierName}]退回采购预付款：{$inMoney}元";
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		DB::commit();
		
		return $this->ok();
	}

	public function prepaymentList($params) {
		
		$params["companyId"] = User::getCompanyId();
		
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$categoryId = $params["categoryId"];
		$supplierId = $params["supplierId"];
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$queryParams = [];
		$sql = "select r.id, c.id as supplier_id, c.code, c.name,
					r.in_money, r.out_money, r.balance_money
				from t_pre_payment r, t_supplier c
				where r.supplier_id = c.id and r.company_id = ? ";
		$queryParams[] = $companyId;
		if ($supplierId) {
			$sql .= " and c.id = ? ";
			$queryParams[] = $supplierId;
		} else if ($categoryId) {
			$sql .= " and c.category_id = ? ";
			$queryParams[] = $categoryId;
		}
		$sql .= " order by c.code
				limit ? , ?
				";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = DB::select($sql, $queryParams);
		$data = json_decode(json_encode($data),true);
		$result = array();
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["supplierId"] = $v["supplier_id"];
			$result[$i]["code"] = $v["code"];
			$result[$i]["name"] = $v["name"];
			$result[$i]["inMoney"] = $v["in_money"];
			$result[$i]["outMoney"] = $v["out_money"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
		}
		
		$queryParams = [];
		$sql = "select count(*) as cnt
				from t_pre_payment r, t_supplier c
				where r.supplier_id = c.id 
					and r.company_id = ?
				";
		$queryParams[] = $companyId;
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

	public function prepaymentDetailList($params) {
		
		$params["companyId"] = User::getCompanyId();
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$start = $params["start"];
		$limit = $params["limit"];
		
		$supplerId = $params["supplierId"];
		$dtFrom = $params["dtFrom"];
		$dtTo = $params["dtTo"];
		
		$sql = "select d.id, d.ref_type, d.ref_number, d.in_money, d.out_money, d.balance_money,
					d.biz_date, d.date_created,
					u1.name as biz_user_name, u2.name as input_user_name
				from t_pre_payment_detail d, t_user u1, t_user u2
				where d.supplier_id = ? and d.biz_user_id = u1.id and d.input_user_id = u2.id
					and (d.biz_date between ? and ?)
					and d.company_id = ?
				order by d.date_created
				limit ? , ?
				";
		$data = DB::select($sql, [$supplerId, $dtFrom, $dtTo, $companyId, $start, $limit]);
		$data = json_decode(json_encode($data),true);
		$result = array();
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["refType"] = $v["ref_type"];
			$result[$i]["refNumber"] = $v["ref_number"];
			$result[$i]["inMoney"] = $v["in_money"];
			$result[$i]["outMoney"] = $v["out_money"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
			$result[$i]["bizDT"] = $this->toYMD($v["biz_date"]);
			$result[$i]["dateCreated"] = $v["date_created"];
			$result[$i]["bizUserName"] = $v["biz_user_name"];
			$result[$i]["inputUserName"] = $v["input_user_name"];
		}
		
		$sql = "select count(*) as cnt
				from t_pre_payment_detail d, t_user u1, t_user u2
				where d.supplier_id = ? and d.biz_user_id = u1.id and d.input_user_id = u2.id
					and (d.biz_date between ? and ?)
					and d.company_id = ?
				";
		
		$data = DB::select($sql, [$supplerId, $companyId, $dtFrom, $dtTo]);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}
}