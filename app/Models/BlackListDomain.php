<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlackListDomain extends Model
{
    protected $table = 'disposable_email_domain';
    protected $fillable = [
        'id',
        'name'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 40;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('disposable_email_domain');
        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('name LIKE ' . DB::connection()->getPdo()->quote('%' . $condition['keyword'] .'%') . ' ');
        }
        if (isset($condition['order'])) {
            $query = $query->orderByRaw($condition['order']);
        } else {
            $query = $query->orderBy('id', 'DESC');
        }

        if ($paginateFlag === TRUE && $limit) {
            $query = $query->paginate($limit);

            return $query;
        }elseif ($paginateFlag == 'query') {
            return $query;
        }
        return $query->get();
    }
}
