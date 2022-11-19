<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;


class VendorController extends Controller
{
    //Dashboard
    public function VendorDashboard(){
        return view('vendor.index');
    }

    // Logout
    public function VendorDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/vendor/login');
    }

    // Login 
    public function VendorLogin(){
        return view('vendor.vendor_login');
    }


    // Vendor Profile
    public function VendorProfile(){
        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return view('vendor.vendor_profile_view',compact('vendorData'));
    }

    // Vendor Profile Store
    public function VendorProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->vendor_join = $request->vendor_join;
        $data->vendor_short_info = $request->vendor_short_info;

        
        // For Storing Photo to DB
        if ($request->file('photo')) {
            # code...
            $file = $request->file('photo');
            @unlink(public_path('upload/vendor_images/'.$data->photo));
            $filename =  date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/vendor_images'),$filename);
            $data['photo'] = $filename;
            // dd($data);

        }
        $data->save();

        $notification = array(
            'message' => 'Vendor Profile Updated Successsfully',
            'alert-type'=>'success'
        );

        return redirect()->back()->with($notification);
    }

     // ChangePassword
     public function VendorChangePassword(){
        return view('vendor.vendor_change_password');
    }

    // Update Password
    public function VendorUpdatePassword(Request $request){
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]); 
        // Check Old Password Validate
        if(!Hash::check($request->old_password,auth::user()->password)){
            // dd(auth::user()->password);
            // dd(!Hash::check($request->old_password,auth::user()->password));
            return back()->with("error","Current Password Doesn't Match !!");
            
        }
        // Update The New Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
 
        return back()->with('status',"Password Changed Successfully");
    }

    
    
}
