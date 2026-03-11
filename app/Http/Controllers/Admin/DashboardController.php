<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
{
    $totalPosts = \App\Models\Post::count();
    $activeUsers = \App\Models\User::where('is_admin', false)
        ->where('expires_at', '>', now())
        ->count();

    return view('admin.dashboard', compact('totalPosts', 'activeUsers'));
}
}