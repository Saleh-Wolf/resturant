<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'description',
        'display_order',
        'is_active',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}