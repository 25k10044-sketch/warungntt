<x-app-layout>
    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-[#7A1F1F] hover:underline">← Kembali ke pesanan</a>
        @php
            $statusClasses = [
                'menunggu' => 'bg-amber-100 text-amber-900',
                'diproses' => 'bg-blue-100 text-blue-900',
                'siap' => 'bg-emerald-100 text-emerald-900',
                'selesai' => 'bg-green-100 text-green-900',
                'dibatalkan' => 'bg-red-100 text-red-900',
            ];
            $paymentLabels = ['cod' => 'COD', 'transfer' => 'Transfer bank', 'qris' => 'QRIS'];
        @endphp
        <div class="mt-4 flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">Detail pesanan</p>
                <h1 class="mt-2 font-serif text-3xl font-bold text-[#1F1A17]">{{ $order->order_code }}</h1>
                <p class="mt-1 text-sm text-stone-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <span class="inline-flex min-h-10 items-center rounded-full px-4 text-sm font-semibold {{ $statusClasses[$order->status] ?? 'bg-stone-100 text-stone-800' }}">{{ ucfirst($order->status) }}</span>
        </div>
        <div class="mt-7 rounded-2xl border border-[#eadfca] bg-white p-6">
            <h2 class="font-serif text-2xl font-bold text-[#1F1A17]">Item pesanan</h2>
            <div class="mt-4 divide-y divide-[#eadfca]">
                @foreach ($order->orderItems as $item)
                    <div class="grid gap-3 py-4 sm:grid-cols-[1fr_auto] sm:items-center">
                        <div>
                            <a href="{{ route('menus.show', $item->menu) }}" class="font-semibold text-[#1F1A17] hover:text-[#7A1F1F]">{{ $item->menu->name }}</a>
                            <p class="mt-1 text-sm text-stone-500">{{ $item->quantity }} × Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-bold text-[#1F1A17]">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                    @if ($order->status === 'selesai' && ! in_array($item->menu_id, $reviewedMenuIds, true))
                        <form method="POST" action="{{ route('reviews.store', $item->menu) }}" class="mb-4 rounded-xl bg-[#FAF3E3] p-4">
                            @csrf
                            <p class="font-semibold text-[#1F1A17]">Bagaimana rasa {{ $item->menu->name }}?</p>
                            <div class="mt-3 grid gap-3 sm:grid-cols-[10rem_1fr_auto]">
                                <label class="sr-only" for="rating-{{ $item->id }}">Rating</label>
                                <select id="rating-{{ $item->id }}" name="rating" required class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                                    <option value="">Pilih rating</option>
                                    @foreach (range(5, 1) as $rating)
                                        <option value="{{ $rating }}" @selected(old('rating') == $rating)>{{ $rating }} bintang</option>
                                    @endforeach
                                </select>
                                <label class="sr-only" for="comment-{{ $item->id }}">Ulasan</label>
                                <input id="comment-{{ $item->id }}" name="comment" value="{{ old('comment') }}" maxlength="1000" placeholder="Tulis ulasan singkat (opsional)" class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                                <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-4 font-semibold text-white hover:bg-[#5f1717]">Kirim ulasan</button>
                            </div>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </form>
                    @elseif ($order->status === 'selesai')
                        <p class="mb-4 text-sm font-medium text-[#4F6F52]">Ulasan untuk menu ini sudah diberikan.</p>
                    @endif
                @endforeach
            </div>
            <dl class="mt-4 space-y-3 border-t border-[#eadfca] pt-5 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-stone-600">Pembayaran</dt>
                    <dd class="font-semibold text-[#1F1A17]">{{ $paymentLabels[$order->payment_method] ?? $order->payment_method }}</dd>
                </div>
                @if ($order->notes)
                    <div class="flex justify-between gap-4">
                        <dt class="text-stone-600">Catatan</dt>
                        <dd class="max-w-md text-right text-[#1F1A17]">{{ $order->notes }}</dd>
                    </div>
                @endif
                <div class="flex justify-between gap-4 border-t border-[#eadfca] pt-4 text-lg font-bold">
                    <dt>Total</dt>
                    <dd class="text-[#7A1F1F]">Rp{{ number_format($order->total_price, 0, ',', '.') }}</dd>
                </div>
            </dl>
        </div>
    </section>
</x-app-layout>
