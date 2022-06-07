<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LinkAffiliate extends Model
{
    protected $table = 'link_affiliate';
    protected $fillable = [
        'code',
        'status',
        'user_root_value',
        'user_use_aff_value',
        'value_type',
        'number_of_uses',
        'number_of_uses_per_user',
        'note'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('link_affiliate AS l')
            ->select('l.id', 'l.code', 'l.status', 'l.user_root_value', 'l.user_use_aff_value', 'l.number_of_uses', 'l.note', 'l.number_of_uses_per_user', 'l.created_at');

        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('g.code LIKE ' . DB::connection()->getPdo()->quote('%' . $condition['keyword'] .'%') . ' ');
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
