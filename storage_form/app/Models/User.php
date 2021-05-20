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
}
