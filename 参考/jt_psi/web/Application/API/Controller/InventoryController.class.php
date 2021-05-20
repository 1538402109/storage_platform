<?php

namespace API\Controller;

use Home\Common\FIdConst;
use API\Service\InventoryApiService;
use API\Service\UserApiService;

/**
 * 库存Controller
 *
 * @author Taoyj
 *        
 */
class InventoryController extends BaseController {

	
	/**
	 * 获得所有仓库列表
	 * 添加权限
	 */
		/**
     * @OA\Get(
     *   path="/Web/API/Inventory/warehouseList", 
     *   summary = "获得所有仓库列表", 
     *   description = "获得所有仓库列表",  
     *    tags={"用户"},
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "tokenId"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
	 */
	public function warehouseList() {
		if (IS_GET) {
			$userId = $this-> getUserId();
		
			$us = new UserApiService();
			if ($us->hasPermission($userId,FIdConst::INVENTORY_QUERY)) {
				$is = new InventoryApiService();
				$this->ajaxReturn($is->warehouseList($userId));
			} else {
				$this->ajaxReturn($this->bad("无权限"));
			}

		}
	}

	/**
	 * 库存总账
	 */
		/**
     * @OA\Get(
     *   path="/Web/API/Inventory/inventoryList", 
     *   summary = "库存总账", 
     *   description = "库存总账",  
     *    tags={"用户"},
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "tokenId"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
	 */
	public function inventoryList() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				    "loginUserId" => $userId,
					"warehouseId" => I("get.warehouseId"),
					"code" => I("get.code"),
					// "name" => I("get.code"),
					// "spec" => I("get.code"),
					// "brandId" => I("get.brandId"),
					// "hasInv" => I("get.hasInv"),
					// "sort" => I("get.sort"),
					// "page" => I("get.page"),
					"start" => I("get.start"),
					"limit" => I("get.limit")
			];
			$this->ajaxReturn((new InventoryApiService())->inventoryList($params));
		}
	}
	public function inventoryListAll() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				    "loginUserId" => $userId,
					"warehouseId" => I("get.warehouseId"),
					"code" => I("get.code"),
					// "name" => I("get.code"),
					// "spec" => I("get.code"),
					// "brandId" => I("get.brandId"),
					// "hasInv" => I("get.hasInv"),
					// "sort" => I("get.sort"),
					// "page" => I("get.page"),
					"start" => I("get.start"),
					"limit" => I("get.limit")
			];
			$this->ajaxReturn((new InventoryApiService())->inventoryListAll($params));
		}
	}

	/**
	 * 库存明细账 商品库存明细
	 */
		/**
     * @OA\Get(
     *   path="/Web/API/Inventory/inventoryList", 
     *   summary = "库存明细账", 
     *   description = "商品库存明细",  
     *    tags={"用户"},
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "tokenId"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
	 */
	public function inventoryDetailList() {
		if (IS_GET) {
			$userId = $this-> getUserId();

			$params = array(
				    "loginUserId" => $userId,
					"warehouseId" => I("get.warehouseId"),
					"goodsId" => I("get.goodsId"),
					"dtFrom" => I("get.dtFrom"),
					"dtTo" => I("get.dtTo"),
					"page" => I("get.page"),
					"start" => I("get.start"),
					"limit" => I("get.limit")
			);
			$is = new InventoryApiService();
			$this->ajaxReturn($is->inventoryDetailList($params));
		}
	}

	// 查找指定商品库存
	/***
	 *  库存盘点 - 主页面
	 *  新建库存盘点  点击商品时 查询库存
	 * 
	 * * */
	public function invGoods()
	{
		if (IS_GET) {
			$params = array(
				"id" => I("get.id")
			);
			$is = new InventoryApiService();
			$this->ajaxReturn($is->inventoryGoodsInfo($params));
		}
	}
}
