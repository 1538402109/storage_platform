<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
	public function __construct(Request $request)
    {   
		$this->model = new User();
    }
    /**
     * @name 修改密码
     *
     */
    public function changePassword()
    {
        return view('user/change-password');
    }

	/**
	 * 获取登录用户的打印服务地址
	 */
	public function getPrintUrl(Request $request){
		if ($request->isMethod('POST')) {
			return json_encode($this->model->getPrintUrl());
		}
	}

	/**
	 * 根据数据域返回可用的组织机构
	 */
	public function orgWithDataOrg(Request $request) {
		if ($request->isMethod('POST')) {
			return json_encode($this->model->orgWithDataOrg());
		}
	}
}
