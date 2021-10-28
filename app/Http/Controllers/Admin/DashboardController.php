<?php

namespace App\Http\Controllers\Admin;

use App\Events\Approval;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
   
    public function dashboard()
    {
        $users = User::whereNotIn('user_type', ['ADMIN'])->orderby('id', 'desc')->paginate(5);
        //print_r($users);exit;
        return view('admin.dashboard', ['users' => $users]);
    }

    public function editProfile(){
        $user = auth()->user();
        //dd($user);
        return view('admin.edit-profile', ['user' => $user]);
    }

    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required',
        ]);
        //dd($request->parent_details[0]['name']);
        $user = User::find(auth()->user()->id);
        $user->name = $request->name;
        $user->address = $request->address;
        if ($request->hasFile('profile_picture')) {
            $filenameWithExt = $request->file('profile_picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = public_path('/images');
            $request->file('profile_picture')->move($destinationPath, $fileNameToStore);
            $user->profile_picture = $fileNameToStore;
        }
        $user->save();
        session()->flash('success', 'You are successfully updated');
        return redirect()->to('/admin/profile');
    }


    public function editStudent($userId)
    {
        $user = User::find($userId);
     //   print_r($user);exit;
        return view('admin.edit-student', ['user' => $user]);
    }
    public function editTeacher($userId)
    {
        $user = User::find($userId);
        return view('admin.edit-teacher', ['user' => $user]);
    }

    public function updateStaudent(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->assigned_teacher = $request->assigned_teacher;
        $user->status = $request->status;
        if($request->status=='active'){
            $user->is_approved=1;
            // send mail when student will be approved  
        }else{
            $user->is_approved=0;
        }
        $user->save();
        $teacherData = User::find($user->assigned_teacher);
        //$teacherData->notify((new \App\Notifications\NewStudentAssignedNotification($user, $teacherData)));
        //Approval::dispatch($user);
        session()->flash('success', 'Student are successfully updated');
        return redirect()->to('/admin/dashboard'); 
    }
    public function updateTeacher(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->status = $request->status;
        if($request->status=='active'){
            $user->is_approved=1;
            // send mail when teacher will be approved
        }else{
            $user->is_approved=0;
        }
        $user->save();
        
        session()->flash('success', 'Teacher are successfully updated');
        return redirect()->to('/admin/dashboard');
    }

}
