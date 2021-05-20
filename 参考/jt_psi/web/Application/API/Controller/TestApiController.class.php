<?php

namespace API\Controller; 

use Think\Controller; 
use API\Service\TestService;
/**
 * @OA\Info(title="PSI接口", version="0.1")
 * @author taoys
 *        
 */


class TestApiController extends Controller {


     /**
     * @OA\Get(
     *   path="/Web/API/TestApi/testApiGet", 
     *   summary = "测试Get接口", 
     *   tags={"测试接口"},
     *   description = "API测试Get接口",  
     *   @OA\Parameter(name = "userId", in = "query", @OA\Schema(type = "string"), required = true, description = "用户ID"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */
	public function testApiGet(int $userId) {
     	$params = [
               "userId" =>  $userId,
             
     ];
          $service = new TestService();
         $this->ajaxReturn($service->doTest($params));
      
	}

    /**
     * @OA\Post(
     *   path="/Web/API/TestApi/testApiPost", 
     *   summary = "测试Post接口", 
     *   description = "API测试Post接口",  
     *    tags={"测试接口"},
     *   @OA\Parameter(name = "loginName", in = "query", @OA\Schema(type = "string"), required = true, description = "用户名"),  
        *   @OA\Parameter(name = "password", in = "query", @OA\Schema(type = "string"), required = true, description = "密码"),  
     *   @OA\Response(  response = "200",   description = "返回json"  )  ) 
     */public function testApiPost() {
          $params = [
               "loginName" => I("post.loginName"),
               "password" => I("post.password")
     ];
     $service = new TestService();
     $this->ajaxReturn($service->doTest($params));
	}

}