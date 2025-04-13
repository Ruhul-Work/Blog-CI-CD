<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $usedNames = [];

        for ($i = 0; $i < 50; $i++) {
            // Ensure unique product names
            do {
                $englishName = ucfirst($faker->unique()->word);
            } while (in_array($englishName, $usedNames));

            // Add the name to the used names array
            $usedNames[] = $englishName;

            // Generate the slug based on the englishName
            $slug = Str::slug($englishName);

            // Check for duplicate slugs in the database
            $existingSlugCount = Product::where('slug', $slug)->count();

            // If slug exists, append a unique identifier
            if ($existingSlugCount > 0) {
                $slug .= '-' . ($existingSlugCount + 1);
            }

            // Create the product
            Product::create([
                'english_name' => $englishName,
                'bangla_name' => ucfirst($faker->unique()->word), // Replace with actual Bangla names if needed
                'slug' => $slug,
                'purchase_price' => $faker->numberBetween(10, 1000),
                'mrp_price' => $faker->numberBetween(10, 1000),
                'current_price' => $faker->numberBetween(10, 1000),
                'stock' => $faker->numberBetween(1, 100),
            ]);
        }
    }
}
