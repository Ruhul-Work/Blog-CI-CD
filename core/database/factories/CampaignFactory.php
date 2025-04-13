<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\CampaignProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'meta_description' => $this->faker->paragraph(3),
            'meta_title' => $this->faker->sentence(3),
            'icon' => $this->faker->imageUrl(350, 350),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'status' => $this->faker->boolean(80), // 80% chance of being active
            'slug' => $this->faker->slug,
        ];
    }

    /**
     * Indicate that the model's state should be after creating.
     *
     * @return \Closure
     */
    public function configure()
    {
        return $this->afterCreating(function (Campaign $campaign) {
            // Define number of CampaignProducts to create for each Campaign
            $numberOfProducts = rand(1, 20); // Adjust as needed

            // Create CampaignProduct instances for this Campaign
            CampaignProduct::factory()->count($numberOfProducts)->create([
                'campaign_id' => $campaign->id,
            ]);
        });
    }
}
