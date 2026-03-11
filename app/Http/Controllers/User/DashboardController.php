<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk user biasa.
     */
    public function index()
    {
        // Kita ambil data user yang sedang login untuk ditampilkan di view
        $user = Auth::user();

        return view('user.dashboard', compact('user'));
    }
}