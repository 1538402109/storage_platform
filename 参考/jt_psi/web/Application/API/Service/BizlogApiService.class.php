<?php

namespace API\Service;

use API\DAO\BizlogApiDAO;

/**
 * 业务日志API Service
 *
 * @author JIATU
 */
class BizlogApiService extends PSIApiBaseService {

	private function getClientIP() {
		return get_client_ip();
	}

	/**
	 * 记录业务日志
	 *
	 * @param string $log
	 *        	日志内容
	 * @param string $category
	 *        	日志分类
	 */
	public function insertBizlog($userId, $log, $category = "系统") {
		// if ($this->tokenIsInvalid($tokenId)) {
		// 	return;
		// }
		
		$params = array(
				"loginUserId" => $userId,
				"log" => $log,
				"category" => $category,
				"ip" => $this->getClientIP(),
				"dataOrg" => $this->getDataOrgFromTokenId($userId),
				"companyId" => $this->getCompanyIdFromTokenId($userId)
		);
		
		$dao = new BizlogApiDAO($this->db());
		$result = $dao->insertBizlog($params);
		if (! $result) {
			$result = $this->ok();
		}
		
		return $result;
	}
}