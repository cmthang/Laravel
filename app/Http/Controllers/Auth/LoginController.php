<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Utils\Constant;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Redirect,Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = route('index');
        $this->middleware('admin.guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $timezone = $request->input('timezone');
        $r = $request->input('r', '');

        $validator = Validator::make([
            'email' => $email,
            'password' => $password
        ], [
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email required',
            'password.required' => 'Password required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator, 'login')->withInput(Input::except('password'));
        } else {
            $user = User::checkAdmin($email, [Constant::USER_ROLE_SUPER_ADMIN, Constant::USER_ROLE_ADMIN, Constant::USER_ROLE_SUPPORTER]);

            if ($user && Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {
                $request->session()->put(Constant::PREFIX_SESSION_TIMEZONE . $user->id, $timezone);

                return Redirect::to($r ? $r : route('index'));
            } else {
                return Redirect::back()->withInput($request->except('password'))->with('wrong_email', 'Incorrect Account or Password');
            }
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('showLoginForm');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }
}
