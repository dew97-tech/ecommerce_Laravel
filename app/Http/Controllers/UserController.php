<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function UserDashboard(){
        $id = Auth::user()->id;
        $userData = User::find($id);
        
        return view('index',compact('userData'));
    
    }
    public function UserProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        
        // For Storing Photo to DB
        if ($request->file('photo')) {
            # code...
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/'.$data->photo));
            $filename =  date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);
            $data['photo'] = $filename;
            // dd($data);

        }
        $data->save();

        $notification = array(
            'message' => 'User Profile Updated Successsfully',
            'alert-type'=>'success'
        );

        return redirect()->back()->with($notification);
    }
    public function UserLogout(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'User Logout was Succesful ',
            'alert-type'=>'success'
        );

        return redirect('/login')->with($notification);
    }
}
