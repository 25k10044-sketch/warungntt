<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Menu;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Menu $menu): RedirectResponse
    {
        $hasCompletedOrder = $request->user()->orders()
            ->where('status', 'selesai')
            ->whereHas('orderItems', fn ($query) => $query->where('menu_id', $menu->id))
            ->exists();

        abort_unless($hasCompletedOrder, 403, 'Ulasan hanya dapat diberikan untuk menu dari pesanan yang selesai.');

        Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'menu_id' => $menu->id],
            $request->validated(),
        );

        return back()->with('success', 'Terima kasih, ulasan Anda berhasil disimpan.');
    }
}
