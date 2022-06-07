<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SoftwarePackageType extends Model
{
    protected $table = 'software_package_type';
    protected $fillable = [
        'software',
        'engine',
        'package_type',
        'type',
        'default_package'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE, $user_email = null)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('software_package_type AS sa')
            ->select('sa.*');
        
        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('sa.software LIKE ' . DB::connection()->getPdo()->quote('%' .$condition['keyword'] . '%') . ' OR sa.engine LIKE ' . DB::connection()->getPdo()->quote('%' . $condition['keyword'] . '%'));
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
