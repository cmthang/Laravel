<?php
namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\RenderJob;
use App\Models\SceneAnalysis;
use App\Models\UserActivity;
use App\Models\ImageServer;
use App\Models\GroupDiscount;
use App\Models\Region;
use App\Models\CustomSystemEnv;
use App\Models\EngineVersion;
use App\User;
use Redirect;
use Illuminate\Http\Request;
use App\Utils\ConsoleClient;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin.auth');
    }

    public function index(Request $request)
    {
        $userActivity = UserActivity::getByCondition();
        $image_server = false;
        return view('index.index', compact('userActivity','image_server'));
    }

    public function user(Request $request)
    {
        return view('index.user', compact($request->user()));
    }

    public function userDetail(Request $request)
    {
        $email = $request->get('email', '');

        if ($email) {
            $user = User::where('email', $email)->first();
            $user_config = $user['user_config'];
            if(!empty($user_config)){
                $user_config = \json_decode($user_config,true);
                $user['chunksize'] = (isset($user_config['chunksize']) && ($user_config['chunksize'] === true || $user_config['chunksize'] === "true")) ? 1 : 0;
                $user['chunksize_val'] = $user_config['chunksize_val'] ?? 0;
                $user['error_checking'] = $user_config['error_checking'] ?? 1;
            }else{
                $user['error_checking'] = 1;
            }
            $totalPurchase = 0;

            $userPayment = PaymentHistory::getAccounting(['user_id' => $user->id, 'status' => 'success'], FALSE);
            if (count($userPayment) > 0) {
                $totalPurchase = $userPayment[0]->total_purchase;
            }

            $totalJob = 0;
            $totalCost = 0;
            $userRenderJob = User::getByCondition(['user_id' => $user->id], FALSE);
            if (count($userRenderJob) > 0) {
                $totalJob = $userRenderJob[0]->total_job;
                $totalCost = $userRenderJob[0]->total_credit;
            }

            $userActivity = UserActivity::getByCondition(['user_id' => $user->id]);

            $image_server = false;

            return view('index.user_detail', compact('user', 'totalPurchase', 'totalJob', 'totalCost', 'userActivity','image_server'));
        }

        return redirect(route('user'));
    }

    public function accounting(Request $request)
    {
        $month = $request->get('month', date('Y-m'));
        $totalAmount = 0;
        $totalExpense = 0;

        $paymentHistories = PaymentHistory::getAccounting(['month' => $month, 'status' => 'success'], FALSE);
        foreach ($paymentHistories as $item) {
            $totalAmount += $item->total_purchase;
        }

        $condition = [
            'from_date' => $month . '-01',
            'to_date' => date('Y-m-t', strtotime($month . '-01')),
        ];

        $renderJobListQuery = RenderJob::getByCondition($condition, 'query')->get();
        foreach ($renderJobListQuery as $item) {
            $totalExpense += $item->cost;
        }

        return view('index.accounting', compact('month', 'totalAmount', 'totalExpense'));
    }

    public function userExpense(Request $request)
    {
        $month = $request->get('month', date('Y-m'));
        $totalExpense = 0;
        $condition = [
            'from_date' => $month . '-01',
            'to_date' => date('Y-m-t', strtotime($month . '-01')),
            'group_by' => 'user_id'
        ];

        $renderJobListQuery = RenderJob::getByCondition($condition, 'query')->get();
        foreach ($renderJobListQuery as $item) {
            $totalExpense += $item->expense;
        }

        return view('index.userExpense', compact('month', 'totalExpense'));
    }

    public function scene(Request $request)
    {
        return view('index.scene');
    }

    public function sceneDetail(Request $request, $id)
    {
        $sceneDetail = SceneAnalysis::find($id);

        return view('index.scene_detail', compact('sceneDetail'));
    }

    public function payment(Request $request)
    {
        $queryCondition = [
            'user_id' => $request->get('user_id', ''),
            'trans_keyword' => $request->get('trans_keyword', ''),
            'status' => $request->get('status', ''),
            'type' => $request->get('type', ''),
        ];
        $paymentHistories = PaymentHistory::getByCondition($queryCondition);

        return view('index.payment', compact('paymentHistories', 'queryCondition'));
    }

    public function imageServers(Request $request)
    {
        $listZone = ImageServer::all();

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

        $userActivity = UserActivity::getByCondition(['type' => 19]);
        $endpoint = ['none' => 'None'];
        $consoleClient = new ConsoleClient();
        $resultConsole = $consoleClient->getImageServersZone([]);
        // dd($resultConsole);
        if(!empty($resultConsole)){
            foreach($listZone as &$item){
                foreach($resultConsole as $data){
                    if($item['zone'] == $data['zone']){
                        $item['available'] = 1;
                        $item['fs_id'] = $data['fs_id'];
                        $endpoint[$data['mount_point']] = $item['zone'];
                    }
                }
            }
        }
        $image_server = true;
        $region = Region::all();
        return view('index.imageServers', compact('listZone','software','software_version','engine_version','endpoint','userActivity','image_server','region'));
    }
    
    public function mailDomain(Request $request)
    {
        return view('index.domain_email');
    }

    public function groupDiscount(Request $request)
    {
        return view('index.group_discount');
    }

    public function editGroupDiscount(Request $request,$id = null)
    {
        $group_discount = [
            'name' => '',
            'discount_cpu' => 0,
            'discount_gpu' => 0,
            'date_from' => '',
            'date_to' => '',
            'active' => 0,
            'id' => ''
        ];
        $group_discount = (object) $group_discount;
        if($id !== null){
            $group_discount = GroupDiscount::find($id);
        }
        return view('index.edit_group_discount', compact('group_discount'));
    }

    public function utm(Request $request)
    {
        return view('index.utm');
    }

    public function swpt(Request $request)
    {
        return view('index.swpt');
    }

    public function systemEnv(Request $request)
    {
        return view('index.custom_system_env');
    }

    public function systemEnvEdit(Request $request, $id = NULL)
    {
        if ($id) {
            $system_env = CustomSystemEnv::find($id);
        } else {
            $system_env = new CustomSystemEnv();
        }

        if ($request->isMethod('POST')) {
            $validator = [];
            $validator['name'] = 'required';
            $validator['value'] = 'required';
            $validator['type'] = 'required';

            $this->validate($request, $validator);

            $data = [];
            $data['name'] = $request->get('name');
            $data['value'] = $request->get('value');
            $data['type'] = $request->get('type');
            $data['note'] = $request->get('note');

            $editFlag = FALSE;
            if ($system_env->id) {
                $data['system_env_id'] = $system_env->id;
                $editFlag = TRUE;
            }

            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateCSENV($data, $editFlag);

            if ($resultConsole ==  200) {
                return Redirect::route('systemEnv')->with('success', 'Update success');
            }
        }

        return view('index.edit_system_env', compact('system_env'));
    }

    public function engineVersionEdit(Request $request, $id = NULL)
    {
        if (!empty($id) && $id !== 'null') {
            $engine_version = EngineVersion::find($id);
        } else {
            $engine_version = new EngineVersion();
        }

        if ($request->isMethod('POST')) {
            $validator = [];
            $validator['software'] = 'required';
            $validator['engine'] = 'required';
            $validator['engine_version'] = 'required';
            $validator['software_version'] = 'required';
            $validator['default_version'] = 'required';
            $validator['selected_vesion'] = 'required';

            $this->validate($request, $validator);

            $data = [];
            $data['software'] = $request->get('software');
            $data['engine'] = $request->get('engine');
            $data['engine_version'] = $request->get('engine_version');
            $data['software_version'] = $request->get('software_version');
            $data['default_version'] = $request->get('default_version');
            $data['selected_vesion'] = $request->get('selected_vesion');

            $editFlag = FALSE;
            if ($engine_version->id) {
                $data['engine_version_id'] = $engine_version->id;
                $editFlag = TRUE;
            }

            dd($data);

            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->updateRenderEngine($data, $editFlag);

            if ($resultConsole ==  200) {
                return Redirect::route('engineVersion')
                ->with('success', 'Update success');
            }
        }

        return view('index.edit_engine_version', compact('engine_version'));
    }

    public function engineVersion(Request $request)
    {
        return view('index.engine_version');
    }



}