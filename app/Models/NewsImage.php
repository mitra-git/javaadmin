<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsImage extends Model
{
    use HasFactory;

    protected $table = 'news_detail';

    protected $fillable = [
        'id_news',
        'image',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'id_news');
    }
}
