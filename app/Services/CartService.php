<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CartService
{
    public function items(Request $request): Collection
    {
        $quantities = $this->quantities($request);

        if ($quantities === []) {
            return new Collection();
        }

        $items = Menu::query()
            ->with('category')
            ->whereIn('id', array_keys($quantities))
            ->get();
        $existingIds = $items->modelKeys();
        $missingIds = array_diff(array_keys($quantities), $existingIds);

        if ($missingIds !== []) {
            foreach ($missingIds as $missingId) {
                unset($quantities[$missingId]);
            }
            $request->session()->put('cart', $quantities);
        }

        return $items->each(function (Menu $menu) use ($quantities): void {
                $menu->setAttribute('cart_quantity', $quantities[$menu->id]);
                $menu->setAttribute('cart_subtotal', $menu->price * $quantities[$menu->id]);
            });
    }

    public function quantities(Request $request): array
    {
        $cart = $request->session()->get('cart', []);

        if (! is_array($cart)) {
            return [];
        }

        $quantities = [];
        foreach ($cart as $menuId => $quantity) {
            if (filter_var($menuId, FILTER_VALIDATE_INT) && filter_var($quantity, FILTER_VALIDATE_INT)) {
                $quantity = (int) $quantity;
                if ($quantity > 0 && $quantity <= 99) {
                    $quantities[(int) $menuId] = $quantity;
                }
            }
        }

        return $quantities;
    }
}
