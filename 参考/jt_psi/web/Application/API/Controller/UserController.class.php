<?php

namespace API\Controller;

use Think\Controller;
use API\Service\UserApiService;

/**
 * 用户Controller
 *
 * @author JIATU
 *        
 */
class UserController extends BaseController {

	/**
     * @OA\Post(
     *   path="/Web/API/User/doLogin", 
     *   summary = "登录", 
     *   description = "登录接口",  
     *    tags={"用户"},
     *   @OA\Parameter(name = "loginName", in = "query", @OA\Schema(type = "string"), required = true, description = "用户名"),  
        *   @OA\Parameter(name = "password", in = "query", @OA\Schema(type = "string"), required = true, description = "密码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
	 */
	public function doLogin() {
		if (IS_POST) {
			$params = [
					"loginName" => I("post.loginName"),
					"password" => I("post.password")
			];
			$service = new UserApiService();
	     
			$this->ajaxReturn($service->doLogin($params));
		}
	}

	/**
	 * 验证token 是否有效
	 */
		/**
     * @OA\Get(
     *   path="/Web/API/User/token", 
     *   summary = "验证token", 
     *   description = "验证token是否有效",  
     *    tags={"用户"},
     *   @OA\Parameter(name = "loginName", in = "query", @OA\Schema(type = "string"), required = true, description = "用户名"),  
        *   @OA\Parameter(name = "password", in = "query", @OA\Schema(type = "string"), required = true, description = "密码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
	 */
	public function token() {
		if (IS_GET) {
			$tokenId = I("tokenId");
			$service = new UserApiService();
			$isvalid = $service->tokenIsInvalid($tokenId);
			if ($isvalid) {
				return	$this->ajaxReturn($this->bad("token is invalid"));
			}
 			$userId = $this-> getUserId();

			$result = $this->ok();
			$result["data"] = $service->recentFid($userId);

			return $this->ajaxReturn($result);
		}
	}


	/**
	 * 退出
	 */
		/**
     * @OA\Get(
     *   path="/Web/API/User/doLogout", 
     *   summary = "退出", 
     *   description = "退出",  
     *    tags={"用户"},
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "tokenId"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
	 */
	public function doLogout() {
		if (IS_POST) {
			$params = [
					"tokenId" => I("post.tokenId")
			];
			$service = new UserApiService();
			
			$this->ajaxReturn($service->doLogout($params));
		}
	}

	
	/**
	 * 获得演示环境下的提示信息
	 */
	public function getUserInfo() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$params = [
				"loginUserId" => $userId
			];
			$service = new UserApiService();
			$this->ajaxReturn($service->getUserInfo($params));
		}
	}

	/**
	 * 获得演示环境下的提示信息
	 */
	public function getDemoLoginInfo() {
		if (IS_POST) {
			$service = new UserApiService();
			$this->ajaxReturn($service->getDemoLoginInfo());
		}
	}

	/**
	 * 小程序常用功能接口列表
	 */
		/**
     * @OA\Get(
     *   path="/Web/API/User/menuItem", 
     *   summary = "小程序常用功能接口列表", 
     *   description = "小程序常用功能接口列表",  
     *    tags={"用户"},
     *   @OA\Parameter(name = "tokenId", in = "query", @OA\Schema(type = "string"), required = true, description = "tokenId"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
	 */
	public function menuItem() {
		if (IS_GET) {
			$userId = $this-> getUserId();
			$service = new UserApiService();

			$result = $this->ok();
			$result["data"] = $service->recentFid($userId);
			$this->ajaxReturn($result );
		}
	}



}