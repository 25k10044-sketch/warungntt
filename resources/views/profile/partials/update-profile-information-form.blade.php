<section>
    <header>
        <h2 class="font-serif text-xl font-bold text-[#1F1A17]">
            Informasi profil
        </h2>

        <p class="mt-1 text-sm text-stone-600">
            Perbarui nama dan alamat email akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama lengkap" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-[#1F1A17]">
                        Email Anda belum terverifikasi.

                        <button form="send-verification" class="inline-flex min-h-11 items-center rounded-md px-2 text-sm font-semibold text-[#7A1F1F] underline underline-offset-4">
                            Kirim ulang tautan verifikasi
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p role="status" class="mt-2 text-sm font-medium text-[#4F6F52]">
                            Tautan verifikasi baru telah dikirim ke email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan perubahan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Perubahan tersimpan.</p>
            @endif
        </div>
    </form>
</section>
