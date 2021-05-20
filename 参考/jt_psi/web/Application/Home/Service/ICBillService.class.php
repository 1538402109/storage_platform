<?php

namespace Home\Service;

use Home\DAO\ICBillDAO;
use Home\DAO\BizConfigDAO;
use Home\DAO\GoodsDAO;
/**
 * 库存盘点Service
 *
 * @author JIATU
 */
class ICBillService extends PSIBaseExService {
	private $LOG_CATEGORY = "库存盘点";

	/**
	 * 获得某个盘点单的详情
	 */
	public function icBillInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = $this->getLoginUserName();
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new ICBillDAO($this->db());
		
		return $dao->icBillInfo($params);
	}

	/**
	 * 新建或盘点数据录入
	 */
	public function editICBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$json = $params["jsonStr"];
		$bill = json_decode(html_entity_decode($json), true);
		if ($bill == null) {
			return $this->bad("传入的参数错误，不是正确的JSON格式");
		}
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new ICBillDAO($db);
		
		$id = $bill["id"];
		
		$log = null;
		
		$bill["companyId"] = $this->getCompanyId();
		
		if ($id) {
			// 编辑单据
			
			$bill["loginUserId"] = $this->getLoginUserId();
			$rc = $dao->updateICBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$ref = $bill["ref"];
			$log = "盘点数据录入，单号：$ref";
		} else {
			// 新建单据
			
			$bill["dataOrg"] = $this->getLoginUserDataOrg();
			$bill["loginUserId"] = $this->getLoginUserId();
			
			$rc = $dao->addICBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$ref = $bill["ref"];
			$log = "新建盘点单，单号：$ref";
		}
		
		// 记录业务日志
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 盘点单列表
	 */
	public function icbillList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new ICBillDAO($this->db());
		return $dao->icbillList($params);
	}

	/**
	 * 盘点单明细记录
	 */
	public function icBillDetailList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new ICBillDAO($this->db());
		return $dao->icBillDetailList($params);
	}

	/**
	 * 删除盘点单
	 */
	public function deleteICBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new ICBillDAO($db);
		$rc = $dao->deleteICBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$bs = new BizlogService($db);
		
		$ref = $params["ref"];
		$log = "删除盘点单，单号：$ref";
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok();
	}

	/**
	 * 提交盘点单
	 */
	public function commitICBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = $this->db();
		$db->startTrans();
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new ICBillDAO($db);
		$rc = $dao->commitICBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		// 记录业务日志
		$bs = new BizlogService($db);
		$ref = $params["ref"];
		$log = "提交盘点单，单号：$ref";
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		$id = $params["id"];
		return $this->ok($id);
	}

	/**
	 * 盘点单生成pdf文件
	 */
	public function pdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$ref = $params["ref"];
		
		$dao = new ICBillDAO($this->db());
		
		$bill = $dao->getDataForPDF($params);
		if (! $bill) {
			return;
		}
		
		// 记录业务日志
		$log = "盘点单(单号：$ref)生成PDF文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstance();
		$pdf->SetTitle("盘点单，单号：{$ref}");
		
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
		
		$pdf->SetHeaderData("", 0, $productionName, "盘点单");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr><td colspan="2">单号：' . $ref . '</td></tr>
					<tr><td>盘点仓库：' . $bill["warehouseName"] . '</td><td></td></tr>
					<tr><td>业务员：' . $bill["bizUserName"] . '</td><td>业务日期：' . $bill["bizDT"] . '</td></tr>
					<tr><td colspan="2">备注：' . $bill["billMemo"] . '</td></tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>商品编号</td><td>商品名称</td><td>规格型号</td><td>盘点后库存数量</td><td>单位</td>
						<td>盘点后库存金额</td><td>备注</td>
					</tr>
				';
		foreach ( $bill["items"] as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["goodsCode"] . '</td>';
			$html .= '<td>' . $v["goodsName"] . '</td>';
			$html .= '<td>' . $v["goodsSpec"] . '</td>';
			$html .= '<td align="right">' . $v["goodsCount"] . '</td>';
			$html .= '<td>' . $v["unitName"] . '</td>';
			$html .= '<td align="right">' . $v["goodsMoney"] . '</td>';
			$html .= '<td>' . $v["memo"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= "";
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
		
		ob_end_clean();
		ob_clean();
		$pdf->Output("$ref.pdf", "I");
	}
	/**
	 * 商品列表
	 */
	public function goodsList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new GoodsDAO($this->db());
		return $dao->goodsList($params);
	}
		/**
	 * 商品列表
	 */
	public function goodsListForCheck($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new GoodsDAO($this->db());
		return $dao->goodsListForCheck($params);
	}
	public function GoodsList1($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$db = M();
		
		$companyId = (new UserService())->getCompanyId();
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$warehouseId = $params["warehouseId"];
		$code = $params["code"];
		$name = $params["name"];
		$spec = $params["spec"];
		$brandId = $params["brandId"];
		$page = $params["page"];
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
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 生成打印盘点单的数据
	 * 
	 * @param array $params        	
	 */
	public function getICBillDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new ICBillDAO($this->db());
		return $dao->getICBillDataForLodopPrint($params);
	}
}