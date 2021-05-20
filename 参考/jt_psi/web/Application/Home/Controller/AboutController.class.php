<?php

namespace Home\Controller;

use Home\Service\AboutService;
use Home\Service\UserService;

/**
 * 关于Controller
 *
 * @author JIATU
 *        
 */
class AboutController extends PSIBaseController {

	/**
	 * 关于 - 主页面
	 */
	public function index() {
		$us = new UserService();
		if ($us->getLoginUserId()) {
			$this->initVar();
			
			$this->assign("title", "关于");
			
			$service = new AboutService();
			
			// PHP版本号
			$this->assign("phpVersion", $service->getPHPVersion());
			// MySQL版本号
			$this->assign("mySQLVersion", $service->getMySQLVersion());
			
			$d = $service->getPSIDBVersion();
			// PSI数据库结构版本号
			$this->assign("PSIDBVersion", $d["version"]);
			// PSI数据库结构最后的更新时间
			$this->assign("PSIDBUpdateDT", $d["dt"]);
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/About/index");
		}
	}
}