<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('menus')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['name']);
        Category::create($data);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['name'], $category);
        $category->update($data);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->menus()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus selama masih memiliki menu.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    private function uniqueSlug(string $value, ?Category $category = null): string
    {
        $base = Str::slug($value)
            ?: Str::slug($category?->name ?? $value)
            ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 1;

        while (Category::where('slug', $slug)->when($category, fn ($query) => $query->whereKeyNot($category->id))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
