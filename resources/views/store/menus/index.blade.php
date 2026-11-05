<x-app-layout>
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div>
            <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">Dari dapur NTT</p>
            <h1 class="mt-2 font-serif text-4xl font-bold text-[#1F1A17]">Jelajahi menu</h1>
        </div>
        <form method="GET" action="{{ route('menus.index') }}" class="mt-7 grid gap-3 rounded-2xl border border-[#eadfca] bg-white p-4 sm:grid-cols-2 lg:grid-cols-4">
            <label class="sr-only" for="menu-search">Cari menu</label>
            <input id="menu-search" name="q" value="{{ $filters['q'] ?? '' }}" type="search" placeholder="Cari nama, bahan, daerah..." class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
            <label class="sr-only" for="category">Kategori</label>
            <select id="category" name="category" class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
            <label class="sr-only" for="sort">Urutkan</label>
            <select id="sort" name="sort" class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                <option value="popular" @selected(($filters['sort'] ?? 'popular') === 'popular')>Terpopuler</option>
                <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Terbaru</option>
                <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Harga terendah</option>
                <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Harga tertinggi</option>
            </select>
            <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white hover:bg-[#5f1717]">Cari menu</button>
        </form>

        @if ($menus->isNotEmpty())
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($menus as $menu)
                    <x-menu-card :menu="$menu" />
                @endforeach
            </div>
            <div class="mt-8">{{ $menus->links() }}</div>
        @else
            <div class="mt-8 rounded-2xl border border-dashed border-[#cbbd9e] bg-white p-10 text-center">
                <p class="font-serif text-2xl font-bold text-[#1F1A17]">Belum menemukan menu yang cocok</p>
                <p class="mt-2 text-stone-600">Coba ganti kata pencarian atau pilih kategori lain.</p>
                <a href="{{ route('menus.index') }}" class="mt-5 inline-flex min-h-11 items-center rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white">Lihat semua menu</a>
            </div>
        @endif
    </section>
</x-app-layout>
