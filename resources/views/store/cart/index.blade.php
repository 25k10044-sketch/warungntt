<x-app-layout>
    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">Belanjaan Anda</p>
        <h1 class="mt-2 font-serif text-4xl font-bold text-[#1F1A17]">Keranjang</h1>

        @if ($items->isEmpty())
            <div class="mt-8 rounded-2xl border border-dashed border-[#cbbd9e] bg-white p-10 text-center">
                <p class="text-5xl" aria-hidden="true">🧺</p>
                <h2 class="mt-4 font-serif text-2xl font-bold text-[#1F1A17]">Keranjangmu masih kosong</h2>
                <p class="mt-2 text-stone-600">Yuk, pilih hidangan khas NTT untuk menemani harimu.</p>
                <a href="{{ route('menus.index') }}" class="mt-5 inline-flex min-h-11 items-center rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white">Jelajahi menu</a>
            </div>
        @else
            <form method="POST" action="{{ route('cart.update') }}" class="mt-8">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    @foreach ($items as $item)
                        <article class="grid gap-4 rounded-2xl border border-[#eadfca] bg-white p-4 sm:grid-cols-[1fr_auto] sm:items-center">
                            <div>
                                <a href="{{ route('menus.show', $item) }}" class="font-serif text-xl font-bold text-[#1F1A17] hover:text-[#7A1F1F]">{{ $item->name }}</a>
                                <p class="mt-1 text-sm text-stone-500">{{ $item->category->name }} · {{ $item->origin_region }}</p>
                                <p class="mt-2 font-semibold text-[#7A1F1F]">Rp{{ number_format($item->price, 0, ',', '.') }} / porsi</p>
                                @unless ($item->is_available)
                                    <p class="mt-2 text-sm font-semibold text-red-700">Menu sedang tidak tersedia.</p>
                                @endunless
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <label class="sr-only" for="quantity-{{ $item->id }}">Jumlah {{ $item->name }}</label>
                                <input id="quantity-{{ $item->id }}" type="number" min="0" max="99" name="quantities[{{ $item->id }}]" value="{{ $item->cart_quantity }}" class="min-h-11 w-24 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                                <span class="min-w-28 text-right font-bold text-[#1F1A17]">Rp{{ number_format($item->cart_subtotal, 0, ',', '.') }}</span>
                                <button form="remove-{{ $item->id }}" class="min-h-11 rounded-lg border border-red-200 px-3 text-sm font-semibold text-red-700 hover:bg-red-50">Hapus</button>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-5 flex flex-wrap items-center justify-between gap-4">
                    <button class="min-h-11 rounded-lg border border-[#7A1F1F] px-5 font-semibold text-[#7A1F1F] hover:bg-[#FAF3E3]">Perbarui jumlah</button>
                    <p class="text-lg font-bold text-[#1F1A17]">Total <span class="ml-2 text-[#7A1F1F]">Rp{{ number_format($total, 0, ',', '.') }}</span></p>
                </div>
            </form>

            @foreach ($items as $item)
                <form id="remove-{{ $item->id }}" method="POST" action="{{ route('cart.remove', $item) }}" onsubmit="return confirm('Hapus {{ $item->name }} dari keranjang?')">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach

            @if ($items->every(fn ($item) => $item->is_available))
                <div class="mt-6 flex justify-end">
                    @auth
                        @if (auth()->user()->role === 'pelanggan')
                            <a href="{{ route('checkout.index') }}" class="inline-flex min-h-12 items-center rounded-lg bg-[#7A1F1F] px-6 font-bold text-white hover:bg-[#5f1717]">Lanjut ke checkout</a>
                        @else
                            <p class="text-sm text-stone-600">Login sebagai pelanggan untuk melanjutkan checkout.</p>
                        @endif
                    @else
                        <a href="{{ route('login', ['redirect' => route('checkout.index')]) }}" class="inline-flex min-h-12 items-center rounded-lg bg-[#7A1F1F] px-6 font-bold text-white hover:bg-[#5f1717]">Login untuk checkout</a>
                    @endauth
                </div>
            @else
                <p class="mt-5 text-right text-sm text-red-700">Hapus menu yang tidak tersedia sebelum checkout.</p>
            @endif
        @endif
    </section>
</x-app-layout>
