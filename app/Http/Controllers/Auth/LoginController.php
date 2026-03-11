<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Beritahu Laravel untuk menggunakan kolom 'username' untuk login
     */
    public function username()
{
    // Menggunakan kolom phone_number untuk login
    return 'phone_number'; 
}

  protected function authenticated(Request $request, $user)
{
    // Jika admin, lempar ke route admin.dashboard
    if ($user->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    
    // Jika user biasa, lempar ke route user.dashboard
    return redirect()->route('user.dashboard');
}
}