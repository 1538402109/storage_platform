<?php

namespace Home\DAO;

use Home\Common\FIdConst;

class PersonnelDAO extends PSIBaseExDAO {
	/**
	 * 获取人员列表
	 */
	public function personnels($params){
		$loginId = session('loginUserId');
		$loginName = $params['loginName'];
		$name = $params['name'];
		$limit = $params['limit'];
		$start = $params['start'];
		
		$db = $this->db;
		//查询org_id
		$sql = "select org_id from t_user where id='%s'";
		$res = $db->query($sql,$loginId);
		$sql = "select id, login_name,  name, enabled, org_code, gender, birthday, id_card_number, tel,
				    tel02, address, data_org
				from t_user
				where (org_id = '%s') ";

		//查询条件
		$where = [];
		$where[] = $res[0]['org_id'];
		
		
		if($loginName){
			$sql .= "and (login_name like '%s')";
			$where[] = "%$loginName%";
		}
		
		if($name){
			$sql .= "and (name like '%s')";
			$where[] = "%$name%";
		}
		
		$sql .= "limit %d,%d";
		$where[] = $start;
		$where[] = $limit;
		$data = $db->query($sql,$where);

		//查询用户权限
		$result = [];
		foreach($data as $k=>$v){
			$userId = $v["id"];
			$sql = "select r.name
					from t_role r, t_role_user u
					where r.id = u.role_id and u.user_id = '%s' ";
			$d = $db->query($sql, $userId);
			foreach($d as $key=>$val){
				$data[$k]['permission'] = $val['name'];
			}
		}

		$sql = "select count(*) as cnt from t_user where org_id = '%s'";
		$count = $db->query($sql,$res[0]['org_id']);
		return ['dataList' => $data,"totalCount"=>$count[0]['cnt']];
	}

	/**
	 * 检查数据是否正确
	 *
	 * @param array $params        	
	 * @return NULL|array 没有错误返回null
	 */
	private function checkParams($params) {
		$loginName = trim($params["loginName"]);
		$name = trim($params["name"]);
		$orgCode = trim($params["orgCode"]);
		$enabled = $params["enabled"];
		$gender = $params["gender"];
		$birthday = $params["birthday"];
		$idCardNumber = trim($params["idCardNumber"]);
		$tel = trim($params["tel"]);
		$tel02 = trim($params["tel02"]);
		$address = trim($params["address"]);
		
		if ($this->isEmptyStringAfterTrim($loginName)) {
			return $this->bad("登录名不能为空");
		}
		if ($this->isEmptyStringAfterTrim($name)) {
			return $this->bad("姓名不能为空");
		}
		if ($this->isEmptyStringAfterTrim($orgCode)) {
			return $this->bad("编码不能为空");
		}
		
		if ($this->stringBeyondLimit($loginName, 20)) {
			return $this->bad("登录名长度不能超过20位");
		}
		if ($this->stringBeyondLimit($name, 20)) {
			return $this->bad("姓名长度不能超过20位");
		}
		if ($this->stringBeyondLimit($idCardNumber, 50)) {
			return $this->bad("身份证号长度不能超过50位");
		}
		if ($this->stringBeyondLimit($tel, 50)) {
			return $this->bad("联系电话长度不能超过50位");
		}
		if ($this->stringBeyondLimit($tel02, 50)) {
			return $this->bad("备用电话长度不能超过50位");
		}
		if ($this->stringBeyondLimit($address, 100)) {
			return $this->bad("家庭住址长度不能超过100位");
		}
		
		return null;
	}

	/**
	 * 做类似这种增长 '01010001' => '01010002', 用户的数据域+1
	 */
	private function incDataOrgForUser($dataOrg) {
		$pre = substr($dataOrg, 0, strlen($dataOrg) - 4);
		$seed = intval(substr($dataOrg, - 4)) + 1;
		
		return $pre . str_pad($seed, 4, "0", STR_PAD_LEFT);
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
	 * 修改/新增人员
	 */
	public function addPersonnel(& $params){
		$db = $this->db;
		$id = $this->newId();
		$loginName = trim($params["loginName"]);
		$name = trim($params["name"]);
		$orgCode = trim($params["orgCode"]);
		$enabled = $params["enabled"];
		$gender = $params["gender"];
		$birthday = $params["birthday"];
		$idCardNumber = trim($params["idCardNumber"]);
		$tel = trim($params["tel"]);
		$tel02 = trim($params["tel02"]);
		$address = trim($params["address"]);
		$py = $params["py"];
		
		$result = $this->checkParams($params);
		if($result){
			return $result;
		}

		//登录名是否重复
		$sql = "select count(*) as count from t_user where login_name = '%s'";
		$check = $db->query($sql,$loginName);
		if($check[0]['count'] > 0){
			return $this->bad("登录名[$loginName]已存在");
		}

		//检查编码是否重复
		$sql = "select count(*) as count from t_user where login_name = '%s'";
		$check = $db->query($sql,$orgCode);
		if($check[0]['count'] >0){
			return $this->bad("编码[$orgCode]已存在");
		}

		//新增用户密码
		$password = md5('123456');

		//生成数据域
		$loginId = session("loginUserId");
		$sql = "select org_id from t_user where id = '%s'";
		$orgId = $db->query($sql,$loginId);
		$orgSql = "select data_org from t_user where org_id='%s' order by data_org desc limit 1";
		$data = $db->query($orgSql,$orgId[0]['org_id']);
		$dataOrg = $this->incDataOrgForUser($data[0]['data_org']);

		$insertSql = "insert into t_user (id, login_name, name, org_code, org_id, enabled, password, py,
					gender, birthday, id_card_number, tel, tel02, address, data_org)
					values ('%s', '%s', '%s', '%s', '%s', %d, '%s', '%s',
					'%s', '%s', '%s', '%s', '%s', '%s', '%s')";
		$res = $db->execute($insertSql, $id, $loginName, $name, $orgCode, $orgId[0]['org_id'], $enabled, $password, $py, 
		$gender, $birthday, $idCardNumber, $tel, $tel02, $address, $dataOrg);
		
		if ($res === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$params["id"] = $id;
		// 操作成功
		return null;
	}

	/**
	 * 获得人员具体信息
	 */
	public function userInfo($params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$sql = "select login_name, name, org_code, org_id,
					birthday, id_card_number, tel, tel02,
					address, gender, enabled ,data_org
				from t_user 
				where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! $data) {
			return $this->emptyResult();
		} else {
			$v = $data[0];
			
			$sql = "select full_name 
					from t_org
					where id = '%s' ";
			$data = $db->query($sql, $v["org_id"]);
			$orgFullName = $data[0]["full_name"];
			return [
					"loginName" => $v["login_name"],
					"name" => $v["name"],
					"orgCode" => $v["org_code"],
					"orgId" => $v["org_id"],
					"orgFullName" => $orgFullName,
					"birthday" => $v["birthday"],
					"idCardNumber" => $v["id_card_number"],
					"tel" => $v["tel"],
					"tel02" => $v["tel02"],
					"address" => $v["address"],
					"gender" => $v["gender"],
					"enabled" => $v["enabled"],
					'dataOrg' => $v['data_org']
			];
		}
	}

	/**
	 * 修改用户
	 */
	public function updateUser($params){
		$db = $this->db;

		$id = $params["id"];
		$enabled = $params["enabled"];
		$loginName = trim($params["loginName"]);
		$orgCode = trim($params["orgCode"]);
		$orgId = $params["orgId"];
		$gender = $params["gender"];
		$name = trim($params["name"]);
		$birthday = $params["birthday"];
		$idCardNumber = trim($params["idCardNumber"]);
		$tel = trim($params["tel"]);
		$tel02 = trim($params["tel02"]);
		$address = trim($params["address"]);
		$py = $params["py"];
		$dataOrg = $params["dataOrg"];
	
		$result = $this->checkParams($params);
		if ($result) {
			return $result;
		}

		$sql = "update t_user
					set login_name = '%s', name = '%s', org_code = '%s',
					    org_id = '%s', enabled = %d, py = '%s',
					    gender = '%s', birthday = '%s', id_card_number = '%s',
					    tel = '%s', tel02 = '%s', address = '%s',data_org = '%s'
					where id = '%s' ";
		$rc = $db->execute($sql, $loginName, $name, $orgCode, $orgId, $enabled, $py, $gender, 
				$birthday, $idCardNumber, $tel, $tel02, $address, $dataOrg,$id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}

		// 操作成功
		return null;

	}
	
	/**
	 * 修改密码
	 */
	public function changePassword($params){
		$db = $this->db;
		
		$id = $params["id"];
		
		$password = $params["password"];
		if (strlen($password) < 5) {
			return $this->bad("密码长度不能小于5位");
		}
		
		$sql = "update t_user
				set password = '%s'
				where id = '%s' ";
		$rc = $db->execute($sql, md5($password), $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}

	/**
	 * 获取人员绑定商品详细信息
	 */
	public function goodsBindingInfo($id){
		$db = $this->db;
		$sql = "select login_name from t_goods_binding where personnel_id = '%s' ";
		$data = $db->query($sql,$id);

		
		//判断人员是否绑定商品信息
		if(empty($data)){
			$sql = "select login_name from t_user where id = '%s' ";
			return $db->query($sql,$id);
		}else{
			//查与人员对应的商品信息
			$sql = "select goods_id,goods_name,goods_code from t_goods_binding where personnel_id = '%s' ";
			$goodsData = $db->query($sql,$id);
			$data['items'] = [];
			foreach ($goodsData as $key => $value) {
				$data['items'][$key]['goodsId'] = $value['goods_id'];
				$data['items'][$key]['goodsName'] = $value['goods_name'];
				$data['items'][$key]['goodsCode'] = $value['goods_code'];
			}
			return $data;

			/*拆分字符串的方法
			$sql = "select personnel_id,login_name,goods from t_goods_binding where personnel_id = '%s' ";
			$goodsData = $db->query($sql,$id);
			$goods = explode('||',$goodsData[0]['goods']);
			$data['items'] = [];
			foreach($goods as $k=>$v){
				$goodsRes[] = explode(',',$v);
			}
			foreach ($goodsRes as $key => $value) {
				$data['items'][$key]['goodsId'] = $value[0];
				$data['items'][$key]['goodsName'] = $value[1];
				$data['items'][$key]['goodsCode'] = $value[2];
			}
			return $data; */
		}	
	}

	/**
	 * 商品绑定
	 */
	public function goodsBinding(& $bill){
		$db = $this->db;
		if($bill['bizUserId'] == ''){
			return $this->bad("业务员不存在");
		}
		//使用id查
		$sql = "select login_name from t_goods_binding where login_name = '%s' ";
		$data = $db->query($sql,$bill['bizUserId']);
		if(!empty($data)){
			$sql = "delete from t_goods_binding where login_name = '%s'";
			$re = $db->query($sql,$bill['bizUserId']);
			if ($re === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}

		$insert_sql = "insert into t_goods_binding(personnel_id,login_name,goods_id,goods_name,goods_code) 
		values";

		$sql_insertvalues = [];
		$container = array();
		$result = array();
		foreach($bill['items'] as $k=>$v){
			$key = $v['goodsName'] . '_' . $v['goodsId'];
			if(empty($container[$key])){
				$container[$key] = $v;
			}else{
				$container[$key] += $v;
			}
		}
		foreach($container as $key=>$vals){
			$s = array_filter($vals, function($values) {
				return !empty($values);
			});

			if(!empty($s)){
				$sql_insertvalues[] =  "('". $bill['id'] ."','". $bill['bizUserId'] ."','". $s['goodsId'] ."','". $s['goodsName'] ."','". $s['goodsCode'] ."')";
			}
		}
		$values = implode(",", $sql_insertvalues);
		$sql = $insert_sql.$values;
		$res = $db->execute($sql);
		/*
			拼接字符串实现,需要更改表结构
		$goodData = [];
		foreach ($bill['items'] as $k => $val) {
			$goodData[] = implode(',',$val);
		}
		$goods = implode('||',$goodData);
		$sql = "insert into t_goods_binding(personnel_id,login_name,goods) values('%s','%s','%s')";
		$res = $db->execute($sql,$bill['id'],$bill['bizUserId'],$goods);
		*/
		if($res === false){
			return $this->sqlError(__METHOD__,__LINE__);
		}
		return null;
	}
}