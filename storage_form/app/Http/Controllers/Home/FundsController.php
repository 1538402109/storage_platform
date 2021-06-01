<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Funds;
use App\Models\Payables;
use App\Models\User;
use App\Models\Receivables;
use App\Models\Cash;
use App\Models\PreReceiving;
use App\Models\PrePayment;

class FundsController extends Controller
{
	public function __construct(Request $request)
    {   
		$this->model = new Funds();
		$this->Payables = new Payables();
		$this->Receivables = new Receivables();
		$this->Cash = new Cash();
		$this->PreReceiving = new PreReceiving();
		$this->PrePayment = new PrePayment();
    }


	/**
	 * 应付账款管理 - 主页面
	 */
	public function payIndex(Request $request) {
		$data = array();
		$data["title"] = "应付账款管理";
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";
		
        return view('home/Funds/payIndex',$data);
	}

	/**
	 * 应付账款，查询往来单位分类
	 */
	public function payCategoryList(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->Payables->payCategoryList($params));
		}
	}

	/**
	 * 应付账款，总账
	 */
	public function payList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"caType" => isset($input['caType']) ? $input['caType'] : '',
					"categoryId" => isset($input['categoryId']) ? $input['categoryId'] : '',
					"supplierId" => isset($input['supplierId']) ? $input['supplierId'] : '',
					"customerId" => isset($input['customerId']) ? $input['customerId'] : '',
					"factoryId" => isset($input['factoryId']) ? $input['factoryId'] : '',
					"page" => isset($input['page']) ? $input['page'] : '',
					"start" => isset($input['start']) ? $input['start'] : '',
					"limit" => isset($input['limit']) ? $input['limit'] : '',
			);

			return json_encode($this->Payables->payList($params));
		}
	}

	/**
	 * 应付账款，明细账
	 */
	public function payDetailList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"caType" => isset($input['caType']) ? $input['caType'] : '',
					"caId" => isset($input['caId']) ? $input['caId'] : '',
					"page" => isset($input['page']) ? $input['page'] : '',
					"start" => isset($input['start']) ? $input['start'] : '',
					"limit" => isset($input['limit']) ? $input['limit'] : ''
			);
			return json_encode($this->Payables->payDetailList($params));
		}
	}

	/**
	 * 应付账款，付款记录
	 */
	public function payRecordList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"refType" => isset($input['refType']) ? $input['refType'] : '',
					"refNumber" => isset($input['refNumber']) ? $input['refNumber'] : '',
					"page" => isset($input['page']) ? $input['page'] : '',
					"start" => isset($input['start']) ? $input['start'] : '',
					"limit" => isset($input['limit']) ? $input['limit'] : ''
			);
			return json_encode($this->Payables->payRecordList($params));
		}
	}

	/**
	 * 应付账款，付款时候查询信息
	 */
	public function payRecInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$us = new User();
			
			return json_encode(
					array(
							"bizUserId" => $this->getLoginUserId(),
							"bizUserName" => $us->getLoginUserName($this->getLoginUserId())
					));
		}
	}

	/**
	 * 应付账款，新增付款记录
	 */
	public function addPayment(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"refType" => isset($input['refType']) ? $input['refType'] : '',
					"refNumber" => isset($input['refNumber']) ? $input['refNumber'] : '',
					"bizDT" => isset($input['bizDT']) ? $input['bizDT'] : '',
					"actMoney" => isset($input['actMoney']) ? $input['actMoney'] : '',
					"bizUserId" => isset($input['bizUserId']) ? $input['bizUserId'] : '',
					"remark" => isset($input['remark']) ? $input['remark'] : ''
			);
			return json_encode($this->Payables->addPayment($params));
		}
	}

	/**
	 * 刷新应付账款总账信息
	 */
	public function refreshPayInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->Payables->refreshPayInfo($params));
		}
	}

	/**
	 * 刷新应付账款明细账信息
	 */
	public function refreshPayDetailInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->Payables->refreshPayDetailInfo($params));
		}
	}

	/**
	 * 应收账款管理 - 主页面
	 */
	public function rvIndex(Request $request) {
		$data = array();
		$data["title"] = "应收账款管理";
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";
		
        return view('home/Funds/rvIndex',$data);
	}

	/**
	 * 获得应收账款往来单位的分类
	 */
	public function rvCategoryList(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->Receivables->rvCategoryList($params));
		}
	}

	/**
	 * 应收账款，总账
	 */
	public function rvList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"caType" => isset($input['caType']) ? $input['caType'] : '',
					"categoryId" => isset($input['categoryId']) ? $input['categoryId'] : '',
					"customerId" => isset($input['customerId']) ? $input['customerId'] : '',
					"supplierId" => isset($input['supplierId']) ? $input['supplierId'] : '',
					"page" => isset($input['page']) ? $input['page'] : '',
					"start" => isset($input['start']) ? $input['start'] : '',
					"limit" => isset($input['limit']) ? $input['limit'] : ''
			);
			return json_encode($this->Receivables->rvList($params));
		}
	}

	/**
	 * 应收账款明细（多条件）
	 */
	public function rvDetailList2(){
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"caType" => isset($input['caType']) ? $input['caType'] : '',
					"categoryId" => isset($input['categoryId']) ? $input['categoryId'] : '',
					"caId" => isset($input['caId']) ? $input['caId'] : '',
					"page" => isset($input['page']) ? $input['page'] : '',
					"start" => isset($input['start']) ? $input['start'] : '',
					"limit" => isset($input['limit']) ? $input['limit'] : '',
					"startDate"=>isset($input['startDate']) ? $input['startDate'] : '',
					"endDate"=>isset($input['endDate']) ? $input['endDate'] : '',
					"code"=>isset($input['code']) ? $input['code'] : '',
					"CollectType"=>isset($input['CollectType']) ? $input['CollectType'] : '',
					"bizUser"=>isset($input['bizUser']) ? $input['bizUser'] : ''
			);
			return json_encode($this->Receivables->rvDetailList2($params));
		}
	}

	/**
	 * 应收账款，明细账
	 */
	public function rvDetailList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"caType" => isset($input['caType']) ? $input['caType'] : "",
					"caId" => isset($input['caId']) ? $input['caId'] : '',
					"page" => isset($input['page']) ? $input['page'] : '',
					"start" => isset($input['start']) ? $input['start'] : '',
					"limit" => isset($input['limit']) ? $input['limit'] : ''
			);
			return json_encode($this->Receivables->rvDetailList($params));
		}
	}
	/**
	 * 将某条物流代收记录转为记应收账款
	 */
	public function changeReceivable(Request $request){
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->Receivables->changeReceivable($params));
		}
	}

	/**
	 * 应收账款，收款记录
	 */
	public function rvRecordList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"refType" => isset($input['refType']) ? $input['refType'] : "",
					"refNumber" => isset($input['refNumber']) ? $input['refNumber'] : "",
					"page" => isset($input['page']) ? $input['page'] : "",
					"start" => isset($input['start']) ? $input['start'] : "",
					"limit" => isset($input['limit']) ? $input['limit'] : ""
			);
			return json_encode($this->Receivables->rvRecordList($params));
		}
	}

	/**
	 * 应收账款收款时候，查询信息
	 */
	public function rvRecInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$us = new User();
			
			return json_encode(
					array(
							"bizUserId" => $this->getLoginUserId(),
							"bizUserName" => $us->getLoginUserName($this->getLoginUserId())
					));
		}
	}

	/**
	 * 记录收款记录
	 */
	public function addRvRecord(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"refType" => isset($input['refType']) ? $input['refType'] : "",
					"refNumber" => isset($input['refNumber']) ? $input['refNumber'] : "",
					"bizDT" => isset($input['bizDT']) ? $input['bizDT'] : "",
					"actMoney" => isset($input['actMoney']) ? $input['actMoney'] : "",
					"bizUserId" => isset($input['bizUserId']) ? $input['bizUserId'] : "",
					"remark" => isset($input['remark']) ? $input['remark'] : ""
			);
			return json_encode($this->Receivables->addRvRecord($params));
		}
	}

	/**
	 * 刷新应收账款总账信息
	 */
	public function refreshRvInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->Receivables->refreshRvInfo($params));
		}
	}

	/**
	 * 刷新应收账款明细账信息
	 */
	public function refreshRvDetailInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"id" => request()->input('id')
			);
			return json_encode($this->Receivables->refreshRvDetailInfo($params));
		}
	}

	/**
	 * 现金收支查询 - 主页面
	 */
	public function cashIndex(Request $request) {
		$data = array();
		$data["title"] = "现金收支查询";
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";
		
        return view('home/Funds/cashIndex',$data);
	}

	/**
	 * 现金收支，总账
	 */
	public function cashList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"dtFrom" => isset($input['dtFrom']) ? $input['dtFrom'] : "",
					"dtTo" => isset($input['dtTo']) ? $input['dtTo'] : "",
					"page" => isset($input['page']) ? $input['page'] : "",
					"start" => isset($input['start']) ? $input['start'] : "",
					"limit" => isset($input['limit']) ? $input['limit'] : ""
			);
			return json_encode($this->Cash->cashList($params));
		}
	}

	/**
	 * 现金收支，明细账
	 */
	public function cashDetailList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"bizDT" => isset($input['bizDT']) ? $input['bizDT'] : "",
					"page" => isset($input['page']) ? $input['page'] : "",
					"start" => isset($input['start']) ? $input['start'] : "",
					"limit" => isset($input['limit']) ? $input['limit'] : ""
			);
			return json_encode($this->Cash->cashDetailList($params));
		}
	}

	/**
	 * 预收款管理
	 */
	public function prereceivingIndex(Request $request) {
		$data = array();
		$data["title"] = "预收款管理";
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";
		
        return view('home/Funds/prereceivingIndex',$data);
	}

	/**
	 * 收取预收款时候，查询信息
	 */
	public function addPreReceivingInfo(Request $request) {
		if ($request->isMethod('POST')) {
			return json_encode($this->PreReceiving->addPreReceivingInfo());
		}
	}

	/**
	 * 退回预收款时候，查询信息
	 */
	public function returnPreReceivingInfo(Request $request) {
		if ($request->isMethod('POST')) {
			return json_encode($this->PreReceiving->returnPreReceivingInfo());
		}
	}

	/**
	 * 收取预收款
	 */
	public function addPreReceiving(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"customerId" => isset($input['customerId']) ? $input['customerId'] : "",
					"bizUserId" => isset($input['bizUserId']) ? $input['bizUserId'] : "",
					"bizDT" => isset($input['bizDT']) ? $input['bizDT'] : "",
					"inMoney" => isset($input['inMoney']) ? $input['inMoney'] : ""
			);
			
			return json_encode($this->PreReceiving->addPreReceiving($params));
		}
	}

	/**
	 * 退回预收款
	 */
	public function returnPreReceiving(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"customerId" => isset($input['customerId']) ? $input['customerId'] : "",
					"bizUserId" => isset($input['bizUserId']) ? $input['bizUserId'] : "",
					"bizDT" => isset($input['bizDT']) ? $input['bizDT'] : "",
					"outMoney" => isset($input['outMoney']) ? $input['outMoney'] : ""
			);
			
			return json_encode($this->PreReceiving->returnPreReceiving($params));
		}
	}

	/**
	 * 预收款，总账
	 */
	public function prereceivingList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"categoryId" => isset($input['categoryId']) ? $input['categoryId'] : "",
					"customerId" => isset($input['customerId']) ? $input['customerId'] : "",
					"page" => isset($input['page']) ? $input['page'] : "",
					"start" => isset($input['start']) ? $input['start'] : "",
					"limit" => isset($input['limit']) ? $input['limit'] : ""
			);
			
			return json_encode($this->PreReceiving->prereceivingList($params));
		}
	}

	/**
	 * 预收款，明细账
	 */
	public function prereceivingDetailList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"customerId" => isset($input['customerId']) ? $input['customerId'] : "",
					"dtFrom" => isset($input['dtFrom']) ? $input['dtFrom'] : "",
					"dtTo" => isset($input['dtTo']) ? $input['dtTo'] : "",
					"page" => isset($input['page']) ? $input['page'] : "",
					"start" => isset($input['start']) ? $input['start'] : "",
					"limit" => isset($input['limit']) ? $input['limit'] : ""
			);
			
			return json_encode($this->PreReceiving->prereceivingDetailList($params));
		}
	}

	/**
	 * 预付款管理
	 */
	public function prepaymentIndex(Request $request) {
		$data = array();
		$data["title"] = "预付款管理";
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";
		
        return view('home/Funds/prepaymentIndex',$data);
	}

	/**
	 * 付预付款时候，查询信息
	 */
	public function addPrePaymentInfo(Request $request) {
		if ($request->isMethod('POST')) {
			return json_encode($this->PrePayment->addPrePaymentInfo());
		}
	}

	/**
	 * 付预付款
	 */
	public function addPrePayment(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"supplierId" => isset($input['supplierId']) ? $input['supplierId'] : "",
					"bizUserId" => isset($input['bizUserId']) ? $input['bizUserId'] : "",
					"bizDT" => isset($input['bizDT']) ? $input['bizDT'] : "",
					"inMoney" => isset($input['inMoney']) ? $input['inMoney'] : ""
			);
			
			return json_encode($this->PrePayment->addPrePayment($params));
		}
	}

	/**
	 * 预付款，总账
	 */
	public function prepaymentList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"categoryId" => isset($input['categoryId']) ? $input['categoryId'] : "",
					"supplierId" => isset($input['supplierId']) ? $input['supplierId'] : "",
					"page" => isset($input['page']) ? $input['page'] : "",
					"start" => isset($input['start']) ? $input['start'] : "",
					"limit" => isset($input['limit']) ? $input['limit'] : ""
			);
			
			return json_encode($this->PrePayment->prepaymentList($params));
		}
	}

	/**
	 * 预付款，明细账
	 */
	public function prepaymentDetailList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"supplierId" => isset($input['supplierId']) ? $input['supplierId'] : "",
					"dtFrom" => isset($input['dtFrom']) ? $input['dtFrom'] : "",
					"dtTo" => isset($input['dtTo']) ? $input['dtTo'] : "",
					"start" => isset($input['start']) ? $input['start'] : "",
					"limit" => isset($input['limit']) ? $input['limit'] : ""
			);
			
			return json_encode($this->PrePayment->prepaymentDetailList($params));
		}
	}

	/**
	 * 返回预付款时候，查询信息
	 */
	public function returnPrePaymentInfo(Request $request) {
		if ($request->isMethod('POST')) {
			return json_encode($this->PrePayment->returnPrePaymentInfo());
		}
	}

	/**
	 * 供应商返回预付款
	 */
	public function returnPrePayment(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"supplierId" => isset($input['supplierId']) ? $input['supplierId'] : "",
					"bizUserId" => isset($input['bizUserId']) ? $input['bizUserId'] : "",
					"bizDT" => isset($input['bizDT']) ? $input['bizDT'] : "",
					"inMoney" => isset($input['inMoney']) ? $input['inMoney'] : ""
			);
			
			return json_encode($this->PrePayment->returnPrePayment($params));
		}
	}

	/**
	 * 应收账款明细管理 - 主页面
	 */
	public function detailIndex(Request $request) {
		$data = array();
		$data["title"] = "应收账款明细管理";
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";
		
        return view('home/Funds/detailIndex',$data);
	}

	/**
	 * 获取组织机构代码
	 */
	public function getOrgCode(){
		return json_encode( $this->Receivables->getOrgCode());
	}

	/**
	 * 物流应收账款
	 */
	public function diRvIndex(Request $request) {
		$data = array();
		$data["title"] = "物流应收账款";
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";
		
        return view('home/Funds/diRvIndex',$data);
	}

	/**
	 * 物流应付账款
	 */
	public function diPayIndex(Request $request) {
		$data = array();
		$data["title"] = "物流应付账款";
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
		
        return view('home/Funds/diPayIndex',$data);
	}
}