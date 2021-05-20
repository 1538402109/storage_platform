<?php

namespace API\Controller;

use API\Service\MainMenuApiService;

/**
 * 主菜单 Controller
 * 
 * @author Taoyj
 *        
 */
class MainMenuController extends BaseController {

	/**
	 * 返回主菜单
	 */
	public function mainMenuItems() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			
			$service = new MainMenuApiService();
			$this->ajaxReturn($service->mainMenuItems($userId));
		}
	}

}