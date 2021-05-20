<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MenuItems;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    //
    public function mainMenuItems()
    {
        $data = MenuItems::getMainMenuItemTree(null);
       return $data;
    }

    public function recentFid()
    {
        $user_id = "6C2A09CD-A129-11E4-9B6A-782BCBD7746B";
        $data = MenuItems::getRecentFid($user_id);
        return $data;
    }
}
