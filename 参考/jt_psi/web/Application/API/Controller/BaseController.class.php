<?php

namespace API\Controller;

use API\Service\PSIApiBaseService;
use Think\Controller;
// use Think\Cache\Driver\Redis;

/**

 * API控制器基类
 *
 * @author taoyj
 * @package API\Controller
 *        
 */
class BaseController extends Controller
{
    const JSON_SUCCESS_STATUS = true;
    const JSON_ERROR_STATUS = false;

    /**
     * 获取当前用户 Id
     * @return mixed
     * @throws \think\exception\Exception
     */
    protected function getUserId()
    {
        $tokenId = I("tokenId");
        //缓存有效期（时间为秒） 是否适用缓存
        $cache = S(array('expire'=> 60 * 60 * 24 * 30));
        // $id = "444";
        // $cache->set("Goods_showgoods_goodsinfo{$id}","22222222");  
        // $goodsinfo= $cache->get("Goods_showgoods_goodsinfo{$id}");

        $token = $cache->get("tokenId_{$tokenId}");

        //$name = "tokenId_{$tokenId}";
		// unset($cache->$name); // 删除缓存
        // $token1 = $cache->get("tokenId_{$tokenId}");

		//$redis = new Redis();
		// $options = array();
		// $options['host'] = C('REDIS_HOST'); // ip  xxx.xxx.xxx.xxx 
		// $options['port'] = C('REDIS_PORT'); // 端口号 6379
 
		// $redis->connect('Redis',$options);
		// $redis->set('test2','hello world2!');
        // $fff =  $redis->get("test2");
      
        if ($tokenId == "" || $tokenId == null) {
            $this->ajaxReturn(array(
                "success" => false,
                "msg" => '缺少必要的参数：token'
            ));
        }
        $service = new PSIApiBaseService();
        if ($service->tokenIsInvalid($tokenId)) {
            header('Content-Type:application/json; charset=utf-8');
            $this->ajaxReturn(array(
                "success" => false,
                "msg" => "user not exit"
            ));
        }
        return $service->getUserIdFromTokenId($tokenId);
    }

    	/**
	 * 操作成功
	 */
	protected function ok($id = null) {
		if ($id) {
			return array(
					"success" => true,
					"id" => $id
			);
		} else {
			return array(
					"success" => true
			);
		}
	}

	/**
	 * 操作失败
	 *
	 * @param string $msg
	 *        	错误信息
	 */
	protected function bad($msg) {
		return array(
				"success" => false,
				"msg" => $msg
		);
	}


}