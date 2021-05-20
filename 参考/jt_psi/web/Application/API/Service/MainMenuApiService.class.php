<?php

namespace API\Service;

use API\DAO\MainMenuApiDAO;

/**
 * 主菜单Service
 *
 * @author Taoyj
 */
class MainMenuApiService extends PSIApiBaseService {

	/**
	 * 当前用户有权限访问的所有菜单项
	 */
	public function mainMenuItems($params) {
		
		$dao = new MainMenuApiDAO($this->db());
		return $dao->mainMenuItems($params);

	}
}