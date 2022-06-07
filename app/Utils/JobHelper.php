<?php
namespace App\Utils;

use App\Models\RenderJob;
use App\User;
use Illuminate\Support\Arr;

class JobHelper
{
    /**
     * @param RenderJob $job
     * @param string $fieldName
     *
     * @return string
     */
    public static function getJobDetailField($job, $fieldName)
    {
        $text = '';

        $jobParam = json_decode($job->params, TRUE);

        if (isset($jobParam[$fieldName])) {
            $text = $jobParam[$fieldName];
        }

        return $text;
    }

    /**
     * @param RenderJob $job
     *
     * @return string
     */
    public static function getJobProgress($job)
    {
        $progressClass = '';
        $progressPercent = round($job->progress, 2);

        if ($job->status == 'completed') {
            $progressClass = 'progress-bar-green';
        } elseif ($job->status == 'paused') {
            $progressClass = 'progress-bar-warning';
        } elseif ($job->status == 'deleted') {
            $progressClass = 'progress-bar-red';
        } elseif ($job->status == 'in rendering') {
            $progressClass = 'progress-bar-aqua';
        } elseif ($job->status == 'submitted') {
            $progressClass = 'progress-bar-primary';
        } elseif ($job->status == 'failed') {
            $progressClass = 'progress-bar-maroon';
        }

        $html = '<span class="text-sm">'.$progressPercent . '%</span><div class="progress xs">
                    <div role="progressbar" aria-valuenow="'.$progressPercent.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progressPercent.'%" class="progress-bar '.$progressClass.'">
                        <span class="sr-only"></span>
                    </div>
                 </div>';

        return $html;
    }

    public static function getJobStatus($job)
    {
        $class = '';

        if ($job->status == 'completed') {
            $class = 'text-green';
        } elseif ($job->status == 'paused') {
            $class = 'text-yellow';
        } elseif ($job->status == 'deleted') {
            $class = 'text-red';
        } elseif ($job->status == 'rendering') {
            $class = 'text-aqua';
        } elseif ($job->status == 'submitted') {
            $class = 'text-primary';
        } elseif ($job->status == 'failed') {
            $class = 'text-maroon';
        }

        $html = '<i class="fa fa-circle text-sm '.$class.'"></i> ' . Common::getJobStatus(TRUE, $job->status);
        $html .= ' <a title="Edit Status" class="btn btn-xs btn-success btn-edit-job-status btn-edit-job-status-' . $job->id . '" href="javascript:void(0)" data-action="' . route('ajax.job.status', ['id' => $job->id]) . '"><i class="fa fa-pencil"></i></a>';

        return $html;
    }

    public static function buildContentActivity($activity,$image_server = false)
    {
        $urlToUser = route('user.detail', ['email' => $activity->email]);
        $userName = sprintf('%s (ID:%s)', $activity->user_name, $activity->user_id);
        $text = sprintf('<a href="%s">%s</a> %s', $urlToUser, $userName, $activity->activity_name);
        $time = Common::timeElapsedString($activity->created_at);
        if($image_server){
            $text =  $activity->activity_name;
            $time = $activity->created_at;
        }
        if ($activity->type == 9) {
            $note = $activity->activity_name;

            $adminUser = User::find($activity->admin_id);
            $strReplace = 'admin ' . sprintf('(%s)', $activity->admin_id);
            if ($adminUser) {
                $strReplace = sprintf('%s (ID: %s) ', $adminUser->name, $activity->admin_id);
            }

            $text = sprintf('<a href="%s">%s</a> ', $urlToUser, $userName);

            $note = str_replace('admin ', $strReplace, $note);
            $note = str_replace(' user', ' ' . $text, $note);

            $text = $note;
        }

        if ($activity->note && intval($activity->note) == 0) {
            $text .= '<br>' . $activity->note;
        }
        

        $text .=  ' <span class="text-muted small create-time-item">(' . $time . ')</span>';

        return $text;
    }

    /**
     * https://discordapp.com/developers/docs/reference#message-formatting
     *
     * @param $activity
     */
    public static function buildDiscordContent($activity)
    {
        $html = '';

        if (env('APP_ENV') !== 'production') {
            $html = '[TEST] ';
        }
        $adminUser = [];
        if(!empty($activity->admin_id)){
            $adminUser = User::find($activity->admin_id);
            $html = '(`'.$adminUser->email.'`) ';
        }

        $userName = sprintf('%s, %s (ID:%s)', $activity->user_name, $activity->email, $activity->user_id);
        $html .= sprintf('%s %s - %s', $userName, $activity->activity_name, $activity->created_at);

        if($activity->type == Constant::ACTIVITY_TYPE_REGISTER_FAIL){
            $html = sprintf('User Register Failed! - %s', $activity->created_at);
        }

        if ($activity->type == Constant::ACTIVITY_TYPE_SUBMIT_JOB) {
            $arr_note = explode("-",$activity->note);
            $jobId = $arr_note[0];
            $jobDetail = RenderJob::find($jobId);

            if ($jobDetail) {
                if ($jobDetail->render_preview == 1) {
                    $html = '[Preview]' . $html;
                }

                $jobParams = json_decode($jobDetail->params, TRUE);
                $additionalHtml = [];
                $additionalHtml[] = self::getJobDetailField($jobDetail, 'render_by');
                $additionalHtml[] = sprintf('ID: %s - %s', $jobId, $jobDetail->scene_name);
                if (Arr::get($jobParams, 'job_detail.frame_list')) {
                    $additionalHtml[] = 'frames: [' . Arr::get($jobParams, 'job_detail.frame_list') . ']';
                } else {
                    $additionalHtml[] = 'frames: [' . Arr::get($jobParams, 'job_detail.frames') . ':' . Arr::get($jobParams, 'job_detail.step_frame') . ']';
                }
                $additionalHtml[] = 'w-h: ' . Arr::get($jobParams, 'width') . '-' . Arr::get($jobParams, 'height');
                $additionalHtml[] = 'percentage: ' . Arr::get($jobParams, 'percentage');
                $additionalHtml[] = 'samples: ' . Arr::get($jobParams, 'samples');
                $additionalHtml[] = 'software: ' . Arr::get($jobParams, 'software') . ' ' . Arr::get($jobParams, 'version');
                $additionalHtml[] = 'engine: ' . Arr::get($jobParams, 'engine');
                $additionalHtml[] = 'machine type: ' . Arr::get($jobParams, 'job_detail.job_package_type');

                $html .= ' (' . implode(', ', $additionalHtml) . ')';
            }else{
                $html = sprintf('%s %s - %s (%s)', $userName, $activity->activity_name, $activity->note, $activity->created_at);
            }

        } elseif ($activity->type == Constant::ACTIVITY_TYPE_ADD_CREDIT) {
            $note = $activity->activity_name;
            $strReplace = 'admin ' . sprintf('(%s)', $activity->admin_id);
            if ($adminUser) {
                $strReplace = sprintf('%s (ID: %s) ', $adminUser->name, $activity->admin_id);
            }

            $note = str_replace('admin ', $strReplace, $note);
            $note = str_replace(' user', ' ' . $userName, $note);

            $html = sprintf('%s - %s', $note, $activity->created_at);
            $html .= sprintf('(Note: %s)', $activity->note);

        } elseif (!empty($activity->note)) {
            $html .= sprintf('(Note: %s)', $activity->note);
        }

        $webHookUrl = env('DISCORD_WEBHOOK_URL');
        if ($activity->type >= 1000) {
            $webHookUrl = env('DISCORD_WEBHOOK_URL_1000');
        } elseif ($activity->type == Constant::ACTIVITY_TYPE_PAYMENT || mb_strpos($activity->note, 'Manually create transaction') !== FALSE || mb_strpos($activity->note, 'payment ') !== FALSE
            || mb_strpos($activity->note, ' payment') !== FALSE) {
            $webHookUrl = env('DISCORD_WEBHOOK_URL_PAYMENT');
        } elseif($activity->type == 22 || $activity->type == 21){
            $webHookUrl = env('DISCORD_WEBHOOK_URL_DECKTOP');
        } elseif($activity->type == 28 || $activity->type == 29 || $activity->type == 30){
            $webHookUrl = env('DISCORD_WEBHOOK_URL_1000');
        } elseif($activity->type == 1 || $activity->type == 2 || $activity->type == 23){
            $webHookUrl = env('DISCORD_WEBHOOK_URL_REGISTER');
        }

        $timestamp = date('c', strtotime($activity->created_at));

        $json_data = json_encode([
            'content' => $html,
            'username' => env('APP_NAME'),
            'tts' => false,
            'embeds' => [
                [
                    'title' => 'User Detail',
                    'url' => route('user.detail', ['email' => $activity->email]),
                    'type' => 'rich',
                    'timestamp' => $timestamp,
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init($webHookUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        //curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($ch);
        // If you need to debug, or find out why you can't send message uncomment line below, and execute script.
        // echo $response;
        curl_close($ch);
    }
}