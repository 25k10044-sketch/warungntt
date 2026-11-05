<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    public function test_admin_role_can_access_admin_routes(): void
    {
        Route::middleware(['auth', 'role:admin'])->get('/_test/admin-only', fn () => response()->noContent());

        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->get('/_test/admin-only')
            ->assertNoContent();
    }

    public function test_customer_role_cannot_access_admin_routes(): void
    {
        Route::middleware(['auth', 'role:admin'])->get('/_test/admin-only', fn () => response()->noContent());

        $this->actingAs(User::factory()->make(['role' => 'pelanggan']))
            ->get('/_test/admin-only')
            ->assertForbidden();
    }
}
