<?php

namespace Database\Seeders;

use App\Models\MessMenu;
use Illuminate\Database\Seeder;

class MessMenuSeeder extends Seeder
{
    public function run(): void
    {
        // hostel_id = null → applies to ALL hostels
        // This is the standard weekly cyclical menu for HITAM Hostels

        $menu = [
            'monday' => [
                'breakfast' => ['Idli (4) + Sambar + Coconut Chutney', 'Tea / Coffee', 'Banana'],
                'lunch'     => ['Rice + Dal Fry + Rajma Curry + Roti (2) + Papad + Pickle + Buttermilk'],
                'snacks'    => ['Bread Pakora (2) + Tamarind Chutney + Tea'],
                'dinner'    => ['Chapati (3) + Paneer Butter Masala + Jeera Rice + Dal Tadka + Salad'],
            ],
            'tuesday' => [
                'breakfast' => ['Upma + Coconut Chutney + Tea / Coffee', 'Boiled Egg (optional)'],
                'lunch'     => ['Rice + Sambar + Aloo Gobi + Roti (2) + Curd + Papad'],
                'snacks'    => ['Samosa (2) + Green Chutney + Tea'],
                'dinner'    => ['Chapati (3) + Chicken Curry (or Soya Curry) + Dal + Rice + Salad'],
            ],
            'wednesday' => [
                'breakfast' => ['Puri (4) + Aloo Bhaji + Tea / Coffee'],
                'lunch'     => ['Sambar Rice + Rasam + Mixed Veg Curry + Papad + Pickle + Buttermilk'],
                'snacks'    => ['Mirchi Bajji (2) + Tea'],
                'dinner'    => ['Chapati (3) + Egg Curry (or Paneer Curry) + Jeera Rice + Dal + Salad'],
            ],
            'thursday' => [
                'breakfast' => ['Dosa (2) + Sambar + Chutney + Tea / Coffee'],
                'lunch'     => ['Rice + Dal + Palak Paneer + Roti (2) + Curd + Papad'],
                'snacks'    => ['Vada (2) + Sambar + Tea'],
                'dinner'    => ['Chapati (3) + Mutton Curry (or Mushroom Curry) + Rice + Dal + Salad'],
            ],
            'friday' => [
                'breakfast' => ['Poha + Tea / Coffee', 'Sprouts Salad'],
                'lunch'     => ['Rice + Sambar + Bhindi Fry + Roti (2) + Buttermilk + Papad'],
                'snacks'    => ['Aloo Bonda (2) + Chutney + Tea'],
                'dinner'    => ['Biryani (Veg / Chicken) + Raita + Mirchi Salan + Salad'],
            ],
            'saturday' => [
                'breakfast' => ['Idli (4) + Sambar + Chutney + Tea / Coffee'],
                'lunch'     => ['Rice + Chicken Curry (or Dal Makhani) + Roti (2) + Curd + Papad'],
                'snacks'    => ['Bread Toast + Butter + Tea', 'Banana'],
                'dinner'    => ['Chapati (3) + Mix Veg + Dal + Rice + Kheer (dessert)'],
            ],
            'sunday' => [
                'breakfast' => ['Poori (4) + Halwa + Tea / Coffee', 'Boiled Egg (optional)'],
                'lunch'     => ['Special Pulao (Veg/Chicken) + Raita + Papad + Salad + Ice Cream'],
                'snacks'    => ['Pakora (Assorted) + Tea'],
                'dinner'    => ['Chapati (3) + Paneer Tikka Masala + Dal + Rice + Gulab Jamun'],
            ],
        ];

        $mealOrder = ['breakfast', 'lunch', 'snacks', 'dinner'];

        foreach ($menu as $day => $meals) {
            foreach ($mealOrder as $meal) {
                if (! isset($meals[$meal])) continue;

                $items = $meals[$meal];
                MessMenu::create([
                    'hostel_id'    => null,                              // Applies to all hostels
                    'day_of_week'  => $day,
                    'meal_type'    => $meal,
                    'menu_title'   => is_array($items) ? $items[0] : $items,
                    'items'        => is_array($items) ? implode(' | ', $items) : $items,
                    'special_note' => in_array($meal, ['breakfast', 'dinner']) && in_array($day, ['tuesday', 'wednesday', 'thursday'])
                                      ? 'Egg / non-veg option available on request'
                                      : null,
                    'is_active'    => true,
                ]);
            }
        }
    }
}
