<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouses;

class WarehouseController extends Controller
{
	public function __construct(Request $request)
    {   
		$this->model = new Warehouses();
    }

	/**
	 * 仓库自定义字段，查询数据
	 */
	public function queryData(Request $request) {
		if ($request->isMethod('POST')) {
			$queryKey = request()->input('queryKey');
			$fid = request()->input('fid');
			return json_encode($this->model->queryData($queryKey, $fid));
		}
	}
}