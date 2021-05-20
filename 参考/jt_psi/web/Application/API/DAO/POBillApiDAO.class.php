<?php
namespace API\DAO;
use Home\Common\FIdConst;
class POBillApiDAO extends PSIApiBaseExDAO 
{
    /**
	 * 根据采购订单id查询采购订单
	 *
	 * @param string $id        	
	 * @return array|NULL
	 */
	public function getPOBillById($id) {
		$db = $this->db;
		
		$sql = "select ref, data_org, bill_status, company_id
				from t_po_bill where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			return [
					"ref" => $data[0]["ref"],
					"dataOrg" => $data[0]["data_org"],
					"billStatus" => $data[0]["bill_status"],
					"companyId" => $data[0]["company_id"]
			];
		} else {
			return null;
		}
	}
    /**
	 * 审核采购订单
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function commitPOBill(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$bill = $this->getPOBillById($id);
		if (! $bill) {
			return $this->bad("要审核的采购订单不存在");
		}
		$ref = $bill["ref"];
		$billStatus = $bill["billStatus"];
		if ($billStatus > 0) {
			return $this->bad("采购订单(单号：$ref)已经被审核，不能再次审核");
		}
		
		$sql = "update t_po_bill
				set bill_status = 1000,
					confirm_user_id = '%s',
					confirm_date = now()
				where id = '%s' ";
		$rc = $db->execute($sql, $loginUserId, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$params["ref"] = $ref;
		
		// 操作成功
		return null;
	}

	/**
	 * 取消审核采购订单
	 *
	 * @param array $params        	
	 * @return NULL|array
	 */
	public function cancelConfirmPOBill(& $params) {
		$db = $this->db;
		$id = $params["id"];
		
		$bill = $this->getPOBillById($id);
		if (! $bill) {
			return $this->bad("要取消审核的采购订单不存在");
		}
		
		$ref = $bill["ref"];
		$params["ref"] = $ref;
		
		$billStatus = $bill["billStatus"];
		if ($billStatus > 1000) {
			return $this->bad("采购订单(单号:{$ref})不能取消审核");
		}
		
		if ($billStatus == 0) {
			return $this->bad("采购订单(单号:{$ref})还没有审核，无需进行取消审核操作");
		}
		
		$sql = "select count(*) as cnt from t_po_pw where po_id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("采购订单(单号:{$ref})已经生成了采购入库单，不能取消审核");
		}
		
		$sql = "update t_po_bill
				set bill_status = 0, confirm_user_id = null, confirm_date = null
				where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}
}
