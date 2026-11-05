<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_are_forbidden_to_customers(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pelanggan']))
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_dashboard_and_reports_render_sales_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->id, 'price' => 30000]);
        $order = Order::factory()->create([
            'status' => 'selesai',
            'total_price' => 60000,
        ]);
        $order->orderItems()->create([
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price' => 30000,
            'subtotal' => 60000,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Omzet harian')
            ->assertSee($menu->name);

        $this->get(route('admin.reports.index', ['period' => 'bulanan']))
            ->assertOk()
            ->assertSee('Rp60.000')
            ->assertSee($menu->name);
    }

    public function test_all_admin_management_pages_render_for_an_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create();
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->id]);
        $order = Order::factory()->create(['user_id' => $customer->id]);

        $this->actingAs($admin);

        foreach ([
            route('admin.menus.index'),
            route('admin.menus.create'),
            route('admin.menus.edit', $menu),
            route('admin.categories.index'),
            route('admin.orders.index'),
            route('admin.orders.show', $order),
            route('admin.users.index'),
            route('admin.reviews.index'),
            route('admin.reports.index'),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_can_create_and_delete_a_category_without_menus(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('admin.categories.store'), ['name' => 'Hidangan Flores'])
            ->assertRedirect();
        $category = Category::where('name', 'Hidangan Flores')->firstOrFail();
        $this->assertSame('hidangan-flores', $category->slug);

        $this->delete(route('admin.categories.destroy', $category))->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_a_category_that_has_menus(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        Menu::factory()->create(['category_id' => $category->id]);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_can_create_a_menu_with_a_validated_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.menus.store'), [
                'category_id' => $category->id,
                'name' => 'Sei Sapi Demo',
                'description' => 'Daging asap khas Timor.',
                'story' => 'Daging diasap perlahan dengan kayu.',
                'origin_region' => 'Kupang',
                'main_ingredients' => 'Daging sapi dan rempah',
                'price' => 65000,
                'is_available' => 1,
                'image' => UploadedFile::fake()->image('sei.jpg'),
            ])
            ->assertRedirect();

        $menu = Menu::where('name', 'Sei Sapi Demo')->firstOrFail();
        $this->assertSame('sei-sapi-demo', $menu->slug);
        $this->assertNotNull($menu->image);
        Storage::disk('public')->assertExists($menu->image);
    }

    public function test_admin_cannot_delete_a_menu_used_by_an_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->id]);
        $order = Order::factory()->create();
        $order->orderItems()->create([
            'menu_id' => $menu->id,
            'quantity' => 1,
            'price' => $menu->price,
            'subtotal' => $menu->price,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.menus.destroy', $menu))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('menus', ['id' => $menu->id]);
    }

    public function test_order_status_must_follow_the_allowed_workflow(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->create(['status' => 'menunggu']);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status', $order), ['status' => 'selesai'])
            ->assertSessionHas('error');
        $this->assertSame('menunggu', $order->fresh()->status);

        $this->patch(route('admin.orders.status', $order), ['status' => 'diproses'])
            ->assertSessionHas('success');
        $this->assertSame('diproses', $order->fresh()->status);
    }

    public function test_admin_cannot_remove_their_own_role_or_delete_users_with_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'pelanggan']);
        Order::factory()->create(['user_id' => $customer->id]);

        $this->actingAs($admin)
            ->patch(route('admin.users.role', $admin), ['role' => 'pelanggan'])
            ->assertSessionHas('error');
        $this->assertSame('admin', $admin->fresh()->role);

        $this->delete(route('admin.users.destroy', $customer))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $customer->id]);
    }

    public function test_admin_can_moderate_reviews(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create();
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->id]);
        $review = Review::factory()->create([
            'user_id' => $customer->id,
            'menu_id' => $menu->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reviews.index'))
            ->assertOk()
            ->assertSee($menu->name);

        $this->delete(route('admin.reviews.destroy', $review))->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
