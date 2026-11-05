<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const TRANSITIONS = [
        'menunggu' => ['diproses', 'dibatalkan'],
        'diproses' => ['siap', 'dibatalkan'],
        'siap' => ['selesai', 'dibatalkan'],
        'selesai' => [],
        'dibatalkan' => [],
    ];

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', 'in:menunggu,diproses,siap,selesai,dibatalkan'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $orders = Order::query()
            ->with('user')
            ->withCount('orderItems')
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['q'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('order_code', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($query) => $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'filters'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'orderItems.menu']);

        return view('admin.orders.show', [
            'order' => $order,
            'nextStatuses' => self::TRANSITIONS[$order->status] ?? [],
        ]);
    }

    public function updateStatus(OrderStatusRequest $request, Order $order): RedirectResponse
    {
        $next = $request->validated('status');
        $allowed = self::TRANSITIONS[$order->status] ?? [];

        if (! in_array($next, $allowed, true)) {
            return back()->with('error', 'Perubahan status tersebut tidak diizinkan.');
        }

        DB::transaction(function () use ($order, $next): void {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            $allowed = self::TRANSITIONS[$lockedOrder->status] ?? [];

            if (! in_array($next, $allowed, true)) {
                abort(409, 'Status pesanan berubah. Muat ulang halaman dan coba lagi.');
            }

            $lockedOrder->update(['status' => $next]);
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
