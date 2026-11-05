<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $favorites = $request->user()
            ->favorites()
            ->with(['menu.category'])
            ->latest()
            ->paginate(12);

        return view('store.favorites.index', compact('favorites'));
    }

    public function toggle(Request $request, Menu $menu): RedirectResponse
    {
        abort_unless($menu->is_available, 404);

        $favorite = Favorite::query()->firstOrNew([
            'user_id' => $request->user()->id,
            'menu_id' => $menu->id,
        ]);

        if ($favorite->exists) {
            $favorite->delete();
            $message = 'Menu dihapus dari favorit.';
        } else {
            $favorite->save();
            $message = 'Menu ditambahkan ke favorit.';
        }

        return back()->with('success', $message);
    }
}
