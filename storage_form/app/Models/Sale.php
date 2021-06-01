<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Base;
use App\Models\Customer;
use App\Models\Warehouses;
use Illuminate\Support\Facades\DB;
class Sale extends Base
{
    use HasFactory;
    private $LOG_CATEGORY = "销售订单";

	/**
	 * 获得销售订单主表信息列表
	 *
	 * @param array $params
	 * @return array
	 */
	public function sobillList($params) {

		$loginUserId = $this->getLoginUserId();

		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}

		$start = $params["start"];
		$limit = $params["limit"];

		$billStatus = $params["billStatus"];
		$ref = $params["ref"];
		$fromDT = $params["fromDT"];
		$toDT = $params["toDT"];
		$customerId = $params["customerId"];
		$receivingType = $params["receivingType"];
		$goodsId = $params["goodsId"];

		$queryParams = array();

		$result = array();
		$sql = "";
		$rs = $this->buildSQL('2028', "s1", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}

		if ($billStatus != - 1) {
			if ($billStatus < 4000) {
				$sql .= " and (s1.bill_status = ?) ";
			} else {
				// 订单关闭 - 有多种状态
				$sql .= " and (s1.bill_status >= ?) ";
			}
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (s1.ref like ?) ";
			$queryParams[] = "%$ref%";
		}
		if ($fromDT) {
			$sql .= " and (s1.deal_date >= ?)";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (s1.deal_date <= ?)";
			$queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (s1.customer_id = ?)";
			$queryParams[] = $customerId;
		}
		if ($receivingType != - 1) {
			$sql .= " and (s1.receiving_type = ?) ";
			$queryParams[] = $receivingType;
		}
		if ($goodsId) {
			$sql .= " and (s1.id in (select distinct sobill_id from t_so_bill_detail where goods_id = ?))";
			$queryParams[] = $goodsId;
		}
		$sql .= " order by s1.deal_date desc, s1.ref desc
		limit ? ,?";
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


		$data = DB::select($sqlR, $queryParams);
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["ref"] = $v["ref"];
			$result[$i]["billStatus"] = $v["bill_status"];
			$result[$i]["dealDate"] = $this->toYMD($v["deal_date"]);
			$result[$i]["dealAddress"] = $v["deal_address"];
			$result[$i]["customerName"] = $v["customer_name"];
			$result[$i]["contact"] = $v["contact"];
			$result[$i]["tel"] = $v["tel"];
			$result[$i]["fax"] = $v["fax"];
			$result[$i]["goodsMoney"] = $v["goods_money"];
			$result[$i]["tax"] = $v["tax"];
			$result[$i]["moneyWithTax"] = $v["money_with_tax"];
			$result[$i]["receivingType"] = $v["receiving_type"];
			$result[$i]["distributionType"] = $v["distribution_type"];
			$result[$i]["billMemo"] = $v["bill_memo"];
			$result[$i]["bizUserName"] = $v["biz_user_name"];
			$result[$i]["orgName"] = $v["org_name"];
			$result[$i]["inputUserName"] = $v["input_user_name"];
			$result[$i]["dateCreated"] = $v["date_created"];

			$confirmUserId = $v["confirm_user_id"];
			if ($confirmUserId) {
				$sql = "select name from t_user where id = ? ";
				$d = DB::select($sql, [$confirmUserId]);
				if ($d) {
					$d = json_decode(json_encode($d),true);
					$result[$i]["confirmUserName"] = $d[0]["name"];
					$result[$i]["confirmDate"] = $v["confirm_date"];
				}
			}

			// 查询是否生成了销售出库单
			$sql = "select count(*) as cnt from t_so_ws
					where so_id = ? ";
			$d = DB::select($sql, [$v["id"]]);
			$d = json_decode(json_encode($d),true);
			$cnt = $d[0]["cnt"];
			$genPWBill = $cnt > 0 ? "▲" : "";
			$result[$i]["genPWBill"] = $genPWBill;
		}

		$sql = "select count(*) as cnt
				from t_so_bill s
				where 1=1
				";
		$queryParams = array();
		$rs = $this->buildSQL("2028", "s", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		if ($billStatus != - 1) {
			if ($billStatus < 4000) {
				$sql .= " and (s.bill_status = ?) ";
			} else {
				// 订单关闭 - 有多种状态
				$sql .= " and (s.bill_status >= ?) ";
			}
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (s.ref like ?) ";
			$queryParams[] = "%$ref%";
		}
		if ($fromDT) {
			$sql .= " and (s.deal_date >= ?)";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (s.deal_date <= ?)";
			$queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (s.customer_id = ?)";
			$queryParams[] = $customerId;
		}
		if ($receivingType != - 1) {
			$sql .= " and (s.receiving_type = ?) ";
			$queryParams[] = $receivingType;
		}
		if ($goodsId) {
			$sql .= " and (s.id in (select distinct sobill_id from t_so_bill_detail where goods_id = ?))";
			$queryParams[] = $goodsId;
		}
		$data = DB::select($sql, $queryParams);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];

		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}
	/**
	 * 获得销售订单的信息
	 */
	public function soBillInfo($params) {

		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = User::getLoginUserName($this->getLoginUserId());
		$params["companyId"] = User::getCompanyId();


		$id = $params["id"];

		// 销售合同号
		// 当从销售合同创建销售订单的时候，这个值就不为空
		$scbillRef = $params["scbillRef"];

		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}

		$result = [];

		$result["taxRate"] = $this->getTaxRate($companyId);

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		if ($id) {
			// 编辑销售订单
			$sql = "select s.ref, s.deal_date, s.deal_address, s.customer_id,c.mobile01,
						c.name as customer_name, s.contact, s.tel, s.fax,
						s.org_id, o.full_name, s.biz_user_id, u.name as biz_user_name,
						s.receiving_type,s.distribution_type, s.bill_memo, s.bill_status
					from t_so_bill s, t_customer c, t_user u, t_org o
					where s.id = ? and s.customer_Id = c.id
						and s.biz_user_id = u.id
						and s.org_id = o.id";
			$data = DB::select($sql, [$id]);
			$data = json_decode(json_encode($data),true);
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
				$result["receivingType"] = $v["receiving_type"];
				$result["distributionType"] = $v["distribution_type"];
				$result["billMemo"] = $v["bill_memo"];
				$result["billStatus"] = $v["bill_status"];
				$result["mobile"] = $v["mobile01"];

				// 明细表
				$sql = "select s.id, s.goods_id, g.code, g.name, g.spec,
							convert(s.goods_count, " . $fmt . ") as goods_count, s.goods_price, s.goods_money,u2.name as unit2_name,u3.name as unit3_name,g.sale_price,g.sale_price as sale_price2,g.sale_price as sale_price3,
							s.tax_rate,s.unit_result, s.tax, s.money_with_tax, u.name as unit_name, s.memo, s.scbilldetail_id
						from t_so_bill_detail s join  t_goods g on s.goods_id = g.id join t_goods_unit u on g.unit_id = u.id left join t_goods_unit u2 on g.unit_id = u2.id left join t_goods_unit u3 on g.unit_id = u3.id
						where s.sobill_id = ?
						order by s.show_order";
				$items = array();
				$data = DB::select($sql, [$id]);

				$data = json_decode(json_encode($data),true);
				foreach ( $data as $v ) {
					$item = array(
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
							"memo" => $v["memo"],
							"scbillDetailId" => $v["scbilldetail_id"],
							"unitResult"=>$v["unit_result"],
							"unit2Name"=>$v["unit2_name"],
							"unit3Name"=>$v["unit3_name"],
							/*"unit2Decimal"=>$v["unit2_decimal"],
							"unit3Decimal"=>$v["unit3_decimal"],
							"locality"=>$v["locality"],
							"guaranteeDay"=>$v["guarantee_day"],*/
							"salePrice"=>$v["sale_price"],
							"salePrice2"=>$v["sale_price2"],
							"salePrice3"=>$v["sale_price3"],

					);
					$items[] = $item;
				}

				$result["items"] = $items;

				// 查询当前销售订单是不是由销售合同创建
				$sql = "select count(*) as cnt from t_sc_so where so_id = ? ";
				$data = DB::select($sql, [$id]);
				$data = json_decode(json_encode($data),true);
				$cnt = $data[0]["cnt"];
				$result["genBill"] = $cnt > 0 ? "1" : "0";
			}
		} else {
			// 新建销售订单

			if ($scbillRef) {
				// 从销售合同创建销售订单
				$sql = "select s.id, s.deal_date, s.deal_address,
							s.customer_id, c.name as customer_name,
							s.org_id, g.full_name as org_full_name
						from t_sc_bill s, t_customer c, t_org g
						where s.ref = ? and s.customer_id = c.id
							and s.org_id = g.id";
				$data = DB::select($sql, [$scbillRef]);
				if (! $data) {
					// 这个时候多半是参数传递错误了
					return $this->emptyResult();
				}
				$data = json_decode(json_encode($data),true);
				$v = $data[0];
				$result["genBill"] = 1;
				$result["customerId"] = $v["customer_id"];
				$result["customerName"] = $v["customer_name"];
				$result["dealDate"] = $this->toYMD($v["deal_date"]);
				$result["dealAddress"] = $v["deal_address"];
				$result["orgId"] = $v["org_id"];
				$result["orgFullName"] = $v["org_full_name"];

				$scBillId = $v["id"];
				// 从销售合同查询商品明细
				$sql = "select s.id, s.goods_id, g.code, g.name, g.spec,
							convert(s.left_count, " . $fmt . ") as goods_count, s.goods_price,
							s.tax_rate, u.name as unit_name
						from t_sc_bill_detail s, t_goods g, t_goods_unit u
						where s.scbill_id = ? and s.goods_id = g.id and g.unit_id = u.id
						order by s.show_order";
				$items = [];
				$data = DB::select($sql, [$scBillId]);
				$data = json_decode(json_encode($data),true);
				foreach ( $data as $v ) {
					$goodsMoney = $v["goods_count"] * $v["goods_price"];
					$tax = $goodsMoney * $v["tax_rate"] / 100;
					$items[] = [
							"id" => $v["id"],
							"goodsId" => $v["goods_id"],
							"goodsCode" => $v["code"],
							"goodsName" => $v["name"],
							"goodsSpec" => $v["spec"],
							"goodsCount" => $v["goods_count"],
							"goodsPrice" => $v["goods_price"],
							"goodsMoney" => $goodsMoney,
							"taxRate" => $v["tax_rate"],
							"tax" => $tax,
							"moneyWithTax" => $goodsMoney + $tax,
							"unitName" => $v["unit_name"],
							"scbillDetailId" => $v["id"]
					];
				}

				$result["items"] = $items;

				$loginUserId = $params["loginUserId"];
				$result["bizUserId"] = $loginUserId;
				$result["bizUserName"] = $params["loginUserName"];
			} else {
				$loginUserId = $params["loginUserId"];
				$result["bizUserId"] = $loginUserId;
				$result["bizUserName"] = $params["loginUserName"];

				$sql = "select o.id, o.full_name
					from t_org o, t_user u
					where o.id = u.org_id and u.id = ? ";
				$data = DB::select($sql, [$loginUserId]);
				if ($data) {
					$data = json_decode(json_encode($data),true);
					$result["orgId"] = $data[0]["id"];
					$result["orgFullName"] = $data[0]["full_name"];
				}
			}

			// 默认收款方式
			$result["receivingType"] = $this->getSOBillDefaultReceving($companyId);
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
		$dealDate = $bill["dealDate"];
		if (! $this->dateIsValid($dealDate)) {
			return $this->bad("交货日期不正确");
		}

		$customerId = $bill["customerId"];
		$customer = $this->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("客户不存在");
		}

		$orgId = $bill["orgId"];
		$org = $this->getOrgById($orgId);
		if (! $org) {
			return $this->bad("组织机构不存在");
		}

		$bizUserId = $bill["bizUserId"];
		$userDAO = new User();
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
		/*$userDAO = new User();
		$isCheckBill= $userDAO->getIsCheckBill($loginUserId);
		if( $isCheckBill==0){
			$billStatus=1000;
		}*/
		// 销售合同号
		// 当销售订单是由销售合同创建的时候，销售合同号就不为空
		$scbillRef = $bill["scbillRef"];

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$id = $this->newId();
		$ref = $this->genNewBillRef($companyId);

		// 主表
		$sql = "insert into t_so_bill(id, ref, bill_status, deal_date, biz_dt, org_id, biz_user_id,
					goods_money, tax, money_with_tax, input_user_id, customer_id, contact, tel, fax,
					deal_address, bill_memo, receiving_type, date_created, data_org, company_id,distribution_type)
				values (?, ?, ?, ?, ?, ?, ?,
					0, 0, 0, ?, ?, ?, ?, ?,
					?, ?, ?, now(), ?, ?,?)";
		$rc = DB::statement($sql, [$id, $ref,$billStatus, $dealDate, $dealDate, $orgId, $bizUserId, $loginUserId,
						$customerId, $contact, $tel, $fax, $dealAddress, $billMemo, $receivingType, $dataOrg,
						$companyId,$distributionType]);
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
			$goodsPrice = $v["goodsPrice"];
			$goodsMoney = $v["goodsMoney"];
			$taxRate = $v["taxRate"];
			$tax = $v["tax"];
			$moneyWithTax = $v["moneyWithTax"];
			$memo = $v["memo"];
			$scbillDetailId = $v["scbillDetailId"];
			$unitResult = $v["unitResult"];
			$sql_insertvalues[] =  "('".$this->newId()."', now(), '".$goodsId."', convert(".$goodsCount.", $fmt), ".$goodsMoney.",
			".$goodsPrice.", '".$id."', ".$taxRate.", ".$tax.", ".$moneyWithTax.", 0, convert(".$goodsCount.", $fmt),".$i.", '".$dataOrg."', '".$companyId."', '".$memo."', '".$scbillDetailId."', '".$unitResult."')";

		}
        if (count($sql_insertvalues)>0) {
            $values = implode(",", $sql_insertvalues);
            $sql = $sql_insert.$values;
            $rc = DB::statement($sql);
            if ($rc === false) {
                return $this->sqlError(__METHOD__, __LINE__);
            }
        }
		// 同步主表的金额合计字段
		$sql = "select sum(goods_money) as sum_goods_money, sum(tax) as sum_tax,
					sum(money_with_tax) as sum_money_with_tax
				from t_so_bill_detail
				where sobill_id = ? ";
		$data = DB::select($sql, [$id]);
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

		$sql = "update t_so_bill
				set goods_money = ?, tax = ?, money_with_tax = ?
				where id = ? ";
		$rc = DB::update($sql, [$sumGoodsMoney, $sumTax, $sumMoneyWithTax, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		// 关联销售合同和销售订单
		if ($scbillRef) {
			$sql = "select id from t_sc_bill where ref = ? ";
			$data = DB::select($sql, [$scbillRef]);
			if ($data) {
				$data = json_decode(json_encode($data),true);
				$scbillId = $data[0]["id"];

				$sql = "insert into t_sc_so(sc_id, so_id) values (?, ?)";
				$rc = DB::statement($sql, [$scbillId, $id]);
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
	 * 新增或编辑销售订单
	 */
	public function editSOBill($json) {

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

			$rc = $this->updateSOBill($bill);
			if ($rc) {
				DB::rollback();
				return $rc;
			}

			$ref = $bill["ref"];

			$log = "编辑销售订单，单号：{$ref}";
		} else {
			// 新建销售订单

			$bill["loginUserId"] = $this->getLoginUserId();
			$bill["dataOrg"] = $this->getLoginUserDataOrg(["loginUserId"=>$this->getLoginUserId()]);

			$rc = $this->addSOBill($bill);
			if ($rc) {
				DB::rollback();
				return $rc;
			}

			$id = $bill["id"];
			$ref = $bill["ref"];

			$scbillRef = $bill["scbillRef"];
			if ($scbillRef) {
				// 从销售合同生成销售订单
				$log = "从销售合同(合同号：{$scbillRef})生成销售订单: 单号 = {$ref}";
			} else {
				// 手工创建销售订单
				$log = "新建销售订单，单号：{$ref}";
			}
		}

		// 记录业务日志
		$this->insertBizlog($log, $this->LOG_CATEGORY);

		DB::commit();

		return $this->ok($id);
	}

	/**
	 * 通过销售订单id查询销售订单
	 *
	 * @param string $id
	 * @return array|NULL
	 */
	public function getSOBillById($id) {

		$sql = "select ref, data_org, bill_status, company_id from t_so_bill where id = ? ";
		$data = DB::select($sql, [$id]);
		if (! $data) {
			return null;
		} else {
			$data = json_decode(json_encode($data),true);
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
		$id = $bill["id"];

		$dealDate = $bill["dealDate"];
		if (! $this->dateIsValid($dealDate)) {
			return $this->bad("交货日期不正确");
		}

		$customerId = $bill["customerId"];
		$customer = $this->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("客户不存在");
		}

		$orgId = $bill["orgId"];
		$org = $this->getOrgById($orgId);
		if (! $org) {
			return $this->bad("组织机构不存在");
		}

		$bizUserId = $bill["bizUserId"];
		$users = new User();
		$user = $users->getUserById($bizUserId);
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

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$sql = "delete from t_so_bill_detail where sobill_id = ? ";
		$rc = DB::delete($sql, [$id]);
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
			$goodsPrice = $v["goodsPrice"];
			$goodsMoney = $v["goodsMoney"];
			$taxRate = $v["taxRate"];
			$tax = $v["tax"];
			$moneyWithTax = $v["moneyWithTax"];
			$memo = $v["memo"];
			$scbillDetailId = $v["scbillDetailId"];
			$unitResult = $v["unitResult"];
			$sql_insertvalues[] =  "('".$this->newId()."', now(), '".$goodsId."', convert(".$goodsCount.", $fmt), ".$goodsMoney.",
			".$goodsPrice.", '".$id."', ".$taxRate.", ".$tax.", ".$moneyWithTax.", 0, convert(".$goodsCount.", $fmt),".$i.", '".$dataOrg."', '".$companyId."', '".$memo."', '".$scbillDetailId."', '".$unitResult."')";
		}

		if(count(	$sql_insertvalues)>0) {
			$values = implode(",", $sql_insertvalues);
			$sql = $sql_insert.$values;
			$rc = DB::statement($sql);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}

		// 同步主表的金额合计字段
		$sql = "select sum(goods_money) as sum_goods_money, sum(tax) as sum_tax,
					sum(money_with_tax) as sum_money_with_tax
				from t_so_bill_detail
				where sobill_id = ? ";
		$data = DB::select($sql, [$id]);
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

		$sql = "update t_so_bill
				set goods_money = ?, tax = ?, money_with_tax = ?,
					deal_date = ?, customer_id = ?,
					deal_address = ?, contact = ?, tel = ?, fax = ?,
					org_id = ?, biz_user_id = ?, receiving_type = ?,
					bill_memo = ?, input_user_id = ?,distribution_type = ?, date_created = now()
				where id = ? ";
		$rc = DB::update($sql, [$sumGoodsMoney, $sumTax, $sumMoneyWithTax, $dealDate, $customerId,
						$dealAddress, $contact, $tel, $fax, $orgId, $bizUserId, $receivingType, $billMemo,
						$loginUserId,$distributionType, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		$bill["ref"] = $ref;

		return null;
	}

	/**
	 * 获得销售订单的明细信息
	 */
	public function soBillDetailList($params) {

		$companyId = User::getCompanyId();
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		// id:销售订单id
		$id = $params["id"];

		$sql = "select s.id, g.code, g.name, g.spec, convert(s.goods_count, " . $fmt . ") as goods_count,
					s.goods_price, s.goods_money,
					s.tax_rate, s.tax, s.money_with_tax,s.unit_result, u.name as unit_name,
					convert(s.ws_count, " . $fmt . ") as ws_count,
					convert(s.left_count, " . $fmt . ") as left_count, s.memo
				from t_so_bill_detail s, t_goods g, t_goods_unit u
				where s.sobill_id = ? and s.goods_id = g.id and g.unit_id = u.id
				order by s.show_order";
		$result = array();
		$data = DB::select($sql, [$id]);
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $v ) {
			$item = array(
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
					"unitResult" => $v["unit_result"],
					// "locality" => $v["locality"],
					// "guarantee_day" => $v["guarantee_day"],
					"wsCount" => $v["ws_count"],
					"leftCount" => $v["left_count"],

					"memo" => $v["memo"]
			);
			$result[] = $item;
		}

		return $result;
	}

	/**
	 * 根据销售订单id查询出库情况
	 *
	 * @param string $soBillId
	 *        	销售订单id
	 * @return array
	 */
	public function soBillWSBillList($params) {
		$soBillId = $params['id'];
		$sql = "select w.id, w.ref, w.bizdt, c.name as customer_name, u.name as biz_user_name,
					user.name as input_user_name, h.name as warehouse_name, w.sale_money,
					w.bill_status, w.date_created, w.receiving_type, w.memo
				from t_ws_bill w, t_customer c, t_user u, t_user user, t_warehouse h,
					t_so_ws s
				where (w.customer_id = c.id) and (w.biz_user_id = u.id)
					and (w.input_user_id = user.id) and (w.warehouse_id = h.id)
					and (w.id = s.ws_id) and (s.so_id = ?)";

		$data = DB::select($sql, [$soBillId]);
		$result = array();
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $v ) {
			$item = array(
					"id" => $v["id"],
					"ref" => $v["ref"],
					"bizDate" => $this->toYMD($v["bizdt"]),
					"customerName" => $v["customer_name"],
					"warehouseName" => $v["warehouse_name"],
					"inputUserName" => $v["input_user_name"],
					"bizUserName" => $v["biz_user_name"],
					"billStatus" => $this->billStatusCodeToName($v["bill_status"]),
					"amount" => $v["sale_money"],
					"dateCreated" => $v["date_created"],
					"receivingType" => $v["receiving_type"],
					"memo" => $v["memo"]
			);

			$result[] = $item;
		}

		return $result;
	}

	/**
	 * 审核销售订单
	 */
	public function commitSOBill($params) {
		$id = $params["id"];
		DB::beginTransaction();

		$params["loginUserId"] = $this->getLoginUserId();

		// Log::record('开始审核'.   date('Y-m-d H:i:s')  , 'DEBUG');
		$rc = $this->commitSOBill1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}
		// Log::record('结束审核'.   date('Y-m-d H:i:s')  , 'DEBUG');
		// 记录业务日志
		$ref = $params["ref"];
		$log = "审核销售订单，单号：{$ref}";
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		// Log::record('记录日志'.   date('Y-m-d H:i:s')  , 'DEBUG');
		DB::commit();

		return $this->ok($id);
	}

	/**
	 * 审核销售订单
	 *
	 * @param array $params
	 * @return null|array
	 */
	public function commitSOBill1(& $params) {
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}

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
					confirm_user_id = ?,
					confirm_date = now()
				where id = ? ";
		$rc = DB::update($sql, [$loginUserId, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		$params["ref"] = $ref;

		return null;
	}

	/**
	 * 取消销售订单审核
	 */
	public function cancelConfirmSOBill($params) {

		$id = $params["id"];
		DB::beginTransaction();

		$rc = $this->cancelConfirmSOBill1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}

		// 记录业务日志
		$ref = $params["ref"];
		$log = "取消审核销售订单，单号：{$ref}";
		$this->insertBizlog($log, $this->LOG_CATEGORY);

		DB::commit();

		return $this->ok($id);
	}

	/**
	 * 取消销售订单审核
	 */
	public function cancelConfirmSOBill1(& $params) {
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

		$sql = "select count(*) as cnt from t_so_ws where so_id = ? ";
		$data = DB::select($sql, [$id]);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("销售订单(单号:{$ref})已经生成了销售出库单，不能取消审核");
		}

		$sql = "update t_so_bill
				set bill_status = 0, confirm_user_id = null, confirm_date = null
				where id = ? ";
		$rc = DB::select($sql, [$id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		$params["ref"] = $ref;

		// 操作成功
		return null;
	}
	/**
	 * 新建或编辑的时候，获得销售出库单的详情
	 */
	public function wsBillInfo($params) {

		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = User::getLoginUserName($this->getLoginUserId());
		$params["companyId"] = User::getCompanyId();
		$params["dataOrg"] = $this->getLoginUserDataOrg(["loginUserId"=>$this->getLoginUserId()]);

		$id = $params["id"];
		$sobillRef = $params["sobillRef"];

		$companyId = $params["companyId"];
		$loginUserId = $params["loginUserId"];
		$dataOrg = 	$params["dataOrg"] ;
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$result = [];

		$userDAO = new User();
		$result["canEditGoodsPrice"] = $this->canEditGoodsPrice($companyId, $loginUserId);
		// $result["showAddCustomerButton"] = $userDAO->hasPermission($loginUserId, FIdConst::CUSTOMER);
		$result["showAddCustomerButton"] = true;

		if (! $id) {
			// 新建销售出库单
			$result["bizUserId"] = $loginUserId;
			$result["bizUserName"] = $params["loginUserName"];

			$sql = "select value from t_config
					where id = '2002-02' and company_id = ? ";
			$data = DB::select($sql, [$companyId]);
			$data = json_decode(json_encode($data),true);
			if ($data&&$data[0]["value"]!='') {
				$warehouseId = $data[0]["value"];
				$sql = "select id, name from t_warehouse where id = ? ";
				$data = DB::select($sql, [$warehouseId]);
				if ($data) {
					$data = json_decode(json_encode($data),true);
					$result["warehouseId"] = $data[0]["id"];
					$result["warehouseName"] = $data[0]["name"];
				}
			}
			else{
				$sql = "select  id, name from t_warehouse where data_org = ? and company_id = ?  and enabled = 1";
				$data = DB::select($sql, [$dataOrg,$companyId]);
				if ($data) {
					$data = json_decode(json_encode($data),true);
					$result["warehouseId"] = $data[0]["id"];
					$result["warehouseName"] = $data[0]["name"];
				}
			}

			if ($sobillRef) {
				// Log::record('开始创建出库单'.   date('Y-m-d H:i:s')  , 'DEBUG');
				// 由销售订单生成销售出库单
				$sql = "select s.id, s.customer_id, c.name as customer_name, s.deal_date,
							s.receiving_type, s.bill_memo, s.deal_address,s.distribution_type,s.biz_user_id,u.login_name
						from t_so_bill s, t_customer c,t_user u
						where s.ref = ? and s.customer_id = c.id and u.id=s.biz_user_id ";
				$data = DB::select($sql, [$sobillRef]);
				if ($data) {
					$data = json_decode(json_encode($data),true);
					$v = $data[0];
					$result["customerId"] = $v["customer_id"];
					$result["customerName"] = $v["customer_name"];
					$result["dealDate"] = $this->toYMD($v["deal_date"]);
					$result["receivingType"] = $v["receiving_type"];
					$result["memo"] = $v["bill_memo"];
					$result["dealAddress"] = $v["deal_address"];
					$result["distributionType"] = $v["distribution_type"];
					$result["bizUserId"] = $v["biz_user_id"];
					$result["bizUserName"] = $v["login_name"];
					$customerDAO = new Customer();
					$warehosue = $customerDAO->getSalesWarehouse($v["customer_id"]);
					if ($warehosue) {
						$result["warehouseId"] = $warehosue["id"];
						$result["warehouseName"] = $warehosue["name"];
					}

					$pobillId = $v["id"];
					// 销售订单的明细
					$items = [];
					$sql = "select s.id, s.goods_id, g.bar_code as code, g.name, g.spec, u.name as unit_name,
								convert(s.goods_count, " . $fmt . ") as goods_count,
								s.goods_price, s.goods_money, s.unit_result,u2.name as unit2_name,u3.name as unit3_name,
								convert(s.left_count, " . $fmt . ") as left_count, s.memo,
								s.tax_rate, s.tax, s.money_with_tax
							from t_so_bill_detail s join t_goods g on s.goods_id = g.id  join t_goods_unit u on g.unit_id = u.id  left  join t_goods_unit u2 on g.unit_id = u2.id  left  join t_goods_unit u3 on g.unit_id = u3.id
							where s.sobill_id = ?
							order by s.show_order ";
					$data = DB::select($sql, [$pobillId]);
					// Log::record('开始循环'.   date('Y-m-d H:i:s')  , 'DEBUG');
					$data = json_decode(json_encode($data),true);
					foreach ( $data as $v ) {
						//获取此订单下的待出库的出库单
						$sqlTemp="select d.id,d.goods_count from t_ws_bill_detail d,t_ws_bill b where d.wsbill_id=b.id and d.sobilldetail_id=? and b.bill_status=0";
						$dataTemp=DB::select($sqlTemp, [$v["id"]]);
						$dataTemp = json_decode(json_encode($dataTemp),true);
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
								"goodsPrice" => $v["goods_price"],
								"goodsMoney" => $goodsMoneyTemp,
								"soBillDetailId" => $v["id"],
								"memo" => $v["memo"],
								"taxRate" => $v["tax_rate"],
								// "locality"=>$v["locality"],
								"unitResult"=>$v["unit_result"],
								// "guaranteeDay"=>$v["guarantee_day"],
								"unit2Name"=>$v["unit2_name"],
								"unit3Name"=>$v["unit3_name"],
								// "unit2Decimal"=>$v["unit2_decimal"],
								// "unit3Decimal"=>$v["unit3_decimal"],
								"tax" => $taxTemp,
								"moneyWithTax" => $goodsMoneyTemp + $taxTemp
								// "batchDate"=>$v["batch_date"]
						];
					}
					// Log::record('结束循环'.   date('Y-m-d H:i:s')  , 'DEBUG');
					$result["items"] = $items;
				}
			} else {
				// 销售出库单默认收款方式
				$result["receivingType"] = $this->getWSBillDefaultReceving($companyId);
			}

			return $result;
		} else {
			// 编辑
			$sql = "select w.id, w.ref, w.bill_status, w.bizdt, c.id as customer_id, c.name as customer_name,c.mobile01 as customer_mobile,c.address as customer_address,
					  u.id as biz_user_id, u.name as biz_user_name,
					  h.id as warehouse_id, h.name as warehouse_name,
						w.receiving_type, w.memo, w.deal_address,w.distribution_type
					from t_ws_bill w, t_customer c, t_user u, t_warehouse h,t_org o
					where w.customer_id = c.id and w.biz_user_id = u.id
					  and w.warehouse_id = h.id
					  and w.id = ? ";
			$data = DB::select($sql, [$id]);
			$data = json_decode(json_encode($data),true);
			if ($data) {
				$result["ref"] = $data[0]["ref"];
				$result["billStatus"] = $data[0]["bill_status"];
				$result["bizDT"] = date("Y-m-d", strtotime($data[0]["bizdt"]));
				$result["customerId"] = $data[0]["customer_id"];
				$result["customerName"] = $data[0]["customer_name"];
				$result["customerMobile"]=$data[0]["customer_mobile"];
				$result["customerAddress"]=$data[0]["customer_address"];
				$result["warehouseId"] = $data[0]["warehouse_id"];
				$result["warehouseName"] = $data[0]["warehouse_name"];
				$result["bizUserId"] = $data[0]["biz_user_id"];
				$result["bizUserName"] = $data[0]["biz_user_name"];
				$result["receivingType"] = $data[0]["receiving_type"];
				$result["memo"] = $data[0]["memo"];
				$result["dealAddress"] = $data[0]["deal_address"];
				$result["distributionType"]=$data[0]["distribution_type"];
				$result["tmsUrl"]="http://www.storageplatform.cn/";
			}

			$sql = "select o.name,o.org_code
				from t_org o, t_user u
				where o.id = u.org_id and u.id = ? ";
			$data = DB::select($sql, [$loginUserId]);
			if ($data) {
				$data = json_decode(json_encode($data),true);
				$result["orgName"] = $data[0]["name"];
				$result["orgCode"] = $data[0]["org_code"];
			}
			$sql = "select d.id, g.id as goods_id, g.bar_code as code, g.name, g.spec, u.name as unit_name,d.batch_date,
						convert(d.goods_count, $fmt) as goods_count,d.unit_result,u2.name as unit2_name,u3.name as unit3_name,
						d.goods_price, d.goods_money, d.sn_note, d.memo, d.sobilldetail_id,
						d.tax_rate, d.tax, d.money_with_tax
					from t_ws_bill_detail d join t_goods g on d.goods_id = g.id join t_goods_unit u on g.unit_id = u.id left  join t_goods_unit u2 on g.unit_id = u2.id  left  join t_goods_unit u3 on g.unit_id = u3.id
					where d.wsbill_id = ?
					order by d.show_order";
			$data = DB::select($sql, [$id]);
			$items = [];
			$data = json_decode(json_encode($data),true);
			foreach ( $data as $v ) {

				$batchDateObj=[];
				/*$sql = "select balance_count,batch_date from  t_inventory_batch  where balance_count>0 and  goods_id  = ? order by batch_date asc ";
				$tempBatch = DB::select($sql,  [$v["goods_id"]]);
				$tempBatch = json_decode(json_encode($tempBatch),true);
                if ($tempBatch) {
                    $batchDateObj =$tempBatch;
                }*/
				$items[] = [
						"id" => $v["id"],
						"goodsId" => $v["goods_id"],
						"goodsCode" => $v["code"],
						"goodsName" => $v["name"],
						"goodsSpec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"goodsCount" => $v["goods_count"],
						"goodsPrice" => $v["goods_price"],
						"goodsMoney" => $v["goods_money"],
						"sn" => $v["sn_note"],
						"memo" => $v["memo"],
						"soBillDetailId" => $v["sobilldetail_id"],
						"taxRate" => $v["tax_rate"],
						"tax" => $v["tax"],
						"moneyWithTax" => $v["money_with_tax"],
						// "locality"=>$v["locality"],
						"unitResult"=>$v["unit_result"],
						// "guaranteeDay"=>$v["guarantee_day"],
						"unit2Name"=>$v["unit2_name"],
						"unit3Name"=>$v["unit3_name"],
						// "unit2Decimal"=>$v["unit2_decimal"],
						// "unit3Decimal"=>$v["unit3_decimal"],
						// "guaranteeDay"=>$v["guarantee_day"],
						"batchDate"=>$v["batch_date"],
						"batchDateObj"=>$batchDateObj,
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
		$sql = "select value from t_config
				where id = '2002-01' and company_id = ? ";
		$data = DB::select($sql, [$companyId]);
		if (! $data) {
			return false;
		}
		$data = json_decode(json_encode($data),true);
		$v = intval($data[0]["value"]);
		if ($v == 0) {
			return false;
		}

		// $us = new UserDAO();
		// 在业务设置中启用编辑的前提下，还需要判断对应的权限（具体的用户）
		// return $us->hasPermission($userId, "2002-01");
		return true;
	}

	/**
	 * 通过销售出库单id查询销售出库单
	 *
	 * @param string $id
	 *        	销售出库单id
	 * @return array|NULL
	 */
	public function getWSBillById($id) {

		$sql = "select ref, bill_status, data_org, company_id from t_ws_bill where id = ? ";
		$data = DB::select($sql, [$id]);
		if (! $data) {
			return null;
		} else {
			$data = json_decode(json_encode($data),true);
			return array(
					"ref" => $data[0]["ref"],
					"billStatus" => $data[0]["bill_status"],
					"dataOrg" => $data[0]["data_org"],
					"companyId" => $data[0]["company_id"]
			);
		}
	}

	/**
	 * 编辑销售出库单
	 *
	 * @param array $bill
	 * @return NULL|array
	 */
	public function updateWSBill(& $bill) {
		$id = $bill["id"];

		$bizDT = $bill["bizDT"];
		$warehouseId = $bill["warehouseId"];
		$customerId = $bill["customerId"];
		$bizUserId = $bill["bizUserId"];
		$receivingType = $bill["receivingType"];
		$billMemo = $bill["billMemo"];
		$items = $bill["items"];
		$dealAddress = $bill["dealAddress"];
		$distributionType =$bill["distributionType"];
		// 检查客户
		$customerDAO = new Customer();
		$customer = $customerDAO->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("选择的客户不存在，无法保存数据");
		}

		// 检查仓库
		$warehouseDAO = new Warehouses();
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			return $this->bad("选择的仓库不存在，无法保存数据");
		}

		// 检查业务员
		$userDAO = new User();
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
				$sqlTemp="select d.left_count,g.name from t_so_bill_detail d,t_goods g where d.goods_id=g.id and d.id=?";
				$dataTemp=DB::select($sqlTemp, [$v["soBillDetailId"]]);
				$dataTemp = json_decode(json_encode($dataTemp),true);
				$goodsTemp="";
				$leftCountTemp=0;
				if($dataTemp){
					$leftCountTemp=$dataTemp[0]["left_count"];
					$goodsTemp=$dataTemp[0]["name"];
				}

				//查询待出库数量
				$sqlTemp="select d.id,d.goods_count from t_ws_bill_detail d,t_ws_bill b where d.wsbill_id=b.id and d.sobilldetail_id=? and b.bill_status=0";
				$dataTemp=DB::select($sqlTemp, [$v["soBillDetailId"]]);
				$dataTemp = json_decode(json_encode($dataTemp),true);
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

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$sql = "delete from t_ws_bill_detail where wsbill_id = ? ";
		$rc = DB::delete($sql, [$id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		$sql = "insert into t_ws_bill_detail (id, date_created, goods_id,
					goods_count, goods_price, goods_money,
					show_order, wsbill_id, sn_note, data_org, memo, company_id, sobilldetail_id,
					tax_rate, tax, money_with_tax,unit_result,batch_date)
				values (?, now(), ?, convert(?, $fmt), ?, ?, ?, ?, ?, ?, ?, ?, ?,
					?, ?, ?,?,now())";
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
				$unitResult = $v["unitResult"];
				$tax = $v["tax"];
				$moneyWithTax = $v["moneyWithTax"];
				$batchDate = $v["batchDate"];

				$rc = DB::insert($sql, [$this->newId(), $goodsId, $goodsCount, $goodsPrice,
										$goodsMoney, $i, $id, $sn, $dataOrg, $memo, $companyId, $soBillDetailId,
										$taxRate, $tax, $moneyWithTax,$unitResult,$batchDate]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
		}
		$sql = "select sum(goods_money) as sum_goods_money,
					sum(tax) as tax, sum(money_with_tax) as money_with_tax
				from t_ws_bill_detail where wsbill_id = ? ";
		$data = DB::select($sql, [$id]);
		$data = json_decode(json_encode($data),true);
		$sumGoodsMoney = $data[0]["sum_goods_money"];
		if (! $sumGoodsMoney) {
			$sumGoodsMoney = 0;
		}
		$sumTax = $data[0]["tax"];
		$sumMoneyWithTax = $data[0]["money_with_tax"];

		$sql = "update t_ws_bill
				set sale_money = ?, customer_id = ?, warehouse_id = ?,
				biz_user_id = ?, bizdt = ?, receiving_type = ?, distribution_type = ?,
				memo = ?, deal_address = ?,
				tax = ?, money_with_tax = ?
				where id = ? ";
		$rc = DB::update($sql, [$sumGoodsMoney, $customerId, $warehouseId, $bizUserId, $bizDT,
						$receivingType, $distributionType,$billMemo, $dealAddress, $sumTax, $sumMoneyWithTax, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		$bill["ref"] = $ref;
		// 操作成功
		return null;
	}

	/**
	 * 新建销售出库单
	 *
	 * @param array $bill
	 * @return NULL|array
	 */
	public function addWSBill(& $bill) {
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
		$customerDAO = new Customer();
		$customer = $customerDAO->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("选择的客户不存在，无法保存数据");
		}

		// 检查仓库
		$warehouseDAO = new Warehouses();
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			return $this->bad("选择的仓库不存在，无法保存数据");
		}

		// 检查业务员
		$us = new User();
		$user = $us->getUserById($bizUserId);
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
				$sqlTemp="select d.left_count,g.name from t_so_bill_detail d,t_goods g where d.goods_id=g.id and d.id=?";
				$dataTemp=DB::select($sqlTemp, [$v["soBillDetailId"]]);
				$dataTemp = json_decode(json_encode($dataTemp),true);
				$goodsTemp="";
				$leftCountTemp=0;
				if($dataTemp){
					$leftCountTemp=$dataTemp[0]["left_count"];
					$goodsTemp=$dataTemp[0]["name"];
				}

				//查询待出库数量
				$sqlTemp="select d.id,d.goods_count from t_ws_bill_detail d,t_ws_bill b where d.wsbill_id=b.id and d.sobilldetail_id=? and b.bill_status=0";
				$dataTemp=DB::select($sqlTemp, [$v["soBillDetailId"]]);
				$dataTemp = json_decode(json_encode($dataTemp),true);
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

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		// 主表
		$id = $this->newId();
		$ref = $this->genNewBillRef($companyId);
		$sql = "insert into t_ws_bill(id, bill_status, bizdt, biz_user_id, customer_id,  date_created,
					input_user_id, ref, warehouse_id, receiving_type, data_org, company_id, memo, deal_address,distribution_type)
				values (?, 0, ?, ?, ?, now(), ?, ?, ?, ?, ?, ?, ?, ?,?)";

		$rc = DB::insert($sql, [$id, $bizDT, $bizUserId, $customerId, $loginUserId, $ref,
						$warehouseId, $receivingType, $dataOrg, $companyId, $billMemo, $dealAddress,$distributionType]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		// 明细表
		$sql = "insert into t_ws_bill_detail (id, date_created, goods_id,
					goods_count, goods_price, goods_money,
					show_order, wsbill_id, sn_note, data_org, memo, company_id, sobilldetail_id,
					tax_rate, tax, money_with_tax,unit_result,batch_date)
				values (?, now(), ?, convert(?, $fmt), ?, ?, ?, ?, ?, ?, ?, ?, ?,
					?, ?, ?,?,now())";
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
				$unitResult = $v["unitResult"];
				$tax = $v["tax"];
				$moneyWithTax = $v["moneyWithTax"];
				$batchDate = $v["batchDate"];


				$rc = DB::insert($sql, [$this->newId(), $goodsId, $goodsCount, $goodsPrice,
										$goodsMoney, $i, $id, $sn, $dataOrg, $memo, $companyId, $soBillDetailId,
										$taxRate, $tax, $moneyWithTax,$unitResult,$batchDate]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
		}
		$sql = "select sum(goods_money) as sum_goods_money,
					sum(tax) as tax, sum(money_with_tax) as money_with_tax
				from t_ws_bill_detail where wsbill_id = ? ";
		$data = DB::select($sql, [$id]);
		$data = json_decode(json_encode($data),true);
		$sumGoodsMoney = $data[0]["sum_goods_money"];
		if (! $sumGoodsMoney) {
			$sumGoodsMoney = 0;
		}
		$sumTax = $data[0]["tax"];
		$sumMoneyWithTax = $data[0]["money_with_tax"];

		$sql = "update t_ws_bill
				set sale_money = ?, tax = ?, money_with_tax = ?
				where id = ? ";
		$rc = DB::update($sql, [$sumGoodsMoney, $sumTax, $sumMoneyWithTax, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		if ($sobillRef) {
			// 从销售订单生成销售出库单
			$sql = "select id, company_id from t_so_bill where ref = ? ";
			$data = DB::select($sql, [$sobillRef]);
			if (! $data) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			$data = json_decode(json_encode($data),true);
			$sobillId = $data[0]["id"];
			$companyId = $data[0]["company_id"];

			$sql = "update t_ws_bill
					set company_id = ?
					where id = ? ";
			$rc = DB::update($sql, [$companyId, $id]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}

			$sql = "insert into t_so_ws(so_id, ws_id) values(?, ?)";
			$rc = DB::insert($sql, [$sobillId, $id]);
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
	 * 新增或编辑销售出库单
	 */
	public function editWSBill($params) {

		$json = $params["jsonStr"];
		$bill = json_decode(html_entity_decode($json), true);
		if ($bill == null) {
			return $this->bad("传入的参数错误，不是正确的JSON格式");
		}

		$id = $bill["id"];

		$sobillRef = $bill["sobillRef"];

		DB::beginTransaction();

		$log = null;

		$bill["companyId"] = User::getCompanyId();

		if ($id) {
			// 编辑

			$bill["loginUserId"] = $this->getLoginUserId();

			$rc = $this->updateWSBill($bill);
			if ($rc) {
				DB::rollback();
				return $rc;
			}

			$ref = $bill["ref"];
			$log = "编辑销售出库单，单号 = {$ref}";
		} else {
			// 新建销售出库单

			$bill["dataOrg"] = $this->getLoginUserDataOrg(["loginUserId"=>$this->getLoginUserId()]);
			$bill["loginUserId"] = $this->getLoginUserId();

			$rc = $this->addWSBill($bill);
			if ($rc) {
				DB::rollback();
				return $rc;
			}

			$id = $bill["id"];
			$ref = $bill["ref"];
			if ($sobillRef) {
				// 从销售订单生成销售出库单
				$log = "从销售订单(单号：{$sobillRef})生成销售出库单: 单号 = {$ref}";
			} else {
				// 手工新建销售出库单
				$log = "新增销售出库单，单号 = {$ref}";
			}
		}

		// 记录业务日志
		$this->insertBizlog($log, $this->LOG_CATEGORY);

		DB::commit();

		return $this->ok($id);
	}

	/**
	 * 关闭销售订单
	 */
	public function closeSOBill1(&$params) {

		$id = $params["id"];

		$sql = "select ref, bill_status
				from t_so_bill
				where id = ? ";
		$data = DB::select($sql, [$id]);

		if (! $data) {
			return $this->bad("要关闭的销售订单不存在");
		}
		$data = json_decode(json_encode($data),true);
		$ref = $data[0]["ref"];
		$billStatus = $data[0]["bill_status"];

		if ($billStatus >= 4000) {
			return $this->bad("销售订单已经被关闭");
		}

		// 检查该销售订单是否有生成的销售出库单，并且这些销售出库单是没有提交出库的
		// 如果存在这类销售出库单，那么该销售订单不能关闭。
		$sql = "select count(*) as cnt
				from t_ws_bill w, t_so_ws s
				where w.id = s.ws_id and s.so_id = ?
					and w.bill_status = 0 ";
		$data = DB::select($sql, [$id]);
		$data= json_decode(json_encode($data),true);
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
				set bill_status = ?
				where id = ? ";
		$rc = DB::update($sql, [$newBillStatus, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		// 操作成功
		$params["ref"] = $ref;
		return null;
	}

	/**
	 * 关闭销售订单
	 */
	public function closeSOBill($params) {

		$id = $params["id"];

		DB::beginTransaction();
		$rc = $this->closeSOBill1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}

		$ref = $params["ref"];

		// 记录业务日志
		$log = "关闭销售订单，单号：{$ref}";
		$this->insertBizlog($log, $this->LOG_CATEGORY);

		DB::commit();

		return $this->ok($id);
	}

	/**
	 * 取消订单关闭状态
	 */
	public function cancelClosedSOBill1(&$params) {
		$id = $params["id"];

		$sql = "select ref, bill_status
				from t_so_bill
				where id = ? ";
		$data = DB::select($sql, [$id]);

		if (! $data) {
			return $this->bad("要关闭的销售订单不存在");
		}
		$data = json_decode(json_encode($data),true);
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
				set bill_status = ?
				where id = ? ";
		$rc = DB::update($sql, [$newBillStatus, $id]);
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
	public function cancelClosedSOBill($params) {

		$id = $params["id"];

		DB::beginTransaction();
		$rc = $this->cancelClosedSOBill1($params);
		if ($rc) {
			DB::rollback();
			return $rc;
		}

		$ref = $params["ref"];

		// 记录业务日志
		$log = "取消销售订单[单号：{$ref}]的关闭状态";
		$this->insertBizlog($log, $this->LOG_CATEGORY);

		DB::commit();

		return $this->ok($id);
	}

	/**
	 * 获得销售出库单主表列表
	 */
	public function wsbillList($params) {

		$loginUserId = $this->getLoginUserId();
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}

		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];

		$billStatus = $params["billStatus"];
		$ref = $params["ref"];
		$fromDT = $params["fromDT"];
		$toDT = $params["toDT"];
		$warehouseId = $params["warehouseId"];
		$customerId = $params["customerId"];
		$sn = $params["sn"];
		$receivingType = $params["receivingType"];
		$goodsId = $params["goodsId"];

		$sql = "select w.id, w.ref, w.bizdt, c.name as customer_name, u.name as biz_user_name,
					user.name as input_user_name, h.name as warehouse_name, w.sale_money,
					w.bill_status, w.date_created, w.receiving_type, w.memo, w.deal_address,
					w.tax, w.money_with_tax,w.distribution_type
				from t_ws_bill w, t_customer c, t_user u, t_user user, t_warehouse h
				where (w.customer_id = c.id) and (w.biz_user_id = u.id)
				  and (w.input_user_id = user.id) and (w.warehouse_id = h.id) ";
		$queryParams = [];

		$rs = $this->buildSQL('2002', "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}

		if ($billStatus != - 1) {
			$sql .= " and (w.bill_status = ?) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (w.ref like ?) ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (w.bizdt >= ?) ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (w.bizdt <= ?) ";
			$queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (w.customer_id = ?) ";
			$queryParams[] = $customerId;
		}
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = ?) ";
			$queryParams[] = $warehouseId;
		}
		if ($sn) {
			$sql .= " and (w.id in
					(select d.wsbill_id from t_ws_bill_detail d
					 where d.sn_note like ?))";
			$queryParams[] = "%$sn%";
		}
		if ($receivingType != - 1) {
			$sql .= " and (w.receiving_type = ?) ";
			$queryParams[] = $receivingType;
		}
		if ($goodsId) {
			$sql .= " and (w.id in
					(select d.wsbill_id from t_ws_bill_detail d
					 where d.goods_id = ?))";
			$queryParams[] = $goodsId;
		}

		$sql .= " order by w.bizdt desc, w.ref desc
				limit ?, ?";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = DB::select($sql, $queryParams);
		$result = [];
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"ref" => $v["ref"],
					"bizDate" => $this->toYMD($v["bizdt"]),
					"customerName" => $v["customer_name"],
					"warehouseName" => $v["warehouse_name"],
					"inputUserName" => $v["input_user_name"],
					"bizUserName" => $v["biz_user_name"],
					"billStatus" => $this->billStatusCodeToName($v["bill_status"]),
					"amount" => $v["sale_money"],
					"dateCreated" => $v["date_created"],
					"receivingType" => $v["receiving_type"],
					"distributionType" => $v["distribution_type"],
					"memo" => $v["memo"],
					"dealAddress" => $v["deal_address"],
					"tax" => $v["tax"],
					"distributionType" => $v["distribution_type"],
					// "distributionStatus"=>$v["distribution_status"],
					"moneyWithTax" => $v["money_with_tax"],
					// "printFlag"=> $v["print_flag"] > 0 ? "▲" : "",
					// $cnt > 0 ? "▲" : "";
			];
		}

		$sql = "select count(*) as cnt
				from t_ws_bill w
				where 1=1 ";
		$queryParams = [];

		$rs = $this->buildSQL('2002', "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}

		if ($billStatus != - 1) {
			$sql .= " and (w.bill_status = ?) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (w.ref like ?) ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (w.bizdt >= ?) ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (w.bizdt <= ?) ";
			$queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (w.customer_id = ?) ";
			$queryParams[] = $customerId;
		}
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = ?) ";
			$queryParams[] = $warehouseId;
		}
		if ($sn) {
			$sql .= " and (w.id in
					(select d.wsbill_id from t_ws_bill_detail d
					 where d.sn_note like ?))";
			$queryParams[] = "%$sn%";
		}
		if ($receivingType != - 1) {
			$sql .= " and (w.receiving_type = ?) ";
			$queryParams[] = $receivingType;
		}
		if ($goodsId) {
			$sql .= " and (w.id in
					(select d.wsbill_id from t_ws_bill_detail d
					 where d.goods_id = ?))";
			$queryParams[] = $goodsId;
		}

		$data = DB::select($sql, $queryParams);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];

		return [
				"dataList" => $result,
				"totalCount" => $cnt
		];
	}

	/**
	 * 获得某个销售出库单的明细记录列表
	 */
	public function wsBillDetailList($params) {
		$companyId = User::getCompanyId();
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$billId = $params["billId"];
		$sql = "select d.id, g.code, g.name, g.spec, u.name as unit_name,g.bar_code,
					convert(d.goods_count, $fmt) as goods_count,
					d.goods_price, d.goods_money, d.sn_note, d.memo,
					d.tax_rate, d.tax, d.money_with_tax,d.unit_result ,d.batch_date
				from t_ws_bill_detail d, t_goods g, t_goods_unit u
				where d.wsbill_id = ? and d.goods_id = g.id and g.unit_id = u.id
				order by d.show_order";
		$data = DB::select($sql, [$billId]);
		$result = [];
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"goodsCode" => $v["code"],
					"goodsName" => $v["name"],
					"goodsSpec" => $v["spec"],
					"unitName" => $v["unit_name"],
					"goodsCount" => $v["goods_count"],
					"goodsPrice" => $v["goods_price"],
					"goodsMoney" => $v["goods_money"],
					"sn" => $v["sn_note"],
					"memo" => $v["memo"],
					"taxRate" => $v["tax_rate"],
					"tax" => $v["tax"],
					"moneyWithTax" => $v["money_with_tax"],
					"unitResult" => $v["unit_result"],
					"batchDate"=>$v["batch_date"],
					// "guaranteeDay"=>$v["guarantee_day"],
					"barCode"=>$v["bar_code"],
			];
		}

		return $result;
	}

	/**
	 * 提交销售出库单
	 */
	public function commitWSBill($params) {

		$id = $params["id"];

		$params["companyId"] = User::getCompanyId();
		$params["loginUserId"] = $this->getLoginUserId();
		$params["userDataOrg"] = $this->getLoginUserDataOrg(["loginUserId"=>$this->getLoginUserId()]);
		$params["loginUserName"] = User::getLoginUserName($this->getLoginUserId());

		DB::beginTransaction();

		$rc = $this->commitWSBill1($params);
		if ($rc && !$rc["success"]) {
			DB::rollback();
			return $rc;
		}

		$ref = $params["ref"];
		$log = "提交销售出库单，单号 = {$ref}";
		$this->insertBizlog($log, $this->LOG_CATEGORY);

		DB::commit();

		return $this->ok($id,$rc["msg"]);
	}

	/**
	 * 提交销售出库单
	 *
	 * @param array $params
	 * @return NULL|array
	 */
	public function commitWSBill1(& $params) {
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

		// true: 先进先出
		$fifo = $this->getInventoryMethod($companyId) == 1;

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$id = $params["id"];

		$sql = "select ref, bill_status, customer_id, warehouse_id, biz_user_id, bizdt, sale_money,
					receiving_type, company_id, money_with_tax
				from t_ws_bill where id = ? ";
		$data = DB::select($sql, [$id]);
		if (! $data) {
			return $this->bad("要提交的销售出库单不存在");
		}
		$data = json_decode(json_encode($data),true);
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

		$customer = $this->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("客户不存在");
		}

		$warehouseDAO = new Warehouses();
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

		$customerDAO = new Customer();
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

		$userDAO = new User();
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
				where wsbill_id = ?
				order by show_order ";
		$items = DB::select($sql, [$id]);
		if (! $items) {
			return $this->bad("销售出库单没有出库商品明细记录，无法出库");
		}
		$items = json_decode(json_encode($items),true);
		// 销售出库数量控制，true - 出库数量不能超过销售订单未出库数量
		$countLimit = $this->getWSCountLimit($companyId) == "1";

		$sql = "select so_id
				from t_so_ws
				where ws_id = ? ";
		$data = DB::select($sql, [$id]);
		$soId = null;
		if ($data) {
			$data = json_decode(json_encode($data),true);
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
					where id = ? ";
			$data = DB::select($sql, [$soBillDetailId]);
			if (! $data) {
				continue;
			}
			$data = json_decode(json_encode($data),true);
			$leftCount = $data[0]["left_count"];
			if ($goodsCount > $leftCount) {
				$index = $i + 1;
				$info = "第{$index}条出库记录中销售出库数量超过销售订单上未出库数量<br/><br/>";
				$info .= "出库数量是: {$goodsCount}<br/>销售订单中未出库数量是: {$leftCount}";
				return $this->bad($info);
			}
		}
		$errGoodsName='';
		foreach ( $items as $v ) {
			$itemId = $v["id"];
			$goodsId = $v["goods_id"];
			$goodsCount = $v["goods_count"];
			$goodsPrice = floatval($v["goods_price"]);

			$sql = "select code, name from t_goods where id = ? ";
			$data = DB::select($sql, [$goodsId]);
			if (! $data) {
				return $this->bad("要出库的商品不存在(商品后台id = {$goodsId})");
			}
			$data = json_decode(json_encode($data),true);
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
						where warehouse_id = ? and goods_id = ? ";
				$data = DB;;select($sql, [$warehouseId, $goodsId]);
				if (! $data) {
					return $this->bad(
							"商品 [ {$goodsName}] 在仓库 [{$warehouseName}] 中没有存货，无法出库");
				}
				$data = json_decode(json_encode($data),true);
				$balanceCount = $data[0]["balance_count"];
				if ($balanceCount < $goodsCount) {
					return $this->bad(
							"商品 [ {$goodsName}] 在仓库 [{$warehouseName}] 中存货数量不足，无法出库");
				}
				$balancePrice = $data[0]["balance_price"];
				$balanceMoney = $data[0]["balance_money"];
				$outCount = $data[0]["out_count"];
				$outMoney = $data[0]["out_money"];

				$sql = "select id, balance_count, balance_price, balance_money,
								out_count, out_price, out_money, date_created
							from t_inventory_fifo
							where warehouse_id = ? and goods_id = ?
								and balance_count > 0
							order by date_created ";
				$data = DB::select($sql, [$warehouseId, $goodsId]);
				if (! $data) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
				$data = json_decode(json_encode($data),true);
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
								set out_count = ?, out_price = ?, out_money = ?,
									balance_count = ?, balance_money = ?
								where id = ? ";
						$rc = DB::update($sql, [$fvOutCount, $fvOutPrice, $fvOutMoney,
														$fvBalanceCount, $fvBalanceMoney, $fvId]);
						if ($rc === false) {
							return $this->sqlError(__METHOD__, __LINE__);
						}

						// fifo 的明细记录
						$sql = "insert into t_inventory_fifo_detail(out_count, out_price, out_money,
								balance_count, balance_price, balance_money, warehouse_id, goods_id,
								date_created, wsbilldetail_id)
								values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
						$rc = DB::insert($sql, [$gc, $fifoPrice, $fifoMoney, $fvBalanceCount,
														$fvBalancePrice, $fvBalanceMoney, $warehouseId, $goodsId,
														$fvDateCreated, $itemId]);
						if ($rc === false) {
							return $this->sqlError(__METHOD__, __LINE__);
						}

						$gc = 0;
					} else {
						$fifoMoneyTotal += $fvBalanceMoney;

						$sql = "update t_inventory_fifo
								set out_count = in_count, out_price = in_price, out_money = in_money,
									balance_count = 0, balance_money = 0
								where id = ? ";
						$rc = DB::update($sql, [$fvId]);
						if ($rc === false) {
							return $this->sqlError(__METHOD__, __LINE__);
						}

						// fifo 的明细记录
						$sql = "insert into t_inventory_fifo_detail(out_count, out_price, out_money,
								balance_count, balance_price, balance_money, warehouse_id, goods_id,
								date_created, wsbilldetail_id)
								values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
						$rc = DB::insert($sql, [$fvBalanceCount, $fvBalancePrice, $fvBalanceMoney,
														0, 0, 0, $warehouseId, $goodsId, $fvDateCreated, $itemId]);
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
						set out_count = ?, out_price = ?, out_money = ?,
						    balance_count = ?, balance_price = ?, balance_money = ?
						where warehouse_id = ? and goods_id = ? ";
				$rc = DB::update($sql, [$outCount, $outPrice, $outMoney, $balanceCount,
										$balancePrice, $balanceMoney, $warehouseId, $goodsId]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}

				// 更新明细账
				$sql = "insert into t_inventory_detail(out_count, out_price, out_money,
						balance_count, balance_price, balance_money, warehouse_id,
						goods_id, biz_date, biz_user_id, date_created, ref_number, ref_type)
						values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now(), ?, '销售出库')";
				$rc = DB::update($sql,[ $goodsCount, $fifoPrice, $fifoMoneyTotal, $balanceCount,
										$balancePrice, $balanceMoney, $warehouseId, $goodsId, $bizDT, $bizUserId,
										$ref]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}


				//更新生产日期记录
				$sql = "select id,balance_count,batch_date from   t_inventory_batch
						where warehouse_id = ? and goods_id = ? order by batch_date asc  ";
				$data = DB::select($sql, [$warehouseId, $goodsId]);
				$data = json_decode(json_encode($data),true);
				$tempCount = $goodsCount;
				if ($data) {
					for($j = 0; $j  < count($data);$j++) {
						$gCount = $data[$j]["balance_count"];
						$tid =  $data[$j]["id"];
						//处理到最新的一批库存 或者只有一条有效期数据
						if($j==count($data)-1)
						{
							$gCount =$tempCount;
						}
						else
						{
							if($gCount<$tempCount){
								$tempCount -=$gCount;
							}
						}
						$sql = "update t_inventory_batch set balance_count = balance_count - ?,out_count = out_count+? where id=?";
							$rc = DB::update($sql, [$gCount,$gCount, $tid]);
					}
				}

				// 更新单据本身的记录
				$sql = "update t_ws_bill_detail
						set inventory_price = ?, inventory_money = ?
						where id = ? ";
				$rc = DB::update($sql, [$fifoPrice, $fifoMoneyTotal, $id]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			} else {
				// 移动平均法

				// 库存总账
				$sql = "select convert(out_count, $fmt) as out_count, out_money,
							convert(balance_count, $fmt) as balance_count, balance_price,
						balance_money from t_inventory
						where warehouse_id = ? and goods_id = ? ";
				$data = DB::select($sql, [$warehouseId, $goodsId]);
				$data = json_decode(json_encode($data),true);
				$balanceCount = $data[0]["balance_count"];
				if ($balanceCount < $goodsCount) {
					$errGoodsName .=" [{$goodsName}]";
				//	return $this->bad("商品 [{$goodsCode} {$goodsName}] 在仓库 [{$warehouseName}] 中存货数量不足，无法出库");
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
						set out_count = convert(?, $fmt), out_price = ?, out_money = ?,
						    balance_count = convert(?, $fmt), balance_money = ?
						where warehouse_id = ? and goods_id = ? ";
				$rc = DB::update($sql, [$outCount, $outPrice, $outMoney, $balanceCount,
										$balanceMoney, $warehouseId, $goodsId]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}

				// 库存明细账
				$sql = "insert into t_inventory_detail(out_count, out_price, out_money,
						balance_count, balance_price, balance_money, warehouse_id,
						goods_id, biz_date, biz_user_id, date_created, ref_number, ref_type)
						values(convert(?, $fmt), ?, ?, convert(?, $fmt), ?, ?, ?, ?, ?, ?, now(), ?, '销售出库')";
				$rc = DB::insert($sql, [$goodsCount, $outPriceDetail, $outMoneyDetail,
										$balanceCount, $balancePrice, $balanceMoney, $warehouseId, $goodsId, $bizDT,
										$bizUserId, $ref]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}

				// 单据本身的记录
				$sql = "update t_ws_bill_detail
						set inventory_price = ?, inventory_money = ?
						where id = ? ";
				$rc = DB::update($sql, [$outPriceDetail, $outMoneyDetail, $itemId]);


			//更新生产日期记录
			$sql = "select id,balance_count,batch_date from   t_inventory_batch
			where warehouse_id = ? and goods_id = ? order by batch_date asc  ";
			$data = DB::select($sql, [$warehouseId, $goodsId]);
			$tempCount = $goodsCount;
			$data = json_decode(json_encode($data),true);
			if ( $data) {
			for($j = 0; $j  < count($data);$j++) {
				$gCount = $data[$j]["balance_count"];
				$tid =  $data[$j]["id"];
				//处理到最新的一批库存 或者只有一条有效期数据
				if($j==count($data)-1)
				{
					$gCount =$tempCount;
				}
				else
				{
					if($gCount<$tempCount){
						$tempCount -=$gCount;
					}
				}
				$sql = "update t_inventory_batch set balance_count = balance_count - ?,out_count = out_count+? where id=?";
				$rc = DB::update($sql, [$gCount,$gCount, $tid]);
				}
			}


				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}

			// 同步销售订单中的出库量
			$sql = "select convert(goods_count, $fmt) as goods_count,
							convert(ws_count, $fmt) as ws_count,
							scbilldetail_id
					from t_so_bill_detail
					where id = ? ";
			$soDetail = DB::select($sql, [$soBillDetailId]);
			if (! $soDetail) {
				// 不是由销售订单创建的出库单
				continue;
			}
			$soDetail = json_decode(json_encode($soDetail),true);
			$scbillDetailId = $soDetail[0]["scbilldetail_id"];

			$totalGoodsCount = $soDetail[0]["goods_count"];
			$totalWSCount = $soDetail[0]["ws_count"];
			$totalWSCount += $goodsCount;
			$totalLeftCount = $totalGoodsCount - $totalWSCount;
			$sql = "update t_so_bill_detail
					set ws_count = convert(?, $fmt), left_count = convert(?, $fmt)
					where id = ? ";
			$rc = DB::update($sql, [$totalWSCount, $totalLeftCount, $soBillDetailId]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}

			// 同步销售合同中的订单执行量
			if ($scbillDetailId) {
				$sql = "select convert(goods_count, $fmt) as goods_count,
							convert(so_count, $fmt) as so_count
						from t_sc_bill_detail
						where id = ? ";
				$data = DB::select($sql, [$scbillDetailId]);
				if (! $data) {
					// 如果执行到这里，多半是数据库数据错误了
					continue;
				}
				$data = json_decode(json_encode($data),true);
				$scGoodsCount = $data[0]["goods_count"];
				$scSoCount = $data[0]["so_count"];
				$scSoCount += $goodsCount;
				$scLeftCount = $scGoodsCount - $scSoCount;

				$sql = "update t_sc_bill_detail
						set so_count = convert(?, $fmt), left_count = convert(?, $fmt)
						where id = ? ";
				$rc = DB::update($sql, [$scSoCount, $scLeftCount, $scbillDetailId]);
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
					where ca_id = ? and ca_type = 'customer' and company_id = ? and data_org = ? ";
			$data = DB::select($sql, [$customerId, $companyId,$userDataOrg]);
			if ($data) {
				$data = json_decode(json_encode($data),true);
				$rvMoney = $data[0]["rv_money"];
				$balanceMoney = $data[0]["balance_money"];

				$rvMoney += $saleMoney;
				$balanceMoney += $saleMoney;

				$sql = "update t_receivables
						set rv_money = ?,  balance_money = ?
						where ca_id = ? and ca_type = 'customer'
							and company_id = ? and data_org  = ? ";
				$rc = DB::update($sql, [$rvMoney, $balanceMoney, $customerId, $companyId,$userDataOrg]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			} else {

				$sql = "insert into t_receivables (id, rv_money, act_money, balance_money,
							ca_id, ca_type, company_id,data_org)
						values (?, ?, 0, ?, ?, 'customer', ?,?)";
				$rc = DB::insert($sql, [$this->newId(), $saleMoney, $saleMoney, $customerId,
										$companyId,$userDataOrg]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}

			// 应收明细账
			$sql = "insert into t_receivables_detail (id, rv_money, act_money, balance_money,
					ca_id, ca_type, date_created, ref_number, ref_type, biz_date, company_id,data_org,receiving_type,operator)
					values(?, ?, 0, ?, ?, 'customer', now(), ?, '销售出库', ?, ?,?,?,?)";

			$rc = DB::insert($sql, [$this->newId(), $saleMoney, $saleMoney, $customerId, $ref,
								$bizDT, $companyId,$userDataOrg,$receivingType,$loginUserName]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		} else if ($receivingType == 1) {
			// 现金收款
			$inCash = $saleMoney;

			$sql = "select in_money, out_money, balance_money
					from t_cash
					where biz_date = ? and company_id = ?  and data_org = ?";
			$data = DB::select($sql, [$bizDT, $companyId,$userDataOrg]);
			if (! $data) {
				$data = json_decode(json_encode($data),true);
				// 当天首次发生现金业务
				$sql = "select sum(in_money) as sum_in_money, sum(out_money) as sum_out_money
							from t_cash
							where biz_date <= ? and company_id = ? and data_org = ? ";
				$data = DB::select($sql, [$bizDT, $companyId,$userDataOrg]);
				$data = json_decode(json_encode($data),true);
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
							values (?, ?, ?, ?,?)";
				$rc = DB::insert($sql, [$inCash, $balanceCash, $bizDT, $companyId,$userDataOrg]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}

				// 记现金明细账
				$sql = "insert into t_cash_detail(in_money, balance_money, biz_date, ref_type,
								ref_number, date_created, company_id,data_org)
							values (?, ?, ?, '销售出库', ?, now(), ?,?)";
				$rc = DB::insert($sql, [$inCash, $balanceCash, $bizDT, $ref, $companyId,$userDataOrg]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			} else {
				$balanceCash = $data[0]["balance_money"] + $inCash;
				$sumInMoney = $data[0]["in_money"] + $inCash;
				$sql = "update t_cash
						set in_money = ?, balance_money = ?
						where biz_date = ? and company_id = ? and data_org = ? ";
				$rc = DB::update($sql, [$sumInMoney, $balanceCash, $bizDT, $companyId,$userDataOrg]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}

				// 记现金明细账
				$sql = "insert into t_cash_detail(in_money, balance_money, biz_date, ref_type,
							ref_number, date_created, company_id,data_org)
						values (?, ?, ?, '销售出库', ?, now(), ?,?)";
				$rc = DB::insert($sql, [$inCash, $balanceCash, $bizDT, $ref, $companyId,$userDataOrg]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}

			// 调整业务日期之后的现金总账和明细账的余额
			$sql = "update t_cash
					set balance_money = balance_money + ?
					where biz_date > ? and company_id = ? and data_org = ?";
			$rc = DB::update($sql, [$inCash, $bizDT, $companyId,$userDataOrg]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}

			$sql = "update t_cash_detail
					set balance_money = balance_money + ?
					where biz_date > ? and company_id = ? and data_org = ? ";
			$rc = DB::update($sql, [$inCash, $bizDT, $companyId,$userDataOrg]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		} else if ($receivingType == 2) {
			// 2: 用预收款支付

			$outMoney = $saleMoney;

			// 预收款总账
			$sql = "select out_money, balance_money from t_pre_receiving
						where customer_id = ? and company_id = ? ";
			$data = DB::select($sql, [$customerId, $companyId]);
			$data = json_decode(json_encode($data),true);
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
					set out_money = ?, balance_money = ?
					where customer_id = ? and company_id = ? ";
			$rc = DB::update($sql, [$totalOutMoney, $totalBalanceMoney, $customerId, $companyId]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}

			// 预收款明细账
			$sql = "insert into t_pre_receiving_detail (id, customer_id, out_money, balance_money,
						biz_date, date_created, ref_number, ref_type, biz_user_id, input_user_id, company_id)
					values (?, ?, ?, ?, ?, now(), ?, '销售出库', ?, ?, ?)";
			$rc = DB::insert($sql, [$this->newId(), $customerId, $outMoney, $totalBalanceMoney,
								$bizDT, $ref, $bizUserId, $loginUserId, $companyId]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}

		// 把单据本身设置为已经提交出库
		$sql = "select sum(inventory_money) as sum_inventory_money
				from t_ws_bill_detail
				where wsbill_id = ? ";
		$data = DB::select($sql, [$id]);
		$data = json_decode(json_encode($data),true);
		$sumInventoryMoney = $data[0]["sum_inventory_money"];
		if (! $sumInventoryMoney) {
			$sumInventoryMoney = 0;
		}

		$profit = $saleMoney - $sumInventoryMoney;

		// 更新本单据的状态
		$sql = "update t_ws_bill
				set bill_status = 1000, inventory_money = ?, profit = ?
				where id = ? ";
		$rc = DB::update($sql, [$sumInventoryMoney, $profit, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		// 同步销售订单的状态
		$sql = "select so_id
				from t_so_ws
				where ws_id = ? ";
		$data = DB::select($sql, [$id]);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			$soBillId = $data[0]["so_id"];
			$sql = "select count(*) as cnt
					from t_so_bill_detail
					where sobill_id = ? and  left_count > 0";
			$data = DB::select($sql, [$soBillId]);
			$data = json_decode(json_encode($data),true);
			$cnt = $data[0]["cnt"];
			$billStatus = 1000;
			if ($cnt > 0) {
				// 部分出库
				$billStatus = 2000;
			} else {
				$billStatus = 3000;
			}
			$sql = "update t_so_bill
					set bill_status = ?
					where id = ? ";
			$rc = DB::update($sql, [$billStatus, $soBillId]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}

		$params["ref"] = $ref;

		if($errGoodsName!="")
		{
			return $this->bad("商品  {$errGoodsName}在仓库 [{$warehouseName}] 中存货数量不足，无法出库");

		}
		else
		{
			return $this->success("成功完成提交操作");
		}

	}
	/**
	 * 销售退货入库单主表信息列表
	 */
	public function srbillList($params) {


		$loginUserId = $this->getLoginUserId();
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}

		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];

		$billStatus = $params["billStatus"];
		$ref = $params["ref"];
		$fromDT = $params["fromDT"];
		$toDT = $params["toDT"];
		$warehouseId = $params["warehouseId"];
		$customerId = $params["customerId"];
		$sn = $params["sn"];
		$paymentType = $params["paymentType"];
		$goodsId = $params["goodsId"];

		$sql = "select w.id, w.ref, w.bizdt, c.name as customer_name, u.name as biz_user_name,
				 	user.name as input_user_name, h.name as warehouse_name, w.rejection_sale_money,
				 	w.bill_status, w.date_created, w.payment_type, w.bill_memo
				 from t_sr_bill w, t_customer c, t_user u, t_user user, t_warehouse h
				 where (w.customer_id = c.id) and (w.biz_user_id = u.id)
				 and (w.input_user_id = user.id) and (w.warehouse_id = h.id) ";
		$queryParams = [];

		$rs = $this->buildSQL('2026', "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}

		if ($billStatus != - 1) {
			$sql .= " and (w.bill_status = ?) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (w.ref like ?) ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (w.bizdt >= ?) ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (w.bizdt <= ?) ";
			$queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (w.customer_id = ?) ";
			$queryParams[] = $customerId;
		}
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = ?) ";
			$queryParams[] = $warehouseId;
		}
		if ($sn) {
			$sql .= " and (w.id in (
					  select d.srbill_id
					  from t_sr_bill_detail d
					  where d.sn_note like ?)) ";
			$queryParams[] = "%$sn%";
		}
		if ($paymentType != - 1) {
			$sql .= " and (w.payment_type = ?) ";
			$queryParams[] = $paymentType;
		}
		if ($goodsId) {
			$sql .= " and (w.id in (
					  select d.srbill_id
					  from t_sr_bill_detail d
					  where d.goods_id = ?)) ";
			$queryParams[] = $goodsId;
		}

		$sql .= " order by w.bizdt desc, w.ref desc
				 limit ?, ?";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = DB::select($sql, $queryParams);
		$data = json_decode(json_encode($data),true);
		$result = [];

		foreach ( $data as $v ) {
			$billStatusName="";
			if($v["bill_status"]==0){
				$billStatusName="待审核";
			}
			elseif ($v["bill_status"]==500) {
				$billStatusName="待入库";
			}
			else{
				$billStatusName="已入库";
			}

			$result[] = [
					"id" => $v["id"],
					"ref" => $v["ref"],
					"bizDate" => $this->toYMD($v["bizdt"]),
					"customerName" => $v["customer_name"],
					"warehouseName" => $v["warehouse_name"],
					"inputUserName" => $v["input_user_name"],
					"bizUserName" => $v["biz_user_name"],
					"billStatus" => $billStatusName,
					"amount" => $v["rejection_sale_money"],
					"dateCreated" => $v["date_created"],
					"paymentType" => $v["payment_type"],
					"billMemo" => $v["bill_memo"]
			];
		}

		$sql = "select count(*) as cnt
				 from t_sr_bill w, t_customer c, t_user u, t_user user, t_warehouse h
				 where (w.customer_id = c.id) and (w.biz_user_id = u.id)
				 and (w.input_user_id = user.id) and (w.warehouse_id = h.id) ";
		$queryParams = [];

		$rs = $this->buildSQL('2026', "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}

		if ($billStatus != - 1) {
			$sql .= " and (w.bill_status = ?) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (w.ref like ?) ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (w.bizdt >= ?) ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (w.bizdt <= ?) ";
			$queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (w.customer_id = ?) ";
			$queryParams[] = $customerId;
		}
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = ?) ";
			$queryParams[] = $warehouseId;
		}
		if ($sn) {
			$sql .= " and (w.id in (
					  select d.srbill_id
					  from t_sr_bill_detail d
					  where d.sn_note like ?)) ";
			$queryParams[] = "%$sn%";
		}
		if ($paymentType != - 1) {
			$sql .= " and (w.payment_type = ?) ";
			$queryParams[] = $paymentType;
		}
		if ($goodsId) {
			$sql .= " and (w.id in (
					  select d.srbill_id
					  from t_sr_bill_detail d
					  where d.goods_id = ?)) ";
			$queryParams[] = $goodsId;
		}

		$data = DB::select($sql, $queryParams);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];

		return [
				"dataList" => $result,
				"totalCount" => $cnt
		];
	}

	/**
	 * 获得退货入库单单据数据
	 */
	public function srBillInfo($params) {

		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = User::getLoginUserName($this->getLoginUserId());
		$params["companyId"] = User::getCompanyId();

		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$id = $params["id"];

		if (! $id) {
			// 新增单据
			$result["bizUserId"] = $params["loginUserId"];
			$result["bizUserName"] = $params["loginUserName"];
			return $result;
		} else {
			// 编辑单据
			$result = [];
			$sql = "select w.id, w.ref, w.bill_status, w.bizdt, c.id as customer_id, c.name as customer_name,
					 u.id as biz_user_id, u.name as biz_user_name,
					 h.id as warehouse_id, h.name as warehouse_name, ifnull(wsBill.ref,'-') as ws_bill_ref,
						w.payment_type, w.bill_memo
					 from t_sr_bill w join t_customer c on w.customer_id = c.id
					 join t_user u  on w.biz_user_id = u.id
					 join t_warehouse h on w.warehouse_id = h.id
					 left join t_ws_bill wsBill on  wsBill.id = w.ws_bill_id
					 where w.id = ? ";
			$data = DB::select($sql, [$id]);
			if ($data) {
				$data = json_decode(json_encode($data),true);
				$result["ref"] = $data[0]["ref"];
				$result["billStatus"] = $data[0]["bill_status"];
				$result["bizDT"] = date("Y-m-d", strtotime($data[0]["bizdt"]));
				$result["customerId"] = $data[0]["customer_id"];
				$result["customerName"] = $data[0]["customer_name"];
				$result["warehouseId"] = $data[0]["warehouse_id"];
				$result["warehouseName"] = $data[0]["warehouse_name"];
				$result["bizUserId"] = $data[0]["biz_user_id"];
				$result["bizUserName"] = $data[0]["biz_user_name"];
				$result["wsBillRef"] = $data[0]["ws_bill_ref"];
				$result["paymentType"] = $data[0]["payment_type"];
				$result["billMemo"] = $data[0]["bill_memo"];
			}

			$sql = "select d.id, g.id as goods_id, g.code, g.name, g.spec, u.name as unit_name,
						convert(d.goods_count, $fmt) as goods_count,
						d.goods_price, d.goods_money,
						convert(d.rejection_goods_count, $fmt) as rejection_goods_count,
						d.rejection_goods_price, d.rejection_sale_money,
						d.wsbilldetail_id, d.sn_note, d.memo
					 from t_sr_bill_detail d, t_goods g, t_goods_unit u
					 where d.srbill_id = ? and d.goods_id = g.id and g.unit_id = u.id
					 order by d.show_order";
			$data = DB::select($sql, [$id]);
			$items = [];
			$data = json_decode(json_encode($data),true);
			foreach ( $data as $v ) {

				$items[] = [
						"id" => $v["wsbilldetail_id"],
						"goodsId" => $v["goods_id"],
						"goodsCode" => $v["code"],
						"goodsName" => $v["name"],
						"goodsSpec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"goodsCount" => $v["goods_count"],
						"goodsPrice" => $v["goods_price"],
						"goodsMoney" => $v["goods_money"],
						"rejCount" => $v["rejection_goods_count"],
						"rejPrice" => $v["rejection_goods_price"],
						"rejMoney" => $v["rejection_sale_money"],
						"sn" => $v["sn_note"],
						"memo" => $v["memo"]
				];
			}

			$result["items"] = $items;

			return $result;
		}
	}

	/**
	 * 根据销售退货入库单id查询销售退货入库单
	 *
	 * @param string $id
	 * @return array|NULL
	 */
	public function getSRBillById($id) {
		$sql = "select bill_status, ref, data_org, company_id from t_sr_bill where id = ? ";
		$data = DB::select($sql, [$id]);
		if (! $data) {
			return null;
		} else {
			$data = json_decode(json_encode($data),true);
			return array(
					"ref" => $data[0]["ref"],
					"billStatus" => $data[0]["bill_status"],
					"dataOrg" => $data[0]["data_org"],
					"companyId" => $data[0]["company_id"]
			);
		}
	}

	/**
	 * 编辑销售退货入库单
	 *
	 * @param array $bill
	 * @return NULL|array
	 */
	public function updateSRBill(& $bill) {

		$id = $bill["id"];

		$oldBill = $this->getSRBillById($id);
		if (! $oldBill) {
			return $this->bad("要编辑的销售退货入库单不存在");
		}
		$billStatus = $oldBill["billStatus"];
		if ($billStatus != 0) {
			return $this->bad("销售退货入库单已经提交，不能再编辑");
		}
		$ref = $oldBill["ref"];
		$dataOrg = $oldBill["dataOrg"];
		$companyId = $oldBill["companyId"];

		$bizDT = $bill["bizDT"];
		$customerId = $bill["customerId"];
		$warehouseId = $bill["warehouseId"];
		$bizUserId = $bill["bizUserId"];
		$items = $bill["items"];
		$paymentType = $bill["paymentType"];
		$billMemo = $bill["billMemo"];

		$customerDAO = new Customer();
		$customer = $this->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("选择的客户不存在");
		}

		$warehouseDAO = new Warehouses();
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			return $this->bad("选择的仓库不存在");
		}

		$userDAO = new UserDAO();
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("选择的业务员不存在");
		}

		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
		}

		$loginUserId = $bill["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}

		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		// 主表
		$sql = "update t_sr_bill
				set bizdt = ?, biz_user_id = ?, date_created = now(),
				   input_user_id = ?, warehouse_id = ?,
					payment_type = ?, bill_memo = ?
				where id = ? ";
		DB::update($sql, [$bizDT, $bizUserId, $loginUserId, $warehouseId, $paymentType, $billMemo,
						$id]);

		// 退货明细
		$sql = "delete from t_sr_bill_detail where srbill_id = ? ";
		$rc = DB::delete()($sql, [$id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		foreach ( $items as $i => $v ) {
			$wsBillDetailId = isset($v["id"]) ? $v["id"] : 0;
			if($wsBillDetailId)//这种情况表示用出库单生成的退库单
			{
            	$sql = "select inventory_price, convert(goods_count, $fmt) as goods_count, goods_price, goods_money
					from t_ws_bill_detail
					where id = ? ";
			$data = DB::select($sql, [$wsBillDetailId]);
			}
			else//这种情况算是直接创建的退库单 从库存中获取
			{
				$goodsId = $v["goodsId"];
				$sql = "select  in_price as inventory_price ,0 as goods_count,0 as goods_price, 0 as goods_money
				from t_inventory
				where goods_id = ? and  warehouse_id = ? ";
				$data = DB::select($sql, [$goodsId,$warehouseId]);

				if (!$data) {

					$sql = "select  name
					from t_goods
					where id = ?  ";
					$good = DB::select($sql, [$goodsId]);
					$good = json_decode(json_encode($good),true);
					$name = $good[0]["name"];
					return $this->bad("选择的仓库不存在要退货的商品[".$name ."]");
				}
			}
			if (! $data) {
				continue;
			}
			$data = json_decode(json_encode($data),true);
			$goodsCount = $data[0]["goods_count"];
			$goodsPrice = $data[0]["goods_price"];
			$goodsMoney = $data[0]["goods_money"];
			$inventoryPrice = $data[0]["inventory_price"];
			$rejCount = $v["rejCount"];
			$rejPrice = $v["rejPrice"];
			if ($rejCount == null) {
				$rejCount = 0;
			}
			$rejSaleMoney = $v["rejMoney"];
			$inventoryMoney = $rejCount * $inventoryPrice;
			$goodsId = $v["goodsId"];
			$sn = $v["sn"];
			$memo = $v["memo"];

			$sql = "insert into t_sr_bill_detail(id, date_created, goods_id, goods_count, goods_money,
					goods_price, inventory_money, inventory_price, rejection_goods_count,
					rejection_goods_price, rejection_sale_money, show_order, srbill_id, wsbilldetail_id,
						sn_note, data_org, company_id, memo)
					values(?, now(), ?, convert(?, $fmt), ?, ?, ?, ?, convert(?, $fmt),
						?, ?, ?, ?, ?, ?, ?, ?, ?) ";
			$rc = DB::insert($sql, [$this->newId(), $goodsId, $goodsCount, $goodsMoney,
								$goodsPrice, $inventoryMoney, $inventoryPrice, $rejCount, $rejPrice,
								$rejSaleMoney, $i, $id, $wsBillDetailId, $sn, $dataOrg, $companyId, $memo]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}

		// 更新主表的汇总信息
		$sql = "select sum(rejection_sale_money) as rej_money,
				sum(inventory_money) as inv_money
				from t_sr_bill_detail
				where srbill_id = ? ";
		$data = DB::select($sql, [$id]);
		$data = json_decode(json_encode($data),true);
		$rejMoney = $data[0]["rej_money"];
		if (! $rejMoney) {
			$rejMoney = 0;
		}
		$invMoney = $data[0]["inv_money"];
		if (! $invMoney) {
			$invMoney = 0;
		}
		$profit = $invMoney - $rejMoney;
		$sql = "update t_sr_bill
				set rejection_sale_money = ?, inventory_money = ?, profit = ?
				where id = ? ";
		$rc = DB::update($sql, [$rejMoney, $invMoney, $profit, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		$bill["ref"] = $ref;

		// 操作成功
		return null;
	}

	/**
	 * 新建销售退货入库单
	 *
	 * @param array $bill
	 * @return NULL|array
	 */
	public function addSRBill(& $bill) {
		$bizDT = $bill["bizDT"];
		$customerId = $bill["customerId"];
		$warehouseId = $bill["warehouseId"];
		$bizUserId = $bill["bizUserId"];
		$items = $bill["items"];
		$wsBillId = isset($bill["wsBillId"]) ? $bill["wsBillId"] : 123;
		$paymentType = $bill["paymentType"];
		$billMemo = $bill["billMemo"];

		$customer = $this->getCustomerById($customerId);
		if (! $customer) {
			return $this->bad("选择的客户不存在");
		}

		$warehouseDAO = new Warehouses();
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			return $this->bad("选择的仓库不存在");
		}

		$userDAO = new User();
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("选择的业务员不存在");
		}

		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
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
		/*$userDAO = new User();
		$isCheckBill= $userDAO->getIsCheckBill($loginUserId);
		if( $isCheckBill==0){
			$billStatus=500;
		}*/
		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";

		$id = $this->newId();
		$ref = $this->genNewBillRef($companyId);
		$sql = "insert into t_sr_bill(id, bill_status, bizdt, biz_user_id, customer_id,
					date_created, input_user_id, ref, warehouse_id, ws_bill_id, payment_type,
					data_org, company_id, bill_memo)
				values (?, ?, ?, ?, ?,
					  now(), ?, ?, ?, ?, ?, ?, ?, ?)";

		$rc = DB::insert($sql, [$id,$billStatus, $bizDT, $bizUserId, $customerId, $loginUserId, $ref,
						$warehouseId, $wsBillId, $paymentType, $dataOrg, $companyId, $billMemo]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		foreach ( $items as $i => $v ) {
			$wsBillDetailId = isset($v["id"]) ? $v["id"] : 0;
			if($wsBillDetailId)//这种情况表示用出库单生成的退库单
			{
                $sql = "select inventory_price, convert(goods_count, $fmt) as goods_count, goods_price, goods_money
					from t_ws_bill_detail
					where id = ? ";
					$data = DB::select($sql, [$wsBillDetailId]);
			}
			else//这种情况算是直接创建的退库单 从库存中获取
			{
				$goodsId = $v["goodsId"];
				$sql = "select  in_price as inventory_price ,0 as goods_count,0 as goods_price, 0 as goods_money
				from t_inventory
				where goods_id = ? and  warehouse_id = ? ";
				$data = DB::select($sql, [$goodsId,$warehouseId]);

				if (!$data) {
					$sql = "select  name
					from t_goods
					where id = ?  ";
					$good = DB::select($sql, [$goodsId]);
					$good = json_decode(json_encode($good),true);
					$name = $good[0]["name"];
					return $this->bad("选择的仓库不存在要退货的商品[".$name ."]");
				}
			}

			if (! $data) {
				continue;
			}
			$data = json_decode(json_encode($data),true);
			$goodsCount = $data[0]["goods_count"];
			$goodsPrice = $data[0]["goods_price"];
			$goodsMoney = $data[0]["goods_money"];
			$inventoryPrice = $data[0]["inventory_price"];
			$rejCount = $v["rejCount"];
			$rejPrice = $v["rejPrice"];
			if ($rejCount == null) {
				$rejCount = 0;
			}
			$rejSaleMoney = $rejCount * $rejPrice;
			$inventoryMoney = $rejCount * $inventoryPrice;
			$goodsId = $v["goodsId"];
			$sn = $v["sn"];
			$memo = $v["memo"];

			$sql = "insert into t_sr_bill_detail(id, date_created, goods_id, goods_count, goods_money,
					goods_price, inventory_money, inventory_price, rejection_goods_count,
					rejection_goods_price, rejection_sale_money, show_order, srbill_id, wsbilldetail_id,
						sn_note, data_org, company_id, memo)
					values(?, now(), ?, convert(?, $fmt), ?, ?, ?, ?, convert(?, $fmt),
					?, ?, ?, ?, ?, ?, ?, ?, ?) ";
			$rc = DB::insert($sql, [$this->newId(), $goodsId, $goodsCount, $goodsMoney,
								$goodsPrice, $inventoryMoney, $inventoryPrice, $rejCount, $rejPrice,
								$rejSaleMoney, $i, $id, $wsBillDetailId, $sn, $dataOrg, $companyId, $memo]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}

		// 更新主表的汇总信息
		$sql = "select sum(rejection_sale_money) as rej_money,
				sum(inventory_money) as inv_money
				from t_sr_bill_detail
				where srbill_id = ? ";
		$data = DB::select($sql, [$id]);
		$data = json_decode(json_encode($data),true);
		$rejMoney = $data[0]["rej_money"];
		$invMoney = $data[0]["inv_money"];
		$profit = $invMoney - $rejMoney;
		$sql = "update t_sr_bill
				set rejection_sale_money = ?, inventory_money = ?, profit = ?
				where id = ? ";
		$rc = DB::update($sql, [$rejMoney, $invMoney, $profit, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		$bill["id"] = $id;
		$bill["ref"] = $ref;

		// 操作成功
		return null;
	}

	/**
	 * 新增或编辑销售退货入库单
	 */
	public function editSRBill($params) {

		$json = $params["jsonStr"];
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

			$rc = $this->updateSRBill($bill);
			if ($rc) {
				DB::rollback();
				return $rc;
			}

			$ref = $bill["ref"];
			$log = "编辑销售退货入库单，单号：{$ref}";
		} else {
			// 新增

			$bill["dataOrg"] = $this->getLoginUserDataOrg(["loginUserId"=>$this->getLoginUserId()]);
			$bill["loginUserId"] = $this->getLoginUserId();

			$rc = $this->addSRBill($bill);
			if ($rc) {
				DB::rollback();
				return $rc;
			}

			$id = $bill["id"];
			$ref = $bill["ref"];

			$log = "新建销售退货入库单，单号：{$ref}";
		}

		// 记录业务日志
		$this->insertBizlog($log, $this->LOG_CATEGORY);

		DB::commit();

		return $this->ok($id);
	}
}
