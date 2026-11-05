<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="theme-color" content="#7A1F1F">
        <title>{{ config('app.name', 'Warung NTT') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|playfair-display:500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <a href="#main-content" class="sr-only z-[100] rounded-lg bg-white p-3 font-semibold text-[#7A1F1F] focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Lewati ke konten</a>
        <div class="min-h-screen bg-[#FAF3E3]">
            @include('layouts.navigation')

            @if (session('success') || session('error'))
                <div x-data="{ visible: true }" x-show="visible" x-init="setTimeout(() => visible = false, 5000)" x-transition class="fixed right-4 top-20 z-50 w-[calc(100%-2rem)] max-w-md sm:right-6" role="status" aria-live="polite">
                    <div class="flex items-start justify-between gap-4 rounded-xl border px-4 py-3 text-sm font-medium shadow-lg {{ session('success') ? 'border-green-200 bg-green-50 text-green-900' : 'border-red-200 bg-red-50 text-red-900' }}">
                        <span>{{ session('success') ?? session('error') }}</span>
                        <button type="button" @click="visible = false" class="inline-flex size-7 shrink-0 items-center justify-center rounded-full text-current hover:bg-black/5" aria-label="Tutup notifikasi">×</button>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8" role="alert">
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                        Ada bagian yang perlu diperiksa:
                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main id="main-content" tabindex="-1">
                {{ $slot }}
            </main>
            <footer class="border-t border-[#eadfca] bg-[#1F1A17] text-[#FAF3E3]">
                <div class="tenun-pattern h-2" aria-hidden="true"></div>
                <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-8 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <p class="font-serif text-lg font-bold">Warung NTT</p>
                    <p>Rasa dan cerita dari Nusa Tenggara Timur.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
