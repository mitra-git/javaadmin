<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBerita extends Model
{
    use HasFactory;

    protected $table = 'news_detail';

    protected $fillable = [
        'id_news',
        'image',
    ];
}
