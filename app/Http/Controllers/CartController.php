<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Menu;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(Request $request): View
    {
        $items = $this->cart->items($request);
        $total = $items->sum('cart_subtotal');

        return view('store.cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'between:1,99'],
        ]);

        abort_unless($menu->is_available, 404);

        $cart = $this->cart->quantities($request);
        $quantity = $cart[$menu->id] ?? 0;
        $quantity += $validated['quantity'] ?? 1;

        if ($quantity > 99) {
            throw ValidationException::withMessages([
                'quantity' => 'Jumlah maksimal untuk satu menu adalah 99.',
            ]);
        }

        $cart[$menu->id] = $quantity;
        $request->session()->put('cart', $cart);

        return back()->with('success', "{$menu->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'integer', 'between:0,99'],
        ]);

        $cart = $this->cart->quantities($request);
        $allowedIds = array_map('intval', array_keys($cart));
        $submittedIds = array_map('intval', array_keys($validated['quantities']));

        if (array_diff($submittedIds, $allowedIds) !== []) {
            throw ValidationException::withMessages([
                'quantities' => 'Keranjang berubah. Muat ulang halaman dan coba lagi.',
            ]);
        }

        foreach ($validated['quantities'] as $menuId => $quantity) {
            if ($quantity === 0) {
                unset($cart[(int) $menuId]);
            } else {
                $cart[(int) $menuId] = $quantity;
            }
        }

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(Request $request, Menu $menu): RedirectResponse
    {
        $cart = $this->cart->quantities($request);
        unset($cart[$menu->id]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Menu dihapus dari keranjang.');
    }

    public function checkout(Request $request): View
    {
        $items = $this->cart->items($request);
        abort_if($items->isEmpty(), 404);

        $total = $items->sum('cart_subtotal');

        return view('store.cart.checkout', compact('items', 'total'));
    }

    public function placeOrder(CheckoutRequest $request): RedirectResponse
    {
        $quantities = $this->cart->quantities($request);
        if ($quantities === []) {
            throw ValidationException::withMessages(['cart' => 'Keranjang Anda masih kosong.']);
        }

        $order = DB::transaction(function () use ($request, $quantities): Order {
            $menus = Menu::query()
                ->whereIn('id', array_keys($quantities))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($menus->count() !== count($quantities) || $menus->contains(fn (Menu $menu) => ! $menu->is_available)) {
                throw ValidationException::withMessages([
                    'cart' => 'Sebagian menu tidak lagi tersedia. Perbarui keranjang sebelum checkout.',
                ]);
            }

            $total = 0;
            foreach ($quantities as $menuId => $quantity) {
                $total += $menus[$menuId]->price * $quantity;
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_code' => 'WNTT-'.Str::upper(Str::random(10)),
                'total_price' => $total,
                'status' => 'menunggu',
                'payment_method' => $request->validated('payment_method'),
                'notes' => $request->validated('notes'),
            ]);

            foreach ($quantities as $menuId => $quantity) {
                $price = $menus[$menuId]->price;
                $order->orderItems()->create([
                    'menu_id' => $menuId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity,
                ]);
            }

            return $order;
        });

        $request->session()->forget('cart');

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat.');
    }
}
