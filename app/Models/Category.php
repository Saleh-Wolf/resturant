<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'section_id',
        'name',
        'slug',
        'is_active',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
