<?php

namespace Database\Factories;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Product::class;

    public function definition()
    {
        // $photoPath = 'storage/photos'; 
        $path = public_path('storage/photos');// Replace with the actual path

        $randomPhoto = $this->getRandomPhoto($path);       

        return [
            'category_id' => $this->faker->numberBetween(1, 5),
            'name' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'quantity' => $this->faker->numberBetween(1, 100),
            'photo' => $randomPhoto
            
        ];
    }


    private function getRandomPhoto($path)
    {
        // $path = public_path('storage/photos');
        $images = File::allFiles($path);

        $randomPhoto = $this->faker->randomElement($images);
        return $randomPhoto;
        // return Storage::url($randomPhoto);
        // $photos = Storage::files($path);
        // $randomPhoto = $this->faker->randomElement($photos);
        // return Storage::url($randomPhoto);
    }


    private function attachPhoto()
    {
        // Assuming "hijab.jpg" is in storage/app/photos
        $photoPath = 'photos/images.jpg';

        if (!Storage::exists($photoPath)) {
            $this->command->error("File {$photoPath} not found.");
            return null;
        }

        // Copy the file to a publicly accessible location (you can change the path if needed)
        $newPath = 'public/storage/photos/' . basename($photoPath);
        Storage::copy($photoPath, $newPath);

        // $path = public_path('storage/photos');
        // $images = File::allFiles($path);

        // // Store the paths of the images in an array
        // $imagePath = '';
        // $imagePaths = [];
        // foreach ($images as $image) {
        //     array_push($imagePaths, $image);
        //     $imagePath = $image;
        // }

        // Return the path that can be used in your database
        return $imagePath;
    }

   
}
// $path = public_path('storage/photos');
        // $images = File::allFiles($path);

        // // Store the paths of the images in an array
        // $imagePath = '';
        // $imagePaths = [];
        // foreach ($images as $image) {
        //     array_push($imagePaths, $image);
        //     $imagePath = $image;
        // }