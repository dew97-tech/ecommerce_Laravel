<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //Dashboard
    public function AdminDashboard(){
        return view ('admin.index');
    }
    // Logout
    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
    // Login
    public function AdminLogin(){
        return view('admin.admin_login');
    }
    // Profile
    public function AdminProfile(){
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view',compact('adminData'));
    }

    // Profile Store
    public function AdminProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        
        // For Storing Photo to DB
        if ($request->file('photo')) {
            # code...
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename =  date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['photo'] = $filename;
            // dd($data);

        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successsfully',
            'alert-type'=>'success'
        );

        return redirect()->back()->with($notification);
    }

    // ChangePassword
    public function AdminChangePassword(){
        return view('admin.admin_change_password');
    }

    public function AdminUpdatePassword(Request $request){
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]); 
        // Check Old Password Validate
        if(!Hash::check($request->old_password,auth::user()->password)){
            // dd(auth::user()->password);
            // dd(Hash::check($request->old_password,auth::user()->password));
            return back()->with("error","Current Password Doesn't Match !!");
            
        }
        // Update The New Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
 
        return back()->with('status',"Password Changed Successfully");
    }
}
