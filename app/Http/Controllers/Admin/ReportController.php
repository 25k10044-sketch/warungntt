<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'period' => ['nullable', 'in:harian,bulanan'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        $period = $filters['period'] ?? 'harian';
        $defaultFrom = $period === 'harian'
            ? now()->subDays(29)->toDateString()
            : now()->subMonths(11)->startOfMonth()->toDateString();
        $defaultTo = now()->toDateString();
        $from = Carbon::parse($filters['from'] ?? $defaultFrom)->startOfDay();
        $to = Carbon::parse($filters['to'] ?? $defaultTo)->endOfDay();

        $driver = DB::connection()->getDriverName();
        $dateExpression = $period === 'harian'
            ? 'DATE(created_at)'
            : ($driver === 'sqlite'
                ? "strftime('%Y-%m', created_at)"
                : "DATE_FORMAT(created_at, '%Y-%m')");

        $sales = Order::query()
            ->selectRaw("{$dateExpression} as sales_period, SUM(total_price) as total, COUNT(*) as order_count")
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('sales_period')
            ->orderBy('sales_period')
            ->get()
            ->keyBy('sales_period');

        $labels = [];
        $values = [];
        $cursor = $period === 'harian' ? $from->copy()->startOfDay() : $from->copy()->startOfMonth();
        $end = $period === 'harian' ? $to->copy()->startOfDay() : $to->copy()->startOfMonth();
        while ($cursor->lte($end)) {
            $key = $period === 'harian' ? $cursor->toDateString() : $cursor->format('Y-m');
            $labels[] = $period === 'harian' ? $cursor->translatedFormat('d M') : $cursor->translatedFormat('M Y');
            $values[] = (int) ($sales[$key]->total ?? 0);
            $cursor->addDay();
            if ($period === 'bulanan') {
                $cursor->subDay()->addMonthNoOverflow()->startOfMonth();
            }
        }

        $bestSellers = OrderItem::query()
            ->select('menu_id', DB::raw('SUM(quantity) as quantity_sold'), DB::raw('SUM(subtotal) as revenue'))
            ->whereHas('order', fn ($query) => $query
                ->where('status', 'selesai')
                ->whereBetween('created_at', [$from, $to]))
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('quantity_sold')
            ->take(10)
            ->get();

        $totalRevenue = (int) $sales->sum('total');
        $completedOrders = (int) $sales->sum('order_count');

        return view('admin.reports.index', compact(
            'period',
            'from',
            'to',
            'labels',
            'values',
            'bestSellers',
            'totalRevenue',
            'completedOrders',
        ));
    }
}
