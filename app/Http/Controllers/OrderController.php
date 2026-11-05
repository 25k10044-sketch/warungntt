<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->withCount('orderItems')
            ->latest()
            ->paginate(10);

        return view('store.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);
        $order->load(['orderItems.menu', 'user']);
        $reviewedMenuIds = auth()->user()->reviews()
            ->whereIn('menu_id', $order->orderItems->pluck('menu_id'))
            ->pluck('menu_id')
            ->all();

        return view('store.orders.show', compact('order', 'reviewedMenuIds'));
    }
}
