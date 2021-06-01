<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;

class PurchaseController extends Controller
{
	public function __construct(Request $request)
    {   
		$this->model = new Purchase();
    }

	/**
	 * 获得采购订单的信息
	 */
	public function poBillInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id'),
					"genBill" => request()->input('genBill'),
					"sobillRef" => request()->input('sobillRef')
			);
			
			return json_encode($this->model->poBillInfo($params));
		}
	}

	/**
	 * 新增或编辑采购订单
	 */
	public function editPOBill(Request $request) {
		if ($request->isMethod('POST')) {
			$json = request()->input('jsonStr');
			return json_encode($this->model->editPOBill($json));
		}
	}
}