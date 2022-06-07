<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenderTaskDetail extends Model
{
    protected $table = 'render_task_details';
    protected $fillable = [
        'job_id',
        'task_details',
        'total_progress',
        'total_task_time',
        'total_task_stt',
    ];

    public static function findByJobId($jobId)
    {
        $query = self::where('job_id', $jobId);

        return $query->first();
    }

    public static function mappingTaskDetail($task_details)
    {
        $results = [];
        $data = [];
        $total_task_pro = 0;
        $total_task_time = 0;
        $arr_stt = [
            1 => "Unknown",
            2 => "Queued",
            3 => "Paused",
            4 => "Rendering",
            5 => "Completed",
            6 => "Failed",
            8 => "Pending",
        ];

        if (!empty($task_details)) {
            foreach ($task_details as $item) {
                $progress = str_replace(' %', '', $item['Prog']);
                $total_task_pro += $progress;
                $total_task_time += $item['RenderTime'];
                $status = $arr_stt[$item['Stat']];
                array_push($data, [
                    'task_id' => $item['TaskID'],
                    'frames' => $item['Frames'],
                    'progress' => $progress,
                    'error' => $item['Errs'],
                    'time_start' => date($item['StartRen']),
                    'time_complete' => date($item['Comp']),
                    'status' => $status,
                ]);
            }
            $results['total_progress'] = $total_task_pro / count($task_details);
            $results['data'] = $data;
            $results['total_task_time'] = $total_task_time / 60;
        }
        return $results;
    }

    public function render_job()
    {
        return $this->hasOne('App\RenderJob');
    }
}
