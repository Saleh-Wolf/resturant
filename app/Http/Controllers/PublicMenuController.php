<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\RestaurantTable;

class PublicMenuController extends Controller
{
    public function index(string $token)
    {
        $table = RestaurantTable::where(
            'qr_token',
            $token
        )->firstOrFail();

        $categories = Category::with([
            'menuItems' => function ($query) {
                $query->where('is_available', true)
                    ->with('offers');
            }
        ])->get();

        return view(
            'public.menu',
            compact(
                'table',
                'categories'
            )
        );
    }
}