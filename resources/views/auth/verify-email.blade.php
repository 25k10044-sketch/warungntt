<x-guest-layout>
    <div class="mb-4 text-sm leading-6 text-stone-600">
        Terima kasih sudah mendaftar. Silakan verifikasi alamat email melalui tautan yang kami kirim. Jika belum menerima email, Anda dapat meminta tautan baru.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div role="status" aria-live="polite" class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm font-medium text-green-900">
            Tautan verifikasi baru telah dikirim ke alamat email Anda.
        </div>
    @endif

    <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Kirim ulang tautan verifikasi
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="inline-flex min-h-11 items-center rounded-md px-3 text-sm font-semibold text-[#7A1F1F] underline underline-offset-4">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
