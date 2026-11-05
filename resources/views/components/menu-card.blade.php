@props(['menu'])

<article class="overflow-hidden rounded-2xl border border-[#eadfca] bg-white shadow-sm">
    <a href="{{ route('menus.show', $menu) }}" class="block">
        @if ($menu->image)
            <img class="h-48 w-full object-cover" src="{{ asset('storage/'.$menu->image) }}" alt="{{ $menu->name }}">
        @else
            <div class="flex h-48 items-center justify-center bg-[#efe4cc] text-5xl" role="img" aria-label="Foto {{ $menu->name }} belum tersedia">🍲</div>
        @endif
    </a>
    <div class="p-5">
        <div class="mb-2 flex items-center justify-between gap-3 text-xs font-semibold uppercase tracking-wide text-[#7A1F1F]">
            <span>{{ $menu->category->name }}</span>
            <span>{{ $menu->origin_region }}</span>
        </div>
        <a href="{{ route('menus.show', $menu) }}" class="font-serif text-xl font-bold text-[#1F1A17] hover:text-[#7A1F1F]">{{ $menu->name }}</a>
        <p class="mt-2 line-clamp-2 min-h-12 text-sm text-stone-600">{{ $menu->description }}</p>
        <div class="mt-4 flex items-center justify-between">
            <div>
                <p class="font-bold text-[#7A1F1F]">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-stone-500">★ {{ number_format((float) ($menu->reviews_avg_rating ?? 0), 1) }}</p>
            </div>
            <form method="POST" action="{{ route('cart.add', $menu) }}">
                @csrf
                <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-4 text-sm font-semibold text-white hover:bg-[#5f1717] focus:outline-none focus:ring-2 focus:ring-[#D9A441]" aria-label="Tambah {{ $menu->name }} ke keranjang">
                    + Keranjang
                </button>
            </form>
        </div>
    </div>
</article>
