<?php

namespace API\Service;

use API\DAO\GoodsApiDAO;

/**
 * 商品 API Ser~vice
 *
 * @author JIATU
 */
class GoodsApiService extends PSIApiBaseService {
   
	public function queryDataWithSalePrice($params) {
		// if ($this->isNotOnline()) {
		// 	return $this->emptyResult();
		// }
		
		// $params = array(
		// 		"queryKey" => $queryKey,
		// 		"customerId" => $customerId,
		// 		"loginUserId" => $this->getLoginUserId()
		// );
		
		$result = $this->ok();
		$dao = new GoodsApiDAO($this->db());
		$result["data"] = $dao->queryDataWithSalePrice($params);
		return $result;
	}


	public function queryGoodsSalePrice($params) {
		$result = $this->ok();
		$dao = new GoodsApiDAO($this->db());
		$result["data"] = $dao->queryGoodsSalePrice($params);
		return $result;
	}
	public function queryGoodsSalePriceByCustomerId($params) {
		$result = $this->ok();
		$dao = new GoodsApiDAO($this->db());
		$result["data"] = $dao->queryGoodsSalePriceByCustomerId($params);
		return $result;
	}
	public function allCategories($params) {
		$result = $this->ok();
		$dao = new GoodsApiDAO($this->db());
		$result["data"] = $dao->allCategories($params);
		return $result;
	}

}