<x-app-layout>
    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('menus.index') }}" class="text-sm font-semibold text-[#7A1F1F] hover:underline">← Kembali ke menu</a>
        <div class="mt-5 grid gap-8 lg:grid-cols-2">
            <div class="overflow-hidden rounded-3xl bg-[#efe4cc]">
                @if ($menu->image)
                    <img class="h-full min-h-80 w-full object-cover" src="{{ asset('storage/'.$menu->image) }}" alt="{{ $menu->name }}">
                @else
                    <div class="flex min-h-80 items-center justify-center text-8xl" role="img" aria-label="Foto {{ $menu->name }} belum tersedia">🍲</div>
                @endif
            </div>
            <div class="rounded-3xl border border-[#eadfca] bg-white p-6 sm:p-8">
                <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">{{ $menu->category->name }} · {{ $menu->origin_region }}</p>
                <h1 class="mt-3 font-serif text-4xl font-bold text-[#1F1A17]">{{ $menu->name }}</h1>
                <p class="mt-4 text-lg leading-7 text-stone-600">{{ $menu->description }}</p>
                <p class="mt-5 text-2xl font-bold text-[#7A1F1F]">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                <p class="mt-2 text-sm text-stone-600">★ {{ number_format($rating, 1) }} dari {{ $menu->reviews->count() }} ulasan</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('cart.add', $menu) }}">
                        @csrf
                        <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white hover:bg-[#5f1717]">Tambah ke keranjang</button>
                    </form>
                    @auth
                        @if (auth()->user()->role === 'pelanggan')
                            <form method="POST" action="{{ route('favorites.toggle', $menu) }}">
                                @csrf
                                <button class="min-h-11 rounded-lg border border-[#7A1F1F] px-5 font-semibold text-[#7A1F1F] hover:bg-[#FAF3E3]">
                                    {{ $isFavorite ? '♥ Tersimpan' : '♡ Simpan favorit' }}
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
                <div class="mt-8 border-t border-[#eadfca] pt-6">
                    <h2 class="font-serif text-xl font-bold text-[#1F1A17]">Bahan utama</h2>
                    <p class="mt-2 leading-6 text-stone-600">{{ $menu->main_ingredients }}</p>
                </div>
                <div class="mt-6">
                    <h2 class="font-serif text-xl font-bold text-[#1F1A17]">Cerita dari daerah</h2>
                    <p class="mt-2 leading-6 text-stone-600">{{ $menu->story }}</p>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <h2 class="font-serif text-3xl font-bold text-[#1F1A17]">Ulasan pelanggan</h2>
            @if ($menu->reviews->isNotEmpty())
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ($menu->reviews as $review)
                        <article class="rounded-2xl border border-[#eadfca] bg-white p-5">
                            <div class="flex justify-between gap-4">
                                <p class="font-semibold text-[#1F1A17]">{{ $review->user->name }}</p>
                                <p class="text-[#7A1F1F]" aria-label="Rating {{ $review->rating }} dari 5">★ {{ $review->rating }}/5</p>
                            </div>
                            <p class="mt-2 text-sm leading-6 text-stone-600">{{ $review->comment ?: 'Pelanggan menyukai menu ini.' }}</p>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="mt-4 text-stone-600">Belum ada ulasan untuk menu ini.</p>
            @endif
            @if ($reviewed)
                <p class="mt-5 text-sm text-stone-600">Anda sudah pernah mengulas menu ini.</p>
            @elseif (auth()->check() && auth()->user()->role === 'pelanggan')
                <p class="mt-5 text-sm text-stone-600">Ulasan dapat diberikan setelah pesanan berstatus selesai.</p>
            @endif
        </div>
    </section>
</x-app-layout>
