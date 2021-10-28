<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class StudentApiController extends Controller
{
    public function register(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'user_type' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'address' => 'required',
            'current_school' => 'required',
            'previous_school' => 'required',
            'parent_details' => 'required',
            'profile_picture' => 'required|image|mimes:jpg,png,jpeg,gif,svg',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 202);
        }

        $userrecord = DB::table('users')->where([
            ['email', '=' , $request->email],
            ['status', '=', 'ACTIVE'],
            ['user_type','=', $request->user_type]
        ])->get();
        if($userrecord->isNotEmpty()){
            return response()->json(['error'=>'Email id already exsit for this user type.']);
        }


        $input = $request->all();
        if ($request->hasFile('profile_picture')) {
            $filenameWithExt = $request->file('profile_picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = public_path('/images');
            $request->file('profile_picture')->move($destinationPath, $fileNameToStore);
        }
        $input['profile_picture'] = $fileNameToStore;
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $responseArr = [];
        $responseArr['msg'] = "You are successfully register and wait for admin approval";
        $responseArr['student'] = $user;
        return response()->json($responseArr, 200);
    }



    public function logout(Request $request){
       
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function updateprofile(Request $request, $userId){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'current_school' => 'required',
            'previous_school' => 'required',
            'parent_details' => 'required',
         ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 202);
        }
       
        $user = User::find($userId);
        if($user){
       
          if($user->update($request->all())){
            return response()->json([
                'data' => $user,
                'message' => 'Successfully updated profile'
            ]);
          }else{
            return response()->json([
                'message' => 'error occures profile update'
            ]);
          }
          
        }else{
            return response()->json(['error'=>'Please provide correct user id']);
        }
    }
    public function profile(Request $request){
        $user = Auth::user();
        if($user){
          return response()->json(['profile'=>$user],200);
        }else{
            return response()->json(['error'=>'You are not login']);
        }
    }
    public function delete(Request $request, $userId){
        $user = User::find($userId);
        
            if($user->delete()){
                return response()->json([
                    'message' => 'Successfully deleted profile'
                ]);
              }else{
                return response()->json([
                    'message' => 'error occures profile update'
                ]);
              }
       
    }
    public function updateprofilepicture(Request $request){
       
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
           'profile_picture' => 'required|image|mimes:jpg,png,jpeg,gif,svg',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 202);
        }
       
        $user = User::find($request->user_id);
     
                if($user){
                    if ($request->hasFile('profile_picture')) {
                        $filenameWithExt = $request->file('profile_picture')->getClientOriginalName();
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension = $request->file('profile_picture')->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        $destinationPath = public_path('/images');
                        $request->file('profile_picture')->move($destinationPath, $fileNameToStore);
                    }
                    $data=array('profile_picture'=>$fileNameToStore);
                if($user->update($data)){
                    return response()->json([
                        'data' => $user,
                        'message' => 'Successfully updated profile picture'
                    ]);
                }else{
                    return response()->json([
                        'error' => 'error occures picture update'
                    ]);
                }
             }else{
                 return response()->json(['error'=>'Please enter correct user id']);
             }
      
    }

    public function updatepassword(Request $request, $userId){
       
        $validator = Validator::make($request->all(), [
           'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 202);
        }
        
        $user = User::find($userId);
        if($user->id == Auth::user()->id){
            $data=array('password'=> Hash::make($request->password));
          if($user->update($data)){
            return response()->json([
                'data' => $user,
                'message' => 'Successfully updated password'
            ]);
          }else{
            return response()->json([
                'message' => 'error occures password update'
            ]);
          }
          
        }else{
            return response()->json(['error'=>'You are not login']);
        }
    }
}
