<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GroupDiscount extends Model
{
    protected $table = 'group_discount';
    protected $fillable = [
        'id',
        'name',
        'discount_cpu',
        'discount_gpu',
        'date_from',
        'date_to',
        'active'
    ];

    public $timestamps = false;

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('group_discount AS gd')
            ->select('gd.*');

        // if (isset($condition['keyword']) && $condition['keyword']) {
        //     $query = $query->whereRaw('g.code LIKE ' . DB::connection()->getPdo()->quote('%' . $condition['keyword'] .'%') . ' ');
        // }

        if (isset($condition['order'])) {
            $query = $query->orderByRaw($condition['order']);
        } else {
            $query = $query->orderBy('name', 'DESC');
        }

        if ($paginateFlag === TRUE && $limit) {
            $query = $query->paginate($limit);
        } elseif ($paginateFlag == 'query') {
            return $query;
        }

        return $query->get();
    }
}
