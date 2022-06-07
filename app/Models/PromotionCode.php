<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Helper;
use Illuminate\Support\Facades\DB;

class PromotionCode extends Model
{
    protected $table = 'promotion_code';
    protected $fillable = [
        'code',
        'status',
        'promotion_value',
        'coupon_type',
        'value_type',
        'valid_date_from',
        'valid_date_to',
        'depend_payment_value',
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

        $query = DB::table('promotion_code AS pc')
            ->select('pc.id', 'pc.code', 'pc.status', 'pc.coupon_type', 'pc.valid_date_from', 'pc.valid_date_to', 'pc.value_type', 'pc.promotion_value', 'pc.depend_payment_value', 'pc.note', 'pc.created_at');

        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('pc.code LIKE ' . DB::connection()->getPdo()->quote('%' . $condition['keyword'] .'%') . ' ');
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
