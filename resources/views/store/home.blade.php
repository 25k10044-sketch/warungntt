<x-app-layout>
    <section class="bg-[#1F1A17] text-[#FAF3E3]">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-24">
            <div>
                <p class="mb-4 text-sm font-bold uppercase tracking-[0.25em] text-[#D9A441]">Rasa dari Timur Indonesia</p>
                <h1 class="font-serif text-4xl font-bold leading-tight sm:text-6xl">Hangatnya kuliner <span class="text-[#D9A441]">Nusa Tenggara Timur</span></h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-[#f4e9d4]">Nikmati se’i, jagung bose, kolo, dan cerita dari dapur-dapur NTT. Dibuat dengan rasa rumahan, dikirim untuk menemani harimu.</p>
                <a href="{{ route('menus.index') }}" class="mt-8 inline-flex min-h-12 items-center rounded-lg bg-[#D9A441] px-6 font-bold text-[#1F1A17] hover:bg-[#e8bd61]">Jelajahi Menu</a>
            </div>
            <div class="rounded-3xl border border-[#D9A441]/40 bg-[#7A1F1F] p-8 shadow-xl sm:p-12">
                <p class="text-sm font-bold uppercase tracking-widest text-[#f5d992]">Dari tanah Nusa</p>
                <p class="mt-5 font-serif text-3xl font-semibold leading-relaxed">“Setiap hidangan membawa cerita tentang tanah, keluarga, dan kebersamaan.”</p>
                <div class="mt-8 h-2 rounded-full bg-[#D9A441]"></div>
                <p class="mt-4 text-sm text-[#FAF3E3]">Kuliner NTT · Warung NTT</p>
            </div>
        </div>
    </section>

    <div class="tenun-pattern h-2" aria-hidden="true"></div>
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">Pilihan warung</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-[#1F1A17]">Kategori favorit</h2>
            </div>
            <a href="{{ route('menus.index') }}" class="font-semibold text-[#7A1F1F] underline underline-offset-4">Semua menu</a>
        </div>
        <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach ($categories as $category)
                <a href="{{ route('menus.index', ['category' => $category->slug]) }}" class="rounded-2xl border border-[#eadfca] bg-white p-5 transition hover:-translate-y-1 hover:border-[#D9A441]">
                    <span class="text-2xl" aria-hidden="true">{{ ['Daging' => '🥩', 'Ikan & Seafood' => '🐟', 'Sayur & Nasi' => '🌽', 'Jajanan' => '🥮', 'Minuman' => '☕'][$category->name] ?? '🍽️' }}</span>
                    <h3 class="mt-3 font-semibold text-[#1F1A17]">{{ $category->name }}</h3>
                    <p class="mt-1 text-sm text-stone-500">{{ $category->menus_count }} pilihan</p>
                </a>
            @endforeach
        </div>
    </section>

    <div class="tenun-pattern h-2" aria-hidden="true"></div>
    <section class="bg-[#f3ead8]">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">Paling dicari</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-[#1F1A17]">Menu terlaris</h2>
            </div>
            @if ($bestSellers->isNotEmpty())
                <div class="mt-7 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($bestSellers as $menu)
                        <x-menu-card :menu="$menu" />
                    @endforeach
                </div>
            @else
                <p class="mt-6 text-stone-600">Menu pilihan sedang disiapkan. Silakan kembali sebentar lagi.</p>
            @endif
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="rounded-3xl border-l-8 border-[#D9A441] bg-white p-7 shadow-sm sm:p-10">
            <h2 class="font-serif text-3xl font-bold text-[#1F1A17]">Lebih dari sekadar hidangan</h2>
            <p class="mt-4 max-w-3xl leading-7 text-stone-600">Dari jagung yang tumbuh di ladang kering Timor hingga nasi bambu dari Manggarai, kuliner NTT lahir dari kearifan lokal dan semangat berbagi. Warung NTT mengajakmu mengenal ragam rasa itu, satu piring demi satu cerita.</p>
        </div>
    </section>
</x-app-layout>
