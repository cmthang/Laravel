<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Helper;
use App\Jobs\UpdateJob;
use App\Jobs\SendMailUpdateJob;
use App\Models\Notifications;
use App\Events\UpdateNotifications;
use Illuminate\Support\Facades\DB;

class RenderJob extends Model
{
    protected $table = 'render_job';
    protected $fillable = [
        'user_id',
        'render_preview',
        'scene_name',
        'completed_at',
        'params',
        'folder_analyze_result',
        'time_rendering',
        'progress',
        'status',
        'output_status',
        'cost',
        'cost_estimation',
        'time_estimation',
        'preview_key',
        'actual_str',
        'output_folder_name',
        'discount',
        'real_cost'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }
        $query = DB::table('render_job AS rj')
            ->select('rj.*', 'u.name', 'u.email');
        if(isset($condition['group_by'])){
            $query = DB::table('render_job AS rj')
            ->select('rj.*', 'u.name', 'u.email',DB::raw('SUM(rj.cost) AS expense'));
        }
        
        $query = $query->leftJoin('users AS u', 'rj.user_id', '=', 'u.id');

        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('u.email LIKE "%' . $condition['keyword'] . '%" OR rj.scene_name LIKE "%' . $condition['keyword'] . '%"'
                . ' OR rj.id LIKE "%' . $condition['keyword'] . '%" OR rj.id LIKE "%' . $condition['keyword'] . '%"');
        }

        if (isset($condition['user_id']) && $condition['user_id']) {
            if (is_array($condition['user_id'])) {
                $query = $query->whereIn('rj.user_id', $condition['user_id']);
            } else {
                $query = $query->where('rj.user_id', $condition['user_id']);
            }
        }

        if (isset($condition['job_id']) && $condition['job_id']) {
            $query = $query->where('rj.id', $condition['job_id']);
        }

        if (isset($condition['from_date']) && $condition['from_date']) {
            $query = $query->where('rj.created_at', '>=', $condition['from_date']);
        }

        if (isset($condition['to_date']) && $condition['to_date']) {
            $query = $query->where('rj.created_at', '<=', $condition['to_date']);
        }

        if (isset($condition['status']) && $condition['status']) {
            if($condition['status'] == 'Rendering'){
                $query = $query->where('rj.status', 'like', '%'.$condition['status'].'%');
            }elseif($condition['status'] == 'Active'){
                $query = $query->where([
                    ['rj.status', '<>', 'deleted'],
                    ['rj.status', '<>', 'paused'],
                    ['rj.status', '<>', 'failed'],
                    ['rj.status', '<>', 'completed'],
                    ['rj.status', '<>', 'submitting'],
                ]);
            }else{
                $query = $query->where('rj.status', $condition['status']);
            }
        }
        if (isset($condition['output_status']) && $condition['output_status']) {
            $query = $query->where('rj.output_status', $condition['output_status']);
        }

        if (isset($condition['email']) && $condition['email']) {
            $query = $query->whereRaw('u.email LIKE "%' . $condition['email'] . '%"');
        }
        if (isset($condition['scene_name']) && $condition['scene_name']) {
            $query = $query->whereRaw('rj.scene_name LIKE "%' . $condition['scene_name'] . '%"');
        }

        if (isset($condition['group_by'])) {
            $query = $query->groupByRaw('rj.'.$condition['group_by']);
        } 

        if (isset($condition['engine']) && $condition['engine']) {
            $query = $query->where('rj.params->engine', $condition['engine']);
        }

        if (isset($condition['software']) && $condition['software']) {
            $query = $query->where('rj.software', $condition['software']);
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

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function render_task_details()
    {
        return $this->hasOne('App\RenderTaskDetail');
    }
}
