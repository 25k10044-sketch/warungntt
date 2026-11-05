@php
    $cartCount = is_array(session('cart')) ? array_sum(session('cart', [])) : 0;
@endphp
<nav x-data="{ open: false }" class="border-b border-[#eadfca] bg-[#FAF3E3]">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-16 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2" aria-label="Warung NTT, beranda">
                <span class="flex size-10 items-center justify-center rounded-full bg-[#7A1F1F] font-serif text-lg font-bold text-[#D9A441]">N</span>
                <span class="font-serif text-xl font-bold text-[#1F1A17]">Warung NTT</span>
            </a>

            <div class="hidden items-center gap-1 lg:flex">
                <a href="{{ route('home') }}" class="min-h-11 rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('home') ? 'bg-white text-[#7A1F1F]' : 'text-[#1F1A17] hover:bg-white' }}">Beranda</a>
                <a href="{{ route('menus.index') }}" class="min-h-11 rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('menus.*') ? 'bg-white text-[#7A1F1F]' : 'text-[#1F1A17] hover:bg-white' }}">Menu</a>
                <a href="{{ route('cart.index') }}" class="inline-flex min-h-11 items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-[#1F1A17] hover:bg-white">
                    Keranjang
                    @if ($cartCount > 0)
                        <span class="inline-flex min-w-5 items-center justify-center rounded-full bg-[#D9A441] px-1.5 py-0.5 text-xs text-[#1F1A17]">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    @if (auth()->user()->role === 'pelanggan')
                        <a href="{{ route('favorites.index') }}" class="min-h-11 rounded-lg px-3 py-2 text-sm font-semibold text-[#1F1A17] hover:bg-white">Favorit</a>
                        <a href="{{ route('orders.index') }}" class="min-h-11 rounded-lg px-3 py-2 text-sm font-semibold text-[#1F1A17] hover:bg-white">Pesanan Saya</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="min-h-11 rounded-lg px-3 py-2 text-sm font-semibold text-[#7A1F1F] hover:bg-white">Admin</a>
                    @endif
                @endauth
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                @auth
                    <a href="{{ route('profile.edit') }}" class="min-h-11 rounded-lg px-3 py-2 text-sm font-semibold text-[#1F1A17] hover:bg-white">{{ Auth::user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="min-h-11 rounded-lg border border-[#7A1F1F] px-4 text-sm font-semibold text-[#7A1F1F] hover:bg-white">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="min-h-11 rounded-lg px-3 py-2 text-sm font-semibold text-[#7A1F1F] hover:bg-white">Masuk</a>
                    <a href="{{ route('register') }}" class="inline-flex min-h-11 items-center rounded-lg bg-[#7A1F1F] px-4 text-sm font-semibold text-white hover:bg-[#5f1717]">Daftar</a>
                @endauth
            </div>

            <button type="button" @click="open = !open" class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-lg border border-[#cbbd9e] text-[#1F1A17] lg:hidden" aria-label="Buka navigasi" :aria-expanded="open.toString()">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div x-cloak x-show="open" class="space-y-1 border-t border-[#eadfca] py-3 lg:hidden">
            <a href="{{ route('home') }}" class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#1F1A17] hover:bg-white">Beranda</a>
            <a href="{{ route('menus.index') }}" class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#1F1A17] hover:bg-white">Menu</a>
            <a href="{{ route('cart.index') }}" class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#1F1A17] hover:bg-white">Keranjang ({{ $cartCount }})</a>
            @auth
                @if (auth()->user()->role === 'pelanggan')
                    <a href="{{ route('favorites.index') }}" class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#1F1A17] hover:bg-white">Favorit</a>
                    <a href="{{ route('orders.index') }}" class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#1F1A17] hover:bg-white">Pesanan Saya</a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#7A1F1F] hover:bg-white">Admin</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#1F1A17] hover:bg-white">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#7A1F1F] hover:bg-white">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#7A1F1F] hover:bg-white">Masuk</a>
                <a href="{{ route('register') }}" class="block min-h-11 rounded-lg px-3 py-2 font-semibold text-[#7A1F1F] hover:bg-white">Daftar</a>
            @endauth
        </div>
    </div>
</nav>
<div class="tenun-pattern h-2" aria-hidden="true"></div>
