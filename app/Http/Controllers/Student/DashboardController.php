<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('student.dashboard');
    }
    public function editProfile()
    {
        $student = auth()->user();
        return view('student.edit-profile',['student'=>$student]);
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',

        ]);
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
        $user->current_school = $request->current_school;
        $user->previous_school = $request->previous_school;
        $parentDetails = [];
        if ($request->parent_details[0]['name'] != '') {
            foreach ($request->parent_details as $parentDetail) {
                $parentDetails[] = array('name' => $parentDetail['name'], 'value' => $parentDetail['value']);
            }
        }
        $user->parent_details = $parentDetails;
        $user->save();
        session()->flash('success', 'You are successfully updated');
        return redirect()->to('/student/profile');
    }
}