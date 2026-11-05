<x-app-layout>
    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('cart.index') }}" class="text-sm font-semibold text-[#7A1F1F] hover:underline">← Kembali ke keranjang</a>
        <h1 class="mt-3 font-serif text-4xl font-bold text-[#1F1A17]">Checkout</h1>
        <div class="mt-7 grid gap-8 lg:grid-cols-[1fr_0.85fr]">
            <form method="POST" action="{{ route('checkout.store') }}" class="space-y-6 rounded-2xl border border-[#eadfca] bg-white p-6">
                @csrf
                <div>
                    <h2 class="font-serif text-2xl font-bold text-[#1F1A17]">Pembayaran simulasi</h2>
                    <p class="mt-1 text-sm text-stone-600">Pilih metode pembayaran. Tidak ada transaksi uang sungguhan.</p>
                    <div class="mt-4 space-y-3">
                        @foreach (['cod' => 'Bayar di tempat (COD)', 'transfer' => 'Transfer bank (simulasi)', 'qris' => 'QRIS statis (simulasi)'] as $value => $label)
                            <label class="flex min-h-12 cursor-pointer items-center gap-3 rounded-lg border border-[#eadfca] p-3 hover:bg-[#FAF3E3]">
                                <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', 'cod') === $value) class="border-stone-300 text-[#7A1F1F] focus:ring-[#7A1F1F]">
                                <span class="font-medium text-[#1F1A17]">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                </div>
                <div>
                    <label for="notes" class="font-semibold text-[#1F1A17]">Catatan pesanan <span class="font-normal text-stone-500">(opsional)</span></label>
                    <textarea id="notes" name="notes" rows="4" maxlength="1000" placeholder="Contoh: sambal dipisah, tidak pedas..." class="mt-2 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>
                <x-input-error :messages="$errors->get('cart')" class="mt-2" />
                <button class="min-h-12 w-full rounded-lg bg-[#7A1F1F] px-5 font-bold text-white hover:bg-[#5f1717]">Buat pesanan</button>
            </form>

            <aside class="h-fit rounded-2xl border border-[#eadfca] bg-[#f3ead8] p-6">
                <h2 class="font-serif text-2xl font-bold text-[#1F1A17]">Ringkasan</h2>
                <div class="mt-5 space-y-4">
                    @foreach ($items as $item)
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-stone-700">{{ $item->name }} × {{ $item->cart_quantity }}</span>
                            <span class="shrink-0 font-semibold text-[#1F1A17]">Rp{{ number_format($item->cart_subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 border-t border-[#cbbd9e] pt-4">
                    <div class="flex justify-between gap-4 text-lg font-bold">
                        <span>Total</span>
                        <span class="text-[#7A1F1F]">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-app-layout>
