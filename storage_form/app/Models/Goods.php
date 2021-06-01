<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class Goods extends Base
{
	use HasFactory;

	public function queryDataWithSalePrice($params) {
		
		$queryKey = $params["queryKey"];
		$loginUserId = $this->getLoginUserId();
	
		//查询客户id
		$customerId = $params["customerId"];
		$psId = $this->getPsIdForCustomer($customerId);
	
		//获得增值税率
		$taxRate = $this->getTaxRate(User::getCompanyId());
	
		//查询并判断用户是否绑定了商品
		/*$bindSql = "select goods_id from t_goods_binding where personnel_id = '".$loginUserId."' ";
		$bindData = DB::select($bindSql);
		if(empty($bindData)){*/
			//未绑定商品,则查询所有商品
			$sql="select g.id, g.code, g.name, g.spec, g.category_id,u.name as unit_name, u2.name as unit2_name,u3.name as unit3_name, g.sale_price,g.memo,g.sale_price as sale_price2,g.sale_price as sale_price3 from t_goods g join  t_goods_unit  u on g.unit_id = u.id left join  t_goods_unit u2 on g.unit_id = u2.id left join t_goods_unit u3 on g.unit_id = u3.id where  (g.record_status = 1000)";

			$queryParams = [];

			$rs = $this->buildSQL("1001-01", "g", $loginUserId);
			if ($rs) {
				$sql .= " and " . $rs[0];
				$queryParams = array_merge($queryParams, $rs[1]);
			}
			
			$sql .= " order by g.code";
					// $sql .= " order by g.code
					// limit 20";
			$data = DB::select($sql, $queryParams);
			//$data = $db->query($sql);
			$result = [];
			$data = json_decode(json_encode($data),true);
			foreach ( $data as $v ) {
				$priceSystem = "";
				$goodsId = $v["id"];
				
				$price = $v["sale_price"];
				
				if ($psId) {
					// 取价格体系里面的价格
					$sql = "select g.price, p.name
							from t_goods_price g, t_price_system p
							where g.goods_id = '".$goodsId."' and g.ps_id = '".$psId."'
								and g.ps_id = p.id";
					$d = DB::select($sql);
					if ($d) {
						$d = json_decode(json_encode($d),true);
						$priceSystem = $d[0]["name"];
						$price = $d[0]["price"];
					}
				}
				
				$taxRateType = 1;
				
				// 查询商品的税率
				// 目前的设计和实现，存在数据量大的情况下会查询缓慢的可能，是需要改进的地方
				$sql = "select tax_rate from t_goods where id = '".$goodsId."' and tax_rate is not null";
				$d = DB::select($sql);
				if ($d) {
					$d = json_decode(json_encode($d),true);
					$taxRateType = 3;
					$taxRate = $d[0]["tax_rate"];
				} else {
					// 商品本身没有设置税率，就去查询该商品分类是否设置了默认税率
					$categoryId = $v["category_id"];
					$sql = "select tax_rate from t_goods_category where id = '".$categoryId."' and tax_rate is not null";
					$d = DB::select($sql);
					if ($d) {
						$d = json_decode(json_encode($d),true);
						$taxRateType = 2;
						$taxRate = $d[0]["tax_rate"];
					}
				}
				$lastPrice="-";//最后一次的价格
				
				$result[] = [
						"id" => $v["id"],
						"code" => $v["code"],
						"name" => $v["name"],
						"spec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"salePrice" => (float)$price,
						"salePrice2" =>$v["sale_price2"],
						"salePrice3" => $v["sale_price3"],
						/*"locality" => $v["locality"],
						"guaranteeDay" => $v["guarantee_day"],*/
						"lastPrice" => $lastPrice,
						"priceSystem" => $priceSystem,
						"memo" =>'',
						"taxRate" => $taxRate,
						"taxRateType" => $taxRateType,
						"unit2Name" => $v["unit2_name"],
						"unit3Name" => $v["unit3_name"],
						/*"unit2Decimal" => \intval($v["unit2_decimal"]),
						"unit3Decimal" => \intval($v["unit3_decimal"]),*/
						"categoryId"=>$v["category_id"],
				];
			}
		/*}else{
			//查询绑定商品
			foreach($bindData as $k=>$val){
				$bindGoodsSql="select g.id, g.code, g.name, g.spec, g.category_id,u.name as unit_name, u2.name as unit2_name,g.unit2_decimal,u3.name as unit3_name, g.unit3_decimal,g.sale_price,g.memo,g.sale_price2,g.sale_price3,g.locality,g.guarantee_day 
				from t_goods g join  t_goods_unit  u on g.unit_id = u.id left join  t_goods_unit u2 on g.unit2_id = u2.id left join t_goods_unit u3 on g.unit3_id = u3.id
				where (g.id = ? ) and (g.record_status = 1000)";

				$queryParams = [];
				$queryParams[] = $val['goods_id'];
				$rs = $this->buildSQL("1001-01", "g", $loginUserId);
				if ($rs) {
					$bindGoodsSql .= " and " . $rs[0];
					$queryParams = array_merge($queryParams, $rs[1]);
				}
				
				$bindGoodsSql .= " order by g.code";
				$goodsData[] = DB::select($bindGoodsSql,$queryParams);
			}
			//获取体系及税率
			$result = [];
			$goodsData = json_decode(json_encode($goodsData),true);
			foreach($goodsData as $k=>$vals){
				$priceSystem = "";
				$goodsId = $vals[0]["id"];
				
				$price = $vals[0]["sale_price"];
				
				if ($psId) {
					// 取价格体系里面的价格
					$sql = "select g.price, p.name
							from t_goods_price g, t_price_system p
							where g.goods_id = ? and g.ps_id = ?
								and g.ps_id = p.id";
					$d = DB::select($sql, [$goodsId, $psId]);
					if ($d) {
						$d = json_decode(json_encode($d),true);
						$priceSystem = $d[0]["name"];
						$price = $d[0]["price"];
					}
				}

				$taxRateType = 1;

				// 查询商品的税率
				// 目前的设计和实现，存在数据量大的情况下会查询缓慢的可能，是需要改进的地方
				$sql = "select tax_rate from t_goods where id = ? and tax_rate is not null";
				$d = DB::select($sql, [$goodsId]);
				if ($d) {
					$d = json_decode(json_encode($d),true);
					$taxRateType = 3;
					$taxRate = $d[0]["tax_rate"];
				} else {
					// 商品本身没有设置税率，就去查询该商品分类是否设置了默认税率
					$categoryId = $vals["category_id"];
					$sql = "select tax_rate from t_goods_category where id = ? and tax_rate is not null";
					$d = DB::select($sql, [$categoryId]);
					if ($d) {
						$taxRateType = 2;
						$taxRate = $d[0]["tax_rate"];
					}
				}
				$lastPrice="-";//最后一次的价格

				$result[] = [
					"id" => $vals[0]["id"],
					"code" => $vals[0]["code"],
					"name" => $vals[0]["name"],
					"spec" => $vals[0]["spec"],
					"unitName" => $vals[0]["unit_name"],
					"salePrice" => (float)$price,
					"salePrice2" =>$vals[0]["sale_price2"],
					"salePrice3" => $vals[0]["sale_price3"],
					"locality" => $vals[0]["locality"],
					"guaranteeDay" => $vals[0]["guarantee_day"],
					"lastPrice" => $lastPrice,
					"priceSystem" => $priceSystem,
					"memo" =>'',
					"taxRate" => $taxRate,
					"taxRateType" => $taxRateType,
					"unit2Name" => $vals[0]["unit2_name"],
					"unit3Name" => $vals[0]["unit3_name"],
					"unit2Decimal" => \intval($vals[0]["unit2_decimal"]),
					"unit3Decimal" => \intval($vals[0]["unit3_decimal"]),
					"categoryId"=>$vals[0]["category_id"],
				];
			}
		}*/
		return $result;
	}

	private function getPsIdForCustomer($customerId) {
		$sql = "select c.ps_id
				from t_customer_category c, t_customer u
				where c.id = u.category_id and u.id = '".$customerId."' ";
		$data = DB::select($sql);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			$result = $data[0]["ps_id"];
		}
		
		return $result;
	}

	/**
	 * 通过商品id查询商品
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getGoodsById($id) {
		$db = $this->db;
		
		$sql = "select code, name, spec from t_goods where id = ? ";
		$data = DB::select($sql, [$id]);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			return array(
					"code" => $data[0]["code"],
					"name" => $data[0]["name"],
					"spec" => $data[0]["spec"]
			);
		} else {
			return null;
		}
	}
}