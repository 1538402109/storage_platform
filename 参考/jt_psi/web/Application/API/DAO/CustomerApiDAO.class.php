<?php

namespace API\DAO;

use Home\DAO\PSIBaseExDAO;
use Home\DAO\DataOrgDAO;
use Home\Common\FIdConst;
use Home\DAO\CustomerDAO;
use Home\DAO\WarehouseDAO;

/**
 * 客户API DAO
 *
 * @author JIATU
 */
class CustomerApiDAO extends PSIBaseExDAO {

	/**
	 * 客户字段，查询数据
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function queryData($params) {
		$db = $this->db;
		
		$loginUserId = $params["userId"];
		
		$queryKey = $params["queryKey"];
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$sql = "select id, code, name, mobile01, tel01, fax, address_receipt, contact01,
					sales_warehouse_id
				from t_customer
				where (record_status = 1000) 
					and (code like '%s' or name like '%s' or py like '%s'
							or mobile01 like '%s' or mobile02 like '%s' ) ";
		$queryParams = [];
		$key = "%{$queryKey}%";
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::CUSTOMER_BILL, "t_customer", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by code limit 20";
		
		$data = $db->query($sql, $queryParams);
		
		$result = [];
		
		$warehouseDAO = new WarehouseDAO($db);
		
		foreach ( $data as $v ) {
			$warehouseId = $v["sales_warehouse_id"];
			$warehouseName = null;
			if ($warehouseId) {
				$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
				if ($warehouse) {
					$warehouseName = $warehouse["name"];
				}
			}
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					// "mobile01" => $v["mobile01"],
					// "tel01" => $v["tel01"],
					// "fax" => $v["fax"],
					// "address_receipt" => $v["address_receipt"],
					// "contact01" => $v["contact01"],
					// "warehouseId" => $warehouseId,
					// "warehouseName" => $warehouseName
			];
		}
		
		return $result;
	}

	public function customerAllList($params) {
		$db = $this->db;
		
		// $start = $params["start"];
		// if (! $start) {
		// 	$start = "0";
		// }
		// $limit = $params["limit"];
		// if (! $limit) {
		// 	$limit = 10;
		// }
		$loginUserId = $params["userId"];
		
		//$categoryId = $params["categoryId"];
		// $code = $params["code"];
		// $name = $params["name"];
		// $address = $params["address"];
		// $contact = $params["contact"];
		// $mobile = $params["mobile"];
		// $tel = $params["tel"];
		// $qq = $params["qq"];
		
		$result = [];
		$queryParam = [];
		
		$sql = "select c.id, c.code, c.name, c.category_id ,c.mobile01, c.tel01, c.fax, c.address_receipt, c.contact01
				from t_customer c
				where (1=1)";
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::CUSTOMER, "c", $loginUserId);
		// if ($rs) {
		// 	$sql .= " and " . $rs[0];
		// 	$queryParam = array_merge($queryParam, $rs[1]);
		// }
		// if ($categoryId != "-1") {
		// 	$sql .= " and (c.category_id = '%s') ";
		// 	$queryParam[] = $categoryId;
		// }
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		// if ($categoryId != "-1") {
		// 	$sql .= " and (c.category_id = '%s') ";
		// 	$queryParam[] = $categoryId;
		// }
		
		$sql .= "order by c.code";
		// $queryParam[] = $start;
		// $queryParam[] = $limit;
		
		$data = $db->query($sql, $queryParam);
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"tel01" => $v["tel01"],
					"fax" => $v["fax"],
					"category_id" => $v["category_id"],
					"address_receipt" => $v["address_receipt"],
					"contact01" => $v["contact01"],
			];
		}
		
		$sql = "select count(*) as cnt
				from t_customer c
				where (1 = 1) ";
		$queryParam = [];
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::CUSTOMER, "c", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		// if ($categoryId != "-1") {
		// 	$sql .= " and (c.category_id = '%s') ";
		// 	$queryParam[] = $categoryId;
		// }
		$data = $db->query($sql, $queryParam);
		$cnt =  intval($data[0]["cnt"]);
		// $totalPage = ceil($cnt / $limit);
		return [
				"dataList" => $result,
				// "totalPage" =>  $totalPage,
				"totalCount" => $cnt
		];
	}

	public function customerList($params) {
		$db = $this->db;
		
		$start = $params["start"];
		if (! $start) {
			$start = "0";
		}
		$limit = $params["limit"];
		if (! $limit) {
			$limit = 10;
		}
		$loginUserId = $params["userId"];
		
		$categoryId = $params["categoryId"];
		// $code = $params["code"];
		// $name = $params["name"];
		// $address = $params["address"];
		// $contact = $params["contact"];
		// $mobile = $params["mobile"];
		// $tel = $params["tel"];
		// $qq = $params["qq"];
		
		$result = [];
		$queryParam = [];
		
		$sql = "select c.id, c.code, c.name, c.mobile01, c.tel01, c.fax, c.address_receipt, c.contact01
				from t_customer c
				where (1=1)";
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::CUSTOMER, "c", $loginUserId);
		// if ($rs) {
		// 	$sql .= " and " . $rs[0];
		// 	$queryParam = array_merge($queryParam, $rs[1]);
		// }
		// if ($categoryId != "-1") {
		// 	$sql .= " and (c.category_id = '%s') ";
		// 	$queryParam[] = $categoryId;
		// }
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		if ($categoryId != "-1") {
			$sql .= " and (c.category_id = '%s') ";
			$queryParam[] = $categoryId;
		}
		// if ($code) {
		// 	$sql .= " and (c.code like '%s' ) ";
		// 	$queryParam[] = "%{$code}%";
		// }
		// if ($name) {
		// 	$sql .= " and (c.name like '%s' or c.py like '%s' ) ";
		// 	$queryParam[] = "%{$name}%";
		// 	$queryParam[] = "%{$name}%";
		// }
		// if ($address) {
		// 	$sql .= " and (c.address like '%s' or c.address_receipt like '%s') ";
		// 	$queryParam[] = "%$address%";
		// 	$queryParam[] = "%{$address}%";
		// }
		// if ($contact) {
		// 	$sql .= " and (c.contact01 like '%s' or c.contact02 like '%s' ) ";
		// 	$queryParam[] = "%{$contact}%";
		// 	$queryParam[] = "%{$contact}%";
		// }
		// if ($mobile) {
		// 	$sql .= " and (c.mobile01 like '%s' or c.mobile02 like '%s' ) ";
		// 	$queryParam[] = "%{$mobile}%";
		// 	$queryParam[] = "%{$mobile}";
		// }
		// if ($tel) {
		// 	$sql .= " and (c.tel01 like '%s' or c.tel02 like '%s' ) ";
		// 	$queryParam[] = "%{$tel}%";
		// 	$queryParam[] = "%{$tel}";
		// }
		// if ($qq) {
		// 	$sql .= " and (c.qq01 like '%s' or c.qq02 like '%s' ) ";
		// 	$queryParam[] = "%{$qq}%";
		// 	$queryParam[] = "%{$qq}";
		// }
		
		$sql .= "order by c.code
				limit %d, %d";
		$queryParam[] = $start;
		$queryParam[] = $limit;
		
		$data = $db->query($sql, $queryParam);
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"tel01" => $v["tel01"],
					"fax" => $v["fax"],
					"address_receipt" => $v["address_receipt"],
					"contact01" => $v["contact01"],
			];
		}
		
		$sql = "select count(*) as cnt
				from t_customer c
				where (1 = 1) ";
		$queryParam = [];
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::CUSTOMER, "c", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		if ($categoryId != "-1") {
			$sql .= " and (c.category_id = '%s') ";
			$queryParam[] = $categoryId;
		}
		// if ($code) {
		// 	$sql .= " and (c.code like '%s' ) ";
		// 	$queryParam[] = "%{$code}%";
		// }
		// if ($name) {
		// 	$sql .= " and (c.name like '%s' or c.py like '%s' ) ";
		// 	$queryParam[] = "%{$name}%";
		// 	$queryParam[] = "%{$name}%";
		// }
		// if ($address) {
		// 	$sql .= " and (c.address like '%s' or c.address_receipt like '%s') ";
		// 	$queryParam[] = "%$address%";
		// 	$queryParam[] = "%{$address}%";
		// }
		// if ($contact) {
		// 	$sql .= " and (c.contact01 like '%s' or c.contact02 like '%s' ) ";
		// 	$queryParam[] = "%{$contact}%";
		// 	$queryParam[] = "%{$contact}%";
		// }
		// if ($mobile) {
		// 	$sql .= " and (c.mobile01 like '%s' or c.mobile02 like '%s' ) ";
		// 	$queryParam[] = "%{$mobile}%";
		// 	$queryParam[] = "%{$mobile}";
		// }
		// if ($tel) {
		// 	$sql .= " and (c.tel01 like '%s' or c.tel02 like '%s' ) ";
		// 	$queryParam[] = "%{$tel}%";
		// 	$queryParam[] = "%{$tel}";
		// }
		// if ($qq) {
		// 	$sql .= " and (c.qq01 like '%s' or c.qq02 like '%s' ) ";
		// 	$queryParam[] = "%{$qq}%";
		// 	$queryParam[] = "%{$qq}";
		// }
		
		$data = $db->query($sql, $queryParam);
		$cnt =  intval($data[0]["cnt"]);
		
		$totalPage = ceil($cnt / $limit);
		
		return [
				"dataList" => $result,
				"totalPage" =>  $totalPage,
				"totalCount" => $cnt
		];
	}

	
	/**
	 * 通过客户id查询客户资料
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getCustomerById($id) {
		$db = $this->db;
		
		$sql = "select code, name from t_customer where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			return [
					"code" => $data[0]["code"],
					"name" => $data[0]["name"]
			];
		} else {
			return null;
		}
	}
	/**
	 * 通过客户手机号查询客户资料
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getCustomerByMobile($mobile) {
		$db = $this->db;
		
		$sql = "select id,code,mobile01,address_receipt, contact01,name from t_customer where mobile01 = '%s' ";
		$data = $db->query($sql, $mobile);
		if ($data) {
			return [
				
					"id" => $data[0]["id"],
					"code" => $data[0]["code"],
					"name" =>  $data[0]["name"],
					"tel" =>  $data[0]["mobile01"],
					"address_receipt" =>  $data[0]["address_receipt"],
					"contact01" =>  $data[0]["contact01"],
			];
		} else {
			return null;
		}
	}

	public function categoryListWithAllCategory($params) {
		$db = $this->db;
		
		$result = [];
		
		$result[] = [
				"id" => "-1",
				"name" => "所有客户"
		];
		
		$loginUserId = $params["userId"];
		$ds = new DataOrgDAO($db);
		$queryParam = [];
		$sql = "select c.id, c.code, c.name
				from t_customer_category c ";
		$rs = $ds->buildSQL(FIdConst::CUSTOMER_CATEGORY, "c", $loginUserId);
		if ($rs) {
			$sql .= " where " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		
		$sql .= " order by c.code";
		
		$data = $db->query($sql, $queryParam);
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"]
			];
		}
		
		return $result;
	}

	public function categoryList($params) {
		$db = $this->db;
		
		$result = [];
		
		$loginUserId = $params["userId"];
		
		$ds = new DataOrgDAO($db);
		$queryParam = [];
		$sql = "select c.id, c.code, c.name, c.ps_id
				from t_customer_category c ";
		$rs = $ds->buildSQL(FIdConst::CUSTOMER_CATEGORY, "c", $loginUserId);
		if ($rs) {
			$sql .= " where " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		
		$sql .= " order by c.code";
		
		$data = $db->query($sql, $queryParam);
		foreach ( $data as $v ) {
			$psId = $v["ps_id"];
			$psName = '';
			if ($psId) {
				$sql = "select name from t_price_system where id = '%s' ";
				$d = $db->query($sql, $psId);
				$psName = $d[0]["name"];
			}
			
			$queryParam = [];
			$sql = "select count(*) as cnt from t_customer c
					where (category_id = '%s') ";
			$queryParam[] = $v["id"];
			$rs = $ds->buildSQL(FIdConst::CUSTOMER, "c", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParam = array_merge($queryParam, $rs[1]);
			}
			$d = $db->query($sql, $queryParam);
			$cnt = $d[0]["cnt"];
			
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"psName" => $psName,
					"cnt" => $cnt
			];
		}
		
		return $result;
	}

	public function addCustomerCategory(& $params) {
		$dao = new CustomerDAO($this->db);
		return $dao->addCustomerCategory($params);
	}

	public function updateCustomerCategory(& $params) {
		$dao = new CustomerDAO($this->db);
		return $dao->updateCustomerCategory($params);
	}

	public function priceSystemList($params) {
		$db = $this->db;
		
		$sql = "select id, name
				from t_price_system
				order by name";
		$data = $db->query($sql);
		
		$result = [
				[
						"id" => "-1",
						"name" => "[无]"
				]
		];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"name" => $v["name"]
			];
		}
		
		return $result;
	}

	public function categoryInfo($params) {
		$db = $this->db;
		
		$id = $params["categoryId"];
		$loginUserId = $params["loginUserId"];
		
		$result = [];
		
		$sql = "select id, code, name, ps_id from t_customer_category where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			$v = $data[0];
			
			$result["id"] = $v["id"];
			$result["code"] = $v["code"];
			$result["name"] = $v["name"];
			$psId = $v["ps_id"];
			$result["psId"] = $psId;
			$result["psName"] = "[无]";
			if ($psId) {
				$sql = "select name from t_price_system where id = '%s' ";
				$d = $db->query($sql, $psId);
				$result["psName"] = $d[0]["name"];
			}
			
			// 统计该分类下的客户数，不用考虑数据域，因为是用来判断是否可以删除该分类用的，需要考虑所有的数据
			$sql = "select count(*) as cnt from t_customer where category_id = '%s' ";
			$d = $db->query($sql, $v["id"]);
			$cnt = $d[0]["cnt"];
			$result["canDelete"] = $cnt == 0;
		}
		
		return $result;
	}

	public function deleteCategory(& $params) {
		$db = $this->db;
		
		$dao = new CustomerDAO($db);
		
		return $dao->deleteCustomerCategory($params);
	}

	public function customerInfo($params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$result = [];
		
		$sql = "select c.category_id, c.code, c.name, c.contact01, c.qq01, c.mobile01, c.tel01,
					c.contact02, c.qq02, c.mobile02, c.tel02, c.address,c.fax, c.address_receipt,
					c.init_receivables, c.init_receivables_dt,
					c.bank_name, c.bank_account, c.tax_number, c.fax, c.note, c.sales_warehouse_id,	c.record_status
				from t_customer c
				where (c.id = '%s')";
		$data = $db->query($sql, $id);
		if ($data) {
			$result["id"] = $id;
			$result["categoryId"] = $data[0]["category_id"];
			// $result["categoryName"] = $data[0]["category_name"];
			$result["code"] = $data[0]["code"];
			$result["name"] = $data[0]["name"];
			$result["contact01"] = $data[0]["contact01"];
			$result["qq01"] = $data[0]["qq01"];
			$result["mobile01"] = $data[0]["mobile01"];
			$result["tel01"] = $data[0]["tel01"];
			$result["contact02"] = $data[0]["contact02"];
			$result["qq02"] = $data[0]["qq02"];
			$result["mobile02"] = $data[0]["mobile02"];
			$result["tel02"] = $data[0]["tel02"];
			$result["address"] = $data[0]["address"];
			$result["addressReceipt"] = $data[0]["address_receipt"];
			$result["initReceivables"] = $data[0]["init_receivables"];
			$d = $data[0]["init_receivables_dt"];
			if ($d) {
				$result["initReceivablesDT"] = $this->toYMD($d);
			}

			$result["initPayables"] = $data[0]["init_payables"];
			$d = $data[0]["init_payables_dt"];
			if ($d) {
				$result["initPayablesDT"] = $this->toYMD($d);
			}

			$result["bankName"] = $data[0]["bank_name"];
			$result["bankAccount"] = $data[0]["bank_account"];
			$result["tax"] = $data[0]["tax_number"];
			$result["fax"] = $data[0]["fax"];
			$result["note"] = $data[0]["note"];
			$result["recordStatus"] =  intval($data[0]["record_status"]);
			
			$result["warehouseId"] = "";
			$result["warehouseName"] = "";
			$warehouseId = $data[0]["sales_warehouse_id"];
			if ($warehouseId) {
				$warehouseDAO = new WarehouseDAO($db);
				$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
				if ($warehouse) {
					$result["warehouseId"] = $warehouseId;
					$result["warehouseName"] = $warehouse["name"];
				}
			}
		}
		
		return $result;
	}

	public function getCustomerCategoryById($id) {
		$dao = new CustomerDAO($this->db);
		return $dao->getCustomerCategoryById($id);
	}

		/**
	 * 新建客户时自动获取客户编号
	 */
	public function getCustomerCode(){
		$db = $this->db;
		$codeSqlTemp="KH%";
		$sql="select code from t_customer where code like '%s'  order by code DESC LIMIT 1";
		$data = $db->query($sql, $codeSqlTemp);
		if($data){
			return $data[0]["code"];
		}
		return "KH00000";
	}


	public function addCustomer(& $params) {
		$db = $this->db;
		
		$code = $params["code"];
		$name = $params["name"];
		$address = $params["address"];
		$addressReceipt = $params["addressReceipt"];
		$contact01 = $params["contact01"];
		$mobile01 = $params["mobile01"];
		$tel01 = $params["tel01"];
		$qq01 = $params["qq01"];
		$contact02 = $params["contact02"];
		$mobile02 = $params["mobile02"];
		$tel02 = $params["tel02"];
		$qq02 = $params["qq02"];
		$bankName = $params["bankName"];
		$bankAccount = $params["bankAccount"];
		$tax = $params["tax"];
		$fax = $params["fax"];
		$note = $params["note"];

		$initReceivables = $params["initReceivables"];
		$initReceivablesDT = $params["initReceivablesDT"];
		$initPayables = $params["initPayables"];
		$initPayablesDT = $params["initPayablesDT"];

		if (! $this->dateIsValid($initReceivablesDT)) {
			return $this->bad("交货日期不正确");
		}
		$initReceivables = intval($initReceivables);
		if (! $this->dateIsValid($initPayablesDT)) {
			return $this->bad("交货日期不正确");
		}	
		$initPayables = intval($initPayables);
		
		
		// 销售出库仓库
		$warehouseId = $params["warehouseId"];
		$warehouseDAO = new WarehouseDAO($db);
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			// 没有选择销售出库仓库
			$warehouseId = "";
		}
		
		$py = $params["py"];
		$categoryId = $params["categoryId"];
		
		$dataOrg = $params["dataOrg"];
		$companyId = $params["companyId"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$recordStatus = $params["recordStatus"];
		
		//根据客户名称 或者手机号 校验数据是否已经存在
		$sql = "select count(*) as cnt from t_customer where name = '%s' ";
		$data = $db->query($sql, $name);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			$sql = "select id,code,name,contact01,mobile01 from t_customer where name = '%s' ";
		    $data = $db->query($sql, $name);
			$result = [];
			if($data){
				$result["id"] = $id;
				$result["code"] = $data[0]["code"];
				$result["name"] = $data[0]["name"];
				$result["contact01"] = $data[0]["contact01"];
				$result["mobile01"] = $data[0]["mobile01"];
			}
			return array(
				"success" => false,
				"error" => false,
				"msg" => "客户名称为 [{$name}] 的客户信息已经存在，您可以直接使用!",
				"data"=> $result
			);
		}
		//或者手机号 校验数据是否已经存在
		$sql = "select count(*) as cnt from t_customer where mobile01 = '%s' ";
		$data = $db->query($sql, $mobile01);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			$sql = "select id,code,name,contact01,mobile01 from t_customer where mobile01 = '%s' ";
		    $data = $db->query($sql, $mobile01);
			$result = [];
			if($data){
				$result["id"] = $id;
				$result["code"] = $data[0]["code"];
				$result["name"] = $data[0]["name"];
				$result["contact01"] = $data[0]["contact01"];
				$result["mobile01"] = $data[0]["mobile01"];
			}
			return array(
				"success" => false,
				"error" => false,
				"msg" => "客户名称为 [{$mobile01}] 的客户已经存在",
				"data"=> $result
			);
		}	

		// 检查编码是否已经存在 
		//TODO 客户编码自动生成
		$sql = "select count(*) as cnt from t_customer where code = '%s' ";
		$data = $db->query($sql, $code);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("编码为 [{$code}] 的客户已经存在");
		}
		
		$id = $this->newId();
		$params["id"] = $id;

		$sql = "insert into t_customer (id, category_id, code, name, py, contact01,
					qq01, tel01, mobile01, contact02, qq02, tel02, mobile02, address, address_receipt,
					bank_name, bank_account, tax_number, fax, note, data_org, company_id, sales_warehouse_id,
					record_status,init_receivables , init_receivables_dt, init_payables, init_payables_dt)
				values ('%s', '%s', '%s', '%s', '%s', '%s',
						'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
						'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
						%d,%d,'%s',%d,'%s')  ";
		$rc = $db->execute($sql, $id, $categoryId, $code, $name, $py, $contact01, $qq01, $tel01, 
				$mobile01, $contact02, $qq02, $tel02, $mobile02, $address, $addressReceipt, 
				$bankName, $bankAccount, $tax, $fax, $note, $dataOrg, $companyId, $warehouseId, 
				$recordStatus,$initReceivables,$initReceivablesDT,$initPayables,$initPayablesDT);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		// 操作成功
		return null;
	}

	public function updateCustomer(& $params) {
		$dao = new CustomerDAO($this->db);
		return $dao->updateCustomer($params);
	}

	public function initReceivables(& $params) {
		$dao = new CustomerDAO($this->db);
		return $dao->initReceivables($params);
	}

	public function warehouseList($params) {
		$db = $this->db;
		
		$loginUserId = $params["loginUserId"];
		$ds = new DataOrgDAO($db);
		$sql = "select w.id,w.name from t_warehouse w ";
	
		$queryParam = [];
		$rs = $ds->buildSQL(FIdConst::WAREHOUSE, "w", $loginUserId);
		if ($rs) {
			$sql .= " where " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		
		$sql .= " order by w.code ";
		$data = $db->query($sql, $queryParam);
		
		// $result = [
		// 		[
		// 				"id" => "",
		// 				"name" => "[无]"
		// 		]
		// ];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"name" => $v["name"]
			];
		}
		
		return $result;
	}
	
	public function deleteCustomer(& $params){
		$dao = new CustomerDAO($this->db);
		return $dao->deleteCustomer($params);
	}
}