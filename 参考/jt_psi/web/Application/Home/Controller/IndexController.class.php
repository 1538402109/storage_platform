<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;

/**
 * 首页Controller
 *
 * @author JIATU
 *        
 */
class IndexController extends PSIBaseController {

	/**
	 * 首页
	 */
	public function index() {
		$us = new UserService();
		
		if ($us->hasPermission()) {
			$this->initVar();
			
			$this->assign("title", "首页");
			
			$this->assign("pSale", $us->hasPermission(FIdConst::PORTAL_SALE) ? 1 : 0);
			$this->assign("pInventory", $us->hasPermission(FIdConst::PORTAL_INVENTORY) ? 1 : 0);
			$this->assign("pPurchase", $us->hasPermission(FIdConst::PORTAL_PURCHASE) ? 1 : 0);
			$this->assign("pMoney", $us->hasPermission(FIdConst::PORTAL_MONEY) ? 1 : 0);
			
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage();
		}
	}
}