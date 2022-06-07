<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserActivity extends Model
{
    protected $table = 'user_activity';
    protected $fillable = [
        'id',
        'user_id',
        'admin_id',
        'note',
        'type',
        'created_at'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 40;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('user_activity AS ua')
            ->select('ua.*', 'at.name AS activity_name', 'u.name AS user_name', 'u.email')
            ->leftJoin('activity_type AS at', 'ua.type', '=', 'at.id')
            ->leftJoin('users AS u', 'ua.user_id', '=', 'u.id');
            
        if (isset($condition['type']) && $condition['type']) {
            $query = $query->where('ua.type', $condition['type']);
        }

        if (isset($condition['id']) && $condition['id']) {
            $query = $query->where('ua.id', $condition['id']);
        }

        if (isset($condition['user_id']) && $condition['user_id']) {
            $query = $query->where('ua.user_id', $condition['user_id']);
        }

        if (isset($condition['order'])) {
            $query = $query->orderByRaw($condition['order']);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        if ($paginateFlag === TRUE && $limit) {
            $query = $query->paginate($limit);

            return $query;
        }

        return $query->get();
    }
}
