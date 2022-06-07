<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UtmTracking extends Model
{
    protected $table = 'utm_tracking';
    protected $fillable = [
        'id',
        'user_id',
        'ip',
        'referrer_link',
        'country_code'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }
        $query = DB::table('utm_tracking AS ut')
            ->select('ut.*', 'u.name', 'u.email');
        
        $query = $query->leftJoin('users AS u', 'ut.user_id', '=', 'u.id');

        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('u.email LIKE "%' . $condition['keyword'] . '%" OR ut.referrer_link LIKE "%' . $condition['keyword'] . '%"'
                . ' OR ut.id LIKE "%' . $condition['keyword'] . '%" OR ut.ip LIKE "%' . $condition['keyword'] . '%"');
        }

        if (isset($condition['order'])) {
            $query = $query->orderByRaw($condition['order']);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        if ($paginateFlag === TRUE && $limit) {
            $query = $query->paginate($limit);
        } elseif ($paginateFlag == 'query') {
            return $query;
        }

        return $query->get();
    }
}
