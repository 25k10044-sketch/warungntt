@props(['title'])

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#7A1F1F">
        <title>{{ $title }} · Admin Warung NTT</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|playfair-display:500,600,700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="font-sans antialiased text-[#1F1A17]">
        <a href="#admin-main" class="sr-only z-[100] rounded-lg bg-white p-3 font-semibold text-[#7A1F1F] focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Lewati ke konten admin</a>
        <div class="min-h-screen bg-[#f7f3eb] lg:flex">
            <aside class="tenun-panel text-[#FAF3E3] lg:flex lg:w-64 lg:shrink-0 lg:flex-col">
                <div class="flex min-h-16 items-center justify-between border-b border-white/10 px-5">
                    <a href="{{ route('admin.dashboard') }}" class="font-serif text-xl font-bold text-[#D9A441]">Warung NTT</a>
                    <span class="rounded-full bg-[#7A1F1F] px-3 py-1 text-xs font-bold uppercase tracking-wider">Admin</span>
                </div>
                <nav aria-label="Navigasi admin" class="grid grid-cols-2 gap-1 p-3 sm:grid-cols-3 lg:flex lg:flex-col">
                    @foreach ([
                        ['admin.dashboard', 'Ringkasan', 'admin.dashboard'],
                        ['admin.menus.index', 'Kelola Menu', 'admin.menus.*'],
                        ['admin.categories.index', 'Kategori', 'admin.categories.*'],
                        ['admin.orders.index', 'Pesanan', 'admin.orders.*'],
                        ['admin.users.index', 'Pengguna', 'admin.users.*'],
                        ['admin.reports.index', 'Laporan', 'admin.reports.*'],
                        ['admin.reviews.index', 'Moderasi Ulasan', 'admin.reviews.*'],
                    ] as [$route, $label, $active])
                        <a href="{{ route($route) }}" class="flex min-h-11 items-center rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($active) ? 'bg-[#7A1F1F] text-white' : 'text-[#f4e9d4] hover:bg-white/10' }}">{{ $label }}</a>
                    @endforeach
                </nav>
                <div class="mt-auto hidden border-t border-white/10 p-4 lg:block">
                    <a href="{{ route('home') }}" class="block min-h-11 rounded-lg px-3 py-2 text-sm text-[#f4e9d4] hover:bg-white/10">← Lihat toko</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button class="min-h-11 w-full rounded-lg px-3 py-2 text-left text-sm text-[#f4e9d4] hover:bg-white/10">Keluar</button>
                    </form>
                </div>
            </aside>

            <div class="min-w-0 flex-1">
                <header class="flex min-h-16 items-center justify-between gap-4 border-b border-[#eadfca] bg-white px-4 sm:px-6 lg:px-8">
                    <h1 class="font-serif text-xl font-bold sm:text-2xl">{{ $title }}</h1>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="hidden text-stone-600 sm:inline">{{ auth()->user()->name }}</span>
                        <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center rounded-lg border border-[#eadfca] px-3 font-semibold text-[#7A1F1F] hover:bg-[#FAF3E3] lg:hidden">Toko</a>
                    </div>
                </header>
                @if (session('success') || session('error'))
                    <div class="px-4 pt-4 sm:px-6 lg:px-8" role="status" aria-live="polite">
                        <div class="rounded-xl border px-4 py-3 text-sm font-medium {{ session('success') ? 'border-green-200 bg-green-50 text-green-900' : 'border-red-200 bg-red-50 text-red-900' }}">{{ session('success') ?? session('error') }}</div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="px-4 pt-4 sm:px-6 lg:px-8" role="alert">
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                            Ada bagian yang perlu diperiksa:
                            <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    </div>
                @endif
                <main id="admin-main" tabindex="-1" class="p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
                @stack('scripts')
            </div>
        </div>
    </body>
</html>
