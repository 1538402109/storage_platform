<?php

namespace API\Controller;

use API\Service\EncodeApiService;
use API\Service\UserAppApiService;

/**
 * 测试
 */
class ServiceTestController{
    public function getToken(){
        $res="10000";
        $codeNumRes=sprintf('%03s', $res);
        $code="CH123456123";
        echo substr($code,0,(mb_strlen($code)-3)).$codeNumRes;
    }
}