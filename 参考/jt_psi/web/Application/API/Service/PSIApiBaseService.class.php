<?php

namespace API\Service;

use Home\Service\PSIBaseService;

/**
 * 用户Service
 *
 * @author JIATU
 */
//class PSIApiBaseService extends PSIBaseExService {
class PSIApiBaseService extends  PSIBaseService {

	// /**
	//  * 当前登录用户的id
	//  * 
	//  * @return string|NULL
	//  */
	// protected function getLoginUserId() {
	// 	$us = $this->us();
	// 	return $us->getLoginUserId();
	// }

	public function tokenIsInvalid(string $tokenId): bool {
		//$userId = session($tokenId);

        $cache = S(array('expire'=>60 * 60 * 24 * 30));
        $userId = $cache->get("tokenId_{$tokenId}");
		if (! $userId) {
			return true;
		}
		
		$db = $this->db();
		$sql = "select count(*) as cnt 
				from t_user
				where id = '%s' and enabled = 1 ";
		$data = $db->query($sql, $userId);
		$cnt = $data[0]["cnt"];
		
		return $cnt != 1;
	}

	protected function getLoginUserDataOrg($loginUserId) {
		$db = $this->db();
		$sql = "select data_org from t_user where id = '%s' ";
		$data = $db->query($sql, $loginUserId);
		if ($data) {
			return $data[0]["data_org"];
		} else {
			return null;
		}
	}

	public function getUserIdFromTokenId(string $tokenId): string {
		//return session($tokenId);
        //缓存有效期（时间为秒） 是否适用缓存
        $cache = S(array('expire'=> 60 * 60 * 24 * 30));
		$userId = $cache->get("tokenId_{$tokenId}");
		return $userId;
	}

	protected function getCompanyIdFromTokenId(string $userId): string {
		// $userId = $this->getUserIdFromTokenId($tokenId);
		$db = $this->db();
		
		$result = "";
		
		if (! $userId) {
			return $result;
		}
		
		// 获得当前登录用户所属公司的算法：
		// 从最底层的组织机构向上找，直到parent_id为null的那个组织机构就是所属公司
		
		$sql = "select org_id from t_user where id = '%s' ";
		$data = $db->query($sql, $userId);
		if (! $data) {
			return $result;
		}
		$orgId = $data[0]["org_id"];
		$found = false;
		while ( ! $found ) {
			$sql = "select id, parent_id from t_org where id = '%s' ";
			$data = $db->query($sql, $orgId);
			if (! $data) {
				return $result;
			}
			
			$orgId = $data[0]["parent_id"];
			
			$result = $data[0]["id"];
			$found = $orgId == null;
		}
		
		return $result;
	}

	protected function getDataOrgFromTokenId(string $userId): string {
		// $userId = $this->getUserIdFromTokenId($tokenId);
		
		$db = $this->db();
		
		$sql = "select data_org from t_user where id = '%s' ";
		$data = $db->query($sql, $userId);
		
		if ($data) {
			return $data[0]["data_org"];
		} else {
			return "";
		}
	}

	/**
	 * 数据库操作类
	 *
	 * @return \Think\Model
	 */
	protected function db() {
		return M();
	}
}