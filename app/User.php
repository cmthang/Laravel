<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable , SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'roles','level','address','credits','work_space_size', 'password','active', 'activation_token','has_uploaded','send_feedback_email','request_more_infomation','company','note','is_student','download_dataset','old_user','region','need_image_server','utm_link'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function checkAdmin($email, $roles)
    {
        return self::select('id', 'email', 'password')
            ->whereIn('roles', $roles)
            ->where('email',  '=', $email)->first();
    }

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('users AS u')
            ->select('u.id', 'u.utm_link', 'u.name', 'u.email', 'u.level', 'u.credits', 'u.hacker','u.multi_az','u.request_more_infomation', 'u.work_space_size', 'u.email_verified_at',
                'u.active', 'u.has_uploaded', 'u.created_at','u.send_feedback_email','u.preview_limit','u.auto_sync_asset','u.country_code','u.company','u.note', DB::raw('SUM(IF(rj.render_preview=0, rj.cost, 0)) AS total_credit'),'u.is_student','u.download_dataset','u.ovr_lv','u.old_user','u.region','u.need_image_server','u.user_config',
                DB::raw('COUNT(rj.id) AS total_job'))
            ->leftJoin('render_job AS rj', 'u.id', '=', 'rj.user_id');

        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('u.email LIKE "%' . $condition['keyword'] . '%" OR u.name LIKE "%' . $condition['keyword'] . '%" OR u.company LIKE "%' . $condition['keyword'] . '%" OR u.region LIKE "%' . $condition['keyword'] . '%" OR u.utm_link LIKE "%' . $condition['keyword'] . '%"');
        }

        if (isset($condition['user_id']) && $condition['user_id']) {
            $query = $query->where('u.id', $condition['user_id']);
        }

        if (isset($condition['order'])) {
            $query = $query->orderByRaw($condition['order']);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        $query = $query->groupBy('u.id');

        if ($paginateFlag === TRUE && $limit) {
            return $query->paginate($limit);
        } elseif ($paginateFlag == 'query') {
            return $query;
        }

        return $query->get();
    }
}
