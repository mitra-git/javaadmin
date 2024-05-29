<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTypeImage extends Model
{
    use HasFactory;

    protected $table = 'project_type_image';

    protected $fillable = [
        'id_project_type',
        'image',
    ];

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'id_project_type');
    }
}
