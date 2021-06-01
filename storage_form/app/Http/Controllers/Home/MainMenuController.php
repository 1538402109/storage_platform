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
            case '2032':
                //销售合同
                return Redirect::to('saleContract/index');
                break;
            case '2028' :
                // 销售订单
                return Redirect::to("Home/Sale/soIndex");
                break;
            case '2002' :
                // 销售出库
                return Redirect::to("/Home/Sale/wsIndex");
                break;
            case '2006' :
                // 销售退货入库
                return Redirect::to("/Home/Sale/srIndex");
                break;
            case '2004' :
                // 应收账款管理
                return Redirect::to("/Home/Funds/rvIndex");
                break;
            case "2005" :
                // 物流应付账款管理
                return Redirect::to("/Home/Funds/diPayIndex");
                break;
            case "2024" :
                // 现金收支查询
                return Redirect::to("/Home/Funds/cashIndex");
                break;
            case "2025" :
                // 预收款管理
                return Redirect::to("/Home/Funds/prereceivingIndex");
                break;
            case "2026" :
                // 预付款管理
                return Redirect::to("/Home/Funds/prepaymentIndex");
                break;
        }
    }
}
