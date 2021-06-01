<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;

class SaleController extends Controller
{
	public function __construct(Request $request)
    {
		$this->model = new Sale();
    }
    /**
	 * 销售订单 - 主页面
	 */
	public function soIndex() {

		$data = array();
		$data["title"] = "销售订单";
		$data["pConfirm"] = 1;
		$data["pGenWSBill"] = 1;
		$data["pGenPOBill"] = 1;
		$data["pAdd"] = 1;
		$data["pEdit"] = 1;
		$data["pDelete"] = 1;
		$data["pGenPDF"] = 1;
		$data["pPrint"] = 1;
		$data["pCloseBill"] = 1;
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";

        return view('home/Sale/soIndex',$data);
	}

	/**
	 * 获得销售订单的信息
	 */
	public function soBillInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id'),
					"genBill" => request()->input('genBill'),
					"scbillRef" => request()->input('scbillRef')
			);

			return json_encode($this->model->soBillInfo($params));
		}
	}

	/**
	 * 新增或编辑销售订单
	 */
	public function editSOBill(Request $request) {
		if ($request->isMethod('POST')) {
			$json = request()->input('jsonStr');
			return json_encode($this->model->editSOBill($json));
		}
	}

	/**
	 * 审核销售订单
	 */
	public function commitSOBill(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->model->commitSOBill($params));
		}
	}

	/**
	 * 取消销售订单审核
	 */
	public function cancelConfirmSOBill(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->model->cancelConfirmSOBill($params));
		}
	}

	/**
	 * 获得销售订单主表信息列表
	 */
	public function sobillList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"billStatus" => isset($input['billStatus']) ? $input['billStatus'] : '',
					"ref" => isset($input['ref']) ? $input['ref'] : '',
					"fromDT" => isset($input['fromDT']) ? $input['fromDT'] : '',
					"toDT" => isset($input['toDT']) ? $input['toDT'] : '',
					"customerId" => isset($input['customerId']) ? $input['customerId'] : '',
					"receivingType" => isset($input['receivingType']) ? $input['receivingType'] : '',
					"goodsId" => isset($input['goodsId']) ? $input['goodsId'] : '',
					"start" => isset($input['start']) ? $input['start'] : '',
					"limit" => isset($input['limit']) ? $input['limit'] : '',
			);
			return json_encode($this->model->sobillList($params));
		}
	}

	/**
	 * 获得销售订单的明细信息
	 */
	public function soBillDetailList(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);

			return json_encode($this->model->soBillDetailList($params));
		}
	}

	/**
	 * 查询销售订单出库情况
	 */
	public function soBillWSBillList(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);

			return json_encode($this->model->soBillWSBillList($params));
		}
	}

	/**
	 * 获得销售出库单的信息
	 */
	public function wsBillInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id'),
					"sobillRef" => request()->input('sobillRef')
			);

			return json_encode($this->model->wsBillInfo($params));
		}
	}

	/**
	 * 新建或编辑销售出库单
	 */
	public function editWSBill(Request $request) {
		if ($request->isMethod('POST')) {
			$json = array(
					"jsonStr" => request()->input('jsonStr')
			);

			return json_encode($this->model->editWSBill($json));
		}
	}

	/**
	 * 关闭销售订单
	 */
	public function closeSOBill(Request $request) {
		if ($request->isMethod('POST')) {
			$params = [
					"id" => request()->input('id')
			];

			return json_encode($this->model->closeSOBill($params));
		}
	}

	/**
	 * 取消订单关闭状态
	 */
	public function cancelClosedSOBill(Request $request) {
		if ($request->isMethod('POST')) {
			$params = [
					"id" => request()->input('id')
			];

			return json_encode($this->model->cancelClosedSOBill($params));
		}
	}

	/**
	 * 销售出库 - 主页面
	 */
	public function wsIndex() {
		$data = array();
		$data["title"] = "销售出库";
		$data["pAdd"] = 1;
		$data["pEdit"] = 1;
		$data["pCommit"] = 1;
		$data["pDelete"] = 1;
		$data["pGenPDF"] = 1;
		$data["pPrint"] = 1;
		$data["pTMSOrder"] = 1;
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";

        return view('home/Sale/wsIndex',$data);
	}

	/**
	 * 销售出库单主表信息列表
	 */
	public function wsbillList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"billStatus" => isset($input['billStatus']) ? $input['billStatus'] : '',
					"ref" => isset($input['ref']) ? $input['ref'] : '',
					"fromDT" => isset($input['fromDT']) ? $input['fromDT'] : '',
					"toDT" => isset($input['toDT']) ? $input['toDT'] : '',
					"warehouseId" => isset($input['warehouseId']) ? $input['warehouseId'] : '',
					"customerId" => isset($input['customerId']) ? $input['customerId'] : '',
					"receivingType" => isset($input['receivingType']) ? $input['receivingType'] : '',
					"sn" => isset($input['sn']) ? $input['sn'] : '',
					"goodsId" => isset($input['goodsId']) ? $input['goodsId'] : '',
					"page" => isset($input['page']) ? $input['page'] : '',
					"start" => isset($input['start']) ? $input['start'] : '',
					"limit" => isset($input['limit']) ? $input['limit'] : '',
			);
			return json_encode($this->model->wsbillList($params));
		}
	}

	/**
	 *
	 * 获取tms接口地址
	 */
	public function GetTmsUrl(){
		// $test=C('TMS_URL');
		return json_encode(["tmsurl"=>'http://www.storageplatform.cn/']);
	}

	/**
	 * 销售出库单明细信息列表
	 */
	public function wsBillDetailList(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"billId" => request()->input('billId')
			);

			return json_encode($this->model->wsBillDetailList($params));
		}
	}

	/**
	 * 提交销售出库单
	 */
	public function commitWSBill(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->model->commitWSBill($params));
		}
	}

	/**
	 * 销售退货入库 - 主界面
	 */
	public function srIndex() {
		$data = array();
		$data["title"] = "销售退货入库";
		$data["pAdd"] = 1;
		$data["pEdit"] = 1;
		$data["pCommit"] = 1;
		$data["pDelete"] = 1;
		$data["pGenPDF"] = 1;
		$data["pPrint"] = 1;
		$data["pVerify"] = 1;
		$data["pUnVerify"] = 1;
		$data["pTMSOrder"] = 1;
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";

        return view('home/Sale/srIndex',$data);
	}

	/**
	 * 销售退货入库单主表信息列表
	 */
	public function srbillList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
				"billStatus" => isset($input['billStatus']) ? $input['billStatus'] : '',
				"ref" => isset($input['ref']) ? $input['ref'] : '',
				"fromDT" => isset($input['fromDT']) ? $input['fromDT'] : '',
				"toDT" => isset($input['toDT']) ? $input['toDT'] : '',
				"warehouseId" => isset($input['warehouseId']) ? $input['warehouseId'] : '',
				"customerId" => isset($input['customerId']) ? $input['customerId'] : '',
				"paymentType" => isset($input['paymentType']) ? $input['paymentType'] : '',
				"sn" => isset($input['sn']) ? $input['sn'] : '',
				"goodsId" => isset($input['goodsId']) ? $input['goodsId'] : '',
				"page" => isset($input['page']) ? $input['page'] : '',
				"start" => isset($input['start']) ? $input['start'] : '',
				"limit" => isset($input['limit']) ? $input['limit'] : '',
			);
			return json_encode($this->model->srbillList($params));
		}
	}

	/**
	 * 获得销售退货入库单的信息
	 */
	public function srBillInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input("id")
			);
			return json_encode($this->model->srBillInfo($params));
		}
	}

	/**
	 * 新增或者编辑销售退货入库单
	 */
	public function editSRBill(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"jsonStr" => request()->input("jsonStr")
			);
			return json_encode($this->model->editSRBill($params));
		}
	}
}
