<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\LineItem;
use App\Models\Order;

class LineItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LineItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'productCode' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'pricePerUnit' => $this->faker->randomFloat(2, 0, 999999.99),
            'order_id' => Order::factory(),
        ];
    }
}
