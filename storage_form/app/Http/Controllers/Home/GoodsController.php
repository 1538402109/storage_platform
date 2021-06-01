<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goods;

class GoodsController extends Controller
{
	public function __construct(Request $request)
    {   
		$this->model = new Goods();
    }

	/**
	 * 商品自定义字段，查询数据
	 */
	public function queryDataWithSalePrice(Request $request) {
		if ($request->isMethod('POST')) {
			$param = ['queryKey'=>request()->input("queryKey"),"customerId"=>"04B53C5E-B812-11E4-8FC9-782BCBD7746B"];
			$queryKey = request()->input("queryKey");
			// $customerId = request()->input("customerId");
			$customerId = "04B53C5E-B812-11E4-8FC9-782BCBD7746B";
			return json_encode($this->model->queryDataWithSalePrice($param));
		}
	}
}