<?php

namespace API\Service;

use API\DAO\SaleBillApiDAO;
use API\DAO\UserApiDAO;

/**
 * 销售订单 API Service
 *
 * @author JIATU
 */
class SaleBillApiService extends PSIApiBaseService {

	/**
	 * 销售订单列表
	 */
	public function sobillList($params) {
		// $tokenId = $params["tokenId"];
		// if ($this->tokenIsInvalid($tokenId)) {
		// 	return $this->emptyResult();
		// }
		// $params["loginUserId"] = $this->getUserIdFromTokenId($tokenId);
		
		$dao = new SaleBillApiDAO($this->db());
		return $dao->sobillList($params);
	}

	/**
	 * 销售订单 商品记录 前10条
	 */
	public function sobillGoods($params) {
		$dao = new SaleBillApiDAO($this->db());
		$params["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);

		return $dao->sobillGoods($params);
	}
	
 
	/**
	 * 销售订单详情
	 */
	public function soBillInfo($params) {
		// $tokenId = $params["tokenId"];
		// if ($this->tokenIsInvalid($tokenId)) {
		// 	return $this->emptyResult();
		// }
		// $params["loginUserId"] = $this->getUserIdFromTokenId($tokenId);
		$params["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);
		
		$dao = new SaleBillApiDAO($this->db());
		return $dao->soBillInfo($params);
	}

	/**
	 * 新增或编辑销售订单
	 */
	public function editSOBill($userId,$json) {
		// if ($this->isNotOnline()) {
		// 	return $this->notOnlineError();
		// }
		
		$bill = json_decode(html_entity_decode($json), true);
		if ($bill == null) {
			return $this->bad("传入的参数错误，不是正确的JSON格式");
		}
		
		$db = $this->db();
		
		$db->startTrans();
		
		$dao = new SaleBillApiDAO($db);
		
		$id = $bill["id"];
		
		$log = null;
		
		$bill["companyId"] = $this->getCompanyIdFromTokenId($userId);
		
		if ($id) {
			// 编辑
			$bill["loginUserId"] = $userId;
			$rc = $dao->updateSOBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			$ref = $bill["ref"];
			
			$log = "编辑销售订单，单号：{$ref}";
		} else {
			// 新建销售订单
			
			$bill["loginUserId"] = $userId;
			$bill["dataOrg"] = $this->getLoginUserDataOrg($userId);
			
			$rc = $dao->addSOBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}

			$id = $bill["id"];
			$ref = $bill["ref"];
			
			$result = array(
				"id" => $bill["id"],
			    "ref" => $bill["ref"]
			);
			
			$scbillRef = $bill["scbillRef"];
			if ($scbillRef) {
				// 从销售合同生成销售订单
				$log = "从销售合同(合同号：{$scbillRef})生成销售订单: 单号 = {$ref}";
			} else {
				// 手工创建销售订单
				$log = "新建销售订单，单号：{$ref}";
			}
		}
		
		// 记录业务日志
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($userId,$log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($result);
	}


	/**
	 * 审核销售订单
	 */
	public function commitSOBill($params) {
		
		$id = $params["id"];
		$db = $this->db();
		$userId = $params["loginUserId"];
		
		$db->startTrans();
		
		$dao = new SaleBillApiDAO($db);
		
		$rc = $dao->commitSOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		// 记录业务日志
		$ref = $params["ref"];
		$log = "审核销售订单，单号：{$ref}";
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($userId, $log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 取消销售订单审核
	 */
	public function cancelConfirmSOBill($params) {
		// if ($this->isNotOnline()) {
		// 	return $this->notOnlineError();
		// }
		$userId = $params["loginUserId"];
		
		$id = $params["id"];
		$db = $this->db();
		
		$db->startTrans();
		
		$dao = new SaleBillApiDAO($db);
		$rc = $dao->cancelConfirmSOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		// 记录业务日志
		$ref = $params["ref"];
		$log = "取消审核销售订单，单号：{$ref}";
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($userId, $log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 删除销售订单
	 */
	public function deleteSOBill($params) {
		
		$userId = $params["loginUserId"];

		$db = $this->db();
		
		$db->startTrans();
		
		$dao = new SaleBillApiDAO($db);
		$rc = $dao->deleteSOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		$log = "删除销售订单，单号：{$ref}";
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($userId, $log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok();
	}


	/**
	 * 关闭销售订单
	 */
	public function closeSOBill($params) {
	
		$id = $params["id"];
		$userId = $params["loginUserId"];
		
		$db = $this->db();
		$db->startTrans();
		$dao = new SaleBillApiDAO($this->db());
		$rc = $dao->closeSOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		
		// 记录业务日志
		$log = "关闭销售订单，单号：{$ref}";
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($userId, $log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

		/**
	 * 取消订单关闭状态
	 */
	public function cancelClosedSOBill($params) {
		
		$id = $params["id"];
		
		$db = $this->db();
		$db->startTrans();
		$dao = new SaleBillApiDAO($this->db());
		$rc = $dao->cancelClosedSOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		
		// 记录业务日志
		$log = "取消销售订单[单号：{$ref}]的关闭状态";
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($loginUserId,$log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 新建或编辑的时候，获得销售出库单的详情
	 */
	public function wsBillInfo($params) {
		
		$us = new UserApiService();
		$params["loginUserName"] = $us->getLoignUserNameWithOrgFullName($params["loginUserId"]);
		$params["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);
		
		$dao = new SaleBillApiDAO($this->db());
		return $dao->wsBillInfo($params);
	}

	/**
	 * 获得销售出库单主表列表
	 */
	public function wsbillList($params) {
		$result = $this->ok();
		$params["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);

		$dao = new SaleBillApiDAO($this->db());
		$result["data"] = $dao->wsbillList($params);
		return $result;
	}

	/**
	 * 获得某个销售出库单的明细记录列表
	 */
	public function wsBillDetailList($params) {
		
		$params["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);
		
		$dao = new SaleBillApiDAO($this->db());
		return $dao->wsBillDetailList($params);
	}


	/**
	 * 新增或编辑销售出库单
	 */
	public function editWSBill($params) {
		
		$json = $params["jsonStr"];
		$bill = json_decode(html_entity_decode($json), true);
		if ($bill == null) {
			return $this->bad("传入的参数错误，不是正确的JSON格式");
		}
		$loginUserId =  $params["loginUserId"];

		$id = $bill["id"];
		
		$sobillRef = $bill["sobillRef"];
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new SaleBillApiDAO($db);
		
		$log = null;		
		$bill["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);
		
		if ($id) {
			// 编辑
			
			$bill["loginUserId"] = $loginUserId;
			
			$rc = $dao->updateWSBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$ref = $bill["ref"];
			$log = "编辑销售出库单，单号 = {$ref}";
		} else {
			// 新建销售出库单
			
			$bill["dataOrg"] = $this->getLoginUserDataOrg($loginUserId);
			$bill["loginUserId"] = $loginUserId;
			
			$rc = $dao->addWSBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$id = $bill["id"];
			$ref = $bill["ref"];
			if ($sobillRef) {
				// 从销售订单生成销售出库单
				$log = "从销售订单(单号：{$sobillRef})生成销售出库单: 单号 = {$ref}";
			} else {
				// 手工新建销售出库单
				$log = "新增销售出库单，单号 = {$ref}";
			}
		}
		
		// 记录业务日志
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($loginUserId,$log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 删除销售出库单
	 */
	public function deleteWSBill($params) {
		$dao = new SaleBillApiDAO($this->db());
		$rc = $dao->deleteWSBill($params);
		if ($rc) {
			return $rc;
		}
		return $this->ok();
	}

		/**
	 * 提交销售出库单
	 */
	public function commitWSBill($params) {
		$id = $params["id"];
		
		$params["loginUserId"] = $params["loginUserId"];
		$params["companyId"] = $this->getCompanyIdFromTokenId($params["loginUserId"]);
		$bill["userDataOrg"] = $this->getLoginUserDataOrg($loginUserId);
		$UserApiDAO = new UserApiDAO($this->db());
		$params["loginUserName"] = $UserApiDAO->getLoginUserName($params["loginUserId"]);

		$db = $this->db();
		$db->startTrans();
		
		$dao = new SaleBillApiDAO($db);
		$rc = $dao->commitWSBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		$log = "提交销售出库单，单号 = {$ref}";
		$bs = new BizlogApiService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}


}