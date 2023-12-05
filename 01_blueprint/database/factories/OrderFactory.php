<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Order;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'orderNumber' => $this->faker->numberBetween(-10000, 10000),
            'deliveryAddress' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'customer_id' => Customer::factory(),
        ];
    }
}
