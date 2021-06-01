<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Base;
use Illuminate\Support\Facades\DB;
class Supplier extends Base
{
    use HasFactory;
	private $LOG_CATEGORY = "基础数据-供应商档案";

	/**
	 * 供应商字段， 查询数据
	 */
	public function queryData($queryKey) {
		
		$params = array(
				"queryKey" => $queryKey,
				"loginUserId" => $this->getLoginUserId()
		);
		$queryKey = $params["queryKey"];
		$loginUserId = $params["loginUserId"];
		
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		if ($queryKey == null) {
			$queryKey = "";
		}
		
		$sql = "select id, code, name, tel01, fax, address_shipping, contact01, tax_rate
				from t_supplier
				where (record_status = 1000)
					and (code like ? or name like ? or py like ?) ";
		$queryParams = array();
		$key = "%{$queryKey}%";
		$queryParams[] = $key;
		$queryParams[] = $key;
		$queryParams[] = $key;
		
		$rs = $this->buildSQL("1004-01", "t_supplier", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = array_merge($queryParams, $rs[1]);
		}
		
		$sql .= " order by code
				limit 20";
		$data = DB::select($sql, $queryParams);
		$data = json_decode(json_encode($data),true);
		$result = [];
		
		foreach ( $data as $v ) {
			$taxRate = $v["tax_rate"];
			if ($taxRate == - 1) {
				$taxRate = null;
			}
			
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"tel01" => $v["tel01"],
					"fax" => $v["fax"],
					"address_shipping" => $v["address_shipping"],
					"contact01" => $v["contact01"],
					"taxRate" => $taxRate
			];
		}
		
		return $result;
	}

	/**
	 * 通过供应商id查询供应商
	 *
	 * @param string $id
	 *        	供应商id
	 * @return array|NULL
	 */
	public function getSupplierById($id) {
		$sql = "select code, name from t_supplier where id = ? ";
		$data = DB::select($sql, [$id]);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			return array(
					"code" => $data[0]["code"],
					"name" => $data[0]["name"]
			);
		} else {
			return null;
		}
	}

	/**
	 * 检查某个商品是否在该供应商关联商品内
	 *
	 * @param string $supplierId        	
	 * @param string $goodsId        	
	 * @return bool true: 存在
	 */
	public function goodsIdIsInGoodsRange($supplierId, $goodsId) {
		$sql = "select goods_range from t_supplier where id = ? ";
		$data = DB::select($sql, [$supplierId]);
		if (! $data) {
			// 供应商不存在
			return false;
		}
		$data = json_decode(json_encode($data),true);
		$goodsRange = $data[0]["goods_range"];
		if ($goodsRange == 1) {
			// 全部商品
			return true;
		}
		
		// 商品分类
		$sql = "select count(*) as cnt
				from t_supplier_goods_range r, t_goods g, t_goods_category c
				where r.supplier_id = ? and r.g_id = c.id 
					and c.id  = g.category_id and g.id = ? ";
		$data = DB::select($sql, [$supplierId, $goodsId]);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return true;
		}
		
		// 个别商品
		$sql = "select count(*) as cnt 
				from t_supplier_goods_range r, t_goods g
				where r.supplier_id = ? and r.g_id = g.id and g.id = ? ";
		$data = $db->query($sql, [$supplierId, $goodsId]);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return true;
		}
		
		return false;
	}
	/**
	 * 供应商分类列表
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
		
		$sql = "select c.id, c.code, c.name
				from t_supplier_category c ";
		$queryParam = [];
		$rs = $this->buildSQL("1004-02", "c", $loginUserId);
		if ($rs) {
			$sql .= " where " . $rs[0];
			$queryParam = array_merge($queryParam, $rs[1]);
		}
		$sql .= " order by c.code";
		
		$data = DB::select($sql, $queryParam);
		$data = json_decode(json_encode($data),true);
		$result = [];
		foreach ( $data as $v ) {
			$id = $v["id"];
			
			$queryParam = [];
			$sql = "select count(s.id) as cnt
					from t_supplier s
					where (s.category_id = ?) ";
			$queryParam[] = $id;
			if ($code) {
				$sql .= " and (s.code like ?) ";
				$queryParam[] = "%{$code}%";
			}
			if ($name) {
				$sql .= " and (s.name like ? or s.py like ? ) ";
				$queryParam[] = "%{$name}%";
				$queryParam[] = "%{$name}%";
			}
			if ($address) {
				$sql .= " and (s.address like ? or s.address_shipping like ?) ";
				$queryParam[] = "%{$address}%";
				$queryParam[] = "%{$address}%";
			}
			if ($contact) {
				$sql .= " and (s.contact01 like ? or s.contact02 like ? ) ";
				$queryParam[] = "%{$contact}%";
				$queryParam[] = "%{$contact}%";
			}
			if ($mobile) {
				$sql .= " and (s.mobile01 like ? or s.mobile02 like ? ) ";
				$queryParam[] = "%{$mobile}%";
				$queryParam[] = "%{$mobile}";
			}
			if ($tel) {
				$sql .= " and (s.tel01 like ? or s.tel02 like ? ) ";
				$queryParam[] = "%{$tel}%";
				$queryParam[] = "%{$tel}";
			}
			if ($qq) {
				$sql .= " and (s.qq01 like ? or s.qq02 like ? ) ";
				$queryParam[] = "%{$qq}%";
				$queryParam[] = "%{$qq}";
			}
			$rs = $this->buildSQL("1004", "s", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParam = array_merge($queryParam, $rs[1]);
			}
			
			$d = DB::select($sql, $queryParam);
			$d = json_decode(json_encode($d),true);
			$supplierCount = $d[0]["cnt"];
			
			if ($inQuery && $supplierCount == 0) {
				// 当前是查询，而且当前分类下没有符合查询条件的供应商，就不返回该供应商分类
				continue;
			}
			
			$result[] = [
					"id" => $id,
					"code" => $v["code"],
					"name" => $v["name"],
					"cnt" => $supplierCount
			];
		}
		
		return $result;
	}
}