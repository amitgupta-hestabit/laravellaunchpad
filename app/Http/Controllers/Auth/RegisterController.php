<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function redirectTo()
    {
        if (Auth()->user()->role_id == 1) {
            return route('admin.dashboard');
        } elseif (Auth()->user()->role_id == 2) {
            return route('teacher.dashboard');
        } elseif (Auth()->user()->role_id == 3) {
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        //dd($data);
        return User::create([
            'name' => $data['name'],
            'user_type' => $data['user_type'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'ACTIVE',
        ]);
    }

    public function register(Request $request)
    {
        // echo "sasas";exit;
        // dd($request->all());
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6',
            'user_type' => 'required',
            'profile_picture' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
            'current_school' => 'required',
            'previous_school' => 'required',
            'address' => 'required',
            'parent_details.*' => 'required|array',
        ]);
       
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->user_type = $request->user_type;
        $user->address = $request->address;
        $user->current_school = $request->current_school;
        $user->previous_school = $request->previous_school;
        if ($request->hasFile('profile_picture')) {
            $filenameWithExt = $request->file('profile_picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = public_path('/images');
            $request->file('profile_picture')->move($destinationPath, $fileNameToStore);
        }
        $user->profile_picture = $fileNameToStore;
        $user->experience = $request->experience;
        $parentDetails = [];
        if ($request->parent_details[0]['name'] != '') {
            foreach ($request->parent_details as $parentDetail) {
                $parentDetails[] = array('name' => $parentDetail['name'], 'value' => $parentDetail['value']);
            }
        }
        $user->parent_details = $parentDetails;
        $user->status = 'ACTIVE';
        if ($user->save()) {
            if (isset($request->expertise_in_subjects)) {
                $user->expertiseInSubjects()->attach($request->expertise_in_subjects);
            }
        }
       
       
        session()->flash('success', 'You are successfully register wait for approval by admin');
        return redirect()->to('/register');
    }
}
