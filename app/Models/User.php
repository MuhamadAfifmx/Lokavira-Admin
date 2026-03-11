<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'is_admin', 
        'package_id', 'business_name', 'representative_name', 
        'address', 'phone_number', 'logo', 'subscribed_at', 'expires_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscribed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function package() {
        return $this->belongsTo(Package::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }
}