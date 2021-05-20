<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\CarsService;

class CarsController extends PSIBaseController{

    /**
	 * 车辆管理 - 主页面
	 */
	public function carsIndex() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::RECEIVING)) {
			$this->initVar();
			
			$this->assign("title", "车辆管理");
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Cars/carsIndex");
		}
	}

	public function editCar(){
		if (IS_POST) {
			$params = array(
					"id" => I("post.id"),
					"plantNumber" => I("post.plantNumber"),
					"size" => I("post.size"),
					"type" => I("post.type"),
					"memo" => I("post.memo")
			);
			
			$cs = new CarsService();
			$this->ajaxReturn($cs->editCar($params));
		}
	}
}