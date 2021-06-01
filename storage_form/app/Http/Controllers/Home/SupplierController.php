<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
	public function __construct(Request $request)
    {   
		$this->model = new Supplier();
    }

	/**
	 * 供应商自定义字段，查询数据
	 */
	public function queryData(Request $request) {
		if ($request->isMethod('POST')) {
			$queryKey = request()->input('queryKey');
			return json_encode($this->model->queryData($queryKey));
		}
	}

	/**
	 * 供应商分类
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