<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Base;
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public static function getCompanyId()
    {
        $result = null;
        $found = false;
        $org_id = DB::table('t_user')->where("id",'6C2A09CD-A129-11E4-9B6A-782BCBD7746B')->value('org_id');
        if(empty($org_id)){
            return $result;
        }
        while ( ! $found ) {
            $data = DB::table('t_org')->select('id','parent_id')->where('id',$org_id)->get()->toArray();
            if(count($data) > 0){
                $id =  $data[0]->id;
                $parent_id =  $data[0]->parent_id;
            } else {
                return $result;
            }
            $org_id = $parent_id;
            $result = $id;
            $found = $parent_id == null;
        }

        return $result;
    }

    public function getPrintUrl(){
        $base = new Base();
        $params = array(
            "loginUserId" => $base->getLoginUserId()
        );

        $loginUserId=$params["loginUserId"];
        $sql = "select org_id from t_user where (id = '".$loginUserId."') ";
        $userData=DB::select($sql);
        if($userData){
            $userData = json_decode(json_encode($userData),true);
            $sql = "select print_url from t_org where id='".$userData[0]["org_id"]."'";
            $orgList1 = DB::select($sql);
            if($orgList1){
                $orgList1 = json_decode(json_encode($orgList1),true);
                return $orgList1[0]["print_url"];
            }
            
        }        
        return "http://127.0.0.1:8000";
    }

    public function orgWithDataOrg() {
        
        $base = new Base();
        
        $loginUserId = $base->getLoginUserId();
        if ($base->loginUserIdNotExists($loginUserId)) {
            return [];
        }
        
        $sql = "select id, full_name
                from t_org ";
        
        $queryParams = array();
        $rs = $base->buildSQL("-8999-01", "t_org", $loginUserId);
        if ($rs) {
            $sql .= " where " . $rs[0];
            $queryParams = $rs[1];
        }
        
        $sql .= " order by full_name";
        
        $data = DB::select($sql, $queryParams);
        $data = json_decode(json_encode($data),true);
        $result = array();
        foreach ( $data as $i => $v ) {
            $result[$i]["id"] = $v["id"];
            $result[$i]["fullName"] = $v["full_name"];
        }
        
        return $result;
    }

    /**
     * 根据用户id查询用户名称
     *
     * @param string $userId
     *          用户id
     *          
     * @return string 用户姓名
     */
    public static function getLoginUserName($userId) {
        $sql = "select name from t_user where id = ? ";
        
        $data = DB::select($sql, [$userId]);
        
        if ($data) {
            $data = json_decode(json_encode($data),true);
            return $data[0]["name"];
        } else {
            return "";
        }
    }

    /**
     * 根据用户id查询用户
     *
     * @param string $id            
     * @return array|NULL
     */
    public function getUserById($id) {
        $sql = "select login_name, name from t_user where id = '".$id."' ";
        $data = DB::select($sql);
        if (! $data) {
            return null;
        }
        $data = json_decode(json_encode($data),true);
        return array(
                "loginName" => $data[0]["login_name"],
                "name" => $data[0]["name"]
        );
    }
    /**
     * 获得用户的组织机构是否需要业务审核
     *
     * @param string $userId            
     * @return string
     */
    public function getIsCheckBill($userId) {
        $orgId = $this->getLoginUserOrgId($userId);
        $sql = "select so_bill_check from t_org where id = ? ";
        
        $data = DB::select($sql, [$orgId]);
        
        if ($data) {
            $data = json_decode(json_encode($data),true);
            return $data[0]["so_bill_check"];
        } else {
            return "";
        }
    }
    public function getLoginUserOrgId($loginUserId) {
        
        $sql = "select org_id from t_user where id = ? ";
        $data = DB::select($sql, [$loginUserId]);
        
        if ($data) {
            $data = json_decode(json_encode($data),true);
            return $data[0]["org_id"];
        } else {
            return null;
        }
    }
}
