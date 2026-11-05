<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800" role="alert">{{ session('error') }}</div>
    @endif

    <a href="{{ route('auth.google') }}" class="flex min-h-12 w-full items-center justify-center gap-3 rounded-lg bg-white px-4 py-3 text-sm font-semibold text-[#1F1A17] ring-1 ring-stone-300 transition hover:bg-[#FAF3E3] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#7A1F1F]">
        <svg class="size-5" viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.24 5.48-4.7 7.18l7.19 5.59c4.2-3.88 7.55-9.57 7.55-17.24z"/><path fill="#FBBC05" d="M10.53 28.59a14.39 14.39 0 0 1 0-9.18l-7.98-6.19a23.94 23.94 0 0 0 0 21.56l7.98-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.91-5.8l-7.19-5.59c-2 1.35-4.56 2.15-8.72 2.15-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
        Masuk dengan Google
    </a>

    <div class="my-5 flex items-center gap-3 text-xs font-medium uppercase tracking-wide text-stone-500">
        <span class="h-px flex-1 bg-[#eadfca]"></span>
        atau dengan email
        <span class="h-px flex-1 bg-[#eadfca]"></span>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata sandi" />

            <x-text-input
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="size-5 rounded border-stone-300 text-[#7A1F1F] focus:ring-[#D9A441]" name="remember">
                <span class="ms-2 text-sm text-stone-600">Ingat saya</span>
            </label>
        </div>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
            @if (Route::has('password.request'))
                <a class="inline-flex min-h-11 items-center rounded-md text-sm font-medium text-[#7A1F1F] underline underline-offset-4 hover:text-[#5f1717]" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif

            <x-primary-button>
                Masuk
            </x-primary-button>
        </div>
    </form>

    <p class="mt-5 text-center text-sm text-stone-600">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-[#7A1F1F] underline underline-offset-4">Daftar sekarang</a></p>
</x-guest-layout>
