<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupportSoftware extends Model
{
    protected $table = 'support_software';
    protected $fillable = [
        'software',
        'lable',
        'value',
        'order_version'
    ];

    public $timestamps = false;
    
    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 10;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }
        $query = DB::table('support_software AS ss')
            ->select('ss.*');
        
        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw(
                'ss.software LIKE "%' . 
                $condition['keyword'] . '%" OR ss.lable LIKE "%' . 
                $condition['keyword'] . '%"'. ' OR ss.value LIKE "%' . 
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
