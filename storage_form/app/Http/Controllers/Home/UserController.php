<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @name 修改密码
     *
     */
    public function changePassword()
    {
        return view('user/change-password');
    }
}
