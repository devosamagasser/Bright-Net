<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};

class Department extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory, InteractsWithMedia, Translatable;
}
