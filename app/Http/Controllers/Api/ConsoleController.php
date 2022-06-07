<?php
namespace App\Http\Controllers\Api;

use App\Events\MessagePushed;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsoleController extends Controller
{
    public function receivePush(Request $request)
    {
        //if(env('APP_ENV') == 'production'){
            $message = $request->input('message');

            Log::info('Message: ' . $message);
            $message = json_decode($message, TRUE);

            event(new MessagePushed($message));
        //}
        return response()->json([], 200);
    }
}
