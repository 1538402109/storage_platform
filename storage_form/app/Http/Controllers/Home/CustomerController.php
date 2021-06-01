<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Common\FIdConst;
use App\Models\Customer;
class CustomerController extends Controller
{
	public function __construct(Request $request)
    {   
		$this->model = new Customer();
    }

	/**
	 * 客户自定义字段，查询客户
	 */
	public function queryData(Request $request) {
		if ($request->isMethod('POST')) {
			$params = array(
					"queryKey" => request()->input("queryKey")
			);
			return json_encode($this->model->queryData($params));
		}
	}

	/**
	 * 获得客户分类列表
	 */
	public function categoryList(Request $request) {
		if ($request->isMethod('POST')) {
			$input = request()->input();
			$params = array(
					"code" => isset($input['code']) ? $input['code'] : '',
					"name" => isset($input['name']) ? $input['name'] : '',
					"address" => isset($input['address']) ? $input['address'] : '',
					"contact" => isset($input['contact']) ? $input['contact'] : '',
					"mobile" => isset($input['mobile']) ? $input['mobile'] : '',
					"tel" => isset($input['tel']) ? $input['tel'] : '',
					"qq" => isset($input['qq']) ? $input['qq'] : '',
			);
			
			return json_encode($this->model->categoryList($params));
		}
	}
}