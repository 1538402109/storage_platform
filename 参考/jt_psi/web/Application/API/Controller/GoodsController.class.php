<?php

namespace API\Controller;

use Think\Controller;
use API\Service\GoodsApiService;
/**
 * 客户资料 Controller
 *
 * @author JIATU
 *        
 */
class GoodsController extends BaseController {
	
	/**
     * @OA\Post(
     *   path="/Web/API/GoodsApi/queryDataWithSalePrice", 
     *   summary = "销售订单中获取商品列表", 
     *   description = "销售订单中获取商品列表", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Parameter(name = "queryKey", in = "query", @OA\Schema(type = "string"), required = true, description = "查询条件"),  
     *   @OA\Parameter(name = "customerId ", in = "query", @OA\Schema(type = "string"), required = true, description = "客户ID"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function queryDataWithSalePrice() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				"queryKey" => I("get.queryKey"),
			    "customerId" => I("get.customerId"),
				"loginUserId" => $userId,
			];

			$gs = new GoodsApiService();
			$this->ajaxReturn($gs->queryDataWithSalePrice($params));
		}
	}


	/**
     * @OA\Post(
     *   path="/Web/API/GoodsApi/queryGoodsSalePrice", 
     *   summary = "客户最后报价和商品列表", 
     *   description = "客户最后报价和商品列表", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function queryGoodsSalePrice() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				"loginUserId" => $userId,
				
			];

			$gs = new GoodsApiService();
			$this->ajaxReturn($gs->queryGoodsSalePrice($params));
		}
	}
		/**
     * @OA\Post(
     *   path="/Web/API/GoodsApi/queryGoodsSalePrice", 
     *   summary = "客户最后报价和商品列表", 
     *   description = "客户最后报价和商品列表", 
	 *   tags={"销售订单"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function queryGoodsSalePriceByCustomerId() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				"loginUserId" => $userId,
				"customerId" => I("get.cid"),
			];

			$gs = new GoodsApiService();
			$this->ajaxReturn($gs->queryGoodsSalePriceByCustomerId($params));
		}
	}
	/**
     * @OA\Post(
     *   path="/Web/API/GoodsApi/allCategories", 
     *   summary = "商品分类(一级)", 
     *   description = "商品分类", 
	 *   tags={"商品分类"}, 
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "token"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function allCategories() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				"queryKey" => I("get.queryKey"),
			    "customerId" => I("get.customerId"),
				"loginUserId" => $userId,
			];
			$gs = new GoodsApiService();
			$this->ajaxReturn($gs->allCategories($params));
		}
	}

}
		