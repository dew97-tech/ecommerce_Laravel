<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $url = '';
        if ($request->user()->role === 'admin') {
            
            # code...
            $notification = array(
                'message' => 'Admin login was successsful',
                'alert-type'=>'success'
            );
            $url = 'admin/dashboard';
        }elseif ($request->user()->role === 'vendor') {
            # code...
            $notification = array(
                'message' => 'Vendor login was successsful',
                'alert-type'=>'success'
            );
            $url = 'vendor/dashboard';
        }elseif ($request->user()->role === 'user') {
            # code...
            $notification = array(
                'message' => 'User login was successsful',
                'alert-type'=>'success'
            );
            $url = '/dashboard';
        }
        return redirect()->intended($url)->with($notification);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
