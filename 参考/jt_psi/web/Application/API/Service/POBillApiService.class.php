<?php
namespace API\Service;
use API\DAO\POBillApiDAO;
use Home\Common\FIdConst;

class POBillApiService extends PSIApiBaseExService 
{
    private $LOG_CATEGORY = "采购订单";
    /**
	 * 审核采购订单
	 */
	public function commitPOBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new POBillApiDAO($db);
		
		
		$rc = $dao->commitPOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		$id = $params["id"];
		
		// 记录业务日志
		$log = "审核采购订单，单号：{$ref}";
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
    }
    /**
	 * 取消审核采购订单
	 */
    public function cancelConfirmPOBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new POBillApiDAO($db);
		$rc = $dao->cancelConfirmPOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		
		// 记录业务日志
		$log = "取消审核采购订单，单号：{$ref}";
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}
}
