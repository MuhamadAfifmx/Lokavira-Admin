<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDash;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;

// 1. Halaman Utama -> Langsung ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Auth Routes (Login, Logout, dll)
Auth::routes();

// 3. GROUP ADMIN (Prefix: /admin)
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'is_admin']], function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminDash::class, 'index'])->name('admin.dashboard');
    
    // Route Manajemen Paket
    Route::resource('packages', PackageController::class)->names([
        'index'   => 'admin.packages.index',
        'store'   => 'admin.packages.store',
        'update'  => 'admin.packages.update',
        'destroy' => 'admin.packages.destroy',
    ]);
    
    // Route Manajemen User / PIC
    // Perhatikan: Route reset diletakkan sebelum resource atau pastikan penamaannya unik
    Route::post('users/{id}/reset', [UserController::class, 'resetPassword'])->name('admin.users.reset');
    
    Route::resource('users', UserController::class)->names([
        'index'   => 'admin.users.index',
        'store'   => 'admin.users.store',
        'update'  => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Route Manajemen Posts / Konten
    // Route khusus untuk input konten berdasarkan ID user tertentu
  // ... route lainnya ...

// ... di dalam Route::group(['prefix' => 'admin', ...])

// Route Manajemen Posts / Konten
// 1. Route khusus untuk Excel (Download & Import)
Route::get('posts/download-template', [PostController::class, 'downloadTemplate'])->name('admin.posts.download_template');
Route::post('posts/import-multi', [PostController::class, 'import_multi'])->name('admin.posts.import_multi');

// 2. Route khusus untuk simpan banyak data manual
Route::post('posts/store-multi', [PostController::class, 'store_multi'])->name('admin.posts.store_multi');

// 3. Route khusus untuk input konten berdasarkan ID user tertentu
Route::get('posts/create/{id}', [PostController::class, 'create'])->name('admin.posts.create.id');

// 4. Resource standar untuk Post
Route::resource('posts', PostController::class)->names([
    'index'   => 'admin.posts.index',
    'create'  => 'admin.posts.create',
    'store'   => 'admin.posts.store',
    'edit'    => 'admin.posts.edit',
    'update'  => 'admin.posts.update',
    'destroy' => 'admin.posts.destroy',
]);

// ... route lainnya ...
});

