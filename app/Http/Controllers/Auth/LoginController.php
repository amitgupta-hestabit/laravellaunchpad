<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function redirectTo()
    {
        if (Auth()->user()->user_type == 'ADMIN') {
            return route('admin.dashboard');
        } elseif (Auth()->user()->user_type == 'TEACHER') {
            return route('teacher.dashboard');
        } elseif (Auth()->user()->user_type == 'STUDENT') {
            return route('student.dashboard');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required|email',
            'password' => "required"
        ]);
        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            $userStatus = Auth::User()->status;
            if ($userStatus == 'INACTIVE') {
                Auth::logout();
                Session::flush();
                return redirect(url('login'))->withInput()->with('error', 'You are not active. please contact to admin');
            }
           
            if (Auth()->user()->user_type == 'ADMIN') {
               redirect()->to('/dashboard');
                return redirect('/admin/dashboard');
            } elseif (Auth()->user()->user_type == 'TEACHER') {
                return  redirect()->to('/teacher/dashboard');
               
            } elseif (Auth()->user()->user_type == 'STUDENT') {
                return  redirect()->to('/student/dashboard');
                
            }
        } else {
            return redirect()->route('login')->with('error', 'email or password is wrong');
        }
    }
}
