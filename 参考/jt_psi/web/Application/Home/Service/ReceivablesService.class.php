<?php

namespace Home\Service;

use Home\DAO\ReceivablesDAO;

/**
 * 应收账款Service
 *
 * @author JIATU
 */
class ReceivablesService extends PSIBaseExService {
	private $LOG_CATEGORY = "应收账款管理";

	/**
	 * 往来单位分类
	 */
	public function rvCategoryList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new ReceivablesDAO($this->db());
		return $dao->rvCategoryList($params);
	}

	/**
	 * 应收账款列表
	 */
	public function rvList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserDataOrg"] = $this->getLoginUserDataOrg;
		$dao = new ReceivablesDAO($this->db());
		return $dao->rvList($params);
	}

	/**
	 * 应收账款明细（多条件）
	 */
	public function rvDetailList2($params){
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new ReceivablesDAO($this->db());
		return $dao->rvDetailList2($params);
	}

	/**
	 * 应收账款的明细记录
	 */
	public function rvDetailList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		//$params["dataOrg"] =$this->getLoginUserDataOrg();
		$params["loginUserId"] = $this->getLoginUserId();
		$dao = new ReceivablesDAO($this->db());
		return $dao->rvDetailList($params);
	}

	/**
	 * 将某条物流代收记录转为记应收账款
	 */
	public function changeReceivable($params){
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = $this->getLoginUserName();
		$dao = new ReceivablesDAO($this->db());
		return $dao->changeReceivable($params);
	}

	/**
	 * 应收账款的收款记录
	 */
	public function rvRecordList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new ReceivablesDAO($this->db());
		//$params["dataOrg"] =$this->getLoginUserDataOrg();
		return $dao->rvRecordList($params);
	}

	/**
	 * 收款记录
	 */
	public function addRvRecord($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$params["companyId"] = $this->getCompanyId();
		$params["dataOrg"] = $this->getLoginUserDataOrg();
		$params["loginUserId"] = $this->getLoginUserId();
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new ReceivablesDAO($db);
		$rc = $dao->addRvRecord($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		// 记录业务日志
		$refType = $params["refType"];
		$refNumber = $params["refNumber"];
		$actMoney = $params["actMoney"];
		$log = "为 {$refType} - 单号：{$refNumber} 收款：{$actMoney}元";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok();
	}

	public function refreshRvInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new ReceivablesDAO($this->db());
		return $dao->refreshRvInfo($params);
	}

	public function refreshRvDetailInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new ReceivablesDAO($this->db());
		return $dao->refreshRvDetailInfo($params);
	}

	public function getOrgCode(){
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		$params["userid"]=$this->getLoginUserId();
		$dao = new ReceivablesDAO($this->db());
		return $dao->getOrgCode($params);
	}
}