<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = 'information';

    protected $fillable = [
        'logo_header',
        'logo_favicon',
        'logo_company',
        'video',
        'name',
        'image',
        'description',
        'address',
        'phone',
        'instagram',
        'youtube',
        'facebook',
        'tiktok',
        'google_map',
        'link_wa',
        'order_wa',
    ];
}
