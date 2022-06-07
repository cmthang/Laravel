<?php
namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\PaymentHistory;
use App\Models\PromotionCode;
use App\Models\RenderJob;
use App\Models\SceneAnalysis;
use App\Models\UserActivity;
use App\Models\LinkAffiliate;
use App\Models\BlackListDomain;
use App\Models\WhileListDomain;
use App\Models\GroupDiscount;
use App\Models\UtmTracking;
use App\Models\SoftwarePackageType;
use App\Models\CustomSystemEnv;
use App\Models\EngineVersion;

//Them Models Support Software
use App\Models\SupportSoftware;

use App\User;
use App\Utils\Common;
use App\Utils\ConsoleClient;
use App\Utils\Constant;
use App\Utils\JobHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Jobs\JobSendMailGetFeedback;
use Request;
use DateTime;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin.auth');
    }

    public function jobList()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order', 2);
        $search = Request::input('search');

        $condition = [
            'filter-user-id' => Request::input('filter-user-id', ''),
            'filter-id' => Request::input('filter-id', ''),
            'filter-daterange' => Request::input('filter-daterange', ''),
            'filter-email' => Request::input('filter-email', ''),
            'filter-status' => Request::input('filter-status', ''),
            'filter-output-status' => Request::input('filter-output-status', ''),
            'filter-scene-name' => Request::input('filter-scene-name', ''),
            'filter-render-engine' => Request::input('filter-render-engine', ''),
            'filter-software' => Request::input('filter-software', ''),
        ];

        $queryCondition = [
            'job_id' => $condition['filter-id'],
            'user_id' => Request::input('user_id', ''),
            'email' => $condition['filter-email'],
            'status' => $condition['filter-status'],
            'output_status' => $condition['filter-output-status'],
            'scene_name' => $condition['filter-scene-name'],
            'engine' => $condition['filter-render-engine'],
            'software' => $condition['filter-software'],
        ];
        if($condition['filter-user-id']){
            $queryCondition['user_id'] = $condition['filter-user-id'];
        }

        if ($condition['filter-daterange']) {
            $dateRange = explode('-', $condition['filter-daterange']);

            $queryCondition['from_date'] = date('Y-m-d', strtotime($dateRange[0])) . ' 00:00:00';
            $queryCondition['to_date'] = date('Y-m-d', strtotime($dateRange[1])) . ' 23:59:59';
        }

        $orderField = '';
        if ($order[0]['column'] == 1) {
            $orderField = 'id';
        } elseif ($order[0]['column'] == 6) {
            $orderField = 'created_at';
        } elseif ($order[0]['column'] == 7) {
            $orderField = 'started_at';
        } elseif ($order[0]['column'] == 8) {
            $orderField = 'completed_at';
        } elseif ($order[0]['column'] == 9) {

        } elseif ($order[0]['column'] == 10) {
            $orderField = 'progress';
        } elseif ($order[0]['column'] == 11) {
            $orderField = 'status';
        } elseif ($order[0]['column'] == 12) {
            $orderField = 'cost';
        } elseif ($order[0]['column'] == 13) {
            $orderField = 'updated_at';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = $search['value'];
        }

        $renderJobListQuery = RenderJob::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $renderJobListQuery->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $renderJobList = $renderJobListQuery->skip($start)->take($length)->get();
        foreach ($renderJobList as $item) {
            $item->url_output = '';
            $err = 0;
            if (Common::checkUserRoleEnv('ROLE_BROWSE_OUTPUT')) {
                $item->url_output = sprintf('/ajax/browseOutput?type=image&user_email=%s&job_id=%s', $item->email, $item->id);
            }

            $itemParam = json_decode($item->params, TRUE);
            $mixedJobName = $item->id .' - '. $item->scene_name . ' [';
            if (Arr::get($itemParam, 'job_detail.frame_list')) {
                $mixedJobName .= Arr::get($itemParam, 'job_detail.frame_list');
            } else {
                $mixedJobName .= Arr::get($itemParam, 'job_detail.frames') .':'. Arr::get($itemParam, 'job_detail.step_frame');
            }
            $mixedJobName .=  ']';

            $resultDataJob = '<a href="'. route('job.detail', ['id' => $item->id]) .'" class="btn-view-job-detail">ID:'. $mixedJobName .'</a>'
                . (($item->render_preview) ? '<br><i>(Free Preview)</i>' : '') . '<br>'
                . $item->email . ' (ID:'.$item->user_id.') <a href="'. route('user.detail', ['email' => $item->email]) .'" target="_blank"><i class="fa fa-eye"></i></a>';

            $resultDataTmp = [
                'job' => $resultDataJob,
                'software' => Arr::get($itemParam, 'software'),
                'engine' => Arr::get($itemParam, 'engine'),
                'package' => Arr::get($itemParam, 'package'),
                'machine_type' => Arr::get($itemParam, 'job_detail.job_package_type'),
                'start_time' => date('Y-m-d H:i:s', strtotime($item->created_at)),
                'start_render_time' => $item->started_at ? date('Y-m-d H:i:s', strtotime($item->started_at)) : '',
                'complete_time' => $item->completed_at ? date('Y-m-d H:i:s', strtotime($item->completed_at)) : '',
                'render_by' => JobHelper::getJobDetailField($item, 'render_by'),
                'progress' => [
                    'display' => JobHelper::getJobProgress($item),
                    'value' => round($item->progress, 2),
                ],
                'status' => [
                    'display' => JobHelper::getJobStatus($item),
                    'value' => $item->status,
                ],
                'cost' => round($item->real_cost, 2).'$'.'<br>'.round($item->cost, 2).'$'.'<br>'.$item->discount.'%',
                'raw_detail' => json_encode($item),
                'updated_at' => date('Y-m-d H:i:s', strtotime($item->updated_at)),
                'region' => Arr::get($itemParam, 'job_detail.region') ?? '--',
                'err' => $item->err ?? 0,
                'browse' => [
                    'email' => $item->email,
                    'job_id' => $item->id
                ]
            ];

            $result['data'][] = $resultDataTmp;
        }

        return \Response::json($result);
    }

    public function userList()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'name';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'level';
        } elseif ($order[0]['column'] == 2) {
            $orderField = 'credits';
        } elseif ($order[0]['column'] == 3) {
            $orderField = 'total_credit';
        } elseif ($order[0]['column'] == 4) {
            $orderField = 'total_job';
        } elseif ($order[0]['column'] == 6) {
            $orderField = 'active';
        } elseif ($order[0]['column'] == 7) {
            $orderField = 'created_at';
        } elseif ($order[0]['column'] == 14) {
            $orderField = 'region';
        } elseif ($order[0]['column'] == 15) {
            $orderField = 'need_image_server';
        } elseif ($order[0]['column'] == 16) {
            $orderField = 'utm_link';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = $search['value'];
        }

        $userListQuery = User::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $userListQuery->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $userLevelArray = Common::getUserLevel();

        $userList = $userListQuery->skip($start)->take($length)->get();
        foreach ($userList as $item) {
            $actionHtml = '';
            if (Common::checkUserRoleEnv('ROLE_EDIT_LEVEL') && $item->active == Constant::STATUS_ACTIVE) {
                $actionHtml = '<a class="btn btn-xs btn-danger btn-active-user" title="Deactive User" data-action="' . route('ajax.user.active', ['id' => $item->id, 'type' => 'inactive']) . '"><i class="fa fa-refresh"></i></a>';
            } elseif (Common::checkUserRoleEnv('ROLE_EDIT_LEVEL') && $item->active == Constant::STATUS_INACTIVE) {
                $actionHtml = '<a class="btn btn-xs btn-success btn-active-user" title="Active User" data-action="' . route('ajax.user.active', ['id' => $item->id, 'type' => 'active']) . '"><i class="fa fa-refresh"></i></a>';
            }

            $result['data'][] = [
                'user_name' => '<span class="name-user">' . $item->name . '</span><br>'
                    . '<a href="' . route('user.detail', ['email' => $item->email]) . '"><b>' . $item->email . '</b></a>' . ' (ID:' . $item->id . ')',
                'level' => isset($userLevelArray[$item->level]) ? $userLevelArray[$item->level] : '',
                'credits' => round($item->credits, 2),
                'total_credit' => round($item->total_credit, 2),
                'total_job' => $item->total_job,
                'work_space_size' => $item->work_space_size,
                'send_mail_get_feedback' => '<a class="btn btn-xs '.(($item->send_feedback_email == 0) ? 'btn-warning' : 'btn-success').' btn-send-mail-get-feedback" title="Get Feedback" data-action="' . route('ajax.user.get-feedback', ['id' => $item->id]) . '"><i class="fa fa-envelope-o"></i></a>'  ,
                'request_more_infomation' => '<a class="btn btn-xs '.(($item->request_more_infomation == 0) ? 'btn-warning' : 'btn-success').' btn-request-more-infomation" title="Request More Infomation" data-action="' . route('ajax.requestMoreInfomation', ['id' => $item->id]) . '"><i class="fa fa-info-circle"></i></a>'  ,
                'active' => [
                    'display' => Common::getStatus($item->active) . ' ' . $actionHtml,
                    'value' => $item->active,
                ],
                'created_at' => $item->created_at ? date('Y-m-d H:i:s', strtotime($item->created_at)) : '',
                'hacker' => ($item->hacker == 1) ? '<i class="fa fa-check" aria-hidden="true"></i>' : '<i class="fa fa-times" aria-hidden="true"></i>',
                'country_code' => $item->country_code,
                'company' => $item->company,
                'note' => $item->note,
                'region' => $item->region,
                'need_image_server' => $item->need_image_server,
                'utm_link' => $item->utm_link
            ];
        }

        return \Response::json($result);
    }

    public function addCredits()
    {
        $user = Auth::guard('admin')->user();

        $amount = Request::input('credit', 0);
        $userId = Request::input('user_id');
        $note = Request::input('note', '');
        $addToPayment = Request::input('addToPayment', 'false');
        $purchaseAmount = Request::input('purchaseAmount', 0);
        $createdAt = Request::input('createdAt', NULL);
        $notification = Request::input('notification', 'false');

        $result = [
            'success' => FALSE,
            'message' => 'Something wrong.',
        ];

        $data = [
            'user_id' => $userId,
            'credit' => $amount,
            'note' => sprintf('%s ($%s)', $note, $amount),
            'admin_id' => $user->id,
            'add_to_payment' => FALSE,
            'purchase_amount' => 0,
            'created_at' => '',
            'notification' => FALSE
        ];

        if ($addToPayment == 'true') {
            $data['add_to_payment'] = TRUE;
            $data['purchase_amount'] = $purchaseAmount;
            $data['created_at'] = $createdAt;
        }

        if ($notification == 'true') {
            $data['notification'] = TRUE;
        }

        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->addCredits($data);

        if ($resultConsole) {
            $result['success'] = TRUE;
            $result['message'] = 'Success';
        }

        return \Response::json($result);
    }

    public function paymentHistory()
    {
        $user = Auth::guard('admin')->user();
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');

        $queryCondition = [
            'user_id' => Request::input('user_id', ''),
        ];

        $orderField = '';
        if ($order[0]['column'] == 2) {
            $orderField = 'credits';
        } elseif ($order[0]['column'] == 3) {
            $orderField = 'promotion_code';
        } elseif ($order[0]['column'] == 4) {
            $orderField = 'purchase_amount';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }
        if (isset($search['value'])) {
            $queryCondition['trans_keyword'] = $search['value'];
        }

        $renderJobListQuery = PaymentHistory::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $renderJobListQuery->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $renderJobList = $renderJobListQuery->skip($start)->take($length)->get();
        foreach ($renderJobList as $item) {
            $dataTemp = [
                'date' => $item->created_at ? date('Y-m-d H:i:s', strtotime($item->created_at)) : '',
                'order_number' => Arr::get(json_decode($item->transaction_detail, TRUE), 'id'),
                'credits' => round($item->credits, 2),
                'promotion_code' => $item->promotion_code,
                'status' => $item->status,
                'purchase_amount' => round($item->purchase_amount, 2),
                'transaction_detail' => '',
            ];

            if ($user->roles == Constant::USER_ROLE_SUPER_ADMIN) {
                $dataTemp['transaction_detail'] = json_decode($item->transaction_detail, TRUE);
            }

            $result['data'][] = $dataTemp;
        }

        return \Response::json($result);
    }

    public function accounting()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');

        $condition = [
            'month' => Request::input('month', ''),
        ];

        $queryCondition = [
            'month' => $condition['month'],
        ];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'user_id';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'email';
        }elseif ($order[0]['column'] == 2) {
            $orderField = 'credits';
        } elseif ($order[0]['column'] == 3) {
            $orderField = 'total_purchase';
        } elseif ($order[0]['column'] == 4) {
            $orderField = 'total_credits';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }
        if (isset($search['value'])) {
            $queryCondition['keyword'] = $search['value'];
        }

        $paymentHistories = PaymentHistory::getAccounting($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $paymentHistories->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $userPaid = [];
        $userExpense = [];

        $renderJobList = $paymentHistories->skip($start)->take($length)->get();
        foreach ($renderJobList as $item) {
            $userPaid[] = $item->user_id;
        }

        $userPaid = array_unique($userPaid);

        if ($userPaid) {
            $condition = [
                'user_id' => $userPaid,
                'from_date' => $condition['month'] . '-01',
                'to_date' => date('Y-m-t', strtotime($condition['month'] . '-01')),
            ];

            $renderJobListQuery = RenderJob::getByCondition($condition, 'query')->get();
            foreach ($renderJobListQuery as $item) {
                if (!isset($userExpense[$item->user_id])) {
                    $userExpense[$item->user_id] = 0;
                }

                $userExpense[$item->user_id] += $item->cost;
            }
        }

        foreach ($renderJobList as $item) {
            $expendCost = isset($userExpense[$item->user_id]) ? $userExpense[$item->user_id] : 0;

            $result['data'][] = [
                'user_id' => '<a href="'. route('user.detail', ['email' => $item->email]) . '">' . $item->user_id . '</a>',
                'email' => '<a href="'. route('user.detail', ['email' => $item->email]) . '">' . $item->email . '</a><br>' . $item->name,
                'remain_credits' => round($item->credits, 2),
                'total_credits' => round($item->total_credits, 2),
                'total_purchase' => round($item->total_purchase, 2),
                'expense' => $expendCost,
            ];
        }

        return \Response::json($result);
    }
    public function userExpense()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');

        $condition = [
            'month' => Request::input('month', ''),
        ];

        $queryCondition = [
            'month' => $condition['month'],
        ];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'user_id';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'email';
        }
        
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = $search['value'];
        }

        $queryCondition['from_date'] = $condition['month'] . '-01';
        $queryCondition['to_date'] = date('Y-m-t', strtotime($condition['month'] . '-01'));
        $queryCondition['group_by'] = 'user_id';

        $renderJobListQuery = RenderJob::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $renderJobListQuery->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $renderJobList = $renderJobListQuery->skip($start)->take($length)->get();

        foreach ($renderJobList as $item) {

            $result['data'][] = [
                'user_id' => '<a href="'. route('user.detail', ['email' => $item->email]) . '">' . $item->user_id . '</a>',
                'email' => '<a href="'. route('user.detail', ['email' => $item->email]) . '">' . $item->email . '</a><br>' . $item->name,
                'expense' => $item->expense,
            ];
        }

        return \Response::json($result);
    }

    public function exportAccounting()
    {
        $condition = [
            'month' => Request::input('month', ''),
        ];

        $queryCondition = [
            'month' => $condition['month'],
        ];

        $paymentHistories = PaymentHistory::getAccounting($queryCondition, 'query');

        $result = [];
        
        $result['data'] = [];

        $userPaid = [];
        $userExpense = [];

        $renderJobList = $paymentHistories->get();
        foreach ($renderJobList as $item) {
            $userPaid[] = $item->user_id;
        }

        $userPaid = array_unique($userPaid);

        if ($userPaid) {
            $condition = [
                'user_id' => $userPaid,
                'from_date' => $condition['month'] . '-01',
                'to_date' => date('Y-m-t', strtotime($condition['month'] . '-01')),
            ];

            $renderJobListQuery = RenderJob::getByCondition($condition, 'query')->get();
            foreach ($renderJobListQuery as $item) {
                if (!isset($userExpense[$item->user_id])) {
                    $userExpense[$item->user_id] = 0;
                }

                $userExpense[$item->user_id] += $item->cost;
            }
        }

        foreach ($renderJobList as $item) {
            $expendCost = isset($userExpense[$item->user_id]) ? $userExpense[$item->user_id] : 0;

            $result['data'][] = [
                'user_id' =>  $item->user_id,
                'email' => $item->email,
                'remain_credits' => round($item->credits, 2),
                'total_credits' => round($item->total_credits, 2),
                'total_purchase' => round($item->total_purchase, 2),
                'expense' => $expendCost,
            ];
        }
        $fileName = "accounting.csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('user_id', 'email','remain_credits','total_credits','total_purchase','expense');

        $callback = function() use($result, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($result['data'] as $item) {
                $row['user_id']  = $item['user_id'];
                $row['email']  = $item['email'];
                $row['remain_credits']  = $item['remain_credits'];
                $row['total_credits']  = $item['total_credits'];
                $row['total_purchase']  = $item['total_purchase'];
                $row['expense']  = $item['expense'];
                fputcsv($file, array($row['user_id'], $row['email'], $row['remain_credits'],$row['total_credits'],$row['total_purchase'],$row['expense']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportUserExpense()
    {
        $condition = [
            'month' => Request::input('month', ''),
        ];

        $queryCondition['from_date'] = $condition['month'] . '-01';
        $queryCondition['to_date'] = date('Y-m-t', strtotime($condition['month'] . '-01'));
        $queryCondition['group_by'] = 'user_id';

        $renderJobListQuery = RenderJob::getByCondition($queryCondition, 'query');
        $renderJobList = $renderJobListQuery->get();
        $result = [];
        
        $result['data'] = [];

        foreach ($renderJobList as $item) {

            $result['data'][] = [
                'user_id' =>  $item->user_id,
                'email' => $item->email,
                'expense' => $item->expense,
            ];
        }
        $fileName = "user_expense.csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('user_id', 'email','expense');

        $callback = function() use($result, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($result['data'] as $item) {
                $row['user_id']  = $item['user_id'];
                $row['email']  = $item['email'];
                $row['expense']  = $item['expense'];
                fputcsv($file, array($row['user_id'], $row['email'], $row['expense']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function activity()
    {
        $userId = Request::input('user_id', '');

        $condition = [];
        $condition['user_id'] = $userId;
        $userActivity = UserActivity::getByCondition($condition);
        $image_server = FALSE;

        return view('ajax.activity', compact('userActivity', 'condition', 'image_server'));
    }

    public function browseOutput()
    {
        $email = Request::input('user_email', '');
        $jobId = Request::input('job_id', '');
        $type = Request::input('type', 'list');

        $consoleClient = new ConsoleClient();
        $result = $consoleClient->browseOutput(['user_email' => $email, 'job_id' => $jobId]);

        if ($type == 'list') {
            $listObjects = [];
            if ($result) {
                $listObjects = $result['list_object'];
            }

            return view('ajax.browse_output', compact('listObjects'));

        } elseif ($type == 'image') {
            return file_get_contents($result['first_object_url']);
        }

        return '';
    }

    public function downloadOutput()
    {
        $key = Request::input('key', '');
        $email = Request::input('email', '');

        $consoleClient = new ConsoleClient();
        $consoleResult = $consoleClient->getUrlOutput(['user_email' => $email, 'key' => $key]);
        $keys = explode('/', $key);
        $fileName = array_pop($keys);
        header('Location: '.$consoleResult);
    }

    public function getLogTask()
    {
        $jobId = Request::input('id', '');
        $email = Request::input('email', '');
        $taskId = Request::input('taskId', '');
        
        $result = [
            'success' => FALSE,
            'message' => 'Something wrong.',
        ];

        try {
            $consoleClient = new ConsoleClient();
            $consoleResult = $consoleClient->getLogTask(['user_email' => $email, 'task_id' => $taskId, 'job_id' => $jobId]);

            if ($consoleResult['message']) {
                $result['success'] = TRUE;
                $result['message'] = 'Success';
                $result['task_log'] = str_replace("\n", '<br>', $consoleResult['task_log']);
            }
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
        }

        return \Response::json($result);
    }

    public function scene()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');
        $user_email = Request::input('user_email');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'user_email';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'created_at';
        } elseif ($order[0]['column'] == 2) {
            $orderField = 'updated_at';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }
        if (isset($search['value'])) {
            $queryCondition['keyword'] = $search['value'];
        }

        if(!empty($user_email)){
            $queryCondition['keyword'] = $user_email;
        }

        $sceneAnalysis = SceneAnalysis::getByCondition($queryCondition, 'query', $user_email);

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $sceneAnalysis->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $renderJobList = $sceneAnalysis->skip($start)->take($length)->get();
        foreach ($renderJobList as $item) {
            $created_at = date("Y-m-d H:i:s");
            $updated_at = date("Y-m-d H:i:s");
            $actions = '<a class="btn btn-sm btn-success" href="' . route('scene.detail', ['id' => $item->id]) . '"><i class="fa fa-desktop"></i> Detail</a>';
            $history = $item->history;
            $arr_history = [];
            $html_history = '';
            if(!empty($history)){
                $arr_history = explode(',',$history);
                foreach($arr_history as $it){
                    if(!empty($it)){
                        $html_history .= sprintf(' <a href="javascript:void(0)" class="btn-view-scene-analyze" data-action="%s" data-id="%s" data-email="%s" data-fname="%s" data-fpath="%s">%s</a>, ', route('scene.analyze'), $it,$item->user_email, $item->file_name, $item->file_path,$it);
                    }
                }
            }
            
            $actions .= sprintf(' <a href="javascript:void(0)" class="btn btn-sm btn-info btn-view-scene-analyze" data-action="%s" data-id="%s" data-email="%s" data-fname="%s" data-fpath="%s"><i class="fa fa-info-circle"></i> Info</a>', route('scene.analyze'),$item->output_folder, $item->user_email, $item->file_name, $item->file_path);

            $result['data'][] = [
                'scene_name' => $item->file_path.$item->file_name . '<br><a href="'. route('user.detail', ['email' => $item->user_email]) .'" target="_blank">' . $item->user_email . ' <i class="fa fa-eye"></i></a>',
                'status' => $item->status,
                'history' => $html_history,
                'created_at' => empty($item->created_at) ? $created_at : date('Y-m-d H:i:s', strtotime($item->created_at)),
                'completed_at' => empty($item->updated_at) ? $updated_at : date('Y-m-d H:i:s', strtotime($item->updated_at)),
                'actions' => $actions,
            ];
        }

        return \Response::json($result);
    }

    public function getListActivityAdminAddCredits()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');

        $userActivity = DB::table('user_activity AS ua')
        ->whereRaw('ua.user_id = ' . Request::input('user_id') . ' and ua.type = 9')
        ->select('ua.*', 'u.email AS admin_email')
        ->leftJoin('users AS u', 'ua.admin_id', '=', 'u.id')
        ->orderBy('created_at', 'DESC');

        if (isset($search['value']) && $search['value']) {
            $userActivity = $userActivity->whereRaw('ua.note LIKE ' . DB::connection()->getPdo()->quote('%' . $search['value'] . '%') . ' OR u.email LIKE ' . DB::connection()->getPdo()->quote('%' . $search['value'] . '%'));
        }
        
        if (isset($condition['order'])) {
            $userActivity = $userActivity->orderByRaw('created_at' . ' ' . $order[0]['dir']);
        } else {
            $userActivity = $userActivity->orderBy('created_at', 'DESC');
        }

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $userActivity->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $userActivitys = $userActivity->skip($start)->take($length)->get();
        foreach ($userActivitys as $item) {
            $item = (array) $item;
            $result['data'][] = [
                'name' => $item['admin_email'] .' just add credits for this user. Note: '.$item['note'],
                'created_at' => $item['created_at']
            ];
        }

        return \Response::json($result);
    }

    public function sceneAnalyze()
    {
        $folder_id = Request::input('folder_id');
        $email = Request::input('email');
        $fName = Request::input('fName');
        $fPath = Request::input('fPath');
        $fPath = empty($fPath) ? '' : $fPath;

        $consoleClient = new ConsoleClient();
        $consoleResult = $consoleClient->sceneAnalyze(['folder_id' => $folder_id, 'user_email' => $email, 'file_name' => $fName, 'file_path' => $fPath]);

        $result = [];
        if (isset($consoleResult['scene_info'])) {
            $tmpResult = [
                'id' => 'info',
                'title' => 'Info',
                'text' => '<pre>' . json_encode(json_decode($consoleResult['scene_info'], TRUE), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</pre>',
            ];

            $result[] = $tmpResult;
        }
        if (isset($consoleResult['log'])) {
            $tmpResult = [
                'id' => 'log',
                'title' => 'Log',
                'text' => '<p>' . nl2br(str_replace('\n', '<br>', $consoleResult['log'])) . '</p>',
            ];

            $result[] = $tmpResult;
        }
        if (isset($consoleResult['missing_asset'])) {
            if(!empty($consoleResult['missing_asset']) && $consoleResult['missing_asset'][0] === '['){
                $consoleResult['missing_asset'] = str_replace("'",'"',$consoleResult['missing_asset']);
                $consoleResult['missing_asset'] = \json_decode($consoleResult['missing_asset'],true);
                $consoleResult['missing_asset'] = '<p>' . implode("<br>", $consoleResult['missing_asset']) . '</p>';
            }else{
                $consoleResult['missing_asset'] = '<p>' . nl2br(str_replace('\n', '<br>', $consoleResult['missing_asset'])) . '</p>';
            }
            $tmpResult = [
                'id' => 'missing_asset',
                'title' => 'Missing Assets',
                'text' => $consoleResult['missing_asset'],
            ];

            $result[] = $tmpResult;
        }

        return view('index.scene_analyze', compact('result'));
    }

    public function editUserLevel($id)
    {
        $editUser = User::find($id);
        $userLevelArray = Common::getUserLevel();

        if (Request::getMethod() == 'POST' && Common::checkUserRoleEnv('ROLE_EDIT_LEVEL')) {
            $level = Request::input('level', '');

            $result = [];
            $result['success'] = FALSE;
            $result['message'] = 'Fail.';

            if (in_array($level, array_keys($userLevelArray))) {
                $data = [
                    'user_id' => $id,
                    'user_level' => $level,
                ];
                $consoleClient = new ConsoleClient();
                $consoleResult = $consoleClient->editUserLevel($data);

                if ($consoleResult == 200) {
                    $result['success'] = TRUE;
                    $result['message'] = 'Update success';
                    $result['new_level'] = $level;
                }
            }

            return \Response::json($result);
        }

        return view('ajax.edit_user_level', compact('editUser', 'userLevelArray'));
    }

    public function editUserRoles($id)
    {
        $editUser = User::find($id);
        $userRolesArray  = [0 => "User", 2 => "Admin", 1 => "Super Admin"];

        if (Request::getMethod() == 'POST' && Common::checkUserRoleEnv('ROLE_EDIT_LEVEL')) {
            $roles = Request::input('roles', '');

            $result = [];
            $result['success'] = FALSE;
            $result['message'] = 'Fail.';

            if (in_array($roles, array_keys($userRolesArray))) {
                $data = [
                    'user_id' => $id,
                    'roles' => $roles,
                ];
                $consoleClient = new ConsoleClient();
                $consoleResult = $consoleClient->editUserRoles($data);

                if ($consoleResult == 200) {
                    $result['success'] = TRUE;
                    $result['message'] = 'Update success';
                    $result['new_roles'] = $userRolesArray[$roles];
                }
            }

            return \Response::json($result);
        }

        return view('ajax.edit_user_roles', compact('editUser', 'userRolesArray'));
    }

    public function getCouponList()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order', 2);
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'code';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'status';
        } elseif ($order[0]['column'] == 2) {
            $orderField = 'coupon_type';
        } elseif ($order[0]['column'] == 3) {
            $orderField = 'valid_date_from';
        } elseif ($order[0]['column'] == 4) {
            $orderField = 'valid_date_to';
        } elseif ($order[0]['column'] == 5) {
            $orderField = 'value_type';
        } elseif ($order[0]['column'] == 6) {
            $orderField = 'promotion_value';
        } elseif ($order[0]['column'] == 8) {
            $orderField = 'created_at';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = trim($search['value']);
        }

        $renderJobListQuery = PromotionCode::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $renderJobListQuery->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $couponTypeArray = Common::getPromotionCouponType();
        $valueTypeArray = Common::getValueCouponType();
        $statusArray = Common::getStatus();

        $renderJobList = $renderJobListQuery->skip($start)->take($length)->get();
        foreach ($renderJobList as $item) {
            $actions = sprintf('<a class="btn btn-xs btn-success" href="%s"><i class="fa fa-pencil"></i> Edit</a>', route('promotion.coupon.edit', ['id' => $item->id]));
            $actions .= sprintf('<a class="btn btn-xs btn-danger btn-remove-coupon" href="javascript:void(0)" data-action="%s"><i class="fa fa-trash-o"></i> Delete</a>', route('promotion.coupon.delete', ['id' => $item->id]));

            $resultDataTmp = [
                'code' => $item->code,
                'status' => isset($statusArray[$item->status]) ? $statusArray[$item->status] : '',
                'coupon_type' => isset($couponTypeArray[$item->coupon_type]) ? $couponTypeArray[$item->coupon_type] : '',
                'valid_date_from' => $item->valid_date_from,
                'valid_date_to' => $item->valid_date_to,
                'value_type' => isset($valueTypeArray[$item->value_type]) ? $valueTypeArray[$item->value_type] : '',
                'promotion_value' => $item->promotion_value,
                'depend_payment_value' => $item->depend_payment_value,
                'created_at' => date('Y-m-d H:i:s', strtotime($item->created_at)),
                'actions' => $actions,
            ];

            $result['data'][] = $resultDataTmp;
        }

        return \Response::json($result);
    }

    public function getGiftList()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order', 2);
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'promotion_code';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'title';
        } elseif ($order[0]['column'] == 2) {
            $orderField = 'subtitle';
        } elseif ($order[0]['column'] == 3) {
            $orderField = 'value';
        } elseif ($order[0]['column'] == 4) {
            $orderField = 'type';
        } elseif ($order[0]['column'] == 5) {
            $orderField = 'active';
        } elseif ($order[0]['column'] == 6) {
            $orderField = 'valid_date_from';
        } elseif ($order[0]['column'] == 7) {
            $orderField = 'valid_date_to';
        } elseif ($order[0]['column'] == 9) {
            $orderField = 'created_at';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = trim($search['value']);
        }

        $giftListQuery = Gift::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $giftListQuery->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $couponTypeArray = Common::getPromotionCouponType();
        $valueTypeArray = Common::getValueCouponType();
        $statusArray = Common::getStatus();

        $giftList = $giftListQuery->skip($start)->take($length)->get();
        foreach ($giftList as $item) {
            $actions = sprintf('<a class="btn btn-xs btn-success" href="%s"><i class="fa fa-pencil"></i> Edit</a>', route('promotion.gift.edit', ['id' => $item->id]));
            $actions .= sprintf('<a class="btn btn-xs btn-danger btn-remove-gift" href="javascript:void(0)" data-action="%s"><i class="fa fa-trash-o"></i> Delete</a>', route('promotion.gift.delete', ['id' => $item->id]));

            $resultDataTmp = [
                'promotion_code' => $item->promotion_code,
                'title' => $item->title,
                'subtitle' => $item->subtitle,
                'value' => $item->value,
                'type' => $item->type,
                'active' => isset($statusArray[$item->active]) ? $statusArray[$item->active] : '',
                'valid_date_from' => $item->valid_date_from,
                'valid_date_to' => $item->valid_date_to,
                'condition' => $item->conditions,
                'created_at' => date('Y-m-d H:i:s', strtotime($item->created_at)),
                'actions' => $actions,
            ];

            $result['data'][] = $resultDataTmp;
        }

        return \Response::json($result);
    }

    public function changeJobAmount()
    {
        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Fail.';

        if (Request::getMethod() == 'GET' && Common::checkUserRoleEnv('ROLE_EDIT_JOB_AMOUNT')) {
            $jobId = Request::input('id');
            $amount = Request::input('amount');

            $data = [
                'job_id' => $jobId,
                'amount_machine' => intval($amount),
            ];
            $consoleClient = new ConsoleClient();
            $consoleResult = $consoleClient->updateJobAmount($data);

            if (isset($consoleResult['data']['code']) && $consoleResult['data']['code'] == 200) {
                $result['success'] = TRUE;
                $result['message'] = 'Update success';
            }
        }

        return \Response::json($result);
    }

    public function activeUser($id)
    {
        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Fail.';

        if (Request::getMethod() == 'POST' && Common::checkUserRoleEnv('ROLE_EDIT_LEVEL')) {
            $type = Request::input('type');

            $consoleClient = new ConsoleClient();

            if ($type == 'active') {
                $consoleResult = $consoleClient->activeUser($id);
            } else {
                $consoleResult = $consoleClient->deActiveUser($id);
            }

            if ($consoleResult == 200) {
                if ($type == 'active') {
                    $actionHtml = 'Active <a class="btn btn-xs btn-danger btn-active-user" title="Deactive User" data-action="' . route('ajax.user.active', ['id' => $id, 'type' => 'inactive']) . '"><i class="fa fa-refresh"></i></a>';
                } else {
                    $actionHtml = 'Inactive <a class="btn btn-xs btn-success btn-active-user" title="Active User" data-action="' . route('ajax.user.active', ['id' => $id, 'type' => 'active']) . '"><i class="fa fa-refresh"></i></a>';
                }

                $result['success'] = TRUE;
                $result['message'] = 'Update success';
                $result['html'] = $actionHtml;
            }
        }

        return \Response::json($result);
    }

    public function editJobStatus($id)
    {
        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Fail.';

        $job = RenderJob::find($id);

        if (Request::getMethod() == 'GET') {
            $jobStatus = Common::getJobStatus();

            return view('ajax.edit_job_status', compact('job', 'jobStatus'));

        } elseif (Request::getMethod() == 'POST') {
            $status = Request::input('status');
            $author = User::find($job->user_id);

            $job->status = $status;

            $consoleClient = new ConsoleClient();
            $consoleResult = $consoleClient->updateJobStatus(['job_id' => $id, 'status' => $status, 'user_email' => $author->email]);

            if ($consoleResult['success'] || $status == 'completed') {
                $result['success'] = TRUE;
                $result['message'] = 'Update success';
                $result['html'] = JobHelper::getJobStatus($job);
            } else {
                $result['message'] = $consoleResult['message'];
            }
        }

        return \Response::json($result);
    }

    public function userSearch()
    {
        $keyword = Request::input('keyword', '');

        $userListQuery = User::getByCondition(['keyword' => $keyword, 'limit' => 1000]);
        $userArray = [];
        foreach ($userListQuery as $item) {
            $userArray[] = [
                'id' => $item->id,
                'text' => sprintf('%s (%s)', $item->name, $item->email),
            ];
        }

        $result = [];
        $result['results'] = $userArray;
        $result['pagination'] = ['more' => FALSE];

        return \Response::json($result);
    }

    public function getMailFeedback($id){
        $user = User::find($id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserFeedback(['user_id' => $id]);
            // $user->send_feedback_email = 1;
            // $user->save();
            JobSendMailGetFeedback::dispatch($user);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function markUserAsHacker($id){
        $user = User::find($id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->markedHacker(['user_id' => $id]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function linkAffiliateList()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order', 2);
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'code';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'status';
        } elseif ($order[0]['column'] == 2) {
            $orderField = 'user_root_value';
        } elseif ($order[0]['column'] == 3) {
            $orderField = 'user_use_aff_value';
        } elseif ($order[0]['column'] == 4) {
            $orderField = 'number_of_uses';
        } elseif ($order[0]['column'] == 5) {
            $orderField = 'number_of_uses_per_user';
        } elseif ($order[0]['column'] == 6) {
            $orderField = 'note';
        } elseif ($order[0]['column'] == 7) {
            $orderField = 'created_at';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = trim($search['value']);
        }
        $linkAffiliateList = LinkAffiliate::getByCondition($queryCondition, 'query');
        $statusArray = Common::getStatus();
        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $linkAffiliateList->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $giftList = $linkAffiliateList->skip($start)->take($length)->get();
        foreach ($giftList as $item) {
            $actions = sprintf('<a class="btn btn-xs btn-success" href="%s"><i class="fa fa-pencil"></i> Edit</a>', route('promotion.affiliate_link.edit', ['id' => $item->id]));
            $actions .= sprintf('<a class="btn btn-xs btn-danger btn-remove-aff-link" href="javascript:void(0)" data-action="%s"><i class="fa fa-trash-o"></i> Delete</a>', route('promotion.affiliate_link.delete', ['id' => $item->id]));

            $resultDataTmp = [
                'code' => $item->code,
                'status' => isset($statusArray[$item->status]) ? $statusArray[$item->status] : '',
                'user_root_value' => $item->user_root_value,
                'user_use_aff_value' => $item->user_use_aff_value,
                'number_of_uses' => $item->number_of_uses,
                'number_of_uses_per_user' => $item->number_of_uses_per_user,
                'note' => $item->note,
                'created_at' => $item->created_at,
                'actions' => $actions
            ];

            $result['data'][] = $resultDataTmp;
        }

        return \Response::json($result);
    }

    public function addImageServers()
    {
        $zones = Request::input('zones', '');
        $size_storage = Request::input('size_storage', '');
        $data = [
            'zones' => $zones,
            'size' => $size_storage
        ];
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->updateImageServersZone($data);
        return \Response::json($resultConsole);
    }

    public function deleteImageServers()
    {
        $id = Request::input('id', '');
        $data = [
            'fs_id' => $id
        ];
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->deleteImageServersZone($data);
        return \Response::json($resultConsole);
    }

    public function getSoftwareVersion()
    {
        $sw = Request::input('software', '3dsmax');

        $software = ['3dsmax' => '3DSMAX','c4d' => 'C4D'];
        $software_version = [
            '3dsmax' => [
                2018 => "3DSMAX 2018",
                2019 => "3DSMAX 2019",
                2020 => "3DSMAX 2020",
                2021 => "3DSMAX 2021"
            ],
            'c4d' => [
                19 => "C4D 19",
                21 => "C4d 21",
                23 => "C4D 23"
            ]
        ];
        $engine_version = [
            '3dsmax' => [
                2018 => ["base" => "base","vray-3.6" => "vray-3.6"],
                2019 => ["base" => "base","corona-5" => "corona-5","corona-6" => "corona-6","vray-4.30.02" => "vray-4.30.02"],
                2020 => ["backup" => "backup","base" => "base","corona-5" => "corona-5","corona-6" => "corona-6","plugins" => "plugins","vray-5.00.05" => "vray-5.00.05","vray-5.10.02" => "vray-5.10.02"],
                2021 => ["backup" => "backup","base" => "base","corona-5" => "corona-5","corona-6" => "corona-6"]
            ],
            'c4d' => [
                19 => ["base" => "base"],
                21 => ["base" => "base"],
                23 => ["base" => "base"]
            ]
        ];

        $result = [];
        $result['software_version'] = $software_version[$sw];
        $result['engine_version'] = ($sw == '3dsmax') ? $engine_version[$sw][2018] : $engine_version[$sw][19];
        return \Response::json($result);
    }

    public function getEngineVersion()
    {
        $sw = Request::input('software', '3dsmax');
        $sw_v = Request::input('software_version', '2018');

        $software = ['3dsmax' => '3DSMAX','c4d' => 'C4D'];
        $software_version = [
            '3dsmax' => [
                2018 => "3DSMAX 2018",
                2019 => "3DSMAX 2019",
                2020 => "3DSMAX 2020",
                2021 => "3DSMAX 2021"
            ],
            'c4d' => [
                19 => "C4D 19",
                21 => "C4d 21",
                23 => "C4D 23"
            ]
        ];
        $engine_version = [
            '3dsmax' => [
                2018 => ["base" => "base","vray-3.6" => "vray-3.6"],
                2019 => ["base" => "base","corona-5" => "corona-5","corona-6" => "corona-6","vray-4.30.02" => "vray-4.30.02"],
                2020 => ["backup" => "backup","base" => "base","corona-5" => "corona-5","corona-6" => "corona-6","plugins" => "plugins","vray-5.00.05" => "vray-5.00.05","vray-5.10.02" => "vray-5.10.02"],
                2021 => ["backup" => "backup","base" => "base","corona-5" => "corona-5","corona-6" => "corona-6"]
            ],
            'c4d' => [
                19 => ["base" => "base"],
                21 => ["base" => "base"],
                23 => ["base" => "base"]
            ]
        ];

        $result = [];
        $result['engine_version'] = $engine_version[$sw][$sw_v];
        return \Response::json($result);
    }

    public function copyImageServer()
    {
        $sw = Request::input('software', '3dsmax');
        $sw_v = Request::input('software_version', '2018');
        $en_v = Request::input('engine_version', 'base');
        $endpoints = Request::input('endpoints', '');
        $zones  = Request::input('zones', '');
        $resultConsole = [];
        if(!empty($endpoints)){
            $data = [
                'from_folder' => $sw.'/'.$sw_v.'/'.$en_v,
                'endpoint' => '',
                'zone' => ''
            ];
            $consoleClient = new ConsoleClient();
            foreach($endpoints as $key => $value){
                $data['endpoint'] = $value;
                $data['zone'] = $zones[$key];
                $resultConsole = $consoleClient->copyImageServerToZone($data);
            }
            
        }
        return \Response::json($resultConsole);
    }

    public function requestMoreInfomation($id)
    {
        $user = User::find($id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->requestGetMoreInfo(['user_id' => $id]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function updateUserMultiAz($id)
    {
        $user = User::find($id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserMultiAzForUser(['user_id' => $id]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function exportUsers()
    {
        $active = Request::input('active', 0);
        $hacker = Request::input('hacker', 0);
        $register_from = Request::input('register_from', '');
        $register_to = Request::input('register_to', '');
        $last_activity_from = Request::input('last_activity_from', '');
        $last_activity_to = Request::input('last_activity_to', '');
        $total_payment_from = Request::input('total_payment_from', 0);
        $total_payment_to = Request::input('total_payment_to', 0);
        $total_job_from = Request::input('total_job_from', 0);
        $total_job_to = Request::input('total_job_to', 0);

        $query = DB::table('users AS u')
        ->select('u.id', 'u.name', 'u.email', 'u.level', 'u.credits', 'u.hacker','u.multi_az','u.request_more_infomation', 'u.work_space_size', 'u.email_verified_at',
            'u.active', 'u.has_uploaded', 'u.created_at','u.send_feedback_email',DB::raw('(select count(*) from render_job where render_job.user_id = u.id) as total_job'),DB::raw('(select sum(payment_history.purchase_amount) from payment_history where payment_history.user_id = u.id) as total_payment'))
        ->leftJoin('payment_history AS ph', 'u.id', '=', 'ph.user_id')
        ->leftJoin('render_job AS rj', 'u.id', '=', 'rj.user_id')
        ->where([
            ['u.active',$active],
            ['u.hacker',$hacker]
        ]);

        if(!empty($register_from) && !empty($register_to)){
            $query = $query->whereBetween('u.created_at', [$register_from." 00:00:00", $register_to." 23:59:59"]);
        }

        if(!empty($total_payment_from) && !empty($total_payment_to)){
            $query = $query->whereRaw('(select sum(payment_history.purchase_amount) from payment_history where payment_history.user_id = u.id) BETWEEN ? and ?',[$total_payment_from,$total_payment_to]);
        }

        if(!empty($total_job_from) && !empty($total_job_to)){
            $query = $query->whereRaw('(select count(*) from render_job where render_job.user_id = u.id) BETWEEN ? and ?',[$total_job_from,$total_job_to]);
        }

        if(!empty($last_activity_from) && !empty($last_activity_to)){
            $query = DB::table('users AS u')
            ->select('u.id', 'u.name', 'u.email','u.work_space_size')
            ->leftJoin('user_activity AS ua', 'u.id', '=', 'ua.user_id')
            ->whereNotBetween('ua.created_at', [$last_activity_from." 00:00:00", $last_activity_to." 23:59:59"])
            ->whereRaw('ua.created_at IN (select MAX(created_at) FROM user_activity GROUP BY user_id)')
            ->groupBy('u.id'); 
            $result = $query->get();
        }else{
            $result = $query->distinct()->get();
        }
        
        $fileName = "users.csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ID', 'Email','Name','Register_At','Credits','Total_Job','Total_payment','Work_space_size');

        $callback = function() use($result, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($result as $item) {
                $row['ID']  = $item->id;
                $row['Email']  = $item->email;
                $row['Name']  = $item->name;
                $row['Register_At']  = $item->created_at ?? '';
                $row['Credits']  = $item->credits ?? 0;
                $row['Total_Job']  = $item->total_job ?? 0;
                $row['Total_payment']  = $item->total_payment ?? 0;
                $row['Work_space_size']  = (isset($item->work_space_size) && !empty($item->work_space_size)) ? $item->work_space_size : 0;
                fputcsv($file, array($row['ID'], $row['Email'], $row['Name'],$row['Register_At'],$row['Credits'],$row['Total_Job'],$row['Total_payment'],$row['Work_space_size']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportUsersLastActivity(){
        $last_activity_from = Request::input('about-days', 0);
        $date = new DateTime();
        $last_activity_from = $date->modify('-'.$last_activity_from.' day')->format('Y-m-d H:i:s');
        $now = date('Y-m-d H:i:s');
        $query = DB::table('users AS u')
            ->select('u.id', 'u.name', 'u.email','u.work_space_size')
            ->leftJoin('user_activity AS ua', 'u.id', '=', 'ua.user_id')
            ->whereNotBetween('ua.created_at', [$last_activity_from." 00:00:00", $now])
            ->whereRaw('ua.created_at IN (select MAX(created_at) FROM user_activity GROUP BY user_id)')
            ->groupBy('u.id'); 
        $result = $query->get();

        return response()->json($result,200);
    }

    public function exportJobs(){
        $condition = [
            'filter-user-id' => Request::input('filter_user_id', ''),
            'filter-id' => Request::input('filter_id', ''),
            'filter-daterange' => Request::input('filter_daterange', ''),
            'filter-time-zone' => Request::input('filter_time_zone', ''),
            'filter-email' => Request::input('filter_email', ''),
            'filter-status' => Request::input('filter_status', ''),
            'filter-output-status' => Request::input('filter_output_status', ''),
            'filter-scene-name' => Request::input('filter_scene_name', ''),
        ];
        $queryCondition = [
            'job_id' => $condition['filter-id'],
            'user_id' => Request::input('user_id', ''),
            'email' => $condition['filter-email'],
            'status' => $condition['filter-status'],
            'output_status' => $condition['filter-output-status'],
            'scene_name' => $condition['filter-scene-name'],
        ];

        if($condition['filter-user-id'] && $condition['filter-user-id'] != "undefined"){
            $queryCondition['user_id'] = $condition['filter-user-id'];
        }

        if ($condition['filter-daterange'] && $condition['filter-daterange'] != "undefined") {
            $dateRange = explode('-', $condition['filter-daterange']);

            $queryCondition['from_date'] = date('Y-m-d', strtotime($dateRange[0])) . ' 00:00:00';
            $queryCondition['to_date'] = date('Y-m-d', strtotime($dateRange[1])) . ' 23:59:59';
        }
        $query = DB::table('render_job AS rj')
            ->select('rj.*', 'u.name', 'u.email')
            ->leftJoin('users AS u', 'rj.user_id', '=', 'u.id');

        if (isset($queryCondition['user_id']) && $queryCondition['user_id']) {
            if (is_array($queryCondition['user_id'])) {
                $query = $query->whereIn('rj.user_id', $queryCondition['user_id']);
            } else {
                $query = $query->where('rj.user_id', $queryCondition['user_id']);
            }
        }

        if (isset($queryCondition['email']) && $queryCondition['email']) {
            $query = $query->whereRaw('u.email LIKE "%' . $queryCondition['email'] . '%"');
        }

        if (isset($queryCondition['job_id']) && $queryCondition['job_id'] && $queryCondition['job_id'] != "undefined") {
            $query = $query->where('rj.id', $queryCondition['job_id']);
        }

        if (isset($queryCondition['from_date']) && $queryCondition['from_date']) {
            if(isset($condition['filter-time-zone']) && $condition['filter-time-zone']){
                $query = $query->whereRaw('DATE_FORMAT(CONVERT_TZ(rj.created_at, "+00:00","+'.$condition['filter-time-zone'].':00"), "%Y-%m-%d 00:00:00") >= STR_TO_DATE("'.$queryCondition['from_date'].'", "%Y-%m-%d 00:00:00")');
            }else{
                $query = $query->where('rj.created_at', '>=', $queryCondition['from_date']);
            }
        }

        if (isset($queryCondition['to_date']) && $queryCondition['to_date']) {
            if(isset($condition['filter-time-zone']) && $condition['filter-time-zone']){
                $query = $query->whereRaw('DATE_FORMAT(CONVERT_TZ(rj.created_at, "+00:00","+'.$condition['filter-time-zone'].':00"), "%Y-%m-%d 00:00:00") <= STR_TO_DATE("'.$queryCondition['to_date'].'", "%Y-%m-%d 23:59:59")');
            }else{
                $query = $query->where('rj.created_at', '<=', $queryCondition['to_date']);
            }
        }

        if (isset($queryCondition['status']) && $queryCondition['status'] && $queryCondition['status'] != "undefined") {
            if($queryCondition['status'] == 'Rendering'){
                $query = $query->where('rj.status', 'like', '%'.$queryCondition['status'].'%');
            }else{
                $query = $query->where('rj.status', $queryCondition['status']);
            }
        }
        if (isset($queryCondition['output_status']) && $queryCondition['output_status'] && $queryCondition['output_status'] != "undefined") {
            $query = $query->where('rj.output_status', $queryCondition['output_status']);
        }

        if (isset($queryCondition['scene_name']) && $queryCondition['scene_name'] && $queryCondition['scene_name'] != "undefined") {
            $query = $query->whereRaw('rj.scene_name LIKE "%' . $queryCondition['scene_name'] . '%"');
        }

        $result = $query->get();
        $fileName = "jobs.csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('User_Email','Job_ID','Scene_Name','Software','Engine','Package','Machine_Type','Render_By','Progress','Status','Cost','Created_At','Started_At','Completed_At');

        $callback = function() use($result, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($result as $item) {
                $row = [];
                $params = json_decode($item->params,true);
                $row['Job_ID']  = $item->id;
                $row['User_Email']  = $item->email;
                $row['Scene_Name']  = $item->scene_name;
                $row['Software']  = $params['software'].'-'.$params['version'];
                $row['Engine']  = (isset($params['engine'])) ? $params['engine'] : '';
                $row['Package']  = $params['package'];
                $row['Machine_Type']  = (isset($params['job_detail']['job_package_type'])) ? $params['job_detail']['job_package_type'] : '';
                $row['Render_By']  = $params['render_by'];
                $row['Progress']  = $item->progress;
                $row['Status']  = $item->status;
                $row['Cost']  = $item->cost;
                $row['Created_At']  = $item->created_at;
                $row['Started_At']  = $item->started_at;
                $row['Completed_At']  = $item->completed_at;

                fputcsv($file, array($row['User_Email'],$row['Job_ID'],$row['Scene_Name'],$row['Software'],$row['Engine'],$row['Package'],$row['Machine_Type'],$row['Render_By'],$row['Progress'],$row['Status'],$row['Cost'],$row['Created_At'],$row['Started_At'],$row['Completed_At']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateJobCost()
    {
        $user = Auth::guard('admin')->user();
        $job_id = Request::input('job_id', '');
        $cost = Request::input('cost', '');
        $note = Request::input('note', '');
        $job = RenderJob::find($job_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($job)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateJobCostF([
                'job_id' => $job_id,
                'cost' => $cost,
                'note' => $note,
                'admin_id' => $user->id
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function updateUserPreviewLimit($user_id)
    {
        $preview_limit = Request::input('preview_limit', '');
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserLimitPreview([
                'user_id' => $user_id,
                'preview_limit' => $preview_limit
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function updateAutoSyncAsset($id)
    {
        $user = User::find($id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserAutoSyncAsset(['user_id' => $id]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function getBlackDomain()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order', 2);
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'name';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = trim($search['value']);
        }
        $blackList = BlackListDomain::getByCondition($queryCondition, 'query');
        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $blackList->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $giftList = $blackList->skip($start)->take($length)->get();
        foreach ($giftList as $item) {
            $actions = sprintf('<a class="btn btn-xs btn-danger btn-remove-black-domain" href="javascript:void(0)" data-action="%s"><i class="fa fa-trash-o"></i> Delete</a>', route('ajax.deleteBlackDomain', ['id' => $item->id]));

            $resultDataTmp = [
                'name' => $item->name,
                'actions' => $actions
            ];

            $result['data'][] = $resultDataTmp;
        }

        return \Response::json($result);
    }
    public function getWhileDomain()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order', 2);
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'domain';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = trim($search['value']);
        }
        $linkAffiliateList = WhileListDomain::getByCondition($queryCondition, 'query');
        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $linkAffiliateList->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $giftList = $linkAffiliateList->skip($start)->take($length)->get();
        foreach ($giftList as $item) {
            $actions = sprintf('<a class="btn btn-xs btn-danger btn-remove-while-domain" href="javascript:void(0)" data-action="%s"><i class="fa fa-trash-o"></i> Delete</a>', route('ajax.deleteWhileDomain', ['id' => $item->id]));

            $resultDataTmp = [
                'name' => $item->domain,
                'actions' => $actions
            ];

            $result['data'][] = $resultDataTmp;
        }

        return \Response::json($result);
    }

    public function addWhileDomain()
    {
        $name = Request::input('name', '');
        $data = [
            'name' => $name,
            'type' => 'while'
        ];
        $result['success'] = FALSE;
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->addDomain($data);
        if($resultConsole == "done"){
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function addBlackDomain()
    {
        $name = Request::input('name', '');
        $data = [
            'name' => $name,
            'type' => 'black'
        ];
        $result['success'] = FALSE;
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->addDomain($data);
        if($resultConsole == "done"){
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function deleteWhileDomain($id)
    {
        $data = [
            'id' => $id,
            'type' => 'while'
        ];
        $result['success'] = FALSE;
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->deleteDomain($data);
        if($resultConsole == "done"){
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function deleteBlackDomain($id)
    {
        $data = [
            'id' => $id,
            'type' => 'black'
        ];
        $result['success'] = FALSE;
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->deleteDomain($data);
        if($resultConsole == "done"){
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function getGroupDiscount()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order', 2);
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'name';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = trim($search['value']);
        }
        $linkAffiliateList = GroupDiscount::getByCondition($queryCondition, 'query');
        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $linkAffiliateList->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $giftList = $linkAffiliateList->skip($start)->take($length)->get();
        foreach ($giftList as $item) {
            $actions = sprintf('<a class="btn btn-xs btn-success btn-edit-group-discount" href="%s"><i class="fa fa-pencil"></i> Edit</a>', route('index.editGroupDiscount', ['id' => $item->id]));
            $actions .= sprintf('<a class="btn btn-xs btn-danger btn-remove-group-discount" href="javascript:void(0)" data-action="%s"><i class="fa fa-trash-o"></i> Delete</a>', route('ajax.deleteGroupDiscount', ['id' => $item->id]));
            $resultDataTmp = [
                'name' => $item->name,
                'discount_cpu' => $item->discount_cpu,
                'discount_gpu' => $item->discount_gpu,
                'active' => ($item->active == 1) ? 'True' : 'False',
                'actions' => $actions
            ];

            $result['data'][] = $resultDataTmp;
        }

        return \Response::json($result);
    }

    public function updateGroupDiscount()
    {
        $id = Request::input('id', '');
        $name = Request::input('name', '');
        $discount_cpu = Request::input('discount_cpu', '');
        $discount_gpu = Request::input('discount_gpu', '');
        $date_from = Request::input('date_from', '');
        $date_to = Request::input('date_to', '');
        $active = Request::input('active', '');
        $data = [
            'name' => $name,
            'discount_cpu' => $discount_cpu,
            'discount_gpu' => $discount_gpu,
            'date_from' => date('Y-m-d', strtotime($date_from)) . ' 00:00:00',
            'date_to' => date('Y-m-d', strtotime($date_to)) . ' 23:59:59',
            'active' => ($active == true) ? 1 : 0
        ];
        $result['success'] = FALSE;
        $consoleClient = new ConsoleClient();
        if(empty($id)){
            $resultConsole = $consoleClient->addGroupDiscount($data);
        }else{
            $data['id'] = $id;
            $resultConsole = $consoleClient->editGroupDiscount($data);
        }
        
        if($resultConsole['status'] == 200){
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function deleteGroupDiscount($id)
    {
        $data = [
            'id' => $id
        ];
        $result['success'] = FALSE;
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->removeGroupDiscount($data);
        if($resultConsole['status'] == 200){
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function updateUserCompany($user_id)
    {
        $company = Request::input('company', '');
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserCompanyName([
                'user_id' => $user_id,
                'company' => $company
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function updateUserNote($user_id)
    {
        $note = Request::input('note', '');
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserNoteCL([
                'user_id' => $user_id,
                'note' => $note
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function updateUserCountry($user_id)
    {
        $country_code = Request::input('country_code', '');
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserCountryCode([
                'user_id' => $user_id,
                'country_code' => $country_code
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function utmList()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'id';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'email';
        } elseif ($order[0]['column'] == 2) {
            $orderField = 'ip';
        } elseif ($order[0]['column'] == 3) {
            $orderField = 'referrer_link';
        }elseif ($order[0]['column'] == 4) {
            $orderField = 'created_at';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }

        if (isset($search['value'])) {
            $queryCondition['keyword'] = $search['value'];
        }

        $utmListQuery = UtmTracking::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $utmListQuery->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $utmList = $utmListQuery->skip($start)->take($length)->get();
        foreach ($utmList as $item) {
            
            $result['data'][] = [
                'id' => $item->id,
                'email' => $item->email,
                'ip' => $item->ip,
                'country_code' => $item->country_code,
                'referrer_link' =>$item->referrer_link,
                'created_at' => $item->created_at ? date('Y-m-d H:i:s', strtotime($item->created_at)) : ''
            ];
        }

        return \Response::json($result);
    }

    public function updateUserStudent($user_id)
    {
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserStudentField([
                'user_id' => $user_id
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
            $result['data'] = $resultConsole;
        }
        return \Response::json($result);
    }

    public function updateUserDownloadDataset($user_id)
    {
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserDownloadDatasetField([
                'user_id' => $user_id
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
            $result['data'] = $resultConsole;
        }
        return \Response::json($result);
    }

    public function ovrUserLv($id)
    {
        $editUser = User::find($id);
        $userLevelArray = Common::getUserLevel();

        $level = Request::input('level', '');

        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Fail.';

        if (in_array($level, array_keys($userLevelArray))) {
            $data = [
                'user_id' => $id,
                'ovr_lv' => $level,
            ];
            $consoleClient = new ConsoleClient();
            $consoleResult = $consoleClient->overrideUserLv($data);

            if ($consoleResult == 200) {
                $result['success'] = TRUE;
                $result['message'] = 'Update success';
                $result['new_level'] = $level;
            }
        }

        return \Response::json($result);
    }

    public function updateJobRenderTime()
    {
        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Fail.';
        $jobId = Request::input('id');
        $time = Request::input('time');

        $data = [
            'job_id' => $jobId,
            'time' => intval($time),
        ];
        $consoleClient = new ConsoleClient();
        $consoleResult = $consoleClient->updateJobRenderTimes($data);
        if ($consoleResult == 200) {
            $result['success'] = TRUE;
            $result['message'] = 'Update success'; 
        }
        return \Response::json($result);
    }

    public function updateJobMachineType($jobId)
    {
        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Fail.';
        $job_package_type = Request::input('job_package_type');

        $data = [
            'job_id' => $jobId,
            'job_package_type' => $job_package_type,
        ];
        $consoleClient = new ConsoleClient();
        $consoleResult = $consoleClient->updateJobPackageType($data);
        if ($consoleResult == 200) {
            $result['success'] = TRUE;
            $result['message'] = 'Update success'; 
        }
        return \Response::json($result);
    }

    public function ListSoftwarePackageType()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'software';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'engine';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }
        if (isset($search['value'])) {
            $queryCondition['keyword'] = $search['value'];
        }

        $swp = SoftwarePackageType::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $swp->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $swpl = $swp->skip($start)->take($length)->get();
        foreach ($swpl as $item) {
            $actions = '<a class="btn btn-sm btn-success btn-update-dswpt" data-sw="'.$item->software.'" data-eg="'.$item->engine.'" data-type="'.$item->type.'"><i class="fa fa-pencil"></i></a>';
            
            $result['data'][] = [
                'software' => $item->software,
                'engine' => $item->engine,
                'package_type' => $item->package_type,
                'type' => $item->type,
                'default_package' => $item->default_package.$actions,
            ];
        }

        return \Response::json($result);
    }

    public function updateDefaultPackage(Request $request)
    {
        $data = [
            'sw' => Request::input('sw', ''),
            'eg' => Request::input('eg', ''),
            'type' => Request::input('type', ''),
            'default_package' => Request::input('default_package', '')
        ];
        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Fail.';

        $consoleClient = new ConsoleClient();
        $consoleResult = $consoleClient->updateDSWPT($data);
        if ($consoleResult == 200) {
            $result['success'] = TRUE;
            $result['message'] = 'Update success'; 
        }
        return \Response::json($result);
    }

    public function markOldUser($user_id)
    {
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->markAsOldUser([
                'user_id' => $user_id
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
            $result['data'] = $resultConsole;
        }
        return \Response::json($result);
    }

    public function updateRegion($user_id)
    {
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserRegion([
                'user_id' => $user_id,
                'region' => Request::input('region', '')
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
            $result['data'] = $resultConsole;
        }
        return \Response::json($result);
    }
    public function updateStatusRegion()
    {
        $data = [
            'region' => Request::input('region', ''),
            'status' => Request::input('status', '')
        ];
        $result = [];
        $result['success'] = FALSE;
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->updateSttRegion($data);
        $result['success'] = TRUE;
        $result['message'] = 'Send success';
        
        return \Response::json($result);
    }

    public function updateUserStatusColumn($user_id)
    {
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserSttColumn([
                'user_id' => $user_id,
                'column' => Request::input('column', '')
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
            $result['data'] = $resultConsole;
        }
        return \Response::json($result);
    }

    public function adminUpdateUserConfig($user_id)
    {
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateUserConfig([
                'user_id' => $user_id,
                'column' => Request::input('column', ''),
                'value' => Request::input('value', '')
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
            $result['data'] = $resultConsole;
        }
        return \Response::json($result);
    }
    public function notifyReloadDesktopApp($user_id)
    {
        $user = User::find($user_id);
        $result = [];
        $result['success'] = FALSE;
        if(!empty($user)){
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->notifyReloadUserApp([
                'user_id' => $user_id
            ]);
            $result['success'] = TRUE;
            $result['message'] = 'Send success';
        }
        return \Response::json($result);
    }

    public function systemEnvList()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'name';
        } elseif ($order[0]['column'] == 1) {
            $orderField = 'value';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }
        if (isset($search['value'])) {
            $queryCondition['keyword'] = $search['value'];
        }

        $swp = CustomSystemEnv::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $swp->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $ste = $swp->skip($start)->take($length)->get();
        foreach ($ste as $item) {
            $actions = '<a class="btn btn-sm btn-success btn-edit-cse" data-id="'.$item->id.'" ><i class="fa fa-pencil"></i></a>';
            
            $result['data'][] = [
                'name' => $item->name,
                'value' => $item->value,
                'note' => $item->note,
                'type' => $item->type,
                'action' => $actions,
            ];
        }

        return \Response::json($result);
    }

    public function forceSyncUserAccess($user_email)
    {   
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->forceSync([
            'user_email' => $user_email
        ]);
        $result['success'] = TRUE;
        $result['message'] = 'Send success';
        
        return \Response::json($result);
    }

    public function forceSyncUserOutput()
    {   
        $email = Request::input('email', '');
        $job_id = Request::input('job_id', '');
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->forceSyncOutput([
            'email' => $email,
            'job_id' => $job_id
        ]);
        $result['success'] = TRUE;
        $result['message'] = 'Send success';
        
        return \Response::json($result);
    }

    public function engineVersionList()
    {
        $draw = Request::input('draw', '');
        $length = Request::input('length', '');
        $start = Request::input('start', 0);
        $order = Request::input('order');
        $search = Request::input('search');

        $queryCondition = [];

        $orderField = '';
        if ($order[0]['column'] == 0) {
            $orderField = 'software';
        }
        if ($orderField) {
            $queryCondition['order'] = $orderField . ' ' . $order[0]['dir'];
        }
        if (isset($search['value'])) {
            $queryCondition['keyword'] = $search['value'];
        }

        $engine_version = EngineVersion::getByCondition($queryCondition, 'query');

        $result = [];
        $result['draw'] = $draw;
        $result['recordsTotal'] = $engine_version->get()->count();
        $result['recordsFiltered'] = $result['recordsTotal'];
        $result['data'] = [];

        $ste = $engine_version->skip($start)->take($length)->get();
        foreach ($ste as $item) {
            $actions = '<div><a class="btn btn-sm btn-success btn-edit-ev" data-id="'.$item->id.'" ><i class="fa fa-pencil"></i></a>
            <a class="btn btn-sm btn-danger btn-delete-ev" data-id="'.$item->id.'" >x</a></div>';
            $result['data'][] = [
                'software' => $item->software,
                'engine' => $item->engine,
                'engine_version' => $item->engine_version,
                'software_version' => $item->software_version,
                'default_version' => $item->default_version,
                'selected_vesion' => $item->selected_vesion,
                'action' => $actions
            ];
        }

        return \Response::json($result);
    }

    public function engineVersionDelete(){
        $id = Request::input('id', '');
        $result = [];
        $result['success'] = FALSE;
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->deleteEngineVersion([
            'engine_version_id' => $id
        ]);
        $result['success'] = TRUE;
        $result['message'] = 'Send success';
        
        return \Response::json($result);
    }

    public function deleteSoftwareBlenderByAPI($model){
        #model->delete()
        
//        curl_init()
    }

}