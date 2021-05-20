<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\ICBillService;
use Home\Service\UserService;
use Home\Service\InventoryService;
/**
 * 库存盘点Controller
 *
 * @author JIATU
 *        
 */
class InvCheckController extends PSIBaseController {

	/**
	 * 库存盘点 - 主页面
	 */
	public function index() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::INVENTORY_CHECK)) {
			$this->initVar();
			
			$this->assign("title", "库存盘点");
			
			$this->assign("pAdd", $us->hasPermission(FIdConst::INVENTORY_CHECK_ADD) ? "1" : "0");
			$this->assign("pEdit", $us->hasPermission(FIdConst::INVENTORY_CHECK_EDIT) ? "1" : "0");
			$this->assign("pDelete", 
					$us->hasPermission(FIdConst::INVENTORY_CHECK_DELETE) ? "1" : "0");
			$this->assign("pCommit", 
					$us->hasPermission(FIdConst::INVENTORY_CHECK_COMMIT) ? "1" : "0");
			$this->assign("pGenPDF", $us->hasPermission(FIdConst::INVENTORY_CHECK_PDF) ? "1" : "0");
			$this->assign("pPrint", $us->hasPermission(FIdConst::INVENTORY_CHECK_PRINT) ? "1" : "0");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/InvCheck/index");
		}
	}

	/**
	 * 盘点单，主表
	 */
	public function icbillList() {
		if (IS_POST) {
			$params = array(
					"billStatus" => I("post.billStatus"),
					"ref" => I("post.ref"),
					"fromDT" => I("post.fromDT"),
					"toDT" => I("post.toDT"),
					"warehouseId" => I("post.warehouseId"),
					"goodsId" => I("post.goodsId"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			
			$ic = new ICBillService();
			$this->ajaxReturn($ic->icbillList($params));
		}
	}

	/**
	 * 获得某个盘点单的信息
	 */
	public function icBillInfo() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ic = new ICBillService();
			
			$this->ajaxReturn($ic->icBillInfo($params));
		}
	}
/**
	 * 获取所以商品
	 */
	public function GoodsList() {
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
			$this->ajaxReturn((new ICBillService())->goodsListForCheck($params));
		}
	}

	
	/**
	 * 新增或盘点数据录入
	 */
	public function editICBill() {
		if (IS_POST) {
			$params = array(
					"jsonStr" => I("post.jsonStr")
			);
			
			$ic = new ICBillService();
			
			$this->ajaxReturn($ic->editICBill($params));
		}
	}

	/**
	 * 盘点单明细记录
	 */
	public function icBillDetailList() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ic = new ICBillService();
			
			$this->ajaxReturn($ic->icBillDetailList($params));
		}
	}

	
	/**
	 * 删除盘点单
	 */
	public function deleteICBill() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ic = new ICBillService();
			
			$this->ajaxReturn($ic->deleteICBill($params));
		}
	}

	/**
	 * 提交盘点单
	 */
	public function commitICBill() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ic = new ICBillService();
			
			$this->ajaxReturn($ic->commitICBill($params));
		}
	}

	/**
	 * 盘点单生成pdf文件
	 */
	public function pdf() {
		$params = array(
				"ref" => I("get.ref")
		);
		
		$ws = new ICBillService();
		$ws->pdf($params);
	}

	/**
	 * 生成打印盘点单的页面
	 */
	public function genICBillPrintPage() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$ss = new ICBillService();
			$data = $ss->getICBillDataForLodopPrint($params);
			$this->assign("data", $data);
			$this->display();
		}
	}
}