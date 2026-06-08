<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

public function section()
{
    return $this->belongsTo(Section::class);
}
}
