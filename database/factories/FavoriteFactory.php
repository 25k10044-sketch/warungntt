<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'menu_id' => Menu::factory(),
        ];
    }
}
