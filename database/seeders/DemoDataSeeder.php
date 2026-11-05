<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo selalu memakai role tetap; role pelanggan tidak berasal dari input registrasi.
        User::updateOrCreate(
            ['email' => 'admin@warungntt.test'],
            [
                'name' => 'Admin Warung NTT',
                'password' => 'Admin12345',
                'role' => 'admin',
                'google_id' => null,
                'avatar' => null,
                'email_verified_at' => now(),
            ],
        );

        $customer = User::updateOrCreate(
            ['email' => 'pelanggan@warungntt.test'],
            [
                'name' => 'Pelanggan Demo',
                'password' => 'Pelanggan12345',
                'role' => 'pelanggan',
                'google_id' => null,
                'avatar' => null,
                'email_verified_at' => now(),
            ],
        );

        $sei = Menu::where('slug', 'sei-sapi')->firstOrFail();
        $bose = Menu::where('slug', 'jagung-bose')->firstOrFail();
        $ikan = Menu::where('slug', 'ikan-kuah-asam')->firstOrFail();
        $kolo = Menu::where('slug', 'kolo')->firstOrFail();

        $completedOrder = Order::updateOrCreate(
            ['order_code' => 'WNTT-DEMO-001'],
            [
                'user_id' => $customer->id,
                'total_price' => ($sei->price * 2) + $bose->price,
                'status' => 'selesai',
                'payment_method' => 'cod',
                'notes' => 'Contoh pesanan selesai untuk demonstrasi.',
            ],
        );
        $completedOrder->orderItems()->updateOrCreate(
            ['menu_id' => $sei->id],
            ['quantity' => 2, 'price' => $sei->price, 'subtotal' => $sei->price * 2],
        );
        $completedOrder->orderItems()->updateOrCreate(
            ['menu_id' => $bose->id],
            ['quantity' => 1, 'price' => $bose->price, 'subtotal' => $bose->price],
        );

        $processingOrder = Order::updateOrCreate(
            ['order_code' => 'WNTT-DEMO-002'],
            [
                'user_id' => $customer->id,
                'total_price' => $ikan->price,
                'status' => 'diproses',
                'payment_method' => 'transfer',
                'notes' => 'Contoh pesanan yang sedang diproses.',
            ],
        );
        $processingOrder->orderItems()->updateOrCreate(
            ['menu_id' => $ikan->id],
            ['quantity' => 1, 'price' => $ikan->price, 'subtotal' => $ikan->price],
        );

        $waitingOrder = Order::updateOrCreate(
            ['order_code' => 'WNTT-DEMO-003'],
            [
                'user_id' => $customer->id,
                'total_price' => $kolo->price,
                'status' => 'menunggu',
                'payment_method' => 'qris',
                'notes' => null,
            ],
        );
        $waitingOrder->orderItems()->updateOrCreate(
            ['menu_id' => $kolo->id],
            ['quantity' => 1, 'price' => $kolo->price, 'subtotal' => $kolo->price],
        );

        Review::updateOrCreate(
            ['user_id' => $customer->id, 'menu_id' => $sei->id],
            ['rating' => 5, 'comment' => 'Se’i-nya harum dan sambalnya segar.'],
        );
        Review::updateOrCreate(
            ['user_id' => $customer->id, 'menu_id' => $bose->id],
            ['rating' => 5, 'comment' => 'Jagung bose lembut dan mengenyangkan.'],
        );

        Favorite::firstOrCreate([
            'user_id' => $customer->id,
            'menu_id' => $sei->id,
        ]);
    }
}
