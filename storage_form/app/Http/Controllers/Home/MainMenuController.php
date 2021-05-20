<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class MainMenuController extends Controller
{
    /**
     * @name  跳转指定路由
     * @param fid
      */

    public function navigateTo(Request $request)
    {
        $url_code = $request->route()->parameter('fid');
        switch($url_code){
            case Config::get("constants.CHANGE_PASSWORD"):
                // 修改我的密码
                return Redirect::to('user/changePassword');
                break;

        }
    }
}
