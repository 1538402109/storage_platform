<?php

namespace API\Service;

use API\DAO\InventoryApiDAO;

/**
 * 库存 Service
 *
 * @author JIATU
 */
class InventoryApiService extends PSIApiBaseService {

	public function warehouseList($userId) {
		$result = $this->ok();
		$dao = new InventoryApiDAO($this->db());
		$result["data"] = $dao->warehouseList($userId);
		return $result;
	}
	
	public function inventoryGoodsInfo($params)
	{
		$result = $this->ok();

		$dao = new InventoryApiDAO($this->db());
		$result["data"] = $dao->inventoryGoodsInfo($params);
		return $result;
	}
	public function inventoryList($params) {
	
		$result = $this->ok();
		$params["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);

		$dao = new InventoryApiDAO($this->db());
		$result["data"] = $dao->inventoryList($params);
		return $result;
	}
	public function inventoryListAll($params) {
	
		$result = $this->ok();
		$params["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);
		$dao = new InventoryApiDAO($this->db());
		$result["data"] = $dao->inventoryListAll($params);
		return $result;
	}

	public function inventoryDetailList($params) {
		$result = $this->ok();
		$params["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);

		$dao = new InventoryApiDAO($this->db());
		$result["data"] = $dao->inventoryDetailList($params);
		return $result;
	}
}