<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MenuItems extends Model
{
    use HasFactory;
    protected $table = 't_menu_item';


    public static function getMainMenuItemTree($parent_id = null)
    {
        if(empty($parent_id)){
            $MenuItems = MenuItems::whereNull('parent_id')->get();
        } else {
            $MenuItems = MenuItems::where('parent_id',$parent_id)->get();
        }
        $arr = array();
        if (sizeof($MenuItems) !=0){
            foreach ($MenuItems as $k =>$datum) {
                $datum['children'] = self::getMainMenuItemTree($datum['id']);
                $arr[]=$datum;
            }
        }
        return $arr;
    }


    public static function getRecentFid($user_id)
    {
//        DB::enableQueryLog();
         return Recentfid::select(DB::RAW('distinct f.fid'),'f.name','t_recent_fid.click_count')
            ->join(DB::raw("(select * from t_fid union select * from t_fid_plus) f"), "t_recent_fid.fid","=","f.fid")
            ->join(DB::raw("(select * from t_permission union select * from t_permission_plus) p"),"t_recent_fid.fid","=","p.fid")
            ->join("t_role_permission","t_role_permission.permission_id","=","p.id")
            ->join("t_role_user","t_role_user.role_id","=","t_role_permission.role_id")
            ->where("t_recent_fid.user_id",$user_id)
            ->where("t_role_user.user_id",$user_id)
            ->orderBy("t_recent_fid.click_count","desc")
            ->limit(10)
            ->get()
            ->toArray();
//         return DB::getQueryLog();
    }
}
