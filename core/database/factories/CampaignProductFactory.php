<?php



namespace Database\Factories;

use App\Models\CampaignProduct;
use App\Models\Campaign;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignProductFactory extends Factory
{
    protected $model = CampaignProduct::class;

    public function definition()
    {
        // Retrieve existing campaign and product IDs
        $campaignIds = Campaign::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        return [
            'campaign_id' => $this->faker->randomElement($campaignIds),
            'product_id' => $this->faker->randomElement($productIds),
        ];
    }
}

