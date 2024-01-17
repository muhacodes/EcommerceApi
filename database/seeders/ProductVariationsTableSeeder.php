<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariation;

class ProductVariationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = \App\Models\Product::pluck('id')->toArray();
        $sizes = \App\Models\Size::pluck('id')->toArray();
        $colors = \App\Models\Color::pluck('id')->toArray();

        // Use array_slice to limit the number of products, sizes, and colors
        $limitedProducts = array_slice($products, 0, 4);
        $limitedSizes = array_slice($sizes, 0, 4);
        $limitedColors = array_slice($colors, 0, 4);

        foreach ($limitedProducts as $product_id) {
            foreach ($limitedSizes as $size_id) {
                foreach ($limitedColors as $color_id) {
                    ProductVariation::create([
                        'product_id' => $product_id,
                        'size_id' => $size_id,
                        'color_id' => $color_id,
                        'quantity' => rand(1, 10),
                    ]);
                }
            }
        }
    }

}
