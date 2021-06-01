<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Base;
use App\Models\Supplier;
use App\Models\Goods;
use Illuminate\Support\Facades\DB;
class Purchase extends Base
{
    use HasFactory;
    private $LOG_CATEGORY = "采购订单";

	/**
	 * 获得采购订单的信息
	 */
	public function poBillInfo($params) {
		
		$params["companyId"] = User::getCompanyId();
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = User::getLoginUserName($this->getLoginUserId());
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$id = $params["id"];
		
		$result = [];
		
		$result["taxRate"] = $this->getTaxRate($companyId);
		
		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		if ($id) {
			// 编辑采购订单
			$sql = "select p.ref, p.deal_date, p.deal_address, p.supplier_id,
						s.name as supplier_name, p.contact, p.tel, p.fax,
						p.org_id, o.full_name, p.biz_user_id, u.name as biz_user_name,
						p.payment_type, p.bill_memo, p.bill_status
					from t_po_bill p, t_supplier s, t_user u, t_org o
					where p.id = ? and p.supplier_id = s.id
						and p.biz_user_id = u.id
						and p.org_id = o.id";
			$data = DB::select($sql, [$id]);
			$data = json_decode(json_encode($data),true);
			if ($data) {
				$v = $data[0];
				$result["ref"] = $v["ref"];
				$result["dealDate"] = $this->toYMD($v["deal_date"]);
				$result["dealAddress"] = $v["deal_address"];
				$result["supplierId"] = $v["supplier_id"];
				$result["supplierName"] = $v["supplier_name"];
				$result["contact"] = $v["contact"];
				$result["tel"] = $v["tel"];
				$result["fax"] = $v["fax"];
				$result["orgId"] = $v["org_id"];
				$result["orgFullName"] = $v["full_name"];
				$result["bizUserId"] = $v["biz_user_id"];
				$result["bizUserName"] = $v["biz_user_name"];
				$result["paymentType"] = $v["payment_type"];
				$result["billMemo"] = $v["bill_memo"];
				$result["billStatus"] = $v["bill_status"];
				
				// 明细表
				$sql = "select p.id, p.goods_id, g.code, g.name, g.spec, 
							convert(p.goods_count, " . $fmt . ") as goods_count, 
							p.goods_price, p.goods_money,
							p.tax_rate, p.tax, p.money_with_tax, u.name as unit_name, p.memo
						from t_po_bill_detail p, t_goods g, t_goods_unit u
						where p.pobill_id = ? and p.goods_id = g.id and g.unit_id = u.id
						order by p.show_order";
				$items = [];
				$data = DB::select($sql, [$id]);
				$data = json_decode(json_encode($data),true);
				foreach ( $data as $v ) {
					$items[] = [
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
			}
		} else {
			// 新建采购订单
			$loginUserId = $params["loginUserId"];
			$result["bizUserId"] = $loginUserId;
			$result["bizUserName"] = $params["loginUserName"];
			
			$sql = "select o.id, o.full_name
					from t_org o, t_user u
					where o.id = u.org_id and u.id = ? ";
			$data = DB::select($sql, [$loginUserId]);
			$data = json_decode(json_encode($data),true);
			if ($data) {
				$result["orgId"] = $data[0]["id"];
				$result["orgFullName"] = $data[0]["full_name"];
			}
			
			// 采购订单默认付款方式
			$result["paymentType"] = $this->getPOBillDefaultPayment($params);
			
			$genBill = boolval($params["genBill"]);
			if ($genBill) {
				// 从销售订单生产采购订单
				
				// 销售订单单号
				$sobillRef = $params["sobillRef"];
				$sql = "select id from t_so_bill where ref = ? ";
				$data = DB::select($sql, [$sobillRef]);
				if ($data) {
					$data = json_decode(json_encode($data),true);
					$sobillId = $data[0]["id"];
					
					$sql = "select d.id, d.goods_id, g.code, g.name, g.spec,
								convert(d.goods_count, " . $fmt . ") as goods_count,
								d.tax_rate, u.name as unit_name 
							from t_so_bill_detail d, t_goods g, t_goods_unit u
							where d.sobill_id = ? and d.goods_id = g.id and g.unit_id = u.id
							order by d.show_order";
					$data = DB::select($sql, [$sobillId]);
					$data = json_decode(json_encode($data),true);
					foreach ( $data as $v ) {
						
						// 查询商品的建议采购价
						$goodsId = $v["goods_id"];
						$sql = "select purchase_price from t_goods where id = ? ";
						$d = DB::select($sql, [$goodsId]);
						if (! $d) {
							continue;
						}
						$d = json_decode(json_encode($d),true);
						$price = $d[0]["purchase_price"];
						$cnt = $v["goods_count"];
						$taxRate = $v["tax_rate"];
						
						$m = null;
						$tax = null;
						$moneyWithTax = null;
						if ($price) {
							$m = $price * $cnt;
							$tax = $m * $taxRate / 100;
							$moneyWithTax = $m + $tax;
						}
						
						$items[] = [
								"id" => $v["id"],
								"goodsId" => $v["goods_id"],
								"goodsCode" => $v["code"],
								"goodsName" => $v["name"],
								"goodsSpec" => $v["spec"],
								"goodsCount" => $v["goods_count"],
								"goodsPrice" => $price,
								"goodsMoney" => $m,
								"taxRate" => $taxRate,
								"tax" => $tax,
								"moneyWithTax" => $moneyWithTax,
								"unitName" => $v["unit_name"]
						];
					}
					
					$result["items"] = $items;
				}
			}
		}
		
		return $result;
	}

	/**
	 * 新建或编辑采购订单
	 */
	public function editPOBill($json) {
		
		$bill = json_decode(html_entity_decode($json), true);
		if ($bill == null) {
			return $this->bad("传入的参数错误，不是正确的JSON格式");
		}
		DB::beginTransaction();
		
		$us = new User();
		$bill["companyId"] = $us->getCompanyId();
		$bill["loginUserId"] = $this->getLoginUserId();
		$bill["dataOrg"] = $this->getLoginUserDataOrg(["loginUserId"=>$this->getLoginUserId()]);
		
		$id = $bill["id"];
		
		$log = null;
		if ($id) {
			// 编辑
			
			$rc = $this->updatePOBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$ref = $bill["ref"];
			
			$log = "编辑采购订单，单号：{$ref}";
		} else {
			// 新建采购订单
			
			$rc = $this->addPOBill($bill);
			if ($rc) {
				DB::rollback();
				return $rc;
			}
			
			$id = $bill["id"];
			$ref = $bill["ref"];
			
			$sobillRef = $bill["sobillRef"];
			if ($sobillRef) {
				$log = "从销售订单( 单号：{$sobillRef} )生成采购订单( 单号:{$ref} )";
			} else {
				$log = "新建采购订单，单号：{$ref}";
			}
		}
		
		// 记录业务日志
		$this->insertBizlog($log, $this->LOG_CATEGORY);
		
		DB::commit();
		
		return $this->ok($id);
	}

	/**
	 * 根据采购订单id查询采购订单
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getPOBillById($id) {
		$sql = "select ref, data_org, bill_status, company_id
				from t_po_bill where id = ? ";
		$data = $DB::select($sql, [$id]);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			return [
					"ref" => $data[0]["ref"],
					"dataOrg" => $data[0]["data_org"],
					"billStatus" => $data[0]["bill_status"],
					"companyId" => $data[0]["company_id"]
			];
		} else {
			return null;
		}
	}

	/**
	 * 新建采购订单
	 *
	 * @param array $bill        	
	 * @return NULL|array
	 */
	public function addPOBill(& $bill) {
		$dealDate = $bill["dealDate"];
		$supplierId = $bill["supplierId"];
		$orgId = $bill["orgId"];
		$bizUserId = $bill["bizUserId"];
		$paymentType = $bill["paymentType"];
		$contact = $bill["contact"];
		$tel = $bill["tel"];
		$fax = $bill["fax"];
		$dealAddress = $bill["dealAddress"];
		$billMemo = $bill["billMemo"];
		
		// 销售订单单号，当从销售订单生成采购订单的时候，会传入该值
		$sobillRef = $bill["sobillRef"];
		
		$items = $bill["items"];
		
		$dataOrg = $bill["dataOrg"];
		$loginUserId = $bill["loginUserId"];
		$companyId = $bill["companyId"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		if (! $this->dateIsValid($dealDate)) {
			return $this->bad("交货日期不正确");
		}
		
		$supplierDAO = new Supplier();
		$supplier = $supplierDAO->getSupplierById($supplierId);
		if (! $supplier) {
			return $this->bad("供应商不存在");
		}
		
		$org = $this->getOrgById($orgId);
		if (! $org) {
			return $this->bad("组织机构不存在");
		}
		
		$userDAO = new User();
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("业务员不存在");
		}
		
		$id = $this->newId();
		$ref = $this->genNewBillRef($companyId);
			//默认订单状态 支持根据组织机构设置是否需要审核
			$billStatus = 0;
			/*$userDAO = new User();
			$isCheckBill= $userDAO->getIsCheckBill($loginUserId);
			if( $isCheckBill==0){
				$billStatus=1000;
			}*/
		// 主表
		$sql = "insert into t_po_bill(id, ref, bill_status, deal_date, biz_dt, org_id, biz_user_id,
					goods_money, tax, money_with_tax, input_user_id, supplier_id, contact, tel, fax,
					deal_address, bill_memo, payment_type, date_created, data_org, company_id)
				values (?, ?, ?, ?, ?, ?, ?,
					0, 0, 0, ?, ?, ?, ?, ?,
					?, ?, ?, now(), ?, ?)";
		$rc = DB::insert($sql, [$id, $ref,$billStatus, $dealDate, $dealDate, $orgId, $bizUserId, $loginUserId, 
						$supplierId, $contact, $tel, $fax, $dealAddress, $billMemo, $paymentType, $dataOrg, 
						$companyId]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 明细记录
		$goodsDAO = new Goods();
		foreach ( $items as $i => $v ) {
			$goodsId = $v["goodsId"];
			if (! $goodsId) {
				continue;
			}
			$goods = $goodsDAO->getGoodsById($goodsId);
			if (! $goods) {
				continue;
			}
			
			$goodsCount = $v["goodsCount"];
			if ($goodsCount <= 0) {
				return $this->bad("采购数量需要大于0");
			}
			
			$goodsPrice = $v["goodsPrice"];
			if ($goodsPrice < 0) {
				return $this->bad("采购单价不能是负数");
			}
			
			// 检查供应商关联商品
			if (! $supplierDAO->goodsIdIsInGoodsRange($supplierId, $goodsId)) {
				$recordInde = $i + 1;
				return $this->bad("第{$recordInde}条记录中的商品不在当前供应商的关联商品内，不能保存");
			}
			
			$goodsMoney = $v["goodsMoney"];
			$taxRate = $v["taxRate"];
			$tax = $v["tax"];
			$moneyWithTax = $v["moneyWithTax"];
			$memo = $v["memo"];
			
			$sql = "insert into t_po_bill_detail(id, date_created, goods_id, goods_count, goods_money,
						goods_price, pobill_id, tax_rate, tax, money_with_tax, pw_count, left_count,
						show_order, data_org, company_id, memo)
					values (?, now(), ?, convert(?, $fmt), ?,
						?, ?, ?, ?, ?, 0, convert(?, $fmt), ?, ?, ?, ?)";
			$rc = DB::insert($sql, [$this->newId(), $goodsId, $goodsCount, $goodsMoney, 
								$goodsPrice, $id, $taxRate, $tax, $moneyWithTax, $goodsCount, $i, $dataOrg, 
								$companyId, $memo]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 同步主表的金额合计字段
		$sql = "select sum(goods_money) as sum_goods_money, sum(tax) as sum_tax,
					sum(money_with_tax) as sum_money_with_tax
				from t_po_bill_detail
				where pobill_id = ? ";
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
		
		$sql = "update t_po_bill
				set goods_money = ?, tax = ?, money_with_tax = ?
				where id = ? ";
		$rc = DB::update($sql, [$sumGoodsMoney, $sumTax, $sumMoneyWithTax, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		if ($sobillRef) {
			// 关联采购订单和销售订单
			$sql = "select id from t_so_bill where ref = ? ";
			$data = DB::select($sql, [$sobillRef]);
			if ($data) {
				$data = json_decode(json_encode($data),true);
				$sobillId = $data[0]["id"];
				
				$sql = "insert into t_so_po (so_id, po_id) values (?, ?)";
				$rc = DB::insert($sql, [$sobillId, $id]);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
		}
		
		$bill["id"] = $id;
		$bill["ref"] = $ref;
		
		// 操作成功
		return null;
	}

	/**
	 * 编辑采购订单
	 *
	 * @param array $bill        	
	 * @return NULL|array
	 */
	public function updatePOBill(& $bill) {
		$id = $bill["id"];
		$poBill = $this->getPOBillById($id);
		if (! $poBill) {
			return $this->bad("要编辑的采购订单不存在");
		}
		
		$ref = $poBill["ref"];
		$dataOrg = $poBill["dataOrg"];
		$companyId = $poBill["companyId"];
		$billStatus = $poBill["billStatus"];
		if ($billStatus != 0) {
			return $this->bad("当前采购订单已经审核，不能再编辑");
		}
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$dataScale = $this->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$dealDate = $bill["dealDate"];
		$supplierId = $bill["supplierId"];
		$orgId = $bill["orgId"];
		$bizUserId = $bill["bizUserId"];
		$paymentType = $bill["paymentType"];
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
		
		if (! $this->dateIsValid($dealDate)) {
			return $this->bad("交货日期不正确");
		}
		
		$supplierDAO = new Supplier;
		$supplier = $supplierDAO->getSupplierById($supplierId);
		if (! $supplier) {
			return $this->bad("供应商不存在");
		}
		
		$org = $this->getOrgById($orgId);
		if (! $org) {
			return $this->bad("组织机构不存在");
		}
		
		$userDAO = new User();
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("业务员不存在");
		}
		
		$sql = "delete from t_po_bill_detail where pobill_id = ? ";
		$rc = DB::delete($sql, [$id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$goodsDAO = new Goods();
		
		foreach ( $items as $i => $v ) {
			$goodsId = $v["goodsId"];
			if (! $goodsId) {
				continue;
			}
			if (! $goodsDAO->getGoodsById($goodsId)) {
				continue;
			}
			
			$goodsCount = $v["goodsCount"];
			if ($goodsCount <= 0) {
				return $this->bad("采购数量需要大于0");
			}
			$goodsPrice = $v["goodsPrice"];
			if ($goodsPrice < 0) {
				return $this->bad("采购单价不能是负数");
			}
			
			// 检查供应商关联商品
			if (! $supplierDAO->goodsIdIsInGoodsRange($supplierId, $goodsId)) {
				$recordInde = $i + 1;
				return $this->bad("第{$recordInde}条记录中的商品不在当前供应商的关联商品内，不能保存");
			}
			
			$goodsMoney = $v["goodsMoney"];
			$taxRate = $v["taxRate"];
			$tax = $v["tax"];
			$moneyWithTax = $v["moneyWithTax"];
			$memo = $v["memo"];
			
			$sql = "insert into t_po_bill_detail(id, date_created, goods_id, goods_count, goods_money,
						goods_price, pobill_id, tax_rate, tax, money_with_tax, pw_count, left_count,
						show_order, data_org, company_id, memo)
					values (?, now(), ?, convert(?, $fmt), ?,
						?, ?, ?, ?, ?, 0, convert(?, $fmt), ?, ?, ?, ?)";
			$rc = DB::insert($sql, [$this->newId(), $goodsId, $goodsCount, $goodsMoney, 
								$goodsPrice, $id, $taxRate, $tax, $moneyWithTax, $goodsCount, $i, $dataOrg, 
								$companyId, $memo]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 同步主表的金额合计字段
		$sql = "select sum(goods_money) as sum_goods_money, sum(tax) as sum_tax,
							sum(money_with_tax) as sum_money_with_tax
						from t_po_bill_detail
						where pobill_id = ? ";
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
		
		$sql = "update t_po_bill
				set goods_money = ?, tax = ?, money_with_tax = ?,
					deal_date = ?, supplier_id = ?,
					deal_address = ?, contact = ?, tel = ?, fax = ?,
					org_id = ?, biz_user_id = ?, payment_type = ?,
					bill_memo = ?, input_user_id = ?, date_created = now()
				where id = ? ";
		$rc = DB::update($sql, [$sumGoodsMoney, $sumTax, $sumMoneyWithTax, $dealDate, $supplierId, 
						$dealAddress, $contact, $tel, $fax, $orgId, $bizUserId, $paymentType, $billMemo, 
						$loginUserId, $id]);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$bill["ref"] = $ref;
		
		// 操作成功
		return null;
	}
}