<?php

namespace App\Http\Controllers;

use App\Utils\Constant;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $timezone = 7;

        if (Auth::guard('admin')->check()) {
            $currentUser = Auth::guard('admin')->user();
            $timezone = session(Constant::PREFIX_SESSION_TIMEZONE . $currentUser->id, 7);
        }

        View::share('metadata_version', env('METADATA_VERSION', ''));
        View::share('local_timezone', $timezone);
    }
}
