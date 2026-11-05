<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function home(): View
    {
        $categories = Category::query()->withCount('menus')->orderBy('name')->get();
        $bestSellers = Menu::query()
            ->where('is_available', true)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withSum('orderItems as units_sold', 'quantity')
            ->orderByDesc('units_sold')
            ->take(4)
            ->get();

        return view('store.home', compact('categories', 'bestSellers'));
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'exists:categories,slug'],
            'sort' => ['nullable', 'in:popular,newest,price_asc,price_desc'],
        ]);

        $menus = Menu::query()
            ->where('is_available', true)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withSum('orderItems as units_sold', 'quantity')
            ->when($filters['q'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('origin_region', 'like', "%{$search}%")
                        ->orWhere('main_ingredients', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'] ?? null, function ($query, string $slug): void {
                $query->whereHas('category', fn ($query) => $query->where('slug', $slug));
            });

        match ($filters['sort'] ?? 'popular') {
            'newest' => $menus->latest(),
            'price_asc' => $menus->orderBy('price'),
            'price_desc' => $menus->orderByDesc('price'),
            default => $menus->orderByDesc('units_sold')->orderBy('name'),
        };

        $categories = Category::query()->orderBy('name')->get();
        $menus = $menus->paginate(12)->withQueryString();

        return view('store.menus.index', compact('menus', 'categories', 'filters'));
    }

    public function show(Menu $menu): View
    {
        abort_unless($menu->is_available, 404);

        $menu->load([
            'category',
            'reviews' => fn ($query) => $query->with('user')->latest(),
        ]);
        $rating = round((float) $menu->reviews->avg('rating'), 1);
        $isFavorite = auth()->check()
            && auth()->user()->favorites()->where('menu_id', $menu->id)->exists();
        $reviewed = auth()->check()
            && auth()->user()->reviews()->where('menu_id', $menu->id)->exists();

        return view('store.menus.show', compact('menu', 'rating', 'isFavorite', 'reviewed'));
    }
}
