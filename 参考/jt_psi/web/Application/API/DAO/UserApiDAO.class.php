<?php

namespace API\DAO;

use Home\DAO\PSIBaseExDAO;

/**
 * 用户 API DAO
 *
 * @author JIATU
 */
class UserApiDAO extends PSIBaseExDAO {

	public function doLogin($params) {
		$loginName = $params["loginName"];
		$password = $params["password"];
		
		$db = $this->db;
		
		$sql = "select id,org_id,tel from t_user where login_name = '%s' and password = '%s' and enabled = 1";
	
		$data = $db->query($sql, $loginName, md5($password));
		
		$result = [];

		if ($data) {
			$v = $data[0];
			$result["id"] = $v["id"];
			$result["org_id"] = $v["org_id"];
			$result["tel"] =  $v["tel"];
			return $result;
		} else {
			return null;
		}
	}

	public function userInfo($params) {
		$userId = $params["loginUserId"];
	
		$db = $this->db;
		
		$sql = "select name,org_code,tel from t_user where id = '%s' and enabled = 1";
		
		$data = $db->query($sql, $userId);
		$result = [];
		if ($data) {
			$v = $data[0];
			$result["name"] = $v["name"];
			$result["org_code"] =  $v["org_code"];
			$result["tel"] =  $v["tel"];
			return $result;
		} else {
			return null;
		}
	}

	/**
	 * 根据用户id查询用户
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getUserById($id) {
		$db = $this->db;
		$sql = "select login_name, name from t_user where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! $data) {
			return null;
		}
		return array(
				"loginName" => $data[0]["login_name"],
				"name" => $data[0]["name"]
		);
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
		$db = $this->db;
		$sql = "select count(*) as cnt
				from  t_role_user ru, t_role_permission rp, t_permission p
				where ru.user_id = '%s' and ru.role_id = rp.role_id
				      and rp.permission_id = p.id and p.fid = '%s' ";
		$data = $db->query($sql, $userId, $fid);
		
		return $data[0]["cnt"] > 0;
	}

	/**
	 * 根据用户id查询用户名称
	 *
	 * @param string $userId
	 *        	用户id
	 *        	
	 * @return string 用户姓名
	 */
	public function getLoginUserName($userId) {
		$db = $this->db;
		
		$sql = "select name from t_user where id = '%s' ";
		
		$data = $db->query($sql, $userId);
		
		if ($data) {
			return $data[0]["name"];
		} else {
			return "";
		}
	}

	/**
	 * 获得带组织机构的用户全名
	 *
	 * @param string $userId
	 *        	用户id
	 * @return string
	 */
	public function getLoignUserNameWithOrgFullName($userId) {
		$db = $this->db;
		$userName = $this->getLoginUserName($userId);
		if ($userName == "") {
			return $userName;
		}
		$sql = "select o.full_name
				from t_org o, t_user u
				where o.id = u.org_id and u.id = '%s' ";
		$data = $db->query($sql, $userId);
		$orgFullName = "";
		if ($data) {
			$orgFullName = $data[0]["full_name"];
		}
		
		return addslashes($orgFullName . "\\" . $userName);
	}
}