<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomSystemEnv extends Model
{
    protected $table = 'custom_system_env';
    protected $fillable = [
        'name',
        'value',
        'note',
        'type'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE, $user_email = null)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('custom_system_env AS cse')
            ->select('cse.*');
        
        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('cse.name LIKE ' . DB::connection()->getPdo()->quote('%' .$condition['keyword'] . '%'));
        }

        if (isset($condition['order'])) {
            $query = $query->orderByRaw($condition['order']);
        } else {
            $query = $query->orderBy('id', 'DESC');
        }

        if ($paginateFlag === TRUE && $limit) {
            $query = $query->paginate($limit);
        } elseif ($paginateFlag == 'query') {
            return $query;
        }

        return $query->get();
    }
}
