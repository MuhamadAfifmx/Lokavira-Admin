<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name', 'type', 'price', 'is_active'];

    // Relasi: Satu paket punya banyak fitur
    public function features()
    {
        return $this->hasMany(PackageFeature::class);
    }
}