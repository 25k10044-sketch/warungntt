<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $reviews = Review::query()
            ->with(['user', 'menu'])
            ->when($filters['rating'] ?? null, fn ($query, int $rating) => $query->where('rating', $rating))
            ->when($filters['q'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('comment', 'like', "%{$search}%")
                        ->orWhereHas('menu', fn ($query) => $query->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn ($query) => $query->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'filters'));
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Ulasan berhasil dimoderasi dan dihapus.');
    }
}
