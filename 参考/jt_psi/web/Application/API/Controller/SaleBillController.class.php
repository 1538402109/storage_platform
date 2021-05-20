<?php

namespace API\Controller;

use API\Service\SaleBillApiService;
use Think\Controller;
use API\Service\UserApiService;
use Home\Common\FIdConst;

/**

 *
 * @author JIATU
 *        
 */
class SaleBillController extends BaseController {

	 /**
     * @OA\Get(
     *   path="/Web/API/SaleBill/sobillList", 
     *   summary = "销售订单列表", 
     *   description = "销售订单列表", 
	 * tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "customerId", in = "query", @OA\Schema(type = "string"), required = true, description = "客户ID"),  
     *   @OA\Parameter(name = "receivingType", in = "query", @OA\Schema(type = "number"), required = true, description = "收款方式"),  
     *   @OA\Parameter(name = "billStatus", in = "query", @OA\Schema(type = "number"), required = true, description = "收款方式"),  
     *   @OA\Parameter(name = "start", in = "query", @OA\Schema(type = "number"), required = true, description = "开始下标"),  
     *   @OA\Parameter(name = "limit", in = "query", @OA\Schema(type = "number"), required = true, description = "每页数量"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function sobillList() {
		if (IS_GET) {
			// $params = [
			// 		"tokenId" => I("tokenId"),
			// 		"page" => I("get.page"),
			// 		"limit" => 10,
			// 		"billStatus" => - 1
			// ];
			$userId = $this-> getUserId();
			$params = [
				"billStatus" => I("get.billStatus"),
				//"ref" => I("get.ref"), //模糊搜索
				"customerId" => I("get.customerId"),
				"receivingType" => I("get.receivingType"),
				"start" => I("get.start"),
				"limit" => I("get.limit"),
				"loginUserId" => $userId,
			];
			$service = new SaleBillApiService();
			$this->ajaxReturn($service->sobillList($params));
		}
	}

	
	 /**
     * @OA\Get(
     *   path="/Web/API/SaleBill/sobillList", 
     *   summary = "销售订单 商品记录 前10条", 
     *   description = "销售订单 商品记录", 
	 * tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "customerId", in = "query", @OA\Schema(type = "string"), required = true, description = "客户ID"),  
     *   @OA\Parameter(name = "receivingType", in = "query", @OA\Schema(type = "number"), required = true, description = "收款方式"),  
     *   @OA\Parameter(name = "billStatus", in = "query", @OA\Schema(type = "number"), required = true, description = "收款方式"),  
     *   @OA\Parameter(name = "start", in = "query", @OA\Schema(type = "number"), required = true, description = "开始下标"),  
     *   @OA\Parameter(name = "limit", in = "query", @OA\Schema(type = "number"), required = true, description = "每页数量"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function sobillGoods() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				"customerId" => I("get.customerId"),
				"start" => I("get.start"),
				"limit" => I("get.limit"),
				"loginUserId" => $userId,
			];
			$service = new SaleBillApiService();
			$this->ajaxReturn($service->sobillGoods($params));
		}
	}

	 /**
     * @OA\Get(
     *   path="/Web/API/SaleBill/sobillInfo", 
     *   summary = "某个销售订单的详情", 
     *   description = "某个销售订单的详情", 
	 * tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "id", in = "query", @OA\Schema(type = "string"), required = true, description = "销售订单ID"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function sobillInfo() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
					"loginUserId" => $userId,
					"id" => I("get.id")
			];
			$service = new SaleBillApiService();
			$result = $service->soBillInfo($params);
			$result["pConfirm"] = false;
			$us = new UserApiService();
			if ($us->hasPermission($userId,FIdConst::SALE_ORDER)) {
				$result["pConfirm"] = $us->hasPermission($userId,FIdConst::SALE_ORDER_CONFIRM) ? true : false;
			}
			$this->ajaxReturn($result);
		}
	}

	 /**
     * @OA\Post(
     *   path="/Web/API/SaleBill/editSOBill", 
     *   summary = "新增或编辑销售订单", 
     *   description = "新增或编辑销售订单", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "jsonStr: ", in = "query", @OA\Schema(type = "string"), required = true, description = "提交字符串 ，中文Unicode 编码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function editSOBill() {
		if (IS_POST) {
			$userId = $this-> getUserId();

			$json = I("post.jsonStr");
			$ps = new SaleBillApiService();
			$this->ajaxReturn($ps->editSOBill($userId,$json));
		}
	}


	 /**
     * @OA\Post(
     *   path="/Web/API/SaleBill/commitSOBill", 
     *   summary = "审核销售订单", 
     *   description = "审核销售订单", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "id: ", in = "query", @OA\Schema(type = "string"), required = true, description = "提交字符串 ，中文Unicode 编码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function commitSOBill() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = array(
					"id" => I("post.id"),
					"loginUserId" => $userId,
			);
			$ps = new SaleBillApiService();
			$this->ajaxReturn($ps->commitSOBill($params));
		}
	}

	 /**
     * @OA\Post(
     *   path="/Web/API/SaleBill/cancelConfirmSOBill", 
     *   summary = "取消销售订单审核", 
     *   description = "取消销售订单审核", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "id: ", in = "query", @OA\Schema(type = "string"), required = true, description = "提交字符串 ，中文Unicode 编码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function cancelConfirmSOBill() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = array(
					"id" => I("post.id"),
					"loginUserId" => $userId,
			);
			
			$ps = new SaleBillApiService();
			$this->ajaxReturn($ps->cancelConfirmSOBill($params));
		}
	}

	 /**
     * @OA\Post(
     *   path="/Web/API/SaleBill/cancelConfirmSOBill", 
     *   summary = "删除销售订单", 
     *   description = "删除销售订单", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "id: ", in = "query", @OA\Schema(type = "string"), required = true, description = "提交字符串 ，中文Unicode 编码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function deleteSOBill() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = array(
				"id" => I("post.id"),
				"loginUserId" => $userId,
			);
			$ps = new SaleBillApiService();
			$this->ajaxReturn($ps->deleteSOBill($params));
		}
	}

     /**
     * @OA\Post(
     *   path="/Web/API/SaleBill/cancelConfirmSOBill", 
     *   summary = "关闭销售订单", 
     *   description = "关闭销售订单", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "id: ", in = "query", @OA\Schema(type = "string"), required = true, description = "提交字符串 ，中文Unicode 编码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function closeSOBill() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = [
				"id" => I("post.id"),
				"loginUserId" => $userId,
			];
			
			$service = new SaleBillApiService();
			$this->ajaxReturn($service->closeSOBill($params));
		}
	}

	  /**
     * @OA\Post(
     *   path="/Web/API/SaleBill/cancelConfirmSOBill", 
     *   summary = "取消订单关闭状态", 
     *   description = "取消订单关闭状态", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "id: ", in = "query", @OA\Schema(type = "string"), required = true, description = "提交字符串 ，中文Unicode 编码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function cancelClosedSOBill() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = [
				"id" => I("post.id"),
				"loginUserId" => $userId,
			];
			$service = new SaleBillApiService();
			$this->ajaxReturn($service->cancelClosedSOBill($params));
		}
	}



     /**
     * @OA\Get(
     *   path="/Web/API/SaleBill/wsBillInfo", 
     *   summary = "获得销售出库单的信息", 
     *   description = "获得销售出库单的信息", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "id: ", in = "query", @OA\Schema(type = "string"), required = true, description = "提交字符串 ，中文Unicode 编码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function wsBillInfo() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = array(
				"id" => I("get.id"),
				"sobillRef" => I("get.sobillRef"),
				"loginUserId" => $userId,
			);
			
			$ws = new SaleBillApiService();
			$this->ajaxReturn($ws->wsBillInfo($params));
		}
	}

     /**
     * @OA\Get(
     *   path="/Web/API/SaleBill/editWSBill", 
     *   summary = "新建或编辑销售出库单", 
     *   description = "新建或编辑销售出库单", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "jsonStr", in = "query", @OA\Schema(type = "string"), required = true, description = "提交字符串 ，中文Unicode 编码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function editWSBill() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = array(
				"jsonStr" => I("post.jsonStr"),
				"loginUserId" => $userId,
			);
			
			$ws = new SaleBillApiService();
			$this->ajaxReturn($ws->editWSBill($params));
		}
	}

	
	/**
	 * 销售出库单主表信息列表
	 */
	public function wsbillList() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = array(
				// "ref" => I("get.ref"),
				// "fromDT" => I("get.fromDT"),
				// "toDT" => I("get.toDT"),
				"warehouseId" => I("get.warehouseId"),
				// "customerId" => I("get.customerId"),
				"receivingType" => I("get.receivingType"),
				"billStatus" => I("get.billStatus"),
					// "sn" => I("get.sn"),
					// "goodsId" => I("get.goodsId"),
					// "page" => I("get.page"),
					"start" => I("get.start"),
					"limit" => I("get.limit"),
					"loginUserId" => $userId,
			);
			
			$ws = new SaleBillApiService();
			$this->ajaxReturn($ws->wsbillList($params));
		}
	}

	/**
	 * 销售出库单明细信息列表
	 */
	public function wsBillDetailList() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = array(
				"id" => I("get.billId"),
				"loginUserId" => $userId,
			);
			
			$ws = new SaleBillApiService();
			$this->ajaxReturn($ws->wsBillDetailList($params));
		}
	}

	/**
	 * 删除销售出库单
	 */
	public function deleteWSBill() {
		if (IS_POST) {
			$params = array(
				"id" => I("post.id")
			);
			$ws = new SaleBillApiService();
			$this->ajaxReturn($ws->deleteWSBill($params));
		}
	}

	/**
	 * 提交销售出库单
	 */
	public function commitWSBill() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = array(
				"id" => I("post.id"),
				"loginUserId" => $userId,
			);
			$ws = new SaleBillApiService();
			$this->ajaxReturn($ws->commitWSBill($params));
		}
	}

	/**
	 * 销售退货入库审核销售出库单 
	 * TODO
	 */
	public function reviewWSBill(){
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ws = new WSBillService();
			$this->ajaxReturn($ws->reviewWSBill($params));
		}
	}

}