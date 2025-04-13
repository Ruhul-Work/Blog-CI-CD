<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\Product;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'english_name' => $this->faker->word,
            'bangla_name' => $this->faker->word,
            'searchable_data' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'status' => $this->faker->boolean,
            'isBundle' => $this->faker->boolean,
            'product_type' => $this->faker->randomElement(['book']),
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
//            'published_year' => $this->faker->year,
            'edition' => $this->faker->randomDigitNotNull,
            'pages_no' => $this->faker->numberBetween(100, 1000),
            'cover_type' => $this->faker->randomElement(['hardcover', 'paperback']),
            'weight' => $this->faker->randomFloat(2, 0.1, 5.0),
            'isbn' => $this->faker->isbn13,
            'product_code' => $this->faker->unique()->ean13,
            'short_description' => $this->faker->text(200),
            'description' => $this->faker->paragraphs(3, true),
            'purchase_price' => $this->faker->randomFloat(2, 10, 100),
            'mrp_price' => $this->faker->randomFloat(2, 20, 200),
            'current_price' => $this->faker->randomFloat(2, 15, 150),
            'discount_type' => $this->faker->randomElement(['amount', 'percentage']),
            'discount_amount' => $this->faker->randomFloat(2, 1, 50),
            'show_discount' => $this->faker->boolean,
            'stock' => $this->faker->numberBetween(0, 1000),
            'stock_status' => $this->faker->randomElement(['in_stock', 'out_of_stock']),
            'thumb_image' => $this->faker->imageUrl(640, 480, 'products', true, 'Faker'),
            'meta_title' => $this->faker->sentence,
            'meta_description' => $this->faker->text(160),
            'meta_image' => $this->faker->imageUrl(640, 480, 'meta', true, 'Faker'),
            'created_by' => $this->faker->randomDigitNotNull,
            'updated_by' => $this->faker->randomDigitNotNull,
            'deleted_by' => $this->faker->randomDigitNotNull
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
//            $categories = Category::inRandomOrder()->take(rand(1, 900))->pluck('id');
            $categories = Category::inRandomOrder()->take(2)->pluck('id');
            $product->categories()->attach($categories);

            $authors = Author::inRandomOrder()->take(2)->pluck('id');

//            $authors = Author::inRandomOrder()->take(rand(1, 5000))->pluck('id');

            $product->authors()->attach($authors);
        });
    }
}
