<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $price = fake()->numberBetween(10000, 150000);

        return [
            'order_id' => Order::factory(),
            'menu_id' => Menu::factory(),
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $quantity * $price,
        ];
    }
}
