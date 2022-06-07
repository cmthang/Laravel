<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EngineVersion extends Model
{
    protected $table = 'engine_version';
    protected $fillable = [
        'software',
        'engine',
        'engine_version',
        'software_version',
        'default_version',
        'selected_vesion'
    ];

    public $timestamps = false;

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }
        $query = DB::table('engine_version AS ev')
            ->select('ev.*');
        
        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw(
                'ev.software LIKE "%' . 
                $condition['keyword'] . '%" OR ev.engine LIKE "%' . 
                $condition['keyword'] . '%"'. ' OR ev.engine_version LIKE "%' . 
                $condition['keyword'] . '%"');
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
