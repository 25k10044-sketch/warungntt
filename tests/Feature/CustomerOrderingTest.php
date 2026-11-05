<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_filter_available_menu_catalog(): void
    {
        $category = Category::factory()->create(['name' => 'Daging']);
        $menu = Menu::factory()->create([
            'category_id' => $category->id,
            'name' => 'Sei Sapi',
            'price' => 42000,
        ]);
        Menu::factory()->create([
            'category_id' => $category->id,
            'name' => 'Menu Tidak Tersedia',
            'is_available' => false,
        ]);

        $this->get(route('menus.index', ['q' => 'Sei', 'category' => $category->slug]))
            ->assertOk()
            ->assertSee($menu->name)
            ->assertDontSee('Menu Tidak Tersedia');
    }

    public function test_checkout_uses_current_menu_prices_and_clears_the_cart(): void
    {
        $customer = User::factory()->create();
        $category = Category::factory()->create();
        $menu = Menu::factory()->create([
            'category_id' => $category->id,
            'price' => 25000,
        ]);

        $this->post(route('cart.add', $menu), ['quantity' => 2])->assertRedirect();

        $this->actingAs($customer)
            ->post(route('checkout.store'), [
                'payment_method' => 'qris',
                'notes' => 'Sambal dipisah',
                'total_price' => 1,
            ])
            ->assertRedirect();

        $order = Order::query()->firstOrFail();
        $this->assertSame(50000, $order->total_price);
        $this->assertSame('qris', $order->payment_method);
        $this->assertSame(2, $order->orderItems()->firstOrFail()->quantity);
        $this->assertSame(25000, $order->orderItems()->firstOrFail()->price);
        $this->assertSame([], session('cart', []));
    }

    public function test_customer_can_only_view_their_own_order(): void
    {
        $owner = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherCustomer)
            ->get(route('orders.show', $order))
            ->assertForbidden();

        $this->actingAs($owner)
            ->get(route('orders.show', $order))
            ->assertOk();
    }

    public function test_review_requires_a_completed_order_and_can_be_updated_once_per_menu(): void
    {
        $customer = User::factory()->create();
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->id]);
        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'diproses',
        ]);
        $order->orderItems()->create([
            'menu_id' => $menu->id,
            'quantity' => 1,
            'price' => $menu->price,
            'subtotal' => $menu->price,
        ]);

        $this->actingAs($customer)
            ->post(route('reviews.store', $menu), ['rating' => 5, 'comment' => 'Enak'])
            ->assertForbidden();

        $order->update(['status' => 'selesai']);

        $this->post(route('reviews.store', $menu), ['rating' => 4, 'comment' => 'Rasanya mantap'])
            ->assertRedirect();
        $this->post(route('reviews.store', $menu), ['rating' => 5, 'comment' => 'Sangat enak'])
            ->assertRedirect();

        $this->assertDatabaseCount('reviews', 1);
        $this->assertDatabaseHas('reviews', [
            'user_id' => $customer->id,
            'menu_id' => $menu->id,
            'rating' => 5,
            'comment' => 'Sangat enak',
        ]);
    }

    public function test_customer_can_toggle_a_favorite(): void
    {
        $customer = User::factory()->create();
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->id]);

        $this->actingAs($customer)->post(route('favorites.toggle', $menu))->assertRedirect();
        $this->assertDatabaseHas('favorites', ['user_id' => $customer->id, 'menu_id' => $menu->id]);

        $this->post(route('favorites.toggle', $menu))->assertRedirect();
        $this->assertDatabaseMissing('favorites', ['user_id' => $customer->id, 'menu_id' => $menu->id]);
    }
}
