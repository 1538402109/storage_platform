<?php

namespace API\Controller;

use Think\Controller;
use API\Service\CustomerApiService;

/**
 * 客户资料 Controller
 *
 * @author JIATU
 *        
 */
class CustomerController extends BaseController {
		
	/**
	 * 客户自定义字段，查询客户
	 */
	public function queryData() {
		if (IS_GET) {
			$params = array(
				"queryKey" => I("get.queryKey"),
			);
	     	//$params["loginUserId"] = $this->getUserId();
		    $params["userId"] = $this->getUserId();

			$cs = new CustomerApiService();
			$this->ajaxReturn($cs->queryData($params));
		}
	}

	
	/**
	 * 查询客户资料列表
	 */
	public function customerAllList() {
		if (IS_GET) {
			$userId = $this->getUserId();
			$params = [
					"userId" => $userId,
					//"start" => I("get.start"),
					//"categoryId" => I("get.categoryId"),
					// "code" => I("get.code"),
					// "name" => I("get.name"),
					// "address" => I("get.address"),
					// "contact" => I("get.contact"),
					// "mobile" => I("get.mobile"),
					// "tel" => I("get.tel"),
					// "qq" => I("get.qq"),
					//"limit" => I("get.limit"),
			];
		
			$service = new CustomerApiService();
			
			$this->ajaxReturn($service->customerAllList($params));
		}
	}

	/**
	 * 查询客户资料列表
	 */
	public function customerList() {
		if (IS_GET) {
			$userId = $this->getUserId();
			$params = [
					"userId" => $userId,
					"start" => I("get.start"),
					"categoryId" => I("get.categoryId"),
					// "code" => I("get.code"),
					// "name" => I("get.name"),
					// "address" => I("get.address"),
					// "contact" => I("get.contact"),
					// "mobile" => I("get.mobile"),
					// "tel" => I("get.tel"),
					// "qq" => I("get.qq"),
					"limit" => I("get.limit"),
			];
		
			$service = new CustomerApiService();
			
			$this->ajaxReturn($service->customerList($params));
		}
	}

	/**
	 * 获得客户分类列表，包括[全部]分类这个数据里面没有的记录，用于查询条件界面里面的客户分类字段
	 */
	public function categoryListWithAllCategory() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				"userId" => $userId,
			];
			$service = new CustomerApiService();
			$this->ajaxReturn($service->categoryListWithAllCategory($params));
		}
	}

	/**
	 * 获得客户分类列表
	 */
	public function categoryList() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				"userId" => $userId,
			];
			$service = new CustomerApiService();
			$this->ajaxReturn($service->categoryList($params));
		}
	}

	/**
	 * 新增或编辑某个客户分类
	 */
	public function editCategory() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = [
				    "loginUserId" => $userId,
					"id" => I("post.id"),
					"code" => I("post.code"),
					"name" => I("post.name"),
					"psId" => I("post.psId"),
					"fromDevice" => I("post.fromDevice")
			];
			
			$service = new CustomerApiService();
			
			$this->ajaxReturn($service->editCategory($params));
		}
	}

	/**
	 * 获得所有的价格体系中的价格
	 */
	public function priceSystemList() {
		if (IS_POST) {
			$params = [
				"tokenId" => I("post.tokenId")
			];
			
			$service = new CustomerApiService();
			
			$this->ajaxReturn($service->priceSystemList($params));
		}
	}

	/**
	 * 获得某个客户分类的详细信息
	 */
	public function categoryInfo() {
		if (IS_POST) {
			$params = [
					"tokenId" => I("post.tokenId"),
					"categoryId" => I("post.categoryId")
			];
			
			$service = new CustomerApiService();
			
			$this->ajaxReturn($service->categoryInfo($params));
		}
	}

	/**
	 * 删除某个客户分类
	 */
	public function deleteCategory() {
		if (IS_POST) {
			$params = [
					"tokenId" => I("post.tokenId"),
					"id" => I("post.categoryId")
			];
			
			$service = new CustomerApiService();
			
			$this->ajaxReturn($service->deleteCategory($params));
		}
	}

	/**
	 * 获得某个客户的详细信息
	 */
	public function customerInfo() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
					"loginUserId" => $userId,
					"userId" => $userId,
					"id" => I("get.id")
			];
			
			$service = new CustomerApiService();
			$this->ajaxReturn($service->customerInfo($params));
		}
	}

	/**
	 * 新建客户时自动获取客户编号
	 */
	public function getCustomerCode(){
		if (IS_GET) {
			$cs = new CustomerApiService();
			$this->ajaxReturn($cs->getCustomerCode());
		}
	}

	/**
	 * 新增或编辑客户资料
	 */
	public function editCustomer() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = [
				    "loginUserId" => $userId,
					"id" => I("post.id"),
					"code" => I("post.code"),
					"name" => I("post.name"),
					"address" => I("post.address"),
					"addressReceipt" => I("post.addressReceipt"),
					"contact01" => I("post.contact01"),
					"mobile01" => I("post.mobile01"),
					"tel01" => I("post.tel01"),
					"qq01" => I("post.qq01"),
					"contact02" => I("post.contact02"),
					"mobile02" => I("post.mobile02"),
					"tel02" => I("post.tel02"),
					"qq02" => I("post.qq02"),
					"bankName" => I("post.bankName"),
					"bankAccount" => I("post.bankAccount"),
					"tax" => I("post.tax"),
					"fax" => I("post.fax"),
					"note" => I("post.note"),
					"categoryId" => I("post.categoryId"),
					"initReceivables" => I("post.initReceivables"),
					"initReceivablesDT" => I("post.initReceivablesDT"),

					"initPayables" => I("post.initPayables"),
					"initPayablesDT" => I("post.initPayablesDT"),

					"warehouseId" => I("post.warehouseId"),
					"recordStatus" => I("post.recordStatus")
			];
			
			$service = new CustomerApiService();
			
			$this->ajaxReturn($service->editCustomer($params));
		}
	}

	/**
	 * 返回仓库列表，用于编辑客户资料的时候，选择销售出库仓库
	 */
	public function warehouseList() {
		if (IS_POST) {
			$userId = $this-> getUserId();
			$params = [
				"loginUserId" => $userId,
			];
			$service = new CustomerApiService();
			$this->ajaxReturn($service->warehouseList($params));
		}
	}

	/**
	 * 删除某个客户资料
	 */
	public function deleteCustomer() {
		if (IS_POST) {
			$params = [
					"tokenId" => I("post.tokenId"),
					"id" => I("post.id")
			];
			
			$service = new CustomerApiService();
			
			$this->ajaxReturn($service->deleteCustomer($params));
		}
	}


}