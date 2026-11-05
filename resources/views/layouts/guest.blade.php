<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#7A1F1F">
        <title>{{ config('app.name', 'Warung NTT') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|playfair-display:500,600,700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <a href="#auth-form" class="sr-only z-[100] rounded-lg bg-white p-3 font-semibold text-[#7A1F1F] focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Lewati ke formulir</a>
        <main id="auth-form" class="min-h-screen bg-[#FAF3E3] lg:grid lg:grid-cols-[1fr_1fr]">
            <section class="tenun-panel relative hidden min-h-screen flex-col justify-between overflow-hidden px-10 py-12 text-[#FAF3E3] lg:flex xl:px-16">
                <a href="{{ route('home') }}" class="relative z-10 inline-flex items-center gap-3" aria-label="Kembali ke beranda Warung NTT">
                    <span class="flex size-12 items-center justify-center rounded-full border border-[#D9A441] bg-[#7A1F1F] font-serif text-2xl font-bold text-[#D9A441]">N</span>
                    <span class="font-serif text-2xl font-bold">Warung NTT</span>
                </a>
                <div class="relative z-10 max-w-xl">
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#D9A441]">Cerita dari timur</p>
                    <p class="mt-5 font-serif text-5xl font-semibold leading-tight">Rasa rumah, hangatnya Nusa Tenggara Timur.</p>
                    <p class="mt-6 max-w-lg text-lg leading-8 text-[#f4e9d4]">Temukan se’i, jagung bose, dan hidangan istimewa yang membawa cerita setiap daerah ke meja makanmu.</p>
                </div>
                <div class="tenun-pattern relative z-10 h-3 w-36 rounded-full" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -bottom-40 -right-32 size-[32rem] rounded-full border border-[#D9A441]/30" aria-hidden="true"></div>
            </section>

            <section class="flex min-h-screen flex-col items-center justify-center px-4 py-10 sm:px-8">
                <div class="mb-6 flex w-full max-w-lg items-center justify-between lg:hidden">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-serif text-lg font-bold text-[#1F1A17]">
                        <span class="flex size-9 items-center justify-center rounded-full bg-[#7A1F1F] text-lg text-[#D9A441]">N</span>
                        Warung NTT
                    </a>
                    <a href="{{ route('home') }}" class="min-h-11 rounded-lg px-3 py-2 text-sm font-semibold text-[#7A1F1F] hover:bg-white">← Beranda</a>
                </div>
                <div class="w-full max-w-lg overflow-hidden rounded-2xl border border-[#eadfca] bg-white p-6 shadow-xl shadow-[#1F1A17]/5 sm:p-9">
                    <div class="mb-6 border-b border-[#eadfca] pb-5">
                        <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">Selamat datang</p>
                        <h1 class="mt-2 font-serif text-3xl font-bold text-[#1F1A17]">
                            {{ request()->routeIs('register') ? 'Gabung di Warung NTT' : (request()->routeIs('login') ? 'Masuk ke akunmu' : 'Atur ulang akun') }}
                        </h1>
                    </div>
                    {{ $slot }}
                </div>
                <p class="mt-5 text-center text-xs text-stone-500">Warung NTT · Rasa dan cerita dari Nusa Tenggara Timur</p>
            </section>
        </main>
    </body>
</html>
