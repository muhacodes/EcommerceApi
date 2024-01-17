<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = ['Red', 'Blue', 'Green', 'Orange',  'Yellow', 'Purple', 'Gray',  'Black', 'White'];

        foreach ($colors as $color) {
            Color::create(['color' => $color]);
        }
    }
}
