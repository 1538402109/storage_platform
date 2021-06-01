<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Common\FIdConst;
use App\Models\Srbill;
class SaleContractController extends Controller
{
	public function __construct(Request $request)
    {   
		$this->model = new Srbill();
    }
    //
    public function index()
    {
    	/*$this->assign("title", "销售合同");
			
		$this->assign("pCommit", 1);
		$this->assign("pGenSOBill", 1);
		
		$this->assign("pAdd", 1);
		$this->assign("pEdit", 1);
		$this->assign("pDelete", 1);
		$this->assign("pGenPDF", 1);
		$this->assign("pPrint", 1);*/
		//获取主题风格
		/*$interfaceStyle = $us->userUi();
		$style = $interfaceStyle['interface_style'];
		$this->assign('interfaceStyle',$style);
		$this->display();*/
		$data = array();
		$data['title'] = "销售合同";
		$data['pCommit'] = 1;
		$data['pGenSOBill'] = 1;
		$data['pAdd'] = 1;
		$data['pEdit'] = 1;
		$data['pDelete'] = 1;
		$data['pGenPDF'] = 1;
		$data['pPrint'] = 1;
		$data['loginUserName'] = "admin";
		$data['productionName'] = "PSI";
        return view('home/SaleContract/index',$data);
    }

    /**
     * @DateTime    2021-05-22
     * @description 销售合同列表
     * @param       Request    $request [description]
     * @return      [type]              [description]
     */
    public function scbillList(Request $request)
    {
    	if ($request->isMethod('POST')) {
            $input = request()->input();
            $params = [
					"billStatus" => isset($input['billStatus']) ? $input['billStatus'] : '',
					"ref" => isset($input['ref']) ? $input['ref'] : '',
					"fromDT" => isset($input['fromDT']) ? $input['fromDT'] : '',
					"toDT" => isset($input['toDT']) ? $input['toDT'] : '',
					"customerId" => isset($input['customerId']) ? $input['customerId'] : '',
					"goodsId" => isset($input['goodsId']) ? $input['goodsId'] : '',
					"start" => isset($input['start']) ? $input['start'] : '',
					"limit" => isset($input['limit']) ? $input['limit'] : ''
			];
			return json_encode($this->model->scbillList($params));
        }
    }

	/**
	 * 销售合同详情
	 */
	public function scBillInfo(Request $request) {
		if ($request->isMethod('POST')) {
			$params = [
					"id" => request()->input('id'),
					"loginUserId"=>'6C2A09CD-A129-11E4-9B6A-782BCBD7746B',
					"loginUserName"=>'admin'
			];
			return json_encode($this->model->scBillInfo($params));
		}
	}

	/**
	 * 新增或编辑销售合同
	 */
	public function editSCBill(Request $request) {
		if ($request->isMethod('POST')) {
			$json = request()->input('jsonStr');
			return json_encode($this->model->editSCBill($json));
		}
	}

	/**
	 * 销售合同商品明细
	 */
	public function scBillDetailList(Request $request) {
		if ($request->isMethod('POST')) {
			$params = [
					"id" => request()->input('id')
			];
			return json_encode($this->model->scBillDetailList($params));
		}
	}

	/**
	 * 审核销售合同
	 */
	public function commitSCBill(Request $request) {
		if ($request->isMethod('POST')) {
			$params = [
					"id" => request()->input('id')
			];
			
			return json_encode($this->model->commitSCBill($params));
		}
	}

	/**
	 * 删除销售合同
	 */
	public function deleteSCBill(Request $request) {
		if ($request->isMethod('POST')) {
			$params = [
					"id" => request()->input('id')
			];
			
			return json_encode($this->model->deleteSCBill($params));
		}
	}

	/**
	 * 取消审核销售合同
	 */
	public function cancelConfirmSCBill(Request $request) {
		if ($request->isMethod('POST')) {
			$params = [
					"id" => request()->input('id')
			];
			
			return json_encode($this->model->cancelConfirmSCBill($params));
		}
	}

	/**
	 * 销售合同生成pdf文件
	 */
	public function scBillPdf(Request $request) {
		$params = [
				"ref" => request()->input('ref')
		];
		
		return json_encode($this->model->pdf($params));
	}
}
