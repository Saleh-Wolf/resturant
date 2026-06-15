<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_available',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'menu_item_ingredients')
            ->withPivot('quantity_required')
            ->withTimestamps();
    }
}
