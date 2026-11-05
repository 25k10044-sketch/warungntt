<x-admin-layout title="Detail Pesanan">
    @php $paymentLabels = ['cod' => 'COD', 'transfer' => 'Transfer bank', 'qris' => 'QRIS']; @endphp
    <div class="mb-5"><a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-[#7A1F1F] hover:underline">← Kembali ke pesanan</a></div>
    <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
        <section class="rounded-2xl border border-[#eadfca] bg-white p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div><p class="text-sm font-semibold text-stone-500">Kode pesanan</p><h2 class="mt-1 font-serif text-2xl font-bold">{{ $order->order_code }}</h2></div>
                <span class="rounded-full bg-[#f3ead8] px-3 py-1 text-sm font-semibold">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="mt-5 divide-y divide-[#eadfca]">
                @foreach ($order->orderItems as $item)
                    <div class="flex justify-between gap-4 py-4">
                        <div><p class="font-semibold">{{ $item->menu->name }}</p><p class="mt-1 text-sm text-stone-500">{{ $item->quantity }} × Rp{{ number_format($item->price, 0, ',', '.') }}</p></div>
                        <p class="shrink-0 font-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between border-t border-[#eadfca] pt-4 text-lg font-bold"><span>Total pesanan</span><span class="text-[#7A1F1F]">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span></div>
            @if ($order->notes)<p class="mt-4 rounded-lg bg-[#FAF3E3] p-3 text-sm"><strong>Catatan:</strong> {{ $order->notes }}</p>@endif
        </section>
        <div class="space-y-6">
            <section class="rounded-2xl border border-[#eadfca] bg-white p-5">
                <h2 class="font-serif text-xl font-bold">Pelanggan</h2>
                <p class="mt-3 font-semibold">{{ $order->user->name }}</p>
                <p class="text-sm text-stone-600">{{ $order->user->email }}</p>
                <p class="mt-4 text-sm text-stone-600">Dibuat {{ $order->created_at->format('d M Y, H:i') }}</p>
                <p class="mt-1 text-sm text-stone-600">Pembayaran: {{ $paymentLabels[$order->payment_method] ?? $order->payment_method }}</p>
            </section>
            <section class="rounded-2xl border border-[#eadfca] bg-white p-5">
                <h2 class="font-serif text-xl font-bold">Perbarui status</h2>
                @if ($nextStatuses)
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4 space-y-3">
                        @csrf @method('PATCH')
                        <label for="status" class="sr-only">Status berikutnya</label>
                        <select id="status" name="status" required class="min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                            @foreach ($nextStatuses as $status)<option value="{{ $status }}">{{ ucfirst($status) }}</option>@endforeach
                        </select>
                        <button class="min-h-11 w-full rounded-lg bg-[#7A1F1F] px-4 font-semibold text-white hover:bg-[#5f1717]">Simpan status</button>
                    </form>
                @else
                    <p class="mt-3 text-sm text-stone-600">Pesanan berstatus final dan tidak dapat diubah.</p>
                @endif
            </section>
        </div>
    </div>
</x-admin-layout>
