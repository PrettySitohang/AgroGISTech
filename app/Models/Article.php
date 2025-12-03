<?php
// app/Models/Article.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','slug','content','status','author_id','editor_id','published_at',
        'cover_image' 
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }
}
