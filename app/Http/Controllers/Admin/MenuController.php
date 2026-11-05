<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $menus = Menu::query()
            ->with('category')
            ->withCount('orderItems')
            ->when($filters['q'] ?? null, fn ($query, string $search) => $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.menus.index', compact('menus', 'filters'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.menus.form', ['menu' => new Menu(), 'categories' => $categories]);
    }

    public function store(MenuRequest $request): RedirectResponse
    {
        $data = $this->menuData($request);
        $menu = Menu::create($data);

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.menus.form', compact('menu', 'categories'));
    }

    public function update(MenuRequest $request, Menu $menu): RedirectResponse
    {
        $data = $this->menuData($request, $menu);
        $oldImage = $menu->image;
        $menu->update($data);

        if ($oldImage && $menu->image !== $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        if ($menu->orderItems()->exists()) {
            return back()->with('error', 'Menu tidak dapat dihapus karena sudah tercatat pada pesanan.');
        }

        $image = $menu->image;
        $menu->delete();

        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    private function menuData(MenuRequest $request, ?Menu $menu = null): array
    {
        $data = $request->safe()->except('image');
        $slug = Str::slug($data['slug'] ?? $data['name']) ?: Str::slug($data['name']);
        $baseSlug = $slug;
        $suffix = 1;

        while (Menu::where('slug', $slug)->when($menu, fn ($query) => $query->whereKeyNot($menu->id))->exists()) {
            $slug = $baseSlug.'-'.$suffix++;
        }

        $data['slug'] = $slug;
        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        return $data;
    }
}
