<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteActivity extends Model
{
    protected $fillable = [
        'title',
        'description',
        'schedule',
        'time',
        'location',
        'category',
        'poster',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];
}