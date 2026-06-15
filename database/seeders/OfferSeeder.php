<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $offer = Offer::updateOrCreate(
            ['name' => 'Pizza Discount 20%'],
            [
                'description' => '20% discount on selected pizza items.',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'start_date' => today()->subDay(),
                'end_date' => today()->addDays(30),
                'is_active' => true,
                'display_on_menu' => true,
            ]
        );

        $pizzaItems = MenuItem::whereIn('name', [
            'Margherita Pizza',
            'Chicken Pizza',
        ])->get();

        $offer->menuItems()->syncWithoutDetaching($pizzaItems->pluck('id')->toArray());
    }
}