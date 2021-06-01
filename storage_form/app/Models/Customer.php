<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Base;
use App\Models\Warehouses;
use Illuminate\Support\Facades\DB;
class Customer extends Base
{
	use HasFactory;

	private $LOG_CATEGORY = "客户关系-客户资料";
	/**
	 * 客户字段，查询数据
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function queryData($params) {
		// if ($this->tokenIsInvalid($tokenId)) {
		// 	return $this->emptyResult();
		// }
		// $params["userId"] = $this->getUserId();
		$res = $this->ok();
		
		$loginUserId = $this->getLoginUserId();
		
		$queryKey = $params["queryKey"];
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$sql = "select id, code, name, mobile01, tel01, fax, address_receipt, contact01,
					sales_warehouse_id
				from t_customer
				where (record_status = 1000) 
					and (code like ? or name like ? or py like ?
							or mobile01 like ? or mobile02 like ? ) ";
		$queryParams = [];
		$key = "%{$queryKey}%";
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		
		$rs = $this->buildSQL('1007-01', "t_customer", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by code limit 20";
		
		$data = DB::select($sql, $queryParams);
		$data = json_decode(json_encode($data),true);
		$result = [];
		
		$Warehouses = new Warehouses();
		
		foreach ( $data as $v ) {
			$warehouseId = $v["sales_warehouse_id"];
			$warehouseName = null;
			if ($warehouseId) {
				$warehouse = $Warehouses->getWarehouseById($warehouseId);
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

	/**
	 * 获得客户的销售出库仓库
	 *
	 * @param string $id
	 *        	客户id
	 * @return array 仓库, 如果没有设置销售出库仓库则返回null
	 */
	public function getSalesWarehouse(string $id) {
		
		$sql = "select sales_warehouse_id from t_customer where id = ? ";
		$data = DB::select($sql, [$id]);
		if (! $data) {
			return null;
		}
		$data = json_decode(json_encode($data),true);
		$warehouseId = $data[0]["sales_warehouse_id"];
		
		$sql = "select id, name from t_warehouse where id = ? ";
		$data = DB::select($sql, [$warehouseId]);
		if (! $data) {
			return null;
		} else {
			$data = json_decode(json_encode($data),true);
			return [
					"id" => $data[0]["id"],
					"name" => $data[0]["name"]
			];
		}
	}
	/**
	 * 客户分类列表
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function categoryList($params) {
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$code = $params["code"];
		$name = $params["name"];
		$address = $params["address"];
		$contact = $params["contact"];
		$mobile = $params["mobile"];
		$tel = $params["tel"];
		$qq = $params["qq"];
		
		$inQuery = false;
		if ($code || $name || $address || $contact || $mobile || $tel || $qq) {
			$inQuery = true;
		}
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$queryParam = [];
		
		$sql = "select c.id, c.code, c.name, c.ps_id
				from t_customer_category c ";
		$rs = $this->buildSQL("1007-02", "c", $loginUserId);
		if ($rs) {
			$sql .= " where " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		$sql .= " order by c.code ";
		
		$data = DB::select($sql, $queryParam);
		$data = json_decode(json_encode($data),true);
		$result = [];
		foreach ( $data as $v ) {
			// 分类中的客户数量
			$id = $v["id"];
			$queryParam = [];
			$sql = "select count(u.id) as cnt
					from t_customer u 
					where (u.category_id = ?) ";
			$queryParam[] = $id;
			if ($code) {
				$sql .= " and (u.code like ?) ";
				$queryParam[] = "%{$code}%";
			}
			if ($name) {
				$sql .= " and (u.name like ? or u.py like ? ) ";
				$queryParam[] = "%{$name}%";
				$queryParam[] = "%{$name}%";
			}
			if ($address) {
				$sql .= " and (u.address like ? or u.address_receipt like ?) ";
				$queryParam[] = "%{$address}%";
				$queryParam[] = "%{$address}%";
			}
			if ($contact) {
				$sql .= " and (u.contact01 like ? or u.contact02 like ? ) ";
				$queryParam[] = "%{$contact}%";
				$queryParam[] = "%{$contact}%";
			}
			if ($mobile) {
				$sql .= " and (u.mobile01 like ? or u.mobile02 like ? ) ";
				$queryParam[] = "%{$mobile}%";
				$queryParam[] = "%{$mobile}";
			}
			if ($tel) {
				$sql .= " and (u.tel01 like ? or u.tel02 like ? ) ";
				$queryParam[] = "%{$tel}%";
				$queryParam[] = "%{$tel}";
			}
			if ($qq) {
				$sql .= " and (u.qq01 like ? or u.qq02 like ? ) ";
				$queryParam[] = "%{$qq}%";
				$queryParam[] = "%{$qq}";
			}
			$rs = $this->buildSQL("1007", "u", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParam = array_merge($queryParam, $rs[1]);
			}
			$d = DB::select($sql, $queryParam);
			$d = json_decode(json_encode($d),true);
			$customerCount = $d[0]["cnt"];
			
			if ($inQuery && $customerCount == 0) {
				// 当前是带查询条件 而且该分类下没有符合的客户资料，则不返回该分类
			//	continue;
			}
			
			// 价格体系
			$psId = $v["ps_id"];
			$priceSystem = null;
			if ($psId) {
				$sql = "select name from t_price_system where id = ? ";
				$d = DB::select($sql, [$psId]);
				$d = json_decode(json_encode($d),true);
				if ($d) {
					$priceSystem = $d[0]["name"];
				}
			}
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"cnt" => $customerCount,
					"priceSystem" => $priceSystem
			];
		}
		return $result;
	}
}