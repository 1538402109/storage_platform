<?php
namespace Home\Service;

use Home\DAO\PersonnelDAO;
use Home\Common\DemoConst;

class PersonnelService extends PSIBaseExService{
    var $db;
    function __construct($db = null){
        if($db == null){
            $db = M();
        }
        $this->db = $db;
    }
    /**
     * 获得人员列表
     */
    public function personnels($params){
        if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
        $db = $this->db();
        $dao = new PersonnelDAO($db);
        return $dao->personnels($params);
    }

    /**
     * 增加/修改人员信息
     */
    public function editPersonnel($params){
        if($this->isNotOnline()){
            return $this->emptyResult();
        }

        $id = $params["id"];
		$loginName = $params["loginName"];
		$name = $params["name"];
		$orgCode = $params["orgCode"];

        $pys = new PinyinService();
		$py = $pys->toPY($name);
		$params["py"] = $py;

        $db = $this->db();
        $dao = new PersonnelDAO($db);
        $log = null;
        if($id){
            //修改
            $rc = $dao->updateUser($params);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$log = "编辑用户： 登录名 = {$loginName} 姓名 = {$name} 编码 = {$orgCode}";
        }else{
            //增加
            $rc = $dao->addPersonnel($params);
            if ($rc) {
				$db->rollback();
				return $rc;
			}
			$id = $params["id"];
			$log = "新建用户： 登录名 = {$loginName} 姓名 = {$name} 编码 = {$orgCode}";
        }

        // 记录业务日志
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
    }

    /**
     * 获得人员具体信息
     */
    public function userInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new PersonnelDAO($this->db());
		
		return $dao->userInfo($params);
	}

    /**
     * 删除人员
     */
    public function deleteUser($params){
        dump($params);
    }

    /**
     * 修改密码
     */
    public function changePassword($params){
        if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		
		if ($this->isDemo() && $id == DemoConst::ADMIN_USER_ID) {
			return $this->bad("在演示环境下，admin用户的密码不希望被您修改，请见谅");
		}
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new PersonnelDAO($db);
		$user = $dao->getUserById($id);
		if (! $user) {
			$db->rollback();
			return $this->bad("要修改密码的用户不存在");
		}
		$loginName = $user["loginName"];
		$name = $user["name"];
		
		$rc = $dao->changePassword($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$log = "修改用户[登录名 ={$loginName} 姓名 = {$name}]的密码";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
    }

    //获取人员绑定信息
    public function goodsBindingSel($id){
        if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
        
		$dao = new PersonnelDAO($this->db());
		return $dao->goodsBindingInfo($id);
    }

    //进行商品绑定
    public function addBinding($json){
        if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
        $bill = json_decode(html_entity_decode($json),true);
        $db = $this->db();
        $dao = new PersonnelDAO($db);
        $rc = $dao->goodsBinding($bill);
        if ($rc) {
            $db->rollback();
            return $rc;
        }

        // 记录业务日志
        $log = "已给{$bill['bizUserId']}绑定了新的商品";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
        return $this->ok();
    }
}