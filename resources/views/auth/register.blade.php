<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama lengkap" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
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
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Ulangi kata sandi" />

            <x-text-input
                id="password_confirmation"
                class="mt-1 block w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
            <a class="inline-flex min-h-11 items-center rounded-md text-sm font-medium text-[#7A1F1F] underline underline-offset-4 hover:text-[#5f1717]" href="{{ route('login') }}">
                Sudah punya akun? Masuk
            </a>

            <x-primary-button>
                Buat akun
            </x-primary-button>
        </div>
    </form>

    <div class="mt-5 border-t border-[#eadfca] pt-5">
        <a href="{{ route('auth.google') }}" class="flex min-h-12 w-full items-center justify-center gap-3 rounded-lg border border-stone-300 px-4 py-2 text-sm font-semibold text-[#1F1A17] transition hover:bg-[#FAF3E3]">
            <span class="font-bold text-[#4285F4]" aria-hidden="true">G</span>
            Daftar dengan Google
        </a>
    </div>
</x-guest-layout>
