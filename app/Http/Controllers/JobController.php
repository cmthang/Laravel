<?php
namespace App\Http\Controllers;

use App\User;
use App\Models\RenderJob;
use App\Models\RenderTaskDetail;
use Auth,Redirect;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin.auth');
    }

    public function index(Request $request)
    {
        $condition = [
            'filter-id' => $request->get('filter-id', ''),
            'filter-daterange' => $request->get('filter-daterange', ''),
            'filter-email' => $request->get('filter-email', ''),
            'filter-status' => $request->get('filter-status', ''),
            'filter-output-status' => $request->get('filter-output-status', ''),
            'filter-scene-name' => $request->get('filter-scene-name', ''),
            'filter-render-engine' => $request->get('filter-render-engine', ''),
            'filter-software' => $request->get('filter-software', ''),
        ];

        return view('job.index', compact('condition'));
    }

    public function detail(Request $request, $id)
    {
        $renderJob = RenderJob::find($id);
        $renderJobParams = json_decode($renderJob->params, TRUE);

        $userData = User::find($renderJob['user_id']);

        $jobDetails = RenderTaskDetail::findByJobId($id);

        $renderTaskDetails = [];
        $renderTaskStt = [];
        if ($jobDetails) {
            $renderTaskDetails = json_decode($jobDetails->task_details, TRUE);
            $renderTaskStt = json_decode($jobDetails->total_task_stt, TRUE);
        }

        return view('job.detail', compact('userData','renderJob', 'renderJobParams', 'renderTaskDetails', 'renderTaskStt'));
    }
}