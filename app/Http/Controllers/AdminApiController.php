<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\StudentAssignedToTutor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Notifications\NotificationToTeacherForAssignedStudent;
use App\Mail\ApprovedMail;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
class AdminApiController extends Controller
{

    public function login(LoginRequest $request){

        $validated = $request->validated();
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
        //     'remember_me' => 'boolean'
        // ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 202);
        }

        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
       
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
       
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function profile(Request $request){
        $user = Auth::user();
        if($user){
          return response()->json(['profile'=>$user],200);
        }else{
            return response()->json(['error'=>'You are not login']);
        }
    }

    public function studentassignedtoteacher(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'teacher_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 202);
        }

        $suser = DB::table('users')->where([
            ['is_approved', '=' , '1'],
            ['status', '=', 'ACTIVE'],
            ['id', '=', $request->student_id],
        ])->get();

        $tuser = DB::table('users')->where([
            ['is_approved', '=', '1'],
            ['status', '=', 'ACTIVE'],
            ['id', '=', $request->teacher_id],
        ])->get();
       
        $sassignedtot = DB::table('student_assigned_to_tutors')->where([
            ['student_id', '=', $request->student_id],
            ['teacher_id', '=', $request->teacher_id],
           
        ])->get();

        if($suser->isEmpty() || $tuser->isEmpty()){
            return response()->json(['error'=>'Please input correct info.']);
        }
      
        if($sassignedtot->isEmpty()){
            $studentassigned = new StudentAssignedToTutor();
            $studentassigned->student_id = $request->student_id;
            $studentassigned->teacher_id = $request->teacher_id;

            
            $studentassigned->save();
            $user=User::find($request->teacher_id);
            $user->notify(new NotificationToTeacherForAssignedStudent($tuser[0]->name,$suser[0]->name));
        
            $responseArr = [];
            $responseArr['msg'] = "You have successfully assigned student to teacher.";
        
            return response()->json($responseArr, 200);
        }else{
            return response()->json(['error'=>'Already record exist.']);
        }
    }
    public function approveduserbyadmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 202);
        }
        $users = User::find($request->id);
        $ustatus= User::where('status','ACTIVE');
        $result = DB::table('users')->where([
            ['status', '=', 'ACTIVE'],
            ['id', '=', $request->id],
        ])->get();
        if($result->isNotEmpty()){
            $user=$result[0];
            $username=$user->name;
            if($user->user_type == 'STUDENT'){
                $usertype="Student";
            }elseif($user->user_type == 'TEACHER'){
                $usertype="Teacher";
            }
            $users->is_approved = 1;
            $users->save();
            $responseArr = [];
            $responseArr['msg'] = $usertype." has been successfully approved";
            Mail::to($user->email)->send(new ApprovedMail($usertype,$username));
            return response()->json($responseArr, 200);
        }   else{
            return response()->json(['error'=>'Id does not exist. Or Status is inactive.']);
        }
    }
    public function userlist(Request $request)
    {
        $tuser = DB::table('users')->whereNotIn('user_type', ['ADMIN'])->orderby('id', 'desc')->get();
        return response()->json(['user_list'=>$tuser],200);
       
    }

    
}
