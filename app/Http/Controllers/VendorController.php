<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class VendorController extends Controller
{
    //Dashboard
    public function VendorDashboard(){
        return view('vendor.index');
    }

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
}
