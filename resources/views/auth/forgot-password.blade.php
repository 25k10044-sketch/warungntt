<x-guest-layout>
    <div class="mb-4 text-sm leading-6 text-stone-600">
        Masukkan email yang terdaftar. Kami akan mengirim tautan untuk mengatur ulang kata sandi.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Kirim tautan reset
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
