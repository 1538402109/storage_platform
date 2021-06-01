<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Base;
use Illuminate\Support\Facades\DB;
class Srbill extends Base
{
    use HasFactory;
    protected $table = 't_sr_bill';

	private $LOG_CATEGORY = "销售合同";

	/**
	 * 获得销售合同主表信息列表
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function scbillList($params) {
		
		$start = $params["start"];
		$limit = $params["limit"];
		
		$billStatus = $params["billStatus"];
		$ref = $params["ref"];
		$fromDT = $params["fromDT"];
		$toDT = $params["toDT"];
		$customerId = $params["customerId"];
		$goodsId = $params["goodsId"];
		
		$sql = "select s.id, s.ref, s.bill_status, c.name as customer_name,
					u.name as input_user_name, g.full_name as org_name,
					s.begin_dt, s.end_dt, s.goods_money, s.tax, s.money_with_tax,
					s.deal_date, s.deal_address, s.bill_memo, s.discount,
					u2.name as biz_user_name, s.biz_dt, s.confirm_user_id, s.confirm_date,
					s.date_created
				from t_sc_bill s, t_customer c, t_user u, t_org g, t_user u2
				where (s.customer_id = c.id) and (s.input_user_id = u.id) 
					and (s.org_id = g.id) and (s.biz_user_id = u2.id) ";
		
		$queryParams = [];
		if ($ref) {
			$sql .= " and (s.ref like '%".$ref."%') ";
			// $queryParams[] = "%{$ref}%";
		}
		if ($billStatus != - 1) {
			$sql .= " and (s.bill_status = ".$billStatus.") ";
			// $queryParams[] = $billStatus;
		}
		if ($fromDT) {
			$sql .= " and (s.deal_date >= '".$fromDT."')";
			// $queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (s.deal_date <= '".$toDT."')";
			// $queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (s.customer_id = '".$customerId."')";
			// $queryParams[] = $customerId;
		}
		if ($goodsId) {
			$sql .= " and (s.id in (select distinct scbill_id from t_sc_bill_detail where goods_id = '".$goodsId."')) ";
			// $queryParams[] = $goodsId;
		}
		$sql .= " order by s.ref desc
				  limit ".$start." , ".$limit;
		/*$queryParams[] = $start;
		$queryParams[] = $limit;*/
		$data = DB::select($sql);
		$data = json_decode(json_encode($data), true);
		$result = [];
		foreach ( $data as $v ) {
			$item = [
					"id" => $v["id"],
					"billStatus" => $v["bill_status"],
					"ref" => $v["ref"],
					"customerName" => $v["customer_name"],
					"orgName" => $v["org_name"],
					"beginDT" => $this->toYMD($v["begin_dt"]),
					"endDT" => $this->toYMD($v["end_dt"]),
					"goodsMoney" => $v["goods_money"],
					"tax" => $v["tax"],
					"moneyWithTax" => $v["money_with_tax"],
					"dealDate" => $this->toYMD($v["deal_date"]),
					"dealAddress" => $v["deal_address"],
					"discount" => $v["discount"],
					"bizUserName" => $v["biz_user_name"],
					"bizDT" => $this->toYMD($v["biz_dt"]),
					"billMemo" => $v["bill_memo"],
					"inputUserName" => $v["input_user_name"],
					"dateCreated" => $v["date_created"]
			];
			
			$confirmUserId = $v["confirm_user_id"];
			if ($confirmUserId) {
				$sql = "select name from t_user where id = '".$confirmUserId."' ";
				$d = DB::select($sql);
				if ($d) {
					$d = json_decode(json_encode($d),true);
					$item["confirmUserName"] = $d[0]["name"];
					$item["confirmDate"] = $v["confirm_date"];
				}
			}
			
			$result[] = $item;
		}
		
		$sql = "select count(*) as cnt
				from t_sc_bill s, t_customer c, t_user u, t_org g, t_user u2
				where (s.customer_id = c.id) and (s.input_user_id = u.id)
					and (s.org_id = g.id) and (s.biz_user_id = u2.id) ";
		$queryParams = [];

		if ($ref) {
			$sql .= " and (s.ref like %'".$ref."'%) ";
			// $queryParams[] = "%{$ref}%";
		}
		if ($billStatus != - 1) {
			$sql .= " and (s.bill_status = ".$billStatus.") ";
			// $queryParams[] = $billStatus;
		}
		if ($fromDT) {
			$sql .= " and (s.deal_date >= '".$fromDT."')";
			// $queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (s.deal_date <= '".$toDT."')";
			// $queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (s.customer_id = '".$customerId."')";
			// $queryParams[] = $customerId;
		}
		if ($goodsId) {
			$sql .= " and (s.id in (select distinct scbill_id from t_sc_bill_detail where goods_id = '".$goodsId."')) ";
			// $queryParams[] = $goodsId;
		}
		
		$data = DB::select($sql);
		$data = json_decode(json_encode($data), true);
		$cnt = $data[0]["cnt"];
		
		return [
				"dataList" => $result,
				"totalCount" => $cnt
		];
	}

	/**
	 * 销售合同详情
	 */
	public function scBillInfo($params) {
		
		// 销售合同id
		$id = $params["id"];
		$result = [];
		
		$companyId = User::getCompanyId();
		if ($this->companyIdNotExists($companyId)) {
			return [];
		}
		
		$result["taxRate"] = $this->getTaxRate($companyId);
		
		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		if ($id) {
			// 编辑或查看
			
			// 主表
			$sql = "select s.ref, s.customer_id, c.name as customer_name,
						s.begin_dt, s.end_dt, s.org_id, g.full_name as org_name,
						s.biz_dt, s.biz_user_id, u.name as biz_user_name,
						s.deal_date, s.deal_address, s.discount, s.bill_memo,
						s.quality_clause, s.insurance_clause, s.transport_clause,
						s.other_clause
					from t_sc_bill s, t_customer c, t_org g, t_user u
					where s.id = '".$id."' and s.customer_id = c.id 
						and s.org_id = g.id and s.biz_user_id = u.id";
			$data = DB::select($sql);
			if (! $data) {
				return [];
			}
			$data = json_decode(json_encode($data),true);
			$v = $data[0];
			$result["ref"] = $v["ref"];
			$result["customerId"] = $v["customer_id"];
			$result["customerName"] = $v["customer_name"];
			$result["beginDT"] = $this->toYMD($v["begin_dt"]);
			$result["endDT"] = $this->toYMD($v["end_dt"]);
			$result["orgId"] = $v["org_id"];
			$result["orgFullName"] = $v["org_name"];
			$result["bizDT"] = $this->toYMD($v["biz_dt"]);
			$result["bizUserId"] = $v["biz_user_id"];
			$result["bizUserName"] = $v["biz_user_name"];
			$result["dealDate"] = $this->toYMD($v["deal_date"]);
			$result["dealAddress"] = $v["deal_address"];
			$result["discount"] = $v["discount"];
			$result["billMemo"] = $v["bill_memo"];
			$result["qualityClause"] = $v["quality_clause"];
			$result["insuranceClause"] = $v["insurance_clause"];
			$result["transportClause"] = $v["transport_clause"];
			$result["otherClause"] = $v["other_clause"];
			
			// 明细
			$sql = "select s.id, s.goods_id, g.code, g.name, g.spec,
							convert(s.goods_count, " . $fmt . ") as goods_count, s.goods_price, s.goods_money,
					s.tax_rate, s.tax, s.money_with_tax, u.name as unit_name, s.memo
				from t_sc_bill_detail s, t_goods g, t_goods_unit u
				where s.scbill_id = '".$id."' and s.goods_id = g.id and g.unit_id = u.id
				order by s.show_order";
			$items = [];
			$data = DB::select($sql);
			$data = json_decode(json_encode($data),true);
			foreach ( $data as $v ) {
				$items[] = [
						"id" => $v["id"],
						"goodsId" => $v["goods_id"],
						"goodsCode" => $v["code"],
						"goodsName" => $v["name"],
						"goodsSpec" => $v["spec"],
						"goodsCount" => $v["goods_count"],
						"goodsPrice" => $v["goods_price"],
						"goodsMoney" => $v["goods_money"],
						"taxRate" => $v["tax_rate"],
						"tax" => $v["tax"],
						"moneyWithTax" => $v["money_with_tax"],
						"unitName" => $v["unit_name"],
						"memo" => $v["memo"]
				];
			}
			
			$result["items"] = $items;
		} else {
			// 新建
			$loginUserId = $params["loginUserId"];
			$result["bizUserId"] = $loginUserId;
			$result["bizUserName"] = $params["loginUserName"];
			
			$sql = "select o.id, o.full_name
					from t_org o, t_user u
					where o.id = u.org_id and u.id = '".$loginUserId."' ";
			$data = DB::select($sql);
			if ($data) {
				$data = json_decode(json_encode($data),true);
				$result["orgId"] = $data[0]["id"];
				$result["orgFullName"] = $data[0]["full_name"];
			}
		}
		
		return $result;
	}

	/**
	 * 新增或编辑销售合同
	 */
	public function editSCBill($json) {
		
		$bill = json_decode(html_entity_decode($json), true);
		if ($bill == null) {
			return $this->bad("传入的参数错误，不是正确的JSON格式");
		}
		
		DB::beginTransaction();
		
		
		$id = $bill["id"];
		
		$log = null;
		
		$bill["companyId"] = User::getCompanyId();
		
		if ($id) {
			// 编辑
			
			$bill["loginUserId"] = $this->getLoginUserId();
			
			$rc = $this->updateSCBill($bill);
			if ($rc) {
				DB::rollBack();
				return $rc;
			}
			
			$ref = $bill["ref"];
			
			$log = "编辑销售合同，合同号：{$ref}";
		} else {
			// 新建销售订单
			
			$bill["loginUserId"] = $this->getLoginUserId();
			$bill["dataOrg"] = $this->getLoginUserDataOrg(["loginUserId"=>$this->getLoginUserId()]);
			
			$rc = $this->addSCBill($bill);
			if ($rc) {
				DB::rollBack();
				return $rc;
			}
			
			$id = $bill["id"];
			$ref = $bill["ref"];
			
			$log = "新建销售合同，合同号：{$ref}";
		}
		
		// 记录业务日志
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		DB::commit();
		
		return $this->ok($id);
	}

	/**
	 * 新增销售合同
	 *
	 * @param array $params        	
	 * @return array|null
	 */
	public function addSCBill(& $bill) {
		$companyId = $bill["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$dataOrg = $bill["dataOrg"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		
		$loginUserId = $bill["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$customerId = $bill["customerId"];
		$customer = $this->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("甲方客户不存在");
		}
		
		$beginDT = $bill["beginDT"];
		if (! $this->dateIsValid($beginDT)) {
			return $this->bad("合同开始日期不正确");
		}
		$endDT = $bill["endDT"];
		if (! $this->dateIsValid($endDT)) {
			return $this->bad("合同结束日期不正确");
		}
		
		$orgId = $bill["orgId"];
		$org = $this->getOrgById($orgId);
		if (! $org) {
			return $this->bad("乙方组织机构不存在");
		}
		
		$dealDate = $bill["dealDate"];
		if (! $this->dateIsValid($dealDate)) {
			return $this->bad("交货日期不正确");
		}
		
		$bizDT = $bill["bizDT"];
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("合同签订日期不正确");
		}
		$bizUserId = $bill["bizUserId"];
		$user = $this->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("业务员不存在");
		}
		
		$dealAddress = $bill["dealAddress"];
		$discount = intval($bill["discount"]);
		if ($discount < 0 || $discount > 100) {
			$discount = 100;
		}
		
		$billMemo = $bill["billMemo"];
		$qualityClause = $bill["qualityClause"];
		$insuranceClause = $bill["insuranceClause"];
		$transportClause = $bill["transportClause"];
		$otherClause = $bill["otherClause"];
		
		$items = $bill["items"];
		
		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$id = $this->newId();
		$ref = $this->genNewBillRef($companyId);
		
		// 主表
		$sql = "insert into t_sc_bill (id, ref, customer_id, org_id, biz_user_id, biz_dt, input_user_id, date_created, bill_status, goods_money, tax, money_with_tax, deal_date, deal_address, bill_memo, data_org, company_id, begin_dt, end_dt, discount, quality_clause, insurance_clause, transport_clause, other_clause) values (?, ?, ?, ?, ?, ?, ?, now(), 0, 0, 0, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$rc = DB::statement($sql, [$id, $ref, $customerId, $orgId, $bizUserId, $bizDT, $loginUserId, 
				$dealDate, $dealAddress, $billMemo, $dataOrg, $companyId, $beginDT, $endDT, 
				$discount, $qualityClause, $insuranceClause, $transportClause, $otherClause]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 明细表
		foreach ( $items as $i => $v ) {
			$goodsId = $v["goodsId"];
			if (! $goodsId) {
				continue;
			}
			$goodsCount = $v["goodsCount"];
			$goodsPrice = $v["goodsPrice"];
			$goodsMoney = $v["goodsMoney"];
			$taxRate = $v["taxRate"];
			$tax = $v["tax"];
			$moneyWithTax = $v["moneyWithTax"];
			$memo = $v["memo"];
			
			$sql = "insert into t_sc_bill_detail(id, date_created, goods_id, goods_count, goods_money,
						goods_price, scbill_id, tax_rate, tax, money_with_tax, so_count, left_count,
						show_order, data_org, company_id, memo, discount)
					values (?, now(), ?, convert(?, $fmt), ?,
						?, ?, ?, ?, ?, 0, convert(?, $fmt), ?, ?, ?, ?, ?)";
			$rc = DB::statement($sql, [$this->newId(), $goodsId, $goodsCount, $goodsMoney, 
					$goodsPrice, $id, $taxRate, $tax, $moneyWithTax, $goodsCount, $i, $dataOrg, 
					$companyId, $memo, $discount]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 同步主表的金额合计字段
		$sql = "select sum(goods_money) as sum_goods_money, sum(tax) as sum_tax,
					sum(money_with_tax) as sum_money_with_tax
				from t_sc_bill_detail
				where scbill_id = '".$id."' ";
		$data = DB::select($sql);
		$data = json_decode(json_encode($data),true);
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
		
		$sql = "update t_sc_bill
				set goods_money = ?, tax = ?, money_with_tax = ?
				where id = ? ";
		$rc = DB::update($sql, [$sumGoodsMoney, $sumTax, $sumMoneyWithTax, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$bill["id"] = $id;
		$bill["ref"] = $ref;
		return null;
	}

	/**
	 * 编辑销售合同
	 *
	 * @param array $params        	
	 * @return array|null
	 */
	public function updateSCBill(& $bill) {
		
		// 销售合同主表id
		$id = $bill["id"];
		$b = $this->getSCBillById($id);
		if (! $b) {
			return $this->bad("要编辑的销售合同不存在");
		}
		$ref = $b["ref"];
		$billStatus = $b["billStatus"];
		if ($billStatus > 0) {
			return $this->bad("销售合同[合同号：{$ref}]已经提交审核，不能再次编辑");
		}
		$dataOrg = $b["dataOrg"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		
		$companyId = $bill["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$customerId = $bill["customerId"];
		$customer = $this->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("甲方客户不存在");
		}
		
		$beginDT = $bill["beginDT"];
		if (! $this->dateIsValid($beginDT)) {
			return $this->bad("合同开始日期不正确");
		}
		$endDT = $bill["endDT"];
		if (! $this->dateIsValid($endDT)) {
			return $this->bad("合同结束日期不正确");
		}
		
		$orgId = $bill["orgId"];
		$org = $this->getOrgById($orgId);
		if (! $org) {
			return $this->bad("乙方组织机构不存在");
		}
		
		$dealDate = $bill["dealDate"];
		if (! $this->dateIsValid($dealDate)) {
			return $this->bad("交货日期不正确");
		}
		
		$bizDT = $bill["bizDT"];
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("合同签订日期不正确");
		}
		$bizUserId = $bill["bizUserId"];
		$user = $this->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("业务员不存在");
		}
		
		$dealAddress = $bill["dealAddress"];
		$discount = intval($bill["discount"]);
		if ($discount < 0 || $discount > 100) {
			$discount = 100;
		}
		
		$billMemo = $bill["billMemo"];
		$qualityClause = $bill["qualityClause"];
		$insuranceClause = $bill["insuranceClause"];
		$transportClause = $bill["transportClause"];
		$otherClause = $bill["otherClause"];
		
		$items = $bill["items"];
		
		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// 主表
		$sql = "update t_sc_bill
				set customer_id = '".$customerId."', begin_dt = '".$beginDT."', end_dt = '".$endDT."',
					org_id = '".$orgId."', biz_dt = '".$bizDT."', deal_date = '".$dealDate."',
					deal_address = '".$dealAddress."', biz_user_id = '".$bizUserId."', discount = ".$discount.",
					bill_memo = '".$billMemo."', quality_clause = '".$qualityClause."',
					insurance_clause = '".$insuranceClause."', transport_clause = '".$transportClause."',
					other_clause = '".$otherClause."'
				where id = '".$id."' ";
		$rc = DB::update($sql);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 明细表
		$sql = "delete from t_sc_bill_detail where scbill_id = '".$id."' ";
		$rc = DB::delete($sql);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		foreach ( $items as $i => $v ) {
			$goodsId = $v["goodsId"];
			if (! $goodsId) {
				continue;
			}
			$goodsCount = $v["goodsCount"];
			$goodsPrice = $v["goodsPrice"];
			$goodsMoney = $v["goodsMoney"];
			$taxRate = $v["taxRate"];
			$tax = $v["tax"];
			$moneyWithTax = $v["moneyWithTax"];
			$memo = $v["memo"];
			
			$sql = "insert into t_sc_bill_detail(id, date_created, goods_id, goods_count, goods_money, goods_price, scbill_id, tax_rate, tax, money_with_tax, so_count, left_count, show_order, data_org, company_id, memo, discount) values (?, now(), ?, convert(?, $fmt), ?, ?, ?, ?, ?, ?, 0, convert(?, $fmt), ?, ?, ?, ?, ?)";
			$rc = DB::statement($sql, [$this->newId(), $goodsId, $goodsCount, $goodsMoney, 
					$goodsPrice, $id, $taxRate, $tax, $moneyWithTax, $goodsCount, $i, $dataOrg, 
					$companyId, $memo, $discount]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 同步主表销售金额
		$sql = "select sum(goods_money) as sum_goods_money, sum(tax) as sum_tax,
					sum(money_with_tax) as sum_money_with_tax
				from t_sc_bill_detail
				where scbill_id = '".$id."' ";
		$data = DB::select($sql);
		$data = json_decode(json_encode($data),true);
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
		
		$sql = "update t_sc_bill
				set goods_money = ?, tax = ?, money_with_tax = ?
				where id = '?' ";
		$rc = DB::update($sql, [$sumGoodsMoney, $sumTax, $sumMoneyWithTax, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$bill["ref"] = $ref;
		return null;
	}

	public function getSCBillById($id) {
		
		$sql = "select ref, bill_status, data_org
				from t_sc_bill
				where id = '".$id."' ";
		$data = DB::select($sql);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			return [
					"id" => $id,
					"ref" => $data[0]["ref"],
					"billStatus" => $data[0]["bill_status"],
					"dataOrg" => $data[0]["data_org"]
			];
		} else {
			return null;
		}
	}

	/**
	 * 销售合同商品明细
	 */
	public function scBillDetailList($params) {
		$companyId = User::getCompanyId();
		if ($this->companyIdNotExists($companyId)) {
			return [];
		}
		
		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// 销售合同主表id
		$id = $params["id"];
		
		$sql = "select quality_clause, insurance_clause, transport_clause, other_clause
				from t_sc_bill where id = '".$id."' ";
		$data = DB::select($sql);
		if (! $data) {
			return [];
		}
		$data = json_decode(json_encode($data),true);
		$v = $data[0];
		$result = [
				"qualityClause" => $v["quality_clause"],
				"insuranceClause" => $v["insurance_clause"],
				"transportClause" => $v["transport_clause"],
				"otherClause" => $v["other_clause"]
		];
		
		$sql = "select s.id, g.code, g.name, g.spec, convert(s.goods_count, " . $fmt . ") as goods_count,
					s.goods_price, s.goods_money,
					s.tax_rate, s.tax, s.money_with_tax, u.name as unit_name,
					convert(s.so_count, " . $fmt . ") as so_count,
					convert(s.left_count, " . $fmt . ") as left_count, s.memo
				from t_sc_bill_detail s, t_goods g, t_goods_unit u
				where s.scbill_id = '".$id."' and s.goods_id = g.id and g.unit_id = u.id
				order by s.show_order";
		$items = [];
		$data = DB::select($sql);
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $v ) {
			$items[] = [
					"id" => $v["id"],
					"goodsCode" => $v["code"],
					"goodsName" => $v["name"],
					"goodsSpec" => $v["spec"],
					"goodsCount" => $v["goods_count"],
					"goodsPrice" => $v["goods_price"],
					"goodsMoney" => $v["goods_money"],
					"taxRate" => $v["tax_rate"],
					"tax" => $v["tax"],
					"moneyWithTax" => $v["money_with_tax"],
					"unitName" => $v["unit_name"],
					"soCount" => $v["so_count"],
					"leftCount" => $v["left_count"],
					"memo" => $v["memo"]
			];
		}
		
		$result["items"] = $items;
		
		return $result;
	}

	/**
	 * 审核销售合同
	 */
	public function commitSCBill($params) {
		
		
		DB::beginTransaction();
		
		$rc = $this->commitSCBill1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		$log = "审核销售合同，合同号：{$ref}";
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		DB::commit();
		
		return $this->ok();
	}
	/**
	 * 审核销售合同
	 */
	public function commitSCBill1(& $params) {
		
		// 销售合同主表
		$id = $params["id"];
		
		$bill = $this->getSCBillById($id);
		if (! $bill) {
			return $this->bad("要审核的销售合同不存在");
		}
		$ref = $bill["ref"];
		$billStatus = $bill["billStatus"];
		if ($billStatus > 0) {
			return $this->bad("销售合同[合同号：{$ref}]已经审核");
		}
		
		$sql = "update t_sc_bill
				set bill_status = 1000
				where id = ? ";
		$rc = DB::update($sql, [$id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["ref"] = $ref;
		return null;
	}

	/**
	 * 删除销售合同
	 */
	public function deleteSCBill($params) {
		DB::beginTransaction();
		
		$rc = $this->deleteSCBill1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		$log = "删除销售合同，合同号：{$ref}";
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		DB::commit();
		
		return $this->ok();
	}

	/**
	 * 删除销售合同
	 */
	public function deleteSCBill1(& $params) {
		// 销售合同主表
		$id = $params["id"];
		
		$bill = $this->getSCBillById($id);
		if (! $bill) {
			return $this->bad("要删除的销售合同不存在");
		}
		$ref = $bill["ref"];
		$billStatus = $bill["billStatus"];
		if ($billStatus > 0) {
			return $this->bad("销售合同[合同号：{$ref}]已经审核，不能被删除");
		}
		
		// 删除明细表
		$sql = "delete from t_sc_bill_detail where scbill_id = ? ";
		$rc = DB::delete($sql, [$id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 删除主表
		$sql = "delete from t_sc_bill where id = ? ";
		$rc = DB::delete($sql, [$id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["ref"] = $ref;
		return null;
	}

	/**
	 * 取消审核销售合同
	 */
	public function cancelConfirmSCBill($params) {
		
		DB::beginTransaction();
		
		$rc = $this->cancelConfirmSCBill1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		$log = "取消审核销售合同，合同号：{$ref}";
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		DB::commit();
		
		return $this->ok();
	}

	/**
	 * 取消审核销售合同
	 */
	public function cancelConfirmSCBill1(& $params) {
		// 销售合同主表
		$id = $params["id"];
		
		$bill = $this->getSCBillById($id);
		if (! $bill) {
			return $this->bad("要取消审核的销售合同不存在");
		}
		$ref = $bill["ref"];
		$billStatus = $bill["billStatus"];
		if ($billStatus == 0) {
			return $this->bad("销售合同[合同号：{$ref}]还没有审核，无需取消");
		}
		
		// 检查是否生成了销售订单
		$sql = "select count(*) as cnt
				from t_sc_so
				where sc_id = ? ";
		$data = DB::select($sql, [$id]);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("销售合同[合同号：{$ref}]已经生成了销售订单，不能再取消");
		}
		
		$sql = "update t_sc_bill
				set bill_status = 0
				where id = ? ";
		$rc = DB::update($sql, [$id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["ref"] = $ref;
		return null;
	}

	/**
	 * 销售合同生成pdf文件
	 */
	public function pdf($params) {
		
		$params["companyId"] = User::getCompanyId();
		
		$productionName = $this->getProductionName();
		
		$ref = $params["ref"];
		
		$bill = $this->getDataForPDF($params);
		if (! $bill) {
			return;
		}
		
		// 记录业务日志
		$log = "销售合同(合同号：$ref)生成PDF文件";
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstance();
		$pdf->SetTitle("销售合同，合同号：{$ref}");
		
		$pdf->setHeaderFont(Array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(Array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售合同");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr><td>合同号：' . $ref . '</td><td>合同期限：' . $bill["beginDT"] . ' - ' . $bill["endDT"] . '</td></tr>
					<tr><td>甲方客户：' . $bill["customerName"] . '</td><td>乙方组织：' . $bill["orgName"] . '</td></tr>
					<tr><td>合同签订日期：' . $bill["bizDT"] . '</td><td>业务员：' . $bill["bizUserName"] . '</td></tr>
					<tr><td>交货日期：' . $bill["dealDate"] . '</td><td>交货地址:' . $bill["dealAddress"] . '</td></tr>
					<tr><td colspan="2">合同金额:' . $bill["goodsMoney"] . '  税金： ' . $bill["tax"] . '  价税合计：' . $bill["moneyWithTax"] . '</td></tr>
					<tr><td colspan="2"></td></tr>
					<tr><td colspan="2">品质条款</td></tr>
					<tr><td colspan="2">' . $bill["qualityClause"] . '</td></tr>
					<tr><td colspan="2"></td></tr>
					<tr><td colspan="2">保险条款</td></tr>
					<tr><td colspan="2">' . $bill["insuranceClause"] . '</td></tr>
					<tr><td colspan="2"></td></tr>
					<tr><td colspan="2">运输条款</td></tr>
					<tr><td colspan="2">' . $bill["transportClause"] . '</td></tr>
					<tr><td colspan="2"></td></tr>
					<tr><td colspan="2">其他条款</td></tr>
					<tr><td colspan="2">' . $bill["otherClause"] . '</td></tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>商品编号</td><td>商品名称</td><td>规格型号</td><td>数量</td><td>单位</td>
						<td>单价</td><td>销售金额</td>
					</tr>
				';
		foreach ( $bill["items"] as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["goodsCode"] . '</td>';
			$html .= '<td>' . $v["goodsName"] . '</td>';
			$html .= '<td>' . $v["goodsSpec"] . '</td>';
			$html .= '<td align="right">' . $v["goodsCount"] . '</td>';
			$html .= '<td>' . $v["unitName"] . '</td>';
			$html .= '<td align="right">' . $v["goodsPrice"] . '</td>';
			$html .= '<td align="right">' . $v["goodsMoney"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= "";
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
		
		ob_end_clean();
		ob_clean();ob_clean();
		$pdf->Output("$ref.pdf", "I");
	}

	/**
	 * 为销售合同生成PDF文件查询数据
	 *
	 * @param array $params        	
	 */
	public function getDataForPDF($params) {
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$ref = $params["ref"];
		
		$sql = "select s.id, s.bill_status, c.name as customer_name,
					u.name as input_user_name, g.full_name as org_name,
					s.begin_dt, s.end_dt, s.goods_money, s.tax, s.money_with_tax,
					s.deal_date, s.deal_address, s.bill_memo, s.discount,
					u2.name as biz_user_name, s.biz_dt, s.date_created,
					s.quality_clause, s.insurance_clause, s.transport_clause, s.other_clause
				from t_sc_bill s, t_customer c, t_user u, t_org g, t_user u2
				where (s.customer_id = c.id) and (s.input_user_id = u.id)
					and (s.org_id = g.id) and (s.biz_user_id = u2.id) 
					and (s.ref = ?)";
		$data = DB::select($sql, [$ref]);
		if (! $data) {
			return $this->emptyResult();
		}
		$data = json_encode(json_encode($data),true);
		$v = $data[0];
		
		$id = $v["id"];
		
		$result = [
				"ref" => $ref,
				"billStatus" => $v["bill_status"],
				"customerName" => $v["customer_name"],
				"orgName" => $v["org_name"],
				"beginDT" => $this->toYMD($v["begin_dt"]),
				"endDT" => $this->toYMD($v["end_dt"]),
				"goodsMoney" => $v["goods_money"],
				"tax" => $v["tax"],
				"moneyWithTax" => $v["money_with_tax"],
				"dealDate" => $this->toYMD($v["deal_date"]),
				"dealAddress" => $v["deal_address"],
				"discount" => $v["discount"],
				"bizUserName" => $v["biz_user_name"],
				"bizDT" => $this->toYMD($v["biz_dt"]),
				"billMemo" => $v["bill_memo"],
				"inputUserName" => $v["input_user_name"],
				"dateCreated" => $v["date_created"],
				"qualityClause" => $v["quality_clause"],
				"insuranceClause" => $v["insurance_clause"],
				"transportClause" => $v["transport_clause"],
				"otherClause" => $v["other_clause"]
		
		];
		
		$sql = "select g.code, g.name, g.spec, convert(s.goods_count, " . $fmt . ") as goods_count,
					s.goods_price, s.goods_money,
					s.tax_rate, s.tax, s.money_with_tax, u.name as unit_name,
					convert(s.so_count, " . $fmt . ") as so_count,
					convert(s.left_count, " . $fmt . ") as left_count, s.memo
				from t_sc_bill_detail s, t_goods g, t_goods_unit u
				where s.scbill_id = ? and s.goods_id = g.id and g.unit_id = u.id
				order by s.show_order";
		$items = [];
		$data = DB::select($sql, [$id]);
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $v ) {
			$items[] = [
					"goodsCode" => $v["code"],
					"goodsName" => $v["name"],
					"goodsSpec" => $v["spec"],
					"goodsCount" => $v["goods_count"],
					"goodsPrice" => $v["goods_price"],
					"goodsMoney" => $v["goods_money"],
					"taxRate" => $v["tax_rate"],
					"tax" => $v["tax"],
					"moneyWithTax" => $v["money_with_tax"],
					"unitName" => $v["unit_name"],
					"soCount" => $v["so_count"],
					"leftCount" => $v["left_count"],
					"memo" => $v["memo"]
			];
		}
		
		$result["items"] = $items;
		
		return $result;
	}
}
