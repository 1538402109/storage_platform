<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class Base extends Model
{
	

	/**
	 * 获得登录用户的数据域
	 *
	 * @param array $params        	
	 * @return string|NULL
	 */
	public function getLoginUserDataOrg($params) {
		$loginUserId = $params["loginUserId"];
		
		
		$sql = "select data_org from t_user where id = '".$loginUserId."' ";
		$data = DB::select($sql);
		
		if ($data) {
			$data = json_decode(json_encode($data),true);
			return $data[0]["data_org"];
		} else {
			return null;
		}
	}

	public function getLoginUserId()
	{
		return "6C2A09CD-A129-11E4-9B6A-782BCBD7746B";
	}

	/**
	 * 操作成功
	 */
	protected function ok($id = null,$msg="") {
		if ($id) {
			return array(
					"success" => true,
					"id" => $id,
					"msg"=>$msg
			);
		} else {
			return array(
					"success" => true
			);
		}
	}

	/**
	 * 构建数据域的查询SQL语句
	 */
	public function buildSQL($fid, $tableName, $loginUserId) {
		$queryParams = [];
		
		$userDataOrg = $this->getLoginUserDataOrg(["loginUserId"=>$loginUserId]);
		
		$dataOrgList = $this->getDataOrgForFId($fid, $loginUserId);
		
		if (count($dataOrgList) == 0) {
			return null; // 全部数据域
		}
		
		// data_org is null 是为了兼容之前的版本遗留下的数据
		$result = " ( " . $tableName . ".data_org is null or " . $tableName . ".data_org = '' ";
		foreach ( $dataOrgList as $i => $dataOrg ) {
			if ($dataOrg == "*") {
				return null; // 全部数据域
			}
			
			// # 表示是当前用户自身的数据域
			if ($dataOrg == "#") {
				$result .= " or " . $tableName . ".data_org = ? ";
				$queryParams[] = $userDataOrg;
				
				continue;
			}
			
			$result .= " or left(" . $tableName . ".data_org, ?) = ? ";
			$queryParams[] = strlen($dataOrg);
			$queryParams[] = $dataOrg;
		}
		
		$result .= " ) ";
		
		return [
				0 => $result,
				1 => $queryParams
		];
	}

	private function getDataOrgForFId($fid, $loginUserId) {
		$result = [];
		
		if ($loginUserId == "6C2A09CD-A129-11E4-9B6A-782BCBD7746B") {
			// admin 是超级管理员
			$result[] = "*";
			return $result;
		}
		
		$sql = "select distinct rpd.data_org
				from t_role_permission rp, t_role_permission_dataorg rpd,
					t_role_user ru
				where ru.user_id = '".$loginUserId."' and ru.role_id = rp.role_id
					and rp.role_id = rpd.role_id and rp.permission_id = rpd.permission_id
					and rpd.permission_id = '".$fid."' ";
		$data = DB::select($sql);
		$data = json_decode(json_encode($data),true);
		foreach ( $data as $v ) {
			$result[] = $v["data_org"];
		}
		
		return $result;
	}

    public function loginUserIdNotExists($loginUserId) {
		$sql = "select count(*) as cnt from t_user where id = '".$loginUserId."' ";
		$data = DB::select($sql);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		
		return $cnt != 1;
	}

	protected function companyIdNotExists($companyId) {
		$sql = "select count(*) as cnt from t_org where id = '".$companyId."' ";
		$data = DB::select($sql);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		
		return $cnt != 1;
	}

	/**
	 * 判断日期是否是正确的Y-m-d格式
	 *
	 * @param string $date        	
	 * @return boolean true: 是正确的格式
	 */
	protected function dateIsValid($date) {
		$dt = strtotime($date);
		if (! $dt) {
			return false;
		}
		
		return date("Y-m-d", $dt) == $date;
	}

	/**
	 * 操作失败
	 *
	 * @param string $msg
	 *        	错误信息
	 */
	protected function bad($msg) {
		return array(
				"success" => false,
				"msg" => $msg
		);
	}

	/**
	 * 数据库错误
	 *
	 * @param string $methodName
	 *        	方法名称
	 * @param int $codeLine
	 *        	代码行号
	 * @return array
	 */
	protected function sqlError($methodName, $codeLine) {
		$info = "数据库错误，请联系管理员<br />错误定位：{$methodName} - {$codeLine}行";
		return $this->bad($info);
	}

	/**
	 * 参数错误
	 *
	 * @param string $param
	 *        	参数名称
	 * @return array
	 */
	protected function badParam($param) {
		return $this->bad("参数" . $param . "不正确");
	}

	protected function dataOrgNotExists($dataOrg) {
		
		$sql = "select count(*) as cnt from t_user where data_org = '".$dataOrg."' ";
		$data = DB::select($sql);
		$data = json_decode(json_encode($data),true);
		$cnt = $data[0]["cnt"];
		
		return $cnt != 1;
	}

	/**
	 * 获得增值税税率
	 *
	 * @param string $companyId        	
	 * @return int
	 */
	public function getTaxRate($companyId) {
		
		$sql = "select value from t_config
				where id = '9001-01' and company_id = '". $companyId."' ";
		$data = DB::select($sql);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			$result = $data[0]["value"];
			return intval($result);
		} else {
			return 0;
		}
	}

	/**
	 * 空结果
	 *
	 * @return array
	 */
	protected function emptyResult() {
		return [];
	}

	/**
	 * 把时间类型格式化成类似2015-08-13的格式
	 *
	 * @param string $d        	
	 * @return string
	 */
	protected function toYMD($d) {
		return date("Y-m-d", strtotime($d));
	}

	/**
	 * 获得商品数量小数位数
	 *
	 * @param string $companyId        	
	 * @return int
	 */
	public function getGoodsCountDecNumber(string $companyId): int {
		
		$result = "0";
		
		$id = "9002-03";
		$sql = "select value from t_config
				where id = '".$id."' and company_id = '".$companyId."' ";
		$data = DB::select($sql);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			$result = $data[0]["value"];
			
			if ($result == null || $result == "") {
				$result = "1";
			}
		}
		
		$r = (int)$result;
		
		// 商品数量小数位数范围：0~8位
		if ($r < 0) {
			$r = 0;
		}
		if ($r > 8) {
			$r = 8;
		}
		
		return $r;
	}

	/**
	 * 获得销售订单默认收款方式
	 *
	 * @param string $companyId        	
	 * @return int
	 */
	public function getSOBillDefaultReceving($companyId) {
		$result = "0";
		
		$id = "2002-04";
		$sql = "select value from t_config
				where id = ? and company_id = ? ";
		$data = DB::select($sql, [$id, $companyId]);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			$result = $data[0]["value"];
			
			if ($result == null || $result == "") {
				$result = "0";
			}
		}
		
		return $result;
	}

	/**
	 * 通过客户id查询客户资料
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getCustomerById($id) {
		$sql = "select code, name from t_customer where id = '".$id."' ";
		$data = DB::select($sql);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			return [
					"code" => $data[0]["code"],
					"name" => $data[0]["name"]
			];
		} else {
			return null;
		}
	}

	/**
	 * 根据组织机构idc查询组织机构
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getOrgById($id) {
		$sql = "select name, org_code from t_org where id = '".$id."' ";
		$data = DB::select($sql);
		if (! $data) {
			return null;
		}
		$data = json_decode(json_encode($data),true);
		return array(
				"name" => $data[0]["name"],
				"orgCode" => $data[0]["org_code"]
		);
	}

	/**
	 * 生成全局唯一Id （UUID）
	 *
	 * @return string
	 */
	public function newId() {
		
		$data = DB::select("select UUID() as uuid");
		$data = json_decode(json_encode($data),true);
		return strtoupper($data[0]["uuid"]);
	}

	
	/**
	 * 生成新的销售订单号
	 *
	 * @param string $companyId        	
	 * @return string
	 */
	public function genNewBillRef($companyId) {
		$db = $this->db;
		
		$pre = $this->getSOBillRefPre($companyId);
		
		$mid = date("Ymd");
		
		$sql = "select ref from t_so_bill where ref like '".$pre . $mid ."%' order by ref desc limit 1";
		$data = DB::select($sql);
		$sufLength = 3;
		$suf = str_pad("1", $sufLength, "0", STR_PAD_LEFT);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			$ref = $data[0]["ref"];
			$nextNumber = intval(substr($ref, strlen($pre . $mid))) + 1;
			$suf = str_pad($nextNumber, $sufLength, "0", STR_PAD_LEFT);
		}
		
		return $pre . $mid . $suf;
	}

	/**
	 * 获得销售订单单号前缀
	 *
	 * @param string $companyId        	
	 * @return string
	 */
	public function getSOBillRefPre($companyId) {
		$result = "PO";
		
		$id = "9003-08";
		$sql = "select value from t_config
				where id = '".$id."' and company_id = '".$companyId."' ";
		$data = DB::select($sql);
		if ($data) {
			$data = json_decode(json_encode($data).true);
			$result = $data[0]["value"];
			
			if ($result == null || $result == "") {
				$result = "SO";
			}
		}
		
		return $result;
	}
	/**
	 * 记录业务日志
	 *
	 * @param string $log
	 *        	日志内容
	 * @param string $category
	 *        	日志分类
	 */
	public function insertBizlog($log, $category = "系统") {
		if ($this->getLoginUserId() == null) {
			return;
		}
		
		$ip = session("PSI_login_user_ip");
		if ($ip == null || $ip == "") {
			$ip = $this->getClientIP();
		}
		
		$ipFrom = session("PSI_login_user_ip_from");
		
		$dataOrg = $this->getLoginUserDataOrg(["loginUserId"=>$this->getLoginUserId()]);
		$companyId = User::getCompanyId();
		
		$params = array(
				"loginUserId" => $this->getLoginUserId(),
				"log" => $log,
				"category" => $category,
				"ip" => $ip,
				"ipFrom" => $ipFrom,
				"dataOrg" => $dataOrg,
				"companyId" => $companyId
		);
		
		return $this->insertBizlog1($params);
	}

	public function insertBizlog1($params) {
		
		$loginUserId = $params["loginUserId"];
		$log = $params["log"];
		$category = $params["category"];
		$ip = $params["ip"];
		$ipFrom = $params["ipFrom"];
		$dataOrg = $params["dataOrg"];
		$companyId = $params["companyId"];
		$sql = "insert into t_biz_log (user_id, info, ip, date_created, log_category, data_org, ip_from, company_id) values (?, ?, ?, now(), ?, ?, ?, ?)";
		$rc = DB::statement($sql, [$loginUserId, $log, $ip, $category, $dataOrg, $ipFrom, $companyId]);
		
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}


	private function getClientIP() {
		return $this->get_client_ip();
	}

	/**
	 * 获取客户端IP地址
	 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
	 * @param boolean $adv 是否进行高级模式获取（有可能被伪装） 
	 * @return mixed
	 */
	function get_client_ip($type = 0,$adv=false) {
	    $type       =  $type ? 1 : 0;
	    static $ip  =   NULL;
	    if ($ip !== NULL) return $ip[$type];
	    if($adv){
	        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	            $pos    =   array_search('unknown',$arr);
	            if(false !== $pos) unset($arr[$pos]);
	            $ip     =   trim($arr[0]);
	        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
	            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
	        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
	            $ip     =   $_SERVER['REMOTE_ADDR'];
	        }
	    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
	        $ip     =   $_SERVER['REMOTE_ADDR'];
	    }
	    // IP地址合法验证
	    $long = sprintf("%u",ip2long($ip));
	    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	    return $ip[$type];
	}

	/**
	 * 获得本产品名称，默认值是：PSI
	 */
	public function getProductionName() {
		$us = new User();
		
		$defaultName = "PSI";
		
		$companyId = $us->getCompanyId();
		
		$sql = "select value from t_config
				where id = '9002-01' and company_id = ? ";
		$data = DB::select($sql, [$companyId]);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			return $data[0]["value"];
		} else {
			// 登录页面的时候，并不知道company_id的值
			$sql = "select value from t_config
				where id = '9002-01' ";
			$data = DB::select($sql);
			if ($data) {
			$data = json_decode(json_encode($data),true);
				return $data[0]["value"];
			}
			
			return $defaultName;
		}
	}

	public function billStatusCodeToName($code) {
		switch ($code) {
			case 0 :
				return "待出库";
			case 1000 :
				return "已出库";
			case 2000 :
				return "已退货";
			default :
				return "";
		}
	}

	/**
	 * 获得采购订单默认付款方式
	 *
	 * @param array $params        	
	 * @return string
	 */
	public function getPOBillDefaultPayment($params) {
		$result = "0";
		
		$companyId = $params["companyId"];
		
		$id = "2001-02";
		$sql = "select value from t_config
				where id = ? and company_id = ? ";
		$data = DB::select($sql, [$id, $companyId]);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			$result = $data[0]["value"];
			
			if ($result == null || $result == "") {
				$result = "0";
			}
		}
		
		return $result;
	}

	/**
	 * 获得销售出库单默认收款方式
	 *
	 * @param string $companyId        	
	 * @return string
	 */
	public function getWSBillDefaultReceving($companyId) {
		$result = "0";
		
		
		$id = "2002-03";
		$sql = "select value from t_config
				where id = ? and company_id = ? ";
		$data = DB::select($sql, [$id, $companyId]);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			$result = $data[0]["value"];
			
			if ($result == null || $result == "") {
				$result = "0";
			}
		}
		
		return $result;
	}

	/**
	 * 获得存货计价方法
	 * 0： 移动平均法
	 * 1：先进先出法
	 *
	 * @param string $companyId        	
	 * @return int
	 */
	public function getInventoryMethod($companyId) {
		// 2015-11-19 为发布稳定版本，临时取消先进先出法
		$result = 0;
		
		return $result;
	}

	/**
	 * 销售出库数量控制的中文含义
	 *
	 * @param string $id        	
	 * @return string
	 */
	private function getWSCountLimitName(string $id): string {
		switch ($id) {
			case "0" :
				return "不做限制";
			case "1" :
				return "不能超过销售订单未出库量";
		}
		
		return "";
	}

	/**
	 * 获得销售出库数量控制设置项
	 *
	 * @param string $companyId        	
	 * @return string "1":不能超过销售订单未出库量; "0":不做限制
	 */
	public function getWSCountLimit(string $companyId): string {
		$result = "1";
		
		$id = "2002-05";
		$sql = "select value from t_config
				where id = ? and company_id = ? ";
		$data = DB::select($sql, [$id, $companyId]);
		if ($data) {
			$data = json_decode(json_encode($data),true);
			$result = $data[0]["value"];
			
			if ($result == null || $result == "") {
				$result = "1";
			}
		}
		
		return $result;
	}

	/**
	 * 成功
	 *
	 * @param string $msg
	 *        	成功的指定提示信息
	 * @return array
	 */
	protected function success($msg) {
		return array(
				"success" => true,
				"msg" => $msg
		);
	}

	/**
	 * 构建数据域的查询SQL语句 忽略空数据域
	 */
	public function buildSQLStr2($fid, $tableName, $loginUserId) {
		$queryParams = [];
		
		$userDataOrg = $this->getLoginUserDataOrg(["loginUserId"=>$loginUserId]);
		
		$dataOrgList = $this->getDataOrgForFId($fid, $loginUserId);
		
		if (count($dataOrgList) == 0) {
			return ' 1=1 '; // 全部数据域
		}
		
		// data_org is null 是为了兼容之前的版本遗留下的数据
		$result = " ( 1!=1 ";
		
		foreach ( $dataOrgList as $i => $dataOrg ) {
			if ($dataOrg == "*") {
				return ' 1=1 '; // 全部数据域
			}
			
			// # 表示是当前用户自身的数据域
			if ($dataOrg == "#") {
				$result .= " or " . $tableName . ".data_org = '".$userDataOrg."' ";
				continue;
			}
			$len = strlen($dataOrg);
			$result .= " or left(" . $tableName . ".data_org,".$len.") = '".$dataOrg."' ";
		
		}
		
		$result .= " ) ";
		
		return $result;
	}
}