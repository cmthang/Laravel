<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PromotionCode;
use App\User;
use Illuminate\Support\Facades\DB;

class PaymentHistory extends Model
{
    protected $table = 'payment_history';
    protected $fillable = [
        'user_id',
        'credits',
        'promotion_code',
        'purchase_amount',
        'transaction_detail'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('payment_history AS ph')
            ->select('ph.user_id', 'ph.credits', 'ph.promotion_code', 'ph.purchase_amount', 'ph.transaction_detail',
                'ph.created_at', 'ph.status', 'ph.type', 'u.email', 'u.name')
            ->leftJoin('users AS u', 'ph.user_id', '=', 'u.id');

        if (isset($condition['trans_keyword']) && $condition['trans_keyword']) {
            $query = $query->whereRaw('ph.transaction_detail LIKE \'%' . $condition['trans_keyword'] . '%\'');
        }
        if (isset($condition['user_id']) && $condition['user_id']) {
            $query = $query->where('ph.user_id', $condition['user_id']);
        }
        if (isset($condition['status']) && $condition['status']) {
            $query = $query->where('ph.status', $condition['status']);
        }
        if (isset($condition['type']) && $condition['type']) {
            $query = $query->where('ph.type', $condition['type']);
        }

        if (isset($condition['order'])) {
            $query = $query->orderByRaw($condition['order']);
        } else {
            $query = $query->orderBy('ph.created_at', 'DESC');
        }

        if ($paginateFlag === TRUE && $limit) {
            return $query->paginate($limit);
        } elseif ($paginateFlag == 'query') {
            return $query;
        }

        return $query->get();
    }

    public static function getAccounting($condition, $paginateFlag = TRUE)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('payment_history AS ph')->where('status','success')
                ->select('u.email', 'u.name', 'u.credits', 'ph.user_id','ph.status', DB::raw('SUM(ph.credits) AS total_credits'), DB::raw('SUM(ph.purchase_amount) AS total_purchase'), 'ph.created_at')
                ->leftJoin('users AS u', 'ph.user_id', '=', 'u.id');

        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('u.email LIKE "%' . $condition['keyword'] . '%" OR u.name LIKE "%' . $condition['keyword'] . '%"'
                . ' OR u.id = "' . $condition['keyword'] . '"');
        }
        if (isset($condition['user_id']) && $condition['user_id']) {
            $query = $query->where('ph.user_id', $condition['user_id']);
        }
        if (isset($condition['status']) && $condition['status']) {
            $query = $query->where('ph.status', $condition['status']);
        }

        if (isset($condition['month']) && $condition['month']) {
            $fromDate = sprintf('%s-01 00:00:00', $condition['month']);
            $toDate = sprintf('%s 23:59:59', date('Y-m-t', strtotime($fromDate)));

            $query = $query->where('ph.created_at', '>=', $fromDate);
            $query = $query->where('ph.created_at', '<=', $toDate);
        }

        $query = $query->groupBy('user_id');

        if (isset($condition['order'])) {
            $query = $query->orderByRaw($condition['order']);
        } else {
            $query = $query->orderBy('ph.created_at', 'DESC');
        }

        if ($paginateFlag === TRUE && $limit) {
            $query = $query->paginate($limit);
        } elseif ($paginateFlag == 'query') {
            return $query;
        }

        return $query->get();
    }
}
