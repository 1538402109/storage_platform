<?php

namespace API\DAO;

use Home\DAO\PSIBaseExDAO;
use Home\Common\FIdConst;

/**
 * 主菜单API DAO
 *
 * @author JIATU
 */
class MainMenuApiDAO extends PSIBaseExDAO {

	public function mainMenuItems($uid) {
		$db = $this->db;

		$userId = $uid;
		
		$userDAO = new UserApiDAO($db);
	
		$sql = "select id, caption, fid from (select * from t_menu_item union select * from t_menu_item_plus) m
					where parent_id is null order by show_order";
		$m1 = $db->query($sql);
		$result = array();
		
		$index1 = 0;
		foreach ( $m1 as $menuItem1 ) {
			
			$children1 = array();
			
			$sql = "select id, caption, fid from (select * from t_menu_item union select * from t_menu_item_plus) m
						where parent_id = '%s' order by show_order ";
			$m2 = $db->query($sql, $menuItem1["id"]);
			
			// 第二级菜单
			$index2 = 0;
			foreach ( $m2 as $menuItem2 ) {
				$children2 = array();
				$sql = "select id, caption, fid from (select * from t_menu_item union select * from t_menu_item_plus) m
							where parent_id = '%s' order by show_order ";
			
				$fid = $menuItem2["fid"];
				if ($userDAO->hasPermission($userId,$fid)) {
					if ($fid) {
						// 仅有二级菜单
						$children1["id"] = $menuItem2["id"];
						$children1["caption"] = $menuItem2["caption"];
						$children1["fid"] = $menuItem2["fid"];
						// $children1[$index2]["children"] = $children2;
						$result[] = $children1;

						$index1 ++;
					}
				}

				// 第三级菜单
				$m3 = $db->query($sql, $menuItem2["id"]);
				$index3 = 0;
				foreach ( $m3 as $menuItem3 ) {
					if ($userDAO->hasPermission($userId,$menuItem3["fid"])) {
						$children2["id"] = $menuItem3["id"];
						$children2["caption"] = $menuItem3["caption"];
						$children2["fid"] = $menuItem3["fid"];
						$children2["children"] = array();

						$result[] = $children2;
					}
				}
			}
			// if (count($children1) > 0) {
			// 	$result[$index1] = $menuItem1;
			// 	$result[$index1]["children"] = $children1;
			// 	$index1 ++;
			// }
		}
		return $result;
	}
}