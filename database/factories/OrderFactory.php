<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_code' => 'WNTT-'.Str::upper(Str::random(10)),
            'total_price' => fake()->numberBetween(10000, 300000),
            'status' => 'menunggu',
            'payment_method' => fake()->randomElement(['cod', 'transfer', 'qris']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
