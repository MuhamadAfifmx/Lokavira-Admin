<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'platform', 'title', 'cover_image', 'post_url', 
        'upload_date', 'views', 'likes', 'comments', 'shares', 
        'age_demographics', 'avg_watch_time'
    ];

    protected $casts = [
        'age_demographics' => 'array',
        'upload_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->title);
        }
        
        // Sesuaikan domain admin kamu di sini
        return 'http://admin.lokavira.test/storage/' . $this->cover_image;
    }
}