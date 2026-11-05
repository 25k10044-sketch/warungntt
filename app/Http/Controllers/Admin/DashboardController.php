<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalOrders = Order::count();
        $revenue = Order::where('status', 'selesai')->sum('total_price');
        $menuCount = Menu::where('is_available', true)->count();
        $customerCount = User::where('role', 'pelanggan')->count();

        $latestOrders = Order::with('user')->latest()->take(8)->get();
        $bestSellers = OrderItem::query()
            ->select('menu_id', DB::raw('SUM(quantity) as quantity_sold'))
            ->whereHas('order', fn ($query) => $query->where('status', '!=', 'dibatalkan'))
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('quantity_sold')
            ->take(5)
            ->get();

        $dailyRevenue = Order::query()
            ->selectRaw('DATE(created_at) as sales_date, SUM(total_price) as total')
            ->where('status', 'selesai')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('sales_date')
            ->pluck('total', 'sales_date');

        $chartLabels = [];
        $chartValues = [];
        for ($offset = 6; $offset >= 0; $offset--) {
            $date = Carbon::today()->subDays($offset);
            $key = $date->toDateString();
            $chartLabels[] = $date->translatedFormat('D, d M');
            $chartValues[] = (int) ($dailyRevenue[$key] ?? 0);
        }

        return view('admin.dashboard', compact(
            'totalOrders',
            'revenue',
            'menuCount',
            'customerCount',
            'latestOrders',
            'bestSellers',
            'chartLabels',
            'chartValues',
        ));
    }
}
