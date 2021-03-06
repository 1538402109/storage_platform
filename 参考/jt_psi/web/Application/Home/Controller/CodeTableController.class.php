<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\CodeTableService;

/**
 * 码表Controller
 *
 * @author JIATU
 *        
 */
class CodeTableController extends PSIBaseController {

	/**
	 * 码表设置 - 主页面
	 */
	public function index() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::CODE_TABLE)) {
			$this->initVar();
			
			$this->assign("title", "码表设置");
			//获取主题风格
			$interfaceStyle = $us->userUi();
			$style = $interfaceStyle['interface_style'];
			$this->assign('interfaceStyle',$style);
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/CodeTable/index");
		}
	}

	/**
	 * 码表运行 - 主页面
	 */
	public function run() {
		$fid = I("get.fid");
		
		$us = new UserService();
		if ($us->hasPermission($fid)) {
			$this->initVar();
			
			$service = new CodeTableService();
			$md = $service->getMetaDataByFid($fid);
			
			if ($md) {
				$this->assign("title", $md["title"]);
				$this->assign("fid", $fid);
				
				$this->display();
			} else {
				
				// 错误的fid，跳转到首页
				$this->gotoLoginPage("/Home");
			}
		} else {
			$this->gotoLoginPage("/Home");
		}
	}

	/**
	 * 码表分类列表
	 */
	public function categoryList() {
		if (IS_POST) {
			$params = [];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->categoryList($params));
		}
	}

	/**
	 * 新增或编辑码表分类
	 */
	public function editCodeTableCategory() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id"),
					"code" => I("post.code"),
					"name" => I("post.name")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->editCodeTableCategory($params));
		}
	}

	/**
	 * 删除码表分类
	 */
	public function deleteCodeTableCategory() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->deleteCodeTableCategory($params));
		}
	}

	/**
	 * 码表列表
	 */
	public function codeTableList() {
		if (IS_POST) {
			$params = [
					"categoryId" => I("post.categoryId")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->codeTableList($params));
		}
	}

	/**
	 * 码表分类自定义字段 - 查询数据
	 */
	public function queryDataForCategory() {
		if (IS_POST) {
			$params = [
					"queryKey" => I("post.queryKey")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->queryDataForCategory($params));
		}
	}

	/**
	 * 新增或编辑码表
	 */
	public function editCodeTable() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id"),
					"categoryId" => I("post.categoryId"),
					"code" => I("post.code"),
					"name" => I("post.name"),
					"tableName" => I("post.tableName"),
					"memo" => I("post.memo")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->editCodeTable($params));
		}
	}

	/**
	 * 某个码表的列
	 */
	public function codeTableColsList() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			$service = new CodeTableService();
			$this->ajaxReturn($service->codeTableColsList($params));
		}
	}

	/**
	 * 删除码表
	 */
	public function deleteCodeTable() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->deleteCodeTable($params));
		}
	}

	/**
	 * 查询码表主表元数据
	 */
	public function codeTableInfo() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->codeTableInfo($params));
		}
	}

	/**
	 * 查询码表元数据 - 运行界面用
	 */
	public function getMetaDataForRuntime() {
		if (IS_POST) {
			$params = [
					"fid" => I("post.fid")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->getMetaDataForRuntime($params));
		}
	}

	/**
	 * 新增或编辑码表记录
	 */
	public function editCodeTableRecord() {
		if (IS_POST) {
			$params = [
					"fid" => I("post.fid")
			];
			
			$service = new CodeTableService();
			
			$md = $service->getMetaDataForRuntime($params);
			
			$params["id"] = I("post.id");
			
			foreach ( $md["cols"] as $colMd ) {
				if ($colMd["isVisible"]) {
					$fieldName = $colMd["fieldName"];
					$params[$fieldName] = I("post.{$fieldName}");
				}
			}
			
			$params["code"] = strtoupper($params["code"]);
			
			$this->ajaxReturn($service->editCodeTableRecord($params));
		}
	}

	/**
	 * 码表记录列表
	 */
	public function codeTableRecordList() {
		if (IS_POST) {
			$params = [
					"fid" => I("post.fid")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->codeTableRecordList($params));
		}
	}
}