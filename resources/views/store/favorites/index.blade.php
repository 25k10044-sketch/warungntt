<x-app-layout>
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">Simpan untuk nanti</p>
        <h1 class="mt-2 font-serif text-4xl font-bold text-[#1F1A17]">Menu favorit</h1>
        @if ($favorites->isEmpty())
            <div class="mt-8 rounded-2xl border border-dashed border-[#cbbd9e] bg-white p-10 text-center">
                <p class="text-5xl" aria-hidden="true">♡</p>
                <h2 class="mt-4 font-serif text-2xl font-bold text-[#1F1A17]">Belum ada menu favorit</h2>
                <p class="mt-2 text-stone-600">Simpan hidangan yang ingin kamu coba di lain waktu.</p>
                <a href="{{ route('menus.index') }}" class="mt-5 inline-flex min-h-11 items-center rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white">Temukan menu</a>
            </div>
        @else
            <div class="mt-7 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($favorites as $favorite)
                    <x-menu-card :menu="$favorite->menu" />
                @endforeach
            </div>
            <div class="mt-8">{{ $favorites->links() }}</div>
        @endif
    </section>
</x-app-layout>
