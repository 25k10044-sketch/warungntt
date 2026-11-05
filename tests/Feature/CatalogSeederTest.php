<?php

namespace Tests\Feature;

use App\Models\Menu;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_seeder_adds_regional_food_and_can_be_repeated(): void
    {
        $this->seed([CategorySeeder::class, MenuSeeder::class]);
        $this->seed(MenuSeeder::class);

        $this->assertSame(14, Menu::count());
        $this->assertSame(14, Menu::where('is_available', true)->count());
        $this->assertDatabaseHas('menus', ['slug' => 'kapu-pantunnu']);
        $this->assertDatabaseHas('menus', ['slug' => 'kaparak']);
    }

    public function test_empty_catalog_command_seeds_only_when_there_are_no_menus(): void
    {
        $this->artisan('catalog:seed-if-empty')
            ->expectsOutput('Katalog awal berhasil dibuat.')
            ->assertSuccessful();

        $this->assertSame(14, Menu::count());

        $sei = Menu::where('slug', 'sei-sapi')->firstOrFail();
        $sei->update(['name' => 'Nama menu buatan admin', 'is_available' => false]);

        $this->artisan('catalog:seed-if-empty')
            ->expectsOutput('Katalog sudah berisi menu; tidak ada perubahan.')
            ->assertSuccessful();

        $this->assertDatabaseHas('menus', [
            'id' => $sei->id,
            'name' => 'Nama menu buatan admin',
            'is_available' => false,
        ]);
    }
}
