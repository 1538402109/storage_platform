<?php
namespace API\Controller;

use API\Service\POBillApiService;
use Think\Controller;

// 审核采购订单
class POBillController extends BaseController {
    public function commitPOBill()
	{
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
		    $params["loginUserId"] = $this->getUserId();
			
			$ps = new POBillApiService();
			$this->ajaxReturn($ps->commitPOBill($params));
		}
	}
	// 取消审核采购订单
	public function cancelConfirmPOBill()
	{
		if (IS_POST) {
		$params = array(
				"id" => I("post.id")
		);
		
		$ps = new POBillService();
		$this->ajaxReturn($ps->cancelConfirmPOBill($params));
		}
			
	}


}