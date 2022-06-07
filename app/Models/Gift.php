<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gift extends Model
{
    protected $table = 'gift';
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'promotion_code',
        'value',
        'type',
        'active',
        'valid_date_from',
        'valid_date_to',
        'conditions'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('gift AS g')
            ->select('g.id', 'g.title', 'g.subtitle', 'g.description', 'g.promotion_code', 'g.value', 'g.type',
                'g.active', 'g.valid_date_from', 'g.valid_date_to', 'g.conditions', 'g.created_at');

        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('g.promotion_code LIKE ' . DB::connection()->getPdo()->quote('%' . $condition['keyword'] .'%') . ' ');
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
