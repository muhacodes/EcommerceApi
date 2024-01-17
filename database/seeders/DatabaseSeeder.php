<?php

namespace Database\Seeders;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Color;
use App\Models\Size;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // $this->call(CategoriesTableSeeder::class); // Adjusted class name
        // $this->call(ColorsTableSeeder::class);
        // $this->call(SizesTableSeeder::class);
        // $this->call(ProductsTableSeeder::class);
        $this->call(ProductVariationsTableSeeder::class);
    }
}
