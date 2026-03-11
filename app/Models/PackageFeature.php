<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    protected $fillable = ['package_id', 'feature_name'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}