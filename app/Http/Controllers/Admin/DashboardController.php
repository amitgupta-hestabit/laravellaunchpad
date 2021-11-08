<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NotificationToTeacherForAssignedStudent;
use App\Mail\ApprovedMail;
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
        //return redirect()->to('/admin/profile');
        return redirect()->route('admin.update-profile');
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

    public function updateStudent(Request $request, $userId)
    {
        $record = User::find($userId);
        $user = User::findOrFail($userId);
        $user->assigned_teacher = $request->assigned_teacher;
        $user->status = $request->status;
      
        $user->save();
        if($request->assigned_teacher){
            
            if($request->assigned_teacher != $record->assigned_teacher){
                
                $teacherData = User::find($user->assigned_teacher);
                $teacherData->notify((new NotificationToTeacherForAssignedStudent($teacherData->name,$user->name)));
            }
        }
       
        session()->flash('success', 'Student are successfully updated');
       // return redirect()->to('/admin/dashboard'); 
        return redirect()->route('admin.dashboard');
    }
    public function updateTeacher(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->status = $request->status;
        $user->save();
        
        session()->flash('success', 'Teacher are successfully updated');
        return redirect()->route('admin.dashboard');
    }
    public function approvedStudent(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
       
        if($request->is_approved==0){
            $user->is_approved=1;
            // send mail when teacher will be approved
        }else{
            $user->is_approved=0;
        }
        $user->save();
        Mail::to($user->email)->send(new ApprovedMail($user->user_type,$user->name));
        session()->flash('success', 'Student has been successfully approved');
        return redirect()->route('admin.dashboard');
    }
    public function approvedTeacher(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
       
        if($request->is_approved==0){
            $user->is_approved=1;
            // send mail when teacher will be approved
        }else{
            $user->is_approved=0;
        }
        $user->save();
        Mail::to($user->email)->send(new ApprovedMail($user->user_type,$user->name));
        session()->flash('success', 'Teacher has been successfully approved');
        return redirect()->route('admin.dashboard');
    }

}
