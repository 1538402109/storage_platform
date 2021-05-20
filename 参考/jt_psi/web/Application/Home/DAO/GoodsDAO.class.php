<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 商品DAO
 *
 * @author JIATU
 */
class GoodsDAO extends PSIBaseExDAO {

	/**
	 * 商品列表
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function goodsList($params) {
		$db = $this->db;
		
		$categoryId = $params["categoryId"];
		$code = $params["code"];
		$name = $params["name"];
		$spec = $params["spec"];
		$barCode = $params["barCode"];
		$brandId = $params["brandId"];
		
		$start = $params["start"];
		$limit = $params["limit"];
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$result = [];
		$sql = "select g.id, g.code, g.name, g.sale_price, g.spec,  g.unit_id, u.name as unit_name,
					g.purchase_price, g.bar_code, g.memo, g.data_org, g.brand_id, g.record_status,
					g.tax_rate
				from t_goods g, t_goods_unit u
				where (g.unit_id = u.id) and (g.category_id = '%s') ";
		$queryParam = [];
		$queryParam[] = $categoryId;
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		
		if ($code) {
			$sql .= " and (g.code like '%s') ";
			$queryParam[] = "%{$code}%";
		}
		if ($name) {
			$sql .= " and (g.name like '%s' or g.py like '%s') ";
			$queryParam[] = "%{$name}%";
			$queryParam[] = "%{$name}%";
		}
		if ($spec) {
			$sql .= " and (g.spec like '%s')";
			$queryParam[] = "%{$spec}%";
		}
		if ($barCode) {
			$sql .= " and (g.bar_code = '%s') ";
			$queryParam[] = $barCode;
		}
		if ($brandId) {
			$sql .= " and (g.brand_id = '%s') ";
			$queryParam[] = $brandId;
		}
		
		$sql .= " order by g.code limit %d, %d";
		$queryParam[] = $start;
		$queryParam[] = $limit;
		$data = $db->query($sql, $queryParam);
		
		foreach ( $data as $v ) {
			$brandId = $v["brand_id"];
			$brandFullName = $brandId ? $this->getBrandFullNameById($db, $brandId) : null;
			
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"salePrice" => $v["sale_price"],
					"spec" => $v["spec"],
					"unitId" => $v["unit_id"],
					"unitName" => $v["unit_name"],
					"purchasePrice" => $v["purchase_price"] == 0 ? null : $v["purchase_price"],
					"barCode" => $v["bar_code"],
					"memo" => $v["memo"],
					"dataOrg" => $v["data_org"],
					"brandFullName" => $brandFullName,
					"recordStatus" => $v["record_status"],
					"taxRate" => $this->toTaxRate($v["tax_rate"])
			];
		}
		
		$sql = "select count(*) as cnt from t_goods g where (g.category_id = '%s') ";
		$queryParam = [];
		$queryParam[] = $categoryId;
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		if ($code) {
			$sql .= " and (g.code like '%s') ";
			$queryParam[] = "%{$code}%";
		}
		if ($name) {
			$sql .= " and (g.name like '%s' or g.py like '%s') ";
			$queryParam[] = "%{$name}%";
			$queryParam[] = "%{$name}%";
		}
		if ($spec) {
			$sql .= " and (g.spec like '%s')";
			$queryParam[] = "%{$spec}%";
		}
		if ($barCode) {
			$sql .= " and (g.bar_code = '%s') ";
			$queryParam[] = $barCode;
		}
		if ($brandId) {
			$sql .= " and (g.brand_id = '%s') ";
			$queryParam[] = $brandId;
		}
		
		$data = $db->query($sql, $queryParam);
		$totalCount = $data[0]["cnt"];
		
		return [
				"goodsList" => $result,
				"totalCount" => $totalCount
		];
	}
/**
	 * 加载商品列表
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function goodsListForCheck($params) {
		$db = $this->db;
		
		$categoryId = $params["categoryId"];
		$code = $params["code"];
		$name = $params["name"];
		$spec = $params["spec"];
		$barCode = $params["barCode"];
		$brandId = $params["brandId"];
		
		$start = $params["start"];
		$limit = $params["limit"];
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$result = [];
		$sql = "select g.id, g.code, g.name, g.sale_price, g.spec,  g.unit_id, u.name as unit_name,
					g.purchase_price, g.bar_code, g.memo, g.data_org, g.brand_id, g.record_status,
					g.tax_rate
				from t_goods g, t_goods_unit u
				where (g.unit_id = u.id)  ";
		$queryParam = [];
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		
		if ($code) {
			$sql .= " and (g.code like '%s') ";
			$queryParam[] = "%{$code}%";
		}
		if ($name) {
			$sql .= " and (g.name like '%s' or g.py like '%s') ";
			$queryParam[] = "%{$name}%";
			$queryParam[] = "%{$name}%";
		}
		if ($spec) {
			$sql .= " and (g.spec like '%s')";
			$queryParam[] = "%{$spec}%";
		}
		if ($barCode) {
			$sql .= " and (g.bar_code = '%s') ";
			$queryParam[] = $barCode;
		}
		if ($brandId) {
			$sql .= " and (g.brand_id = '%s') ";
			$queryParam[] = $brandId;
		}
		
		$sql .= " order by g.code limit %d, %d";
		$queryParam[] = $start;
		$queryParam[] = $limit;
		$data = $db->query($sql, $queryParam);
		
		foreach ( $data as $v ) {
			$brandId = $v["brand_id"];
			$brandFullName = $brandId ? $this->getBrandFullNameById($db, $brandId) : null;
			
			$result[] = [
					"goodsId" => $v["id"],
					"code" => $v["code"],
					"goodsName" => $v["name"],
					"salePrice" => $v["sale_price"],
					"goodsSpec" => $v["spec"],
					"unitId" => $v["unit_id"],
					"unitName" => $v["unit_name"],
					"purchasePrice" => $v["purchase_price"] == 0 ? null : $v["purchase_price"],
					"barCode" => $v["bar_code"],
					"memo" => $v["memo"],
					"dataOrg" => $v["data_org"],
					"brandFullName" => $brandFullName,
					"recordStatus" => $v["record_status"],
					"taxRate" => $this->toTaxRate($v["tax_rate"])
			];
		}
		
		$sql = "select count(*) as cnt from t_goods g where (g.category_id = '%s') ";
		$queryParam = [];
		$queryParam[] = $categoryId;
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		if ($code) {
			$sql .= " and (g.code like '%s') ";
			$queryParam[] = "%{$code}%";
		}
		if ($name) {
			$sql .= " and (g.name like '%s' or g.py like '%s') ";
			$queryParam[] = "%{$name}%";
			$queryParam[] = "%{$name}%";
		}
		if ($spec) {
			$sql .= " and (g.spec like '%s')";
			$queryParam[] = "%{$spec}%";
		}
		if ($barCode) {
			$sql .= " and (g.bar_code = '%s') ";
			$queryParam[] = $barCode;
		}
		if ($brandId) {
			$sql .= " and (g.brand_id = '%s') ";
			$queryParam[] = $brandId;
		}
		
		$data = $db->query($sql, $queryParam);
		$totalCount = $data[0]["cnt"];
		
		return [
				"dataList" => $result,
				"totalCount" => $totalCount
		];
	}
	private function toTaxRate($taxRate) {
		if (! $taxRate) {
			return null;
		}
		
		$r = intval($taxRate);
		if ($r >= 0 && $r <= 17) {
			return "{$r}%";
		} else {
			return null;
		}
	}

	private function getBrandFullNameById($db, $brandId) {
		$sql = "select full_name from t_goods_brand where id = '%s' ";
		$data = $db->query($sql, $brandId);
		if ($data) {
			return $data[0]["full_name"];
		} else {
			return null;
		}
	}

	/**
	 * 新增商品
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function addGoods(& $params) {
		$db = $this->db;
		
		$code = $params["code"];
		$name = $params["name"];
		$spec = $params["spec"];
		$categoryId = $params["categoryId"];
		$unitId = $params["unitId"];
		$salePrice = $params["salePrice"];
		$purchasePrice = $params["purchasePrice"];
		$barCode = $params["barCode"];
		$memo = $params["memo"];
		$brandId = $params["brandId"];
		$recordStatus = $params["recordStatus"];
		$taxRate = $params["taxRate"];
		$lev2SalePrice = $params["lev2SalePrice"];
		$salePrice2 = $params["salePrice2"];
		$salePrice3 = $params["salePrice3"];
		$unit2Id = $params["unit2Id"];
		$unit3Id = $params["unit3Id"];
		$unit2Decimal = $params["unit2Decimal"];
		$unit3Decimal = $params["unit3Decimal"];
		$guaranteeDay = $params["guaranteeDay"];
		$locality = $params["locality"];
		
		if ($lev2SalePrice == "") {
			$lev2SalePrice = $salePrice;
		}
		$dataOrg = $params["dataOrg"];
		$companyId = $params["companyId"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$py = $params["py"];
		$specPY = $params["specPY"];
		
		$goodsUnitDAO = new GoodsUnitDAO($db);
		$unit = $goodsUnitDAO->getGoodsUnitById($unitId);
		if (! $unit) {
			return $this->bad("计量单位不存在");
		}
		
		$goodsCategoryDAO = new GoodsCategoryDAO($db);
		$category = $goodsCategoryDAO->getGoodsCategoryById($categoryId);
		if (! $category) {
			return $this->bad("商品分类不存在");
		}
		
		// 检查商品品牌
		if ($brandId) {
			$brandDAO = new GoodsBrandDAO($db);
			$brand = $brandDAO->getBrandById($brandId);
			if (! $brand) {
				return $this->bad("商品品牌不存在");
			}
		}
		
		// 检查商品编码是否唯一
		$sql = "select count(*) as cnt from t_goods where code = '%s' ";
		$data = $db->query($sql, $code);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("编码为 [{$code}]的商品已经存在");
		}
		
		// 如果录入了条形码，则需要检查条形码是否唯一
		if ($barCode) {
			$sql = "select count(*) as cnt from t_goods where bar_code = '%s' ";
			$data = $db->query($sql, $barCode);
			$cnt = $data[0]["cnt"];
			if ($cnt != 0) {
			//	return $this->bad("条形码[{$barCode}]已经被其他商品使用");
			}
		}
		
		$id = $this->newId();
		$sql = "insert into t_goods (id, code, name, spec, category_id, unit_id, sale_price,
					py, purchase_price, bar_code, memo, data_org, company_id, spec_py, brand_id,
					record_status, lev2_sale_price,sale_price2,sale_price3,unit2_id,unit3_id,unit2_decimal,unit3_decimal,guarantee_day,locality)
				values ('%s', '%s', '%s', '%s', '%s', '%s', %f, '%s', %f, '%s', '%s', '%s', '%s', '%s',
					if('%s' = '', null, '%s'),
					%d,%f,%f,%f,'%s','%s',%d,%d,%d,'%s')";
		$rc = $db->execute($sql, $id, $code, $name, $spec, $categoryId, $unitId, $salePrice, $py, 
				$purchasePrice, $barCode, $memo, $dataOrg, $companyId, $specPY, $brandId, $brandId, 
				$recordStatus,$lev2SalePrice,$salePrice2,$salePrice3,$unit2Id,$unit3Id,$unit2Decimal,$unit3Decimal,$guaranteeDay,$locality);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 税率
		if ($taxRate == - 1) {
			$sql = "update t_goods set tax_rate = null where id = '%s' ";
			$rc = $db->execute($sql, $id);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		} else {
			$taxRate = intval($taxRate);
			if ($taxRate > 17) {
				$taxRate = 17;
			}
			if ($taxRate < 0) {
				$taxRate = 0;
			}
			$sql = "update t_goods set tax_rate = %d where id = '%s' ";
			$rc = $db->execute($sql, $taxRate, $id);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		$params["id"] = $id;
		
		// 操作成功
		return null;
	}

	/**
	 * 编辑商品
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function updateGoods(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		$code = $params["code"];
		$name = $params["name"];
		$spec = $params["spec"];
		$categoryId = $params["categoryId"];
		$unitId = $params["unitId"];
		$salePrice = $params["salePrice"];
		$purchasePrice = $params["purchasePrice"];
		$barCode = $params["barCode"];
		$memo = $params["memo"];
		$brandId = $params["brandId"];
		$recordStatus = $params["recordStatus"];
		$taxRate = $params["taxRate"];
		$py = $params["py"];
		$specPY = $params["specPY"];
		$unit2Id = $params["unit2Id"];
		$unit3Id = $params["unit3Id"];
		$salePrice2 = $params["salePrice2"];
		$salePrice3 = $params["salePrice3"];
		$locality = $params["locality"];
		$guaranteeDay = $params["guaranteeDay"];
		$unit2Decimal = $params["unit2Decimal"];
		$unit3Decimal = $params["unit3Decimal"];
		$lev2SalePrice=$params["lev2SalePrice"];
		$goods = $this->getGoodsById($id);
		if (! $goods) {
			return $this->bad("要编辑的商品不存在");
		}
		
		$goodsUnitDAO = new GoodsUnitDAO($db);
		$unit = $goodsUnitDAO->getGoodsUnitById($unitId);
		if (! $unit) {
			return $this->bad("计量单位不存在");
		}
		
		$goodsCategoryDAO = new GoodsCategoryDAO($db);
		$category = $goodsCategoryDAO->getGoodsCategoryById($categoryId);
		if (! $category) {
			return $this->bad("商品分类不存在");
		}
		
		// 检查商品品牌
		if ($brandId) {
			$brandDAO = new GoodsBrandDAO($db);
			$brand = $brandDAO->getBrandById($brandId);
			if (! $brand) {
				return $this->bad("商品品牌不存在");
			}
		}
		
		// 编辑
		// 检查商品编码是否唯一
		$sql = "select count(*) as cnt from t_goods where code = '%s' and id <> '%s' ";
		$data = $db->query($sql, $code, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("编码为 [{$code}]的商品已经存在");
		}
		
		// 如果录入了条形码，则需要检查条形码是否唯一
		if ($barCode) {
			$sql = "select count(*) as cnt from t_goods where bar_code = '%s' and id <> '%s' ";
			$data = $db->query($sql, $barCode, $id);
			$cnt = $data[0]["cnt"];
			if ($cnt != 0) {
			//	return $this->bad("条形码[{$barCode}]已经被其他商品使用");
			}
		}
		
		$sql = "update t_goods
				set code = '%s', name = '%s', spec = '%s', category_id = '%s',
				    unit_id = '%s', sale_price = %f, py = '%s', purchase_price = %f,
					bar_code = '%s', memo = '%s', spec_py = '%s',
					brand_id = if('%s' = '', null, '%s'),
					record_status = %d,
					unit2_id='%s',
					unit3_id='%s',
					unit2_decimal=%f,
					unit3_decimal=%f,
					locality = '%s',
					guarantee_day = %f,
					sale_price2=%f,
					sale_price3=%f,
					lev2_sale_price=%f
				where id = '%s' ";
		
		$rc = $db->execute($sql, $code, $name, $spec, $categoryId, $unitId, $salePrice, $py, 
				$purchasePrice, $barCode, $memo, $specPY, $brandId, $brandId, $recordStatus,$unit2Id,$unit3Id,$unit2Decimal,$unit3Decimal,
				$locality,$guaranteeDay,$salePrice2,$salePrice3,$lev2SalePrice, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 税率
		if ($taxRate == - 1) {
			$sql = "update t_goods set tax_rate = null where id = '%s' ";
			$rc = $db->execute($sql, $id);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		} else {
			$taxRate = intval($taxRate);
			if ($taxRate > 17) {
				$taxRate = 17;
			}
			if ($taxRate < 0) {
				$taxRate = 0;
			}
			$sql = "update t_goods set tax_rate = %d where id = '%s' ";
			$rc = $db->execute($sql, $taxRate, $id);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 操作成功
		return null;
	}

	/**
	 * 通过商品id查询商品
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getGoodsById($id) {
		$db = $this->db;
		
		$sql = "select code, name, spec from t_goods where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			return array(
					"code" => $data[0]["code"],
					"name" => $data[0]["name"],
					"spec" => $data[0]["spec"]
			);
		} else {
			return null;
		}
	}

	/**
	 * 删除商品
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function deleteGoods(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$goods = $this->getGoodsById($id);
		if (! $goods) {
			return $this->bad("要删除的商品不存在");
		}
		$code = $goods["code"];
		$name = $goods["name"];
		$spec = $goods["spec"];
		
		// 判断商品是否能删除
		$sql = "select count(*) as cnt from t_po_bill_detail where goods_id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("商品[{$code} {$name}]已经在采购订单中使用了，不能删除");
		}
		
		$sql = "select count(*) as cnt from t_pw_bill_detail where goods_id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("商品[{$code} {$name}]已经在采购入库单中使用了，不能删除");
		}
		
		$sql = "select count(*) as cnt from t_ws_bill_detail where goods_id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("商品[{$code} {$name}]已经在销售出库单中使用了，不能删除");
		}
		
		$sql = "select count(*) as cnt from t_inventory_detail where goods_id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("商品[{$code} {$name}]在业务中已经使用了，不能删除");
		}
		
		$sql = "delete from t_goods where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$params["code"] = $code;
		$params["name"] = $name;
		$params["spec"] = $spec;
		
		// 操作成功
		return null;
	}

	/**
	 * 商品字段，查询数据
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function queryData($params) {
		$db = $this->db;
		
		$queryKey = $params["queryKey"];
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$bcDAO = new BizConfigDAO($db);
		$taxRate = $bcDAO->getTaxRate($companyId);
		
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$key = "%{$queryKey}%";
		
		$sql = "select g.id, g.code, g.name, g.spec, u.name as unit_name,
					g.category_id
				from t_goods g, t_goods_unit u
				where (g.unit_id = u.id) and (g.record_status = 1000)
				and (g.code like '%s' or g.name like '%s' or g.py like '%s'
					or g.spec like '%s' or g.spec_py like '%s') ";
		$queryParams = [];
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS_BILL, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by g.code
				limit 20";
		$data = $db->query($sql, $queryParams);
		$result = [];
		foreach ( $data as $v ) {
			$goodsId = $v["id"];
			
			// 查询商品的税率
			// 目前的设计和实现，存在数据量大的情况下会查询缓慢的可能，是需要改进的地方
			$sql = "select tax_rate from t_goods where id = '%s' and tax_rate is not null";
			$d = $db->query($sql, $goodsId);
			if ($d) {
				$taxRate = $d[0]["tax_rate"];
			} else {
				// 商品本身没有设置税率，就去查询该商品分类是否设置了默认税率
				$categoryId = $v["category_id"];
				$sql = "select tax_rate from t_goods_category where id = '%s' and tax_rate is not null";
				$d = $db->query($sql, $categoryId);
				if ($d) {
					$taxRate = $d[0]["tax_rate"];
				}
			}
			
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"spec" => $v["spec"],
					"unitName" => $v["unit_name"],
					"taxRate" => $taxRate
			];
		}
		
		return $result;
	}

	/**
	 * 商品字段，查询数据 - - 只显示有子商品的商品，用于加工业务中
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function queryDataForBOM($params) {
		$db = $this->db;
		
		$queryKey = $params["queryKey"];
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$key = "%{$queryKey}%";
		
		$sql = "select g.id, g.code, g.name, g.spec, u.name as unit_name
				from t_goods g, t_goods_unit u
				where (g.unit_id = u.id) and (g.record_status = 1000)
				and (g.code like '%s' or g.name like '%s' or g.py like '%s'
					or g.spec like '%s' or g.spec_py like '%s') 
				and g.id in (select goods_id as id from t_goods_bom) ";
		$queryParams = [];
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS_BILL, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by g.code
				limit 20";
		$data = $db->query($sql, $queryParams);
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"spec" => $v["spec"],
					"unitName" => $v["unit_name"]
			];
		}
		
		return $result;
	}

	private function getPsIdForCustomer($customerId) {
		$result = null;
		$db = $this->db;
		$sql = "select c.ps_id
				from t_customer_category c, t_customer u
				where c.id = u.category_id and u.id = '%s' ";
		$data = $db->query($sql, $customerId);
		if ($data) {
			$result = $data[0]["ps_id"];
		}
		
		return $result;
	}

	/**
	 * 商品字段，查询数据
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function queryDataWithSalePrice($params) {
		$db = $this->db;
		
		$queryKey = $params["queryKey"];
		$loginUserId = $params["loginUserId"];
		$companyId=$params["companyId"];

		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$customerId = $params["customerId"];
		$psId = $this->getPsIdForCustomer($customerId);
		
		$bcDAO = new BizConfigDAO($db);
		$taxRate = $bcDAO->getTaxRate($companyId);
		
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$key = "%{$queryKey}%";
		
		$sql = "select g.id, g.code, g.name, g.spec, u.name as unit_name, u2.name as unit2_name,g.unit2_decimal,u3.name as unit3_name, g.unit3_decimal,g.sale_price, 
		g.sale_price2,g.sale_price3,g.locality,g.guarantee_day,g.memo
		from t_goods g join  t_goods_unit  u on g.unit_id = u.id left join  t_goods_unit u2 on g.unit2_id = u2.id left join t_goods_unit u3 on g.unit3_id = u3.id
		where  (g.record_status = 1000)
				and (g.code like '%s' or g.name like '%s' or g.py like '%s'
					or g.spec like '%s' or g.spec_py like '%s') ";


			
		
		$queryParams = [];
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS_BILL, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by g.code
				limit 20";
		$data = $db->query($sql, $queryParams);
		$result = [];
		$resDate=[];//客户有订单记录，则记录在这里
		foreach ( $data as $v ) {
			$priceSystem = "";
			$goodsId = $v["id"];
			
			$price = $v["sale_price"];
			
			if ($psId) {
				// 取价格体系里面的价格
				$sql = "select g.price, p.name
						from t_goods_price g, t_price_system p
						where g.goods_id = '%s' and g.ps_id = '%s'
							and g.ps_id = p.id";
				$d = $db->query($sql, $goodsId, $psId);
				if ($d) {
					$priceSystem = $d[0]["name"];
					$price = $d[0]["price"];
				}
			}
			
			$taxRateType = 1;
			
			// 查询商品的税率
			// 目前的设计和实现，存在数据量大的情况下会查询缓慢的可能，是需要改进的地方
			//20201111  屏蔽税率
			// $sql = "select tax_rate from t_goods where id = '%s' and tax_rate is not null";
			// $d = $db->query($sql, $goodsId);
			// if ($d) {
			// 	$taxRateType = 3;
			// 	$taxRate = $d[0]["tax_rate"];
			// } else {
			// 	// 商品本身没有设置税率，就去查询该商品分类是否设置了默认税率
			// 	$categoryId = $v["category_id"];
			// 	$sql = "select tax_rate from t_goods_category where id = '%s' and tax_rate is not null";
			// 	$d = $db->query($sql, $categoryId);
			// 	if ($d) {
			// 		$taxRateType = 2;
			// 		$taxRate = $d[0]["tax_rate"];
			// 	}
			// }

			$balanceCount=0;//库存
			$sql = "select sum(balance_count) balance_count  from t_inventory where goods_id = '%s' ";
			$temp = $db->query($sql, $goodsId);
			if($temp){
				$balanceCount = $temp[0]["balance_count"]; 
			}
			$batchDateObj=[];
			$batchDate='';
			$sql = "select balance_count,batch_date from  t_inventory_batch  where balance_count>0 and  goods_id  = '%s' order by batch_date asc ";
			$tempBatch = $db->query($sql, $goodsId);
			if($tempBatch){
				$batchDateObj =$tempBatch; 
				$batchDate = $tempBatch[0]["batch_date"];
			}

			$lastPrice="-";//最后一次的价格
			if($customerId!="")
			{
				//查询当前客户可选商品的销售价格，找到最近的一次的销售价
				$dateStart=date('Y-m-d',strtotime('-365 day'))." 0:00:00";//查询起始日期，为一年前
				$dateEnd=date('Y-m-d')." 23:59:59";
				$sql = "select b.id, d.date_created,d.goods_price from t_so_bill b, t_so_bill_detail d where b.customer_id = '%s' and d.goods_id='%s' and b.id = d.sobill_id and d.date_created between '%s' and '%s' order by d.date_created   DESC";	
				$d = $db->query($sql, $customerId,$goodsId,$dateStart,$dateEnd);
				$dCount=sizeof($d);
				if($dCount>0){
					$lastPrice = $d[0]["goods_price"];
					$resDate[]=[
						"id" => $v["id"],
						"code" => $v["code"],
						"name" => $v["name"],
						"spec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"salePrice" => $price,
						"salePrice2" => $v["sale_price2"],
						"salePrice3" => $v["sale_price3"],
						"lastPrice" => $lastPrice,
						"priceSystem" => $priceSystem,
						"memo" => $v["memo"],
						"taxRate" => $taxRate,
						"taxRateType" => $taxRateType,
						"unit2Name" => $v["unit2_name"],
						"unit3Name" => $v["unit3_name"],
						"unit2Decimal" => $v["unit2_decimal"],
						"unit3Decimal" => $v["unit3_decimal"],
						"locality" => $v["locality"],
						"guaranteeDay" => $v["guarantee_day"],
						"balanceCount"=>$balanceCount,
						"batchDateObj"=>$batchDateObj,
						"batchDate" =>$batchDate,

					];
				}
				else {
					$result[] = [
						"id" => $v["id"],
						"code" => $v["code"],
						"name" => $v["name"],
						"spec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"salePrice" => $price,
						"salePrice2" => $v["sale_price2"],
						"salePrice3" => $v["sale_price3"],
						"lastPrice" => $lastPrice,
						"priceSystem" => $priceSystem,
						"memo" => $v["memo"],
						"taxRate" => $taxRate,
						"taxRateType" => $taxRateType,
						"unit2Name" => $v["unit2_name"],
						"unit3Name" => $v["unit3_name"],
						"unit2Decimal" => $v["unit2_decimal"],
						"unit3Decimal" => $v["unit3_decimal"],
						"locality" => $v["locality"],
						"guaranteeDay" => $v["guarantee_day"],
						"balanceCount"=>$balanceCount,
						"batchDateObj"=>$batchDateObj,
						"batchDate" =>$batchDate,
					];
				}
			}
			else {
				$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"spec" => $v["spec"],
					"unitName" => $v["unit_name"],
					"salePrice" => $price,
					"salePrice2" => $v["sale_price2"],
					"salePrice3" => $v["sale_price3"],
					"lastPrice" => $lastPrice,
					"priceSystem" => $priceSystem,
					"memo" => $v["memo"],
					"taxRate" => $taxRate,
					"taxRateType" => $taxRateType,
					"unit2Name" => $v["unit2_name"],
					"unit3Name" => $v["unit3_name"],
					"unit2Decimal" => $v["unit2_decimal"],
					"unit3Decimal" => $v["unit3_decimal"],
					"locality" => $v["locality"],
					"guaranteeDay" => $v["guarantee_day"],
					"balanceCount"=>$balanceCount,
					"batchDateObj"=>$batchDateObj,
					"batchDate" =>$batchDate,
				];
			}
		}
		//按生产商品订单时间进行排序，将最近生成订单的商品显示到最前面
		$ctime_str = array();
		foreach ($resDate as $key => $v) {
			$resDate[$key]['ctime_str'] = strtotime($v['creatDate']);
			$ctime_str[] = $resDate[$key]['ctime_str'];
		}
		array_multisort($ctime_str, SORT_DESC, $resDate);
		

		return \array_merge($resDate, $result);
	}

	/**
	 * 商品字段，查询数据
	 *
	 * @param array $params        	
	 * @return array
	 */
	private function queryDataWithPurchasePriceWithSupplier($params) {
		$db = $this->db;
		
		$supplierId = $params["supplierId"];
		
		$queryKey = $params["queryKey"];
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$bcDAO = new BizConfigDAO($db);
		$taxRate = $bcDAO->getTaxRate($companyId);
		
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$key = "%{$queryKey}%";
		
		$sql = "select g.id, g.code, g.name, g.spec, u.name as unit_name, g.purchase_price, g.memo,
					g.tax_rate
				from (
						select g.*
						from t_supplier_goods_range r, t_goods g
						where r.supplier_id = '%s' and r.g_id = g.id and r.g_id_type = 1
						union
						select g.*
						from t_supplier_goods_range r, t_goods_category c, t_goods g
						where r.supplier_id = '%s' and r.g_id = c.id and c.id = g.category_id
						) g, t_goods_unit u
				where (g.unit_id = u.id) and (g.record_status = 1000)
				and (g.code like '%s' or g.name like '%s' or g.py like '%s'
					or g.spec like '%s' or g.spec_py like '%s') ";
		
		$queryParams = [];
		$queryParams[] = $supplierId;
		$queryParams[] = $supplierId;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS_BILL, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by g.code
				limit 20";
		$data = $db->query($sql, $queryParams);
		$result = [];
		foreach ( $data as $v ) {
			$goodsId = $v["id"];
			$taxRateType = 1;
			
			// 查询商品的税率
			// 目前的设计和实现，存在数据量大的情况下会查询缓慢的可能，是需要改进的地方
			$sql = "select tax_rate from t_goods where id = '%s' and tax_rate is not null";
			$d = $db->query($sql, $goodsId);
			if ($d) {
				$taxRateType = 3;
				$taxRate = $d[0]["tax_rate"];
			} else {
				// 商品本身没有设置税率，就去查询该商品分类是否设置了默认税率
				$categoryId = $v["category_id"];
				$sql = "select tax_rate from t_goods_category where id = '%s' and tax_rate is not null";
				$d = $db->query($sql, $categoryId);
				if ($d) {
					$taxRateType = 2;
					$taxRate = $d[0]["tax_rate"];
				}
			}
			
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"spec" => $v["spec"],
					"unitName" => $v["unit_name"],
					"purchasePrice" => $v["purchase_price"] == 0 ? null : $v["purchase_price"],
					"memo" => $v["memo"],
					"taxRate" => $taxRate,
					"taxRateType" => $taxRateType
			];
		}
		
		return $result;
	}

	/**
	 * 商品字段，查询数据
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function queryDataWithPurchasePrice($params) {
		$db = $this->db;
		
		$supplierId = $params["supplierId"];
		if ($supplierId) {
			$sql = "select goods_range from t_supplier where id = '%s' ";
			$data = $db->query($sql, $supplierId);
			if ($data) {
				$goodsRange = $data[0]["goods_range"];
				if ($goodsRange == 2) {
					// 该供应商启用了关联商品
					return $this->queryDataWithPurchasePriceWithSupplier($params);
				}
			}
		}
		
		$queryKey = $params["queryKey"];
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		$bcDAO = new BizConfigDAO($db);
		$taxRate = $bcDAO->getTaxRate($companyId);
		
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$key = "%{$queryKey}%";
		
		// $sql = "select g.id, g.code, g.name, g.spec, u.name as unit_name, g.purchase_price, g.memo,
		// 			g.tax_rate
		// 		from t_goods g, t_goods_unit u
		// 		where (g.unit_id = u.id) and (g.record_status = 1000)
		// 		and (g.code like '%s' or g.name like '%s' or g.py like '%s'
		// 			or g.spec like '%s' or g.spec_py like '%s') ";


				$sql ="select g.id,g.purchase_price, 	g.tax_rate,g.code, g.name, g.spec, u.name as unit_name, u2.name as unit2_name,g.unit2_decimal,u3.name as unit3_name, g.unit3_decimal,g.sale_price, g.memo
				from t_goods g join  t_goods_unit  u on g.unit_id = u.id left join  t_goods_unit u2 on g.unit2_id = u2.id left join t_goods_unit u3 on g.unit3_id = u3.id where (g.record_status = 1000)
				and (g.code like '%s' or g.name like '%s' or g.py like '%s'
					or g.spec like '%s' or g.spec_py like '%s') ";
		
		$queryParams = [];
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS_BILL, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by g.code
				limit 20";
		$data = $db->query($sql, $queryParams);
		$result = [];
		$resDate=[];//保存有商品采购日期的数据
		foreach ( $data as $v ) {
			$goodsId = $v["id"];
			$taxRateType = 1;
			
			// 查询商品的税率
			// 目前的设计和实现，存在数据量大的情况下会查询缓慢的可能，是需要改进的地方
			$sql = "select tax_rate from t_goods where id = '%s' and tax_rate is not null";
			$d = $db->query($sql, $goodsId);
			if ($d) {
				$taxRateType = 3;
				$taxRate = $d[0]["tax_rate"];
			} else {
				// 商品本身没有设置税率，就去查询该商品分类是否设置了默认税率
				// $categoryId = $v["category_id"];
				// $sql = "select tax_rate from t_goods_category where id = '%s' and tax_rate is not null";
				// $d = $db->query($sql, $categoryId);
				// if ($d) {
				// 	$taxRateType = 2;
				// 	$taxRate = $d[0]["tax_rate"];
				// }
			}
			$createDateTemp=null;
			if($supplierId!="")
			{
				//查询供应商提供此商品的历史记录
				$dateStart=date('Y-m-d',strtotime('-365 day'))." 0:00:00";//查询起始日期，为一年前
				$dateEnd=date('Y-m-d')." 23:59:59";

				$sql="select b.id, d.date_created,d.goods_price from t_po_bill b,t_po_bill_detail d where b.supplier_id='%s' and b.id=d.pobill_id and d.goods_id='%s' and d.date_created between '%s' and '%s' order by d.date_created DESC";
				$dTemp=$db->query($sql,$supplierId,$goodsId,$dateStart,$dateEnd);
				if(!$dTemp){
					$sql="select b.id, d.date_created,d.goods_price from t_pw_bill b,t_pw_bill_detail d where b.supplier_id='%s' and b.id=d.pwbill_id and d.goods_id='%s' and d.date_created between '%s' and '%s' order by d.date_created DESC";
					$dTemp=$db->query($sql,$supplierId,$goodsId,$dateStart,$dateEnd);
				}
				if($dTemp){
					$createDateTemp=$dTemp[0]["date_created"];
					$resDate[]=[
						"id" => $v["id"],
						"code" => $v["code"],
						"name" => $v["name"],
						"spec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"purchasePrice" => $v["purchase_price"] == 0 ? null : $v["purchase_price"],
						"memo" => $v["memo"],
						"lastPrice" => $dTemp[0]["goods_price"],
						"taxRate" => $taxRate,
						"creatDate"=>$createDateTemp,
						"taxRateType" => $taxRateType,
						"unit2Name" => $v["unit2_name"],
						"unit3Name" => $v["unit3_name"],
						"unit2Decimal" => $v["unit2_decimal"],
						"unit3Decimal" => $v["unit3_decimal"],
					];
				}
				else{
					$result[] = [
						"id" => $v["id"],
						"code" => $v["code"],
						"name" => $v["name"],
						"spec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"purchasePrice" => $v["purchase_price"] == 0 ? null : $v["purchase_price"],
						"memo" => $v["memo"],
						"taxRate" => $taxRate,
						"creatDate"=>$createDateTemp,
						"taxRateType" => $taxRateType,
						"lastPrice" => '-',
					"unit2Name" => $v["unit2_name"],
					"unit3Name" => $v["unit3_name"],
					"unit2Decimal" => $v["unit2_decimal"],
					"unit3Decimal" => $v["unit3_decimal"]
					];
				}
				
			}
			else
			{
				$result[] = [
						"id" => $v["id"],
						"code" => $v["code"],
						"name" => $v["name"],
						"spec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"purchasePrice" => $v["purchase_price"] == 0 ? null : $v["purchase_price"],
						"memo" => $v["memo"],
						"taxRate" => $taxRate,
						"creatDate"=>$createDateTemp,
						"taxRateType" => $taxRateType,
						"unit2Name" => $v["unit2_name"],
						"unit3Name" => $v["unit3_name"],
						"lastPrice" => '-',
						"unit2Decimal" => $v["unit2_decimal"],
						"unit3Decimal" => $v["unit3_decimal"]
				];
			}
		}
		//按订单采购录入时间进行排序，将最近生成订单的商品显示到最前面
		$ctime_str = array();
		foreach ($resDate as $key => $v) {
			$resDate[$key]['ctime_str'] = strtotime($v['creatDate']);
			$ctime_str[] = $resDate[$key]['ctime_str'];
		}
		array_multisort($ctime_str, SORT_DESC, $resDate);

		
		return \array_merge($resDate, $result);
	}

	/**
	 * 获得某个商品的详情
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getGoodsInfo($params) {
		$db = $this->db;
		
		$id = $params["id"];
		$categoryId = $params["categoryId"];
		
		$sql = "select category_id, code, name, spec, unit_id, sale_price, purchase_price,
					bar_code, memo, brand_id, record_status, tax_rate, lev2_sale_price,sale_price2,sale_price3,unit2_id,unit3_id,unit2_decimal,unit3_decimal,locality,guarantee_day
				from t_goods
				where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			$result = array();
			$categoryId = $data[0]["category_id"];
			$result["categoryId"] = $categoryId;
			$result["lev2SalePrice"] = $data[0]["lev2_sale_price"];
			$result["code"] = $data[0]["code"];
			$result["name"] = $data[0]["name"];
			$result["spec"] = $data[0]["spec"];
			$result["unitId"] = $data[0]["unit_id"];
			$result["salePrice"] = $data[0]["sale_price"];
			$result["salePrice2"] = $data[0]["sale_price2"];
			$result["salePrice3"] = $data[0]["sale_price3"];

			$result["unit2Id"] = $data[0]["unit2_id"];
			$result["unit3Id"] = $data[0]["unit3_id"];
			$result["unit2Decimal"] = $data[0]["unit2_decimal"];
			$result["unit3Decimal"] = $data[0]["unit3_decimal"];
			$result["locality"] = $data[0]["locality"];
			$result["guaranteeDay"] = $data[0]["guarantee_day"];


			$brandId = $data[0]["brand_id"];
			$result["brandId"] = $brandId;
			
			$v = $data[0]["purchase_price"];
			if ($v == 0) {
				$result["purchasePrice"] = null;
			} else {
				$result["purchasePrice"] = $v;
			}
			
			$result["barCode"] = $data[0]["bar_code"];
			$result["memo"] = $data[0]["memo"];
			$result["recordStatus"] = $data[0]["record_status"];
			$result["taxRate"] = $data[0]["tax_rate"];
			
			$sql = "select full_name from t_goods_category where id = '%s' ";
			$data = $db->query($sql, $categoryId);
			if ($data) {
				$result["categoryName"] = $data[0]["full_name"];
			}
			
			if ($brandId) {
				$sql = "select full_name from t_goods_brand where id = '%s' ";
				$data = $db->query($sql, $brandId);
				$result["brandFullName"] = $data[0]["full_name"];
			}
			
			return $result;
		} else {
			$result = array();
			
			$sql = "select full_name from t_goods_category where id = '%s' ";
			$data = $db->query($sql, $categoryId);
			if ($data) {
				$result["categoryId"] = $categoryId;
				$result["categoryName"] = $data[0]["full_name"];
			}
			return $result;
		}
	}

	/**
	 * 通过条形码查询商品信息, 销售出库单使用
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function queryGoodsInfoByBarcode($params) {
		$db = $this->db;
		
		$barcode = $params["barcode"];
		$companyId = $params["companyId"];
		
		$result = [];
		
		$sql = "select g.id, g.code, g.name, g.spec, g.sale_price, u.name as unit_name,
					g.category_id
				from t_goods g, t_goods_unit u
				where g.bar_code = '%s' and g.unit_id = u.id ";
		$data = $db->query($sql, $barcode);
		
		if (! $data) {
			$result["success"] = false;
			$result["msg"] = "条码为[{$barcode}]的商品不存在";
		} else {
			$result["success"] = true;
			$result["id"] = $data[0]["id"];
			$result["code"] = $data[0]["code"];
			$result["name"] = $data[0]["name"];
			$result["spec"] = $data[0]["spec"];
			$result["salePrice"] = $data[0]["sale_price"];
			$result["unitName"] = $data[0]["unit_name"];
			
			// 税率
			$bcDAO = new BizConfigDAO($db);
			$taxRate = $bcDAO->getTaxRate($companyId);
			
			$goodsId = $data[0]["id"];
			$categoryId = $data[0]["cateogry_id"];
			
			$sql = "select tax_rate from t_goods where id = '%s' and tax_rate is not null";
			$d = $db->query($sql, $goodsId);
			if ($d) {
				$taxRate = $d[0]["tax_rate"];
			} else {
				// 商品本身没有设置税率，就去查询该商品分类是否设置了默认税率
				$sql = "select tax_rate from t_goods_category where id = '%s' and tax_rate is not null";
				$d = $db->query($sql, $categoryId);
				if ($d) {
					$taxRate = $d[0]["tax_rate"];
				}
			}
			$result["taxRate"] = $taxRate;
		}
		
		return $result;
	}

	/**
	 * 通过条形码查询商品信息, 采购入库单使用
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function queryGoodsInfoByBarcodeForPW($params) {
		$db = $this->db;
		
		$barcode = $params["barcode"];
		$companyId = $params["companyId"];
		
		$result = [];
		
		$sql = "select g.id, g.code, g.name, g.spec, g.purchase_price, u.name as unit_name,
					g.category_id
				from t_goods g, t_goods_unit u
				where g.bar_code = '%s' and g.unit_id = u.id ";
		$data = $db->query($sql, $barcode);
		
		if (! $data) {
			$result["success"] = false;
			$result["msg"] = "条码为[{$barcode}]的商品不存在";
		} else {
			$result["success"] = true;
			$result["id"] = $data[0]["id"];
			$result["code"] = $data[0]["code"];
			$result["name"] = $data[0]["name"];
			$result["spec"] = $data[0]["spec"];
			$result["purchasePrice"] = $data[0]["purchase_price"];
			$result["unitName"] = $data[0]["unit_name"];
			
			// 税率
			$bcDAO = new BizConfigDAO($db);
			$taxRate = $bcDAO->getTaxRate($companyId);
			
			$goodsId = $data[0]["id"];
			$categoryId = $data[0]["cateogry_id"];
			
			$sql = "select tax_rate from t_goods where id = '%s' and tax_rate is not null";
			$d = $db->query($sql, $goodsId);
			if ($d) {
				$taxRate = $d[0]["tax_rate"];
			} else {
				// 商品本身没有设置税率，就去查询该商品分类是否设置了默认税率
				$sql = "select tax_rate from t_goods_category where id = '%s' and tax_rate is not null";
				$d = $db->query($sql, $categoryId);
				if ($d) {
					$taxRate = $d[0]["tax_rate"];
				}
			}
			$result["taxRate"] = $taxRate;
		}
		
		return $result;
	}

	/**
	 * 查询商品种类总数
	 *
	 * @param array $params        	
	 * @return int
	 */
	public function getTotalGoodsCount($params) {
		$db = $this->db;
		
		$code = $params["code"];
		$name = $params["name"];
		$spec = $params["spec"];
		$barCode = $params["barCode"];
		
		$loginUserId = $params["loginUserId"];
		
		$sql = "select count(*) as cnt
					from t_goods c
					where (1 = 1) ";
		$queryParam = array();
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS, "c", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		if ($code) {
			$sql .= " and (c.code like '%s') ";
			$queryParam[] = "%{$code}%";
		}
		if ($name) {
			$sql .= " and (c.name like '%s' or c.py like '%s') ";
			$queryParam[] = "%{$name}%";
			$queryParam[] = "%{$name}%";
		}
		if ($spec) {
			$sql .= " and (c.spec like '%s')";
			$queryParam[] = "%{$spec}%";
		}
		if ($barCode) {
			$sql .= " and (c.bar_code = '%s') ";
			$queryParam[] = $barCode;
		}
		$data = $db->query($sql, $queryParam);
		
		return array(
				"cnt" => $data[0]["cnt"]
		);
	}

	/**
	 * 子商品字段，查询数据
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function queryDataForSubGoods($params) {
		$db = $this->db;
		
		$parentGoodsId = $params["parentGoodsId"];
		if (! $parentGoodsId) {
			return $this->emptyResult();
		}
		
		$queryKey = $params["queryKey"];
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$key = "%{$queryKey}%";
		
		$sql = "select g.id, g.code, g.name, g.spec, u.name as unit_name
				from t_goods g, t_goods_unit u
				where (g.unit_id = u.id)
				and (g.code like '%s' or g.name like '%s' or g.py like '%s'
					or g.spec like '%s' or g.spec_py like '%s') 
				and (g.id <> '%s')";
		$queryParams = [];
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $parentGoodsId;
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::GOODS, "g", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by g.code
				limit 20";
		$data = $db->query($sql, $queryParams);
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"spec" => $v["spec"],
					"unitName" => $v["unit_name"]
			];
		}
		
		return $result;
	}

	/**
	 * 新增商品时获取最新的商品编码
	 */
	public function getGoodsCode($params){
		$db = $this->db;
		$loginUserId = $params["loginUserId"];
		$corpId=$params["corpId"];
		$org=$params["org"];
		$codeSqlTemp="P".$org."%";
		$sql="select code from t_goods where company_id='%s' and code like '%s' order by code DESC LIMIT 1";
		$data = $db->query($sql, $corpId,$codeSqlTemp);
		if($data){
			return $data[0]["code"];
		}
		return "P".$org."0000";
	}
}