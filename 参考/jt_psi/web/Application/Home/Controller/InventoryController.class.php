<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\InventoryService;
use Home\Service\UserService;

/**
 * 库存Controller
 *
 * @author JIATU
 *        
 */
class InventoryController extends PSIBaseController {

	/**
	 * 库存建账 - 主页面
	 */
	public function initIndex() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::INVENTORY_INIT)) {
			$this->initVar();
			
			$this->assign("title", "库存建账");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Inventory/initIndex");
		}
	}

	/**
	 * 库存账查询
	 */
	public function inventoryQuery() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::INVENTORY_QUERY)) {
			$this->initVar();
			
			$this->assign("title", "库存账查询");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Inventory/inventoryQuery");
		}
	}

	/**
	 * 获得所有仓库列表
	 */
	public function warehouseList() {
		if (IS_POST) {
			$is = new InventoryService();
			$this->ajaxReturn($is->warehouseList());
		}
	}

	/**
	 * 库存总账
	 */
	public function inventoryList() {
		if (IS_POST) {
			$params = [
					"warehouseId" => I("post.warehouseId"),
					"code" => I("post.code"),
					"name" => I("post.name"),
					"spec" => I("post.spec"),
					"brandId" => I("post.brandId"),
					"hasInv" => I("post.hasInv"),
					"sort" => I("post.sort"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			];
			$this->ajaxReturn((new InventoryService())->inventoryList($params));
		}
	}

	/**
	 * 库存明细账
	 */
	public function inventoryDetailList() {
		if (IS_POST) {
			$params = array(
					"warehouseId" => I("post.warehouseId"),
					"goodsId" => I("post.goodsId"),
					"dtFrom" => I("post.dtFrom"),
					"dtTo" => I("post.dtTo"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			$is = new InventoryService();
			$this->ajaxReturn($is->inventoryDetailList($params));
		}
	}

	/**
	 * 库存批次明细
	 */
	public function inventoryBatchList() {
		if (IS_POST) {
			$params = array(
					"warehouseId" => I("post.warehouseId"),
					"goodsId" => I("post.goodsId"),
					"dtFrom" => I("post.dtFrom"),
					"dtTo" => I("post.dtTo"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			$is = new InventoryService();
			$this->ajaxReturn($is->inventoryBatchList($params));
		}
	}
	// 查找指定商品库存
	public function invGoods()
	{
		if (IS_POST) {
			$params = array(
				"id" => I("post.id")
			);
			$is = new InventoryService();
			$this->ajaxReturn($is->inventoryGoodsInfo($params));
		}
	}
}
