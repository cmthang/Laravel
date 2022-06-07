<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SceneAnalysis extends Model
{
    protected $table = 'scene_analysis';
    protected $fillable = [
        'user_email',
        'file_path',
        'file_name',
        'status',
        'output_folder',
        'scene_info',
        'project_path'
    ];

    public static function getByCondition($condition = [], $paginateFlag = TRUE, $user_email = null)
    {
        $limit = 50;
        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
        }

        $query = DB::table('scene_analysis AS sa')
            ->select('sa.id', 'sa.user_email', 'sa.file_path', 'sa.file_name', 'sa.status', 'sa.output_folder', 'sa.scene_info', 'sa.run_in_background', 'sa.project_path', 'sa.created_at', 'sa.updated_at','sa.history');
        
        if(!empty($user_email)){
            $query = $query->where('sa.user_email',$user_email);
        }
        
        if (isset($condition['keyword']) && $condition['keyword']) {
            $query = $query->whereRaw('sa.user_email LIKE ' . DB::connection()->getPdo()->quote('%' .$condition['keyword'] . '%') . ' OR sa.file_name LIKE ' . DB::connection()->getPdo()->quote('%' . $condition['keyword'] . '%'));
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
