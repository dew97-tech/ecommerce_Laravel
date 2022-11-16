<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorController extends Controller
{
    //Dashboard
    public function VendorDashboard(){
        return view('vendor.vendor_dashboard');
    }
}
