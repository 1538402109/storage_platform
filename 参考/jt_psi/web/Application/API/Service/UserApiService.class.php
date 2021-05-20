<?php

namespace API\Service;

use API\DAO\CustomerApiDAO;
use API\DAO\UserApiDAO;
use API\DAO\MainMenuApiDAO;

/**
 * 用户Service
 *
 * @author JIATU
 */
class UserApiService extends PSIApiBaseService {

	public function doLogin($params) {
		$dao = new UserApiDAO($this->db());
		$mainMenuApiDAO = new MainMenuApiDAO($this->db());
		$customerDAO = new CustomerApiDAO($this->db());
		$user = $dao->doLogin($params);
		$userId = $user["id"];
		if ($userId) {
			$result = $this->ok();
			
			$tokenId = session_id();
			session($tokenId, $userId);
			 
			$cache = S(array('expire'=> 60 * 60 * 24 * 30)); //缓存30天
			$cache->set("tokenId_{$tokenId}",$userId);  
			$token = $cache->get("tokenId_{$tokenId}");

			$user["tokenId"] = $tokenId;
		
			$result["data"] = $user;

			$menu = $mainMenuApiDAO->mainMenuItems($userId);
			
			if($user["tel"]){
				$cinfo = $customerDAO->getCustomerByMobile($user["tel"]);
				$result["customer"] = $cinfo;
			}
			// $fromDevice = $params["fromDevice"];
			// if (! $fromDevice) {
			// 	$fromDevice = "移动端";
			// }

			//$menu = $this->recentFid($userId);
			$result["menu"] = $menu;

		
			
			$service = new BizlogApiService();
			$log = "从{$fromDevice}登录系统";
			$service->insertBizlog($tokenId, $log);
			
			return $result;
		} else {
			return $this->bad("用户名或密码错误");
		}
	}

	public function doLogout($params) {
		$result = $this->ok();
		
		$tokenId = $params["tokenId"];
		if (! $tokenId) {
			return $result;
		}
		
		if ($this->tokenIsInvalid($tokenId)) {
			return $result;
		}
		
		$fromDevice = $params["fromDevice"];
		if (! $fromDevice) {
			$fromDevice = "移动端";
		}
		
		$service = new BizlogApiService();
		$log = "从{$fromDevice}退出系统";
		$service->insertBizlog($tokenId, $log);
		
		// 清除session
		session($tokenId, null);
		
		$cache = S(array('expire'=>  60 * 60 * 24 * 30));
		$name = "tokenId_{$tokenId}";
		unset($cache->$name); // 删除缓存
		
		return $result;
	}
	
	public function getUserInfo($params) {
		$result = $this->ok();
		$dao = new UserApiDAO($this->db());
		$result["data"] = $dao->userInfo($params);

		$menu = $this->recentFid($userId);
		$result["menu"] = $menu;
		
		return $result;
	}
	
	/**
	 * 当前登录用户带组织机构的用户全名
	 *
	 * @return string
	 */
	public function getLoignUserNameWithOrgFullName($userId) {
		$dao = new UserApiDAO($this->db());
		return $dao->getLoignUserNameWithOrgFullName($userId);
	}

	public function getDemoLoginInfo() {
		$result = $this->ok();
		
		if ($this->isDemo()) {
			$result["msg"] = "当前处于演示环境，请勿保存正式数据，默认的登录名和密码均为 admin";
		} else {
			$result["msg"] = "";
		}
		
		return $result;
	}
	
	/**
	 * 判断当前用户是否有某个功能的权限
	 *
	 * @param string $userId
	 *        	用户id
	 * @param string $fid
	 *        	功能id
	 * @return boolean true:有该功能的权限
	 */
	public function hasPermission($userId, $fid) {
		$result = $this->ok();
		$dao = new UserApiDAO($this->db());
		return $dao->hasPermission($userId, $fid);
	}
	
	public function recentFid($userId) {
		//
		// 这里的SQL里面之所以和 t_permission、t_role_permission有关联
		// 是为了处理：某个模块权限原来有，但是现在没有了，这样在常用功能里面就不应该出现该模块
		//
		// SQL的select部分有一个不需要返回给前端的 r.click_count，是因为在MySQL 5.7+因为SQL_MODE的原因
		// 不加上r.click_count就会出错。
		//
		$sql = " select distinct f.fid, f.name, r.click_count 
				from t_recent_fid r,  
					(select * from t_fid union select * from t_fid_plus) f, 
					(select * from t_permission union select * from t_permission_plus) p, 
					t_role_permission rp, t_role_user ru
				where r.fid = f.fid and r.user_id = '%s' and r.fid = p.fid 
				and p.id = rp.permission_id and rp.role_id = ru.role_id 
				and ru.user_id = '%s' 
				order by r.click_count desc
				limit 10";
		
		$data = M()->query($sql, $userId, $userId);
		
		$menu = [];
		foreach ( $data as $v ) {
			$menu[] = [
					"fid" => $v["fid"],
					"name" => $v["name"]
			];
		}
		return $menu;
	}



}